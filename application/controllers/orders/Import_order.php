<?php
class Import_order extends CI_Controller
{
  public $ms;
  public $mc;
  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE); //--- SAP database
    $this->mc = $this->load->database('mc', TRUE); //--- Temp Database

    $this->load->model('orders/orders_model');
    $this->load->model('masters/channels_model');
    $this->load->model('masters/payment_methods_model');
    $this->load->model('masters/products_model');
    $this->load->model('masters/customers_model');
    $this->load->model('orders/order_state_model');
    $this->load->model('masters/products_model');
    $this->load->model('address/address_model');
    $this->load->model('stock/stock_model');

    $this->load->library('excel');
    $this->load->library('api');
  }


  public function index()
  {
    $sc = TRUE;
    $import = 0;
    $file = isset( $_FILES['uploadFile'] ) ? $_FILES['uploadFile'] : FALSE;
  	$path = $this->config->item('upload_path').'orders/';
    $file	= 'uploadFile';
		$config = array(   // initial config for upload class
			"allowed_types" => "xlsx",
			"upload_path" => $path,
			"file_name"	=> "import_order",
			"max_size" => 5120,
			"overwrite" => TRUE
			);

			$this->load->library("upload", $config);

			if(! $this->upload->do_upload($file))
      {
				echo $this->upload->display_errors();
			}
      else
      {
        $info = $this->upload->data();
        /// read file
				$excel = PHPExcel_IOFactory::load($info['full_path']);
				//get only the Cell Collection
        $collection	= $excel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

        $i = 1;
        $count = count($collection);
        $limit = intval(getConfig('IMPORT_ROWS_LIMIT'))+1;

        if( $count <= $limit )
        {
          $ds = array();

          //--- รหัสเล่มเอกสาร [อ้างอิงจาก SAP]
          //--- ถ้าเป็นฝากขายแบบโอนคลัง ยืมสินค้า เบิกแปรสภาพ เบิกสินค้า (ไม่เปิดใบกำกับ เปิดใบโอนคลังแทน) นอกนั้น เปิด SO
          $bookcode = getConfig('BOOK_CODE_ORDER');

          $role = 'S';

          //---- กำหนดช่องทางขายสำหรับเว็บไซต์ เพราะมีลูกค้าแยกตามช่องทางการชำระเงินอีกที
          //---- เลยต้องกำหนดลูกค้าแยกตามช่องทางการชำระเงินต่างๆ สำหรับเว็บไซต์เท่านั้น
          //---- เพราะช่องทางอื่นๆในการนำเข้าจะใช้ช่องทางการชำระเงินแบบเดียวทั้งหมด
          //---- เช่น K plus จะจ่ายด้วยบัตรเครดิตทั้งหมด  LAZADA จะไปเรียกเก็บเงินกับทาง ลาซาด้า
          $web_channels = getConfig('WEB_SITE_CHANNELS_CODE');

          //--- รหัสลูกค้าสำหรับ COD เว็บไซต์
          $web_customer_cod = getConfig('CUSTOMER_CODE_COD');

          //--- รหัสลูกค้าสำหรับ 2c2p บนเว็บไซต์
          $web_customer_2c2p = getConfig('CUSTOMER_CODE_2C2P');

          //--- รหัสลูกค้าเริ่มต้น หากพอว่าไม่มีการระบุรหัสลูกค้าไว้ จะใช้รหัสนี้แทน
          $default_customer = getConfig('DEFAULT_CUSTOMER');

          $prefix = getConfig('PREFIX_SHIPPING_NUMBER');

          foreach($collection as $rs)
          {
            //--- ถ้าพบ Error ให้ออกจากลูปทันที
            if($sc === FALSE)
            {
              break;
            }

            if($i == 1)
            {
              $i++;
              $headCol = array(
                'A' => 'Date',
                'B' => 'Document No',
                'C' => 'Reference No',
                'D' => 'Shipping No',
                'E' => 'Consignee Name',
                'F' => 'Address',
                'G' => 'District',
                'H' => 'Province',
                'I' => 'Postcode',
                'J' => 'Phone',
                'K' => 'Channels',
                'L' => 'Payment',
                'M' => 'Item',
                'N' => 'Price',
                'O' => 'Qty',
                'P' => 'Discount',
                'Q' => 'Amount',
                'R' => 'Shipping fee',
                'S' => 'Service fee',
                'T' => 'State',
                'U' => 'Force update'
              );

              foreach($headCol as $col => $field)
              {
                if($rs[$col] !== $field)
                {
                  $sc = FALSE;
                  $message = 'Column '.$col.' Should be '.$field;
                  break;
                }
              }

              if($sc === FALSE)
              {
                break;
              }
            }
            else if(!empty($rs['A']))
            {
              $date = PHPExcel_Style_NumberFormat::toFormattedString($rs['A'], 'YYYY-MM-DD');
              $date_add = db_date($date, TRUE);

              //--- order code ได้มาแล้วจากระบบ IS
              $order_code = $rs['B'];

              //---- order code from web site
              $ref_code = $rs['C'];

              //--- shipping Number
              $shipping_code = $rs['D'];

              //---- กำหนดช่องทางการขายเป็นรหัส
              $channels = $this->channels_model->get($rs['K']);

              //--- หากไม่ระบุช่องทางขายมา หรือ ช่องทางขายไม่ถูกต้องใช้ default
              if(empty($channels))
              {
                $channels = $this->channels_model->get_default();
              }

              //--- กำหนดช่องทางการชำระเงิน
              $payment = $this->payment_methods_model->get($rs['L']);
              if(empty($payment))
              {
                $payment = $this->payment_methods_model->get_default();
              }

              //--- คลังสินค้า
              $warehouse_code = getConfig('WEB_SITE_WAREHOUSE_CODE');

              //--- เลขที่เอกสาร
              $order_code = $rs['B']; //----

              $is_exists = FALSE;

              //------ เช็คว่ามีออเดอร์นี้อยู่ในฐานข้อมูลแล้วหรือยัง
              //------ ถ้ามีแล้วจะได้ order_code กลับมา ถ้ายังจะได้ FALSE;
              if(!empty($order_code))
              {
                //--- ถ้ามีการกำหนดเลขที่เอกสารมาแล้ว
                //---- ตรวจสอบว่ามีเอกสารแล้วหรือยัง ถ้ายังไม่มีให้เป็น FALSE ถ้ามีแล้ว ให้ระบุเลขที่
                $is_exists = $this->orders_model->is_exists_order($order_code);
              }
              else
              {
                //---- ถ้าไม่ได้ระบุเลขทีเอกสารมา ให้เช็คเลขที่เอกสารด้วยเลขที่อ้างอิง
                //---- ถ้ามีแล้วให้ระบุเลขที่ไป ถ้ายังไม่มีจะได้ FALSE เพื่อให้สร้างเลขที่อัตโนมัติต่อไป
                $order_code  = $this->orders_model->get_order_code_by_reference($ref_code);
                if($order_code === FALSE)
                {
                  $is_exists = FALSE;
                  $order_code = $this->get_new_code($date_add);
                }
              }

              //-- state ของออเดอร์ จะมีการเปลี่ยนแปลงอีกที
              $state = 3;

              //---- ถ้ายังไม่มีออเดอร์ ให้เพิ่มใหม่ หรือ มีออเดอร์แล้ว แต่ต้องการ update
              //---- โดยการใส่ force update มาเป็น 1
              if($is_exists === FALSE OR $rs['U'] == 1)
              {
                //---- รหัสลูกค้าจะมีการเปลี่ยนแปลงตามเงื่อนไขด้านล่างนี้
                $customer_code = NULL;
                //---- ตรวจสอบว่าช่องทางขายที่กำหนดมา เป็นเว็บไซต์หรือไม่(เพราะจะมีช่องทางการชำระเงินหลายช่องทาง)
                if($channels->code === $web_channels)
                {
                  if($payment->code === '2C2P')
                  {
                    //---- กำหนดรหัสลูกค้าตามค่าที่ config สำหรับเว็บไซต์ที่ชำระโดยบัตรเครดติ(2c2p)
                    $customer_code = $web_customer_2c2p;
                  }
                  else if($payment->code === 'COD')
                  {
                    //---- กำหนดรหัสลูกค้าตามค่าที่ config สำหรับเว็บไซต์ที่ชำระแบบ COD
                    $customer_code = $web_customer_cod;
                  }
                }
                else
                {
                  //--- หากไม่ใช่ช่องทางเว็บไซต์
                  //--- กำหนดรหัสลูกค้าตามช่องทางขายที่ได้ผูกไว้
                  //--- หากไม่มีการผูกไว้ให้
                  $customer_code = empty($channels->customer_code) ? $default_customer : $channels->customer_code;
                }

                $customer = $this->customers_model->get($customer_code);

              	//---	ถ้าเป็นออเดอร์ขาย จะมี id_sale
              	$sale_code = $customer->sale_code;

              	//---	หากเป็นออนไลน์ ลูกค้าออนไลน์ชื่ออะไร
              	$customer_ref = addslashes(trim($rs['E']));

                //---	ช่องทางการชำระเงิน
                $payment_code = $payment->code;

                //---	ช่องทางการขาย
                $channels_code = $channels->code;

              	//---	วันที่เอกสาร
              	//$date_add = PHPExcel_Style_NumberFormat::toFormattedString($rs['A'], 'YYYY-MM-DD');
                $date_add = db_date($date_add, TRUE);

                //--- ค่าจัดส่ง
                $shipping_fee = $rs['R'] == '' ? 0.00 : $rs['R'];

                //--- ค่าบริการอื่นๆ
                $service_fee = $rs['S'] == '' ? 0.00 : $rs['S'];

                //---- กรณียังไม่มีออเดอร์
                if($is_exists === FALSE)
                {
                  //--- เตรียมข้อมูลสำหรับเพิ่มเอกสารใหม่
                  $ds = array(
                    'code' => $order_code,
                    'role' => $role,
                    'bookcode' => $bookcode,
                    'reference' => $ref_code,
                    'customer_code' => $customer_code,
                    'customer_ref' => $customer_ref,
                    'channels_code' => $channels_code,
                    'payment_code' => $payment_code,
                    'sale_code' => $sale_code,
                    'state' => $state,
                    'is_paid' => 0,
                    'is_term' => $payment->has_term,
                    'shipping_code' => $shipping_code,
                    'status' => 1,
                    'date_add' => $date_add,
                    'warehouse_code' => $warehouse_code,
                    'user' => get_cookie('uname'),
                    'is_import' => 1
                  );

                  //--- เพิ่มเอกสาร
                  if($this->orders_model->add($ds) === TRUE)
                  {
                    $arr = array(
                      'order_code' => $order_code,
                      'state' => 3,
                      'update_user' => get_cookie('uname')
                    );
                    //--- add state event
                    $this->order_state_model->add_state($arr);

                    $id_address = $this->address_model->get_id($customer_ref, trim($rs['F']));

                    if($id_address === FALSE)
                    {
                      $arr = array(
                        'code' => $customer_ref,
                        'name' => $customer_ref,
                        'address' => trim($rs['F']),
                        //'sub_district' => trim($rs['E']),
                        'district' => trim($rs['G']),
                        'province' => trim($rs['H']),
                        'postcode' => trim($rs['I']),
                        'phone' => trim($rs['J']),
                        'alias' => 'Home',
                        'is_default' => 1
                      );

                      $id = $this->address_model->add_shipping_address($arr);
                      $this->orders_model->set_address_id($order_code, $id);
                    }

                    $import++;
                  }
                  else
                  {
                    $sc = FALSE;
                    $message = $ref_code.': เพิ่มออเดอร์ไม่สำเร็จ';
                  }
                }
                else
                {
                  $order = $this->orders_model->get($order_code);
                  if($order->state <= 3)
                  {
                    //--- เตรียมข้อมูลสำหรับเพิ่มเอกสารใหม่
                    $ds = array(
                      'customer_code' => $customer_code,
                      'customer_ref' => $customer_ref,
                      'channels_code' => $channels_code,
                      'payment_code' => $payment_code,
                      'sale_code' => $sale_code,
                      'state' => $state,
                      'is_term' => $payment->has_term,
                      'date_add' => $date_add,
                      'user' => get_cookie('uname')
                    );

                    $this->orders_model->update($order_code, $ds);
                  }

                  $import++;
                }
              }


              //---- เตรียมข้อมูลสำหรับเพิมรายละเอียดออเดอร์
              $item = $this->products_model->get(trim($rs['M']));

              if(empty($item))
              {
                $sc = FALSE;
                $message = 'ไม่พบข้อมูลสินค้าในระบบ : '.$rs['M'];
                break;
              }

              //---- เช็คข้อมูล ว่ามีรายละเอียดนี้อยู่ในออเดอร์แล้วหรือยัง
              //---- ถ้ามีข้อมูลอยู่แล้ว (TRUE)ให้ข้ามการนำเข้ารายการนี้ไป
              if($this->orders_model->is_exists_detail($order_code, $item->code) === FALSE)
              {
                //--- ถ้ายังไม่มีรายการอยู่ เพิ่มใหม่
                $arr = array(
                  "order_code"	=> $order_code,
                  "style_code"		=> $item->style_code,
                  "product_code"	=> $item->code,
                  "product_name"	=> $item->name,
                  "cost"  => $item->cost,
                  "price"	=> $rs['N'],
                  "qty"		=> $rs['O'],
                  "discount1"	=> 0,
                  "discount2" => 0,
                  "discount3" => 0,
                  "discount_amount" => 0,
                  "total_amount"	=> round($rs['O'] * $rs['N'],2),
                  "id_rule"	=> NULL,
                  "is_count" => $item->count_stock,
                  "is_import" => 1
                );

                if( $this->orders_model->add_detail($arr) === FALSE )
                {
                  $sc = FALSE;
                  $message = 'เพิ่มรายละเอียดรายการไม่สำเร็จ : '.$ref_code;
                  break;
                }
                else
                {
                  $this->update_api_stock($item->code, $item->old_code);
                }
              }
              else
              {
                //----  ถ้ามี force update และ สถานะออเดอร์ไม่เกิน 3 (รอจัดสินค้า)
                if($rs['U'] == 1 && $state <= 3)
                {
                  $od  = $this->orders_model->get_order_detail($order_code, $item->code);

                  $arr = array(
                    "style_code"		=> $item->style_code,
                    "product_code"	=> $item->code,
                    "product_name"	=> $item->name,
                    "cost"  => $item->cost,
                    "price"	=> $rs['N'],
                    "qty"		=> $rs['O'],
                    "discount1"	=> 0,
                    "discount2" => 0,
                    "discount3" => 0,
                    "discount_amount" => 0,
                    "total_amount"	=> round($rs['O'] * $rs['N'],2),
                    "id_rule"	=> NULL,
                    "is_count" => $item->count_stock,
                    "is_import" => 1
                  );

                  if($this->orders_model->update_detail($od->id, $arr) === FALSE)
                  {
                    $sc = FALSE;
                    $message = 'เพิ่มรายละเอียดรายการไม่สำเร็จ : '.$ref_code;
                    break;
                  }
                  else
                  {
                    $this->update_api_stock($item->code, $item->old_code);
                  }
                } //--- enf force update
              } //--- end if exists detail

            } //--- end header column

          } //--- end foreach
        }
        else
        {
          $sc = FALSE;
          $message = "ไฟล์มีจำนวนรายการเกิน {$limit} บรรทัด";
        }
    } //-- end import success

    echo $sc === TRUE ? 'success' : $message;
  }



  public function update_api_stock($code, $old_code)
  {
    if(getConfig('SYNC_WEB_STOCK') == 1)
    {
      $sell_stock = $this->stock_model->get_sell_stock($code);
      $reserv_stock = $this->orders_model->get_reserv_stock($code);
      $qty = $sell_stock - $reserv_stock;
      $item = empty($old_code) ? $code : $old_code;
      $this->api->update_web_stock($item, $qty);
    }

  }


  public function get_new_code($date)
  {
    $date = $date == '' ? date('Y-m-d') : $date;
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_ORDER');
    $run_digit = getConfig('RUN_DIGIT_ORDER');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->orders_model->get_max_code($pre);
    if(! is_null($code))
    {
      $run_no = mb_substr($code, ($run_digit*-1), NULL, 'UTF-8') + 1;
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', $run_no);
    }
    else
    {
      $new_code = $prefix . '-' . $Y . $M . sprintf('%0'.$run_digit.'d', '001');
    }

    return $new_code;
  }
}

 ?>
