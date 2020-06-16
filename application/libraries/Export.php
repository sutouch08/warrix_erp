<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Export
{
  protected $ci;
  public $error;

	public function __construct()
	{
    // Assign the CodeIgniter super-object
    $this->ci =& get_instance();
	}



  //--- ODLN  DLN1
  public function export_order($code)
  {
    $sc = TRUE;
    $this->ci->load->model('orders/orders_model');
    $this->ci->load->model('inventory/delivery_order_model');
    $this->ci->load->model('masters/customers_model');
    $this->ci->load->model('masters/products_model');
    $this->ci->load->model('discount/discount_policy_model');
    $this->ci->load->helper('discount');

    $order = $this->ci->orders_model->get($code);
    $cust = $this->ci->customers_model->get($order->customer_code);
    $total_amount = $this->ci->orders_model->get_bill_total_amount($code);

    $service_wh = getConfig('SERVICE_WAREHOUSE');

    $do = $this->ci->delivery_order_model->get_sap_delivery_order($code);

    if(empty($do))
    {
      $middle = $this->ci->delivery_order_model->get_middle_delivery_order($code);
      if(!empty($middle))
      {
        foreach($middle as $rows)
        {
          if($this->ci->delivery_order_model->drop_middle_exits_data($rows->DocEntry) === FALSE)
          {
            $sc = FALSE;
            $this->error = "ลบรายการที่ค้างใน Temp ไม่สำเร็จ";
          }
        }
      }

      if($sc === TRUE)
      {
        $currency = getConfig('CURRENCY');
        $vat_rate = getConfig('SALE_VAT_RATE');
        $vat_code = getConfig('SALE_VAT_CODE');
        //--- header
        $ds = array(
          'DocType' => 'I', //--- I = item, S = Service
          'CANCELED' => 'N', //--- Y = Yes, N = No
          'DocDate' => sap_date($order->date_add, TRUE), //--- วันที่เอกสาร
          'DocDueDate' => sap_date($order->date_add,TRUE), //--- วันที่เอกสาร
          'CardCode' => $order->customer_code, //--- รหัสลูกค้า
          'CardName' => $cust->name, //--- ชื่อลูกค้า
          'DiscPrcnt' => $order->bDiscText,
          'DiscSum' => $order->bDiscAmount,
          'DiscSumFC' => $order->bDiscAmount,
          'DocCur' => $currency,
          'DocRate' => 1.000000,
          'DocTotal' => $total_amount,
          'DocTotalFC' => $total_amount,
          'GroupNum' => $cust->GroupNum,
          'SlpCode' => $cust->sale_code,
          'ToWhsCode' => NULL,
          'Comments' => $order->remark,
          'U_SONO' => $order->code,
          'U_ECOMNO' => $order->code,
          'U_BOOKCODE' => $order->bookcode,
          'F_E_Commerce' => 'A',
          'F_E_CommerceDate' => sap_date(now(), TRUE)
        );

        $this->ci->mc->trans_begin();

        $docEntry = $this->ci->delivery_order_model->add_sap_delivery_order($ds);


        if($docEntry !== FALSE)
        {
          $details = $this->ci->delivery_order_model->get_sold_details($code);
          if(!empty($details))
          {
            $line = 0;

            foreach($details as $rs)
            {

              $arr = array(
                'DocEntry' => $docEntry,
                'U_ECOMNO' => $rs->reference,
                'LineNum' => $line,
                'ItemCode' => $rs->product_code,
                'Dscription' => $rs->product_name,
                'Quantity' => $rs->qty,
                'UnitMsr' => $this->ci->products_model->get_unit_code($rs->product_code),
                'PriceBefDi' => $rs->price,  //---มูลค่าต่อหน่วยก่อนภาษี/ก่อนส่วนลด
                'LineTotal' => $rs->total_amount,
                'Currency' => $currency,
                'Rate' => 1.000000,
                'DiscPrcnt' => discountAmountToPercent($rs->discount_amount, $rs->qty, $rs->price), ///--- discount_helper
                'Price' => remove_vat($rs->price), //--- ราคา
                'TotalFrgn' => $rs->total_amount, //--- จำนวนเงินรวม By Line (Currency)
                'WhsCode' => ($rs->is_count == 1 ? $rs->warehouse_code : $service_wh),
                'BinCode' => $rs->zone_code,
                'TaxStatus' => 'Y',
                'VatPrcnt' => $vat_rate,
                'VatGroup' => $vat_code,
                'PriceAfVat' => $rs->sell,
                'GTotal' => round($rs->total_amount, 2),
                'VatSum' => get_vat_amount($rs->total_amount), //---- tool_helper
                'TaxType' => 'Y', //--- คิดภาษีหรือไม่
                'F_E_Commerce' => 'A', //--- A = Add , U = Update
                'F_E_CommerceDate' => sap_date(now(), TRUE),
                'U_PROMOTION' => $this->ci->discount_policy_model->get_code($rs->id_policy)
              );

              $this->ci->delivery_order_model->add_delivery_row($arr);
              $line++;
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "ไม่พบรายการขาย";
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "เพิ่มเอกสารไม่สำเร็จ";
        }

        if($sc === TRUE)
        {
          $this->ci->mc->trans_commit();
        }
        else
        {
          $this->ci->mc->trans_rollback();
        }
      }

    }
    else
    {
      $sc = FALSE;
      $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
    }

    return $sc;
  }



  //--- ตัดยอดฝากขาย (WM)(เปิดใบกำกับเมื่อขายได้)
  //--- ODLN  DLN1
  public function export_consign_order($code)
  {
    $sc = TRUE;
    $this->ci->load->model('account/consign_order_model');
    $this->ci->load->model('orders/orders_model');
    $this->ci->load->model('inventory/delivery_order_model');
    $this->ci->load->model('masters/customers_model');
    $this->ci->load->model('masters/products_model');
    $this->ci->load->model('discount/discount_policy_model');
    $this->ci->load->helper('discount');

    $order = $this->ci->consign_order_model->get($code);
    $cust = $this->ci->customers_model->get($order->customer_code);
    $total_amount = $this->ci->orders_model->get_bill_total_amount($code);

    $service_wh = getConfig('SERVICE_WAREHOUSE');

    $do = $this->ci->delivery_order_model->get_sap_delivery_order($code);

    if(empty($do))
    {
      $middle = $this->ci->delivery_order_model->get_middle_delivery_order($code);
      if(!empty($middle))
      {
        foreach($middle as $rows)
        {
          if($this->ci->delivery_order_model->drop_middle_exits_data($rows->DocEntry) === FALSE)
          {
            $sc = FALSE;
            $this->error = "ลบรายการที่ค้างใน Temp ไม่สำเร็จ";
          }
        }

      }

      if($sc === TRUE)
      {
        $currency = getConfig('CURRENCY');
        $vat_rate = getConfig('SALE_VAT_RATE');
        $vat_code = getConfig('SALE_VAT_CODE');
        //--- header
        $ds = array(
          'DocType' => 'I', //--- I = item, S = Service
          'CANCELED' => 'N', //--- Y = Yes, N = No
          'DocDate' => sap_date($order->date_add, TRUE), //--- วันที่เอกสาร
          'DocDueDate' => sap_date($order->date_add,TRUE), //--- วันที่เอกสาร
          'CardCode' => $order->customer_code, //--- รหัสลูกค้า
          'CardName' => $cust->name, //--- ชื่อลูกค้า
          'DocCur' => $currency,
          'DocRate' => 1.000000,
          'DocTotal' => $total_amount,
          'DocTotalFC' => $total_amount,
          'GroupNum' => $cust->GroupNum,
          'SlpCode' => $cust->sale_code,
          'ToWhsCode' => NULL,
          'Comments' => $order->remark,
          'U_SONO' => $order->code,
          'U_ECOMNO' => $order->code,
          'U_BOOKCODE' => $order->bookcode,
          'F_E_Commerce' => 'A',
          'F_E_CommerceDate' => sap_date(now(), TRUE)
        );

        $this->ci->mc->trans_begin();

        $docEntry = $this->ci->delivery_order_model->add_sap_delivery_order($ds);


        if($docEntry !== FALSE)
        {
          $details = $this->ci->delivery_order_model->get_sold_details($code);
          if(!empty($details))
          {
            $line = 0;

            foreach($details as $rs)
            {

              $arr = array(
                'DocEntry' => $docEntry,
                'U_ECOMNO' => $rs->reference,
                'LineNum' => $line,
                'ItemCode' => $rs->product_code,
                'Dscription' => $rs->product_name,
                'Quantity' => $rs->qty,
                'UnitMsr' => $this->ci->products_model->get_unit_code($rs->product_code),
                'PriceBefDi' => $rs->price,  //---มูลค่าต่อหน่วยก่อนภาษี/ก่อนส่วนลด
                'LineTotal' => $rs->total_amount,
                'Currency' => $currency,
                'Rate' => 1.000000,
                'DiscPrcnt' => discountAmountToPercent($rs->discount_amount, $rs->qty, $rs->price), ///--- discount_helper
                'Price' => remove_vat($rs->price), //--- ราคา
                'TotalFrgn' => $rs->total_amount, //--- จำนวนเงินรวม By Line (Currency)
                'WhsCode' => ($rs->is_count == 1 ? $rs->warehouse_code : $service_wh),
                'BinCode' => $rs->zone_code,
                'TaxStatus' => 'Y',
                'VatPrcnt' => $vat_rate,
                'VatGroup' => $vat_code,
                'PriceAfVat' => $rs->sell,
                'GTotal' => round($rs->total_amount, 2),
                'VatSum' => get_vat_amount($rs->total_amount), //---- tool_helper
                'TaxType' => 'Y', //--- คิดภาษีหรือไม่
                'F_E_Commerce' => 'A', //--- A = Add , U = Update
                'F_E_CommerceDate' => sap_date(now(), TRUE),
                'U_PROMOTION' => $this->ci->discount_policy_model->get_code($rs->id_policy)
              );

              $this->ci->delivery_order_model->add_delivery_row($arr);
              $line++;
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "ไม่พบรายการขาย";
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "เพิ่มเอกสารไม่สำเร็จ";
        }

        if($sc === TRUE)
        {
          $this->ci->mc->trans_commit();
        }
        else
        {
          $this->ci->mc->trans_rollback();
        }
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
    }

    return $sc;
  }



  //---- WL เบิก ยืมสินค้า
  //---- OWTR WTR1
  public function export_transfer_order($code)
  {
    $sc = TRUE;
    $this->ci->load->model('orders/orders_model');
    $this->ci->load->model('inventory/delivery_order_model');
    $this->ci->load->model('inventory/transfer_model');
    $this->ci->load->model('masters/customers_model');
    $this->ci->load->model('masters/products_model');
    $this->ci->load->helper('discount');

    $doc = $this->ci->orders_model->get($code);
    $sap = $this->ci->transfer_model->get_sap_transfer_doc($code);

    if($doc->role == 'L' OR $doc->role == 'R')
    {
      $cust = new stdClass();
      $cust->code = NULL;
      $cust->name = NULL;
    }
    else
    {
      $cust = $this->ci->customers_model->get($doc->customer_code);
    }

    if(!empty($doc))
    {
      if(empty($sap))
      {
        if($doc->status == 1)
        {
          //--- เช็คของเก่าก่อนว่ามีในถังกลางหรือยัง
          $middle = $this->ci->transfer_model->get_middle_transfer_doc($code);
          if(!empty($middle))
          {
            foreach($middle as $rows)
            {
              if($this->ci->transfer_model->drop_middle_exits_data($rows->DocEntry) === FALSE)
              {
                $sc = FALSE;
                $this->error = "ลบรายที่ค้างใน temp ไม่สำเร็จ";
              }
            }

          }

          if($sc === TRUE)
          {
            $currency = getConfig('CURRENCY');
            $vat_rate = getConfig('SALE_VAT_RATE');
            $vat_code = getConfig('SALE_VAT_CODE');
            $total_amount = $this->ci->orders_model->get_bill_total_amount($code);
            $ds = array(
              'U_ECOMNO' => $doc->code,
              'DocType' => 'I',
              'CANCELED' => 'N',
              'DocDate' => sap_date($doc->date_add, TRUE),
              'DocDueDate' => sap_date($doc->date_add, TRUE),
              'CardCode' => $cust->code,
              'CardName' => $cust->name,
              'VatPercent' => $vat_rate,
              'VatSum' => round(get_vat_amount($total_amount), 6),
              'VatSumFc' => round(get_vat_amount($total_amount), 6),
              'DiscPrcnt' => 0.000000,
              'DiscSum' => 0.000000,
              'DiscSumFC' => 0.000000,
              'DocCur' => $currency,
              'DocRate' => 1,
              'DocTotal' => remove_vat($total_amount),
              'DocTotalFC' => remove_vat($total_amount),
              'Filler' => $doc->warehouse_code,
              'ToWhsCode' => $doc->warehouse_code,
              'Comments' => $doc->remark,
              'F_E_Commerce' => 'A',
              'F_E_CommerceDate' => sap_date(now(), TRUE),
              'U_BOOKCODE' => $doc->bookcode,
              'U_REQUESTER' => $doc->empName,
              'U_APPROVER' => $doc->approver
            );

            $this->ci->mc->trans_begin();

            $cs = $this->ci->transfer_model->add_sap_transfer_doc($ds);

            if($cs)
            {
              $drop = $middle === TRUE ? $this->ci->transfer_model->drop_sap_exists_details($code) : TRUE;

              $details = $this->ci->delivery_order_model->get_sold_details($code);

              if(!empty($details) && $drop === TRUE)
              {
                $line = 0;
                foreach($details as $rs)
                {
                  $arr = array(
                    'U_ECOMNO' => $rs->reference,
                    'LineNum' => $line,
                    'ItemCode' => $rs->product_code,
                    'Dscription' => $rs->product_name,
                    'Quantity' => $rs->qty,
                    'unitMsr' => $this->ci->products_model->get_unit_code($rs->product_code),
                    'PriceBefDi' => round($rs->price,2),
                    'LineTotal' => round($rs->total_amount,2),
                    'ShipDate' => $doc->date_add,
                    'Currency' => $currency,
                    'Rate' => 1,
                    //--- คำนวณส่วนลดจากยอดเงินกลับมาเป็น % (เพราะบางทีมีส่วนลดหลายชั้น)
                    'DiscPrcnt' => discountAmountToPercent($rs->discount_amount, $rs->qty, $rs->price), ///--- discount_helper
                    'Price' => round(remove_vat($rs->price),2),
                    'TotalFrgn' => round($rs->total_amount,2),
                    'FromWhsCod' => $rs->warehouse_code,
                    'WhsCode' => $doc->warehouse_code,
                    'FisrtBin' => $doc->zone_code, //-- โซนปลายทาง
                    'F_FROM_BIN' => $rs->zone_code, //--- โซนต้นทาง
                    'F_TO_BIN' => $doc->zone_code, //--- โซนปลายทาง
                    'TaxStatus' => 'Y',
                    'VatPrcnt' => $vat_rate,
                    'VatGroup' => $vat_code,
                    'PriceAfVAT' => round($rs->sell,2),
                    'VatSum' => round(get_vat_amount($rs->total_amount),2),
                    'GTotal' => round($rs->total_amount, 2),
                    'TaxType' => 'Y',
                    'F_E_Commerce' => 'A',
                    'F_E_CommerceDate' => sap_date(now())
                  );

                  if( ! $this->ci->transfer_model->add_sap_transfer_detail($arr))
                  {
                    $sc = FALSE;
                    $this->error = 'เพิ่มรายการไม่สำเร็จ';
                  }

                  $line++;
                }
              }
              else
              {
                $sc = FALSE;
                $this->error = "ไม่พบรายการสินค้า";
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "เพิ่มเอกสารไม่สำเร็จ";
            }

            if($sc === TRUE)
            {
              $this->ci->mc->trans_commit();
            }
            else
            {
              $this->ci->mc->trans_rollback();
            }
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "สถานะเอกสารไม่ถูกต้อง";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "ไม่พบเอกสาร {$code}";
    }

    return $sc;
  }
//--- end export transfer order



//---- WT ฝากขายโอนคลัง
//---- OWTR WTR1
public function export_transfer_draft($code)
{
  $sc = TRUE;
  $this->ci->load->model('orders/orders_model');
  $this->ci->load->model('inventory/delivery_order_model');
  $this->ci->load->model('inventory/transfer_model');
  $this->ci->load->model('masters/customers_model');
  $this->ci->load->model('masters/products_model');
  $this->ci->load->helper('discount');

  $doc = $this->ci->orders_model->get($code);
  $sap = $this->ci->transfer_model->get_sap_transfer_doc($code);

  if($doc->role == 'L' OR $doc->role == 'U' OR $doc->role == 'R')
  {
    $cust = new stdClass();
    $cust->code = NULL;
    $cust->name = NULL;
  }
  else
  {
    $cust = $this->ci->customers_model->get($doc->customer_code);
  }

  if(!empty($doc))
  {
    if(empty($sap))
    {
      if($doc->status == 1)
      {
        $middle = $this->ci->transfer_model->get_middle_transfer_draft($code);
        if(!empty($middle))
        {
          foreach($middle as $rows)
          {
            if($this->ci->transfer_model->drop_middle_transfer_draft($rows->DocEntry) === FALSE)
            {
              $sc = FALSE;
              $this->error = "ลบรายการที่ค้างใน temp ไม่สำเร็จ";
            }
          }
        }

        if($sc === TRUE)
        {
          $currency = getConfig('CURRENCY');
          $vat_rate = getConfig('SALE_VAT_RATE');
          $vat_code = getConfig('SALE_VAT_CODE');
          $total_amount = $this->ci->orders_model->get_bill_total_amount($code);
          $ds = array(
            'U_ECOMNO' => $doc->code,
            'DocType' => 'I',
            'CANCELED' => 'N',
            'DocDate' => sap_date($doc->date_add, TRUE),
            'DocDueDate' => sap_date($doc->date_add, TRUE),
            'CardCode' => $cust->code,
            'CardName' => $cust->name,
            'VatPercent' => $vat_rate,
            'VatSum' => round(get_vat_amount($total_amount), 6),
            'VatSumFc' => round(get_vat_amount($total_amount), 6),
            'DiscPrcnt' => 0.000000,
            'DiscSum' => 0.000000,
            'DiscSumFC' => 0.000000,
            'DocCur' => $currency,
            'DocRate' => 1,
            'DocTotal' => remove_vat($total_amount),
            'DocTotalFC' => remove_vat($total_amount),
            'Filler' => $doc->warehouse_code,
            'ToWhsCode' => $doc->warehouse_code,
            'Comments' => $doc->remark,
            'F_E_Commerce' => 'A',
            'F_E_CommerceDate' => sap_date(now(), TRUE),
            'U_BOOKCODE' => $doc->bookcode,
            'U_REQUESTER' => $doc->empName,
            'U_APPROVER' => $doc->approver
          );

          $this->ci->mc->trans_begin();

          $docEntry = $this->ci->transfer_model->add_sap_transfer_draft($ds);

          if($docEntry)
          {
            $details = $this->ci->delivery_order_model->get_sold_details($code);

            if(!empty($details))
            {
              $line = 0;
              foreach($details as $rs)
              {
                $arr = array(
                  'DocEntry' => $docEntry,
                  'U_ECOMNO' => $rs->reference,
                  'LineNum' => $line,
                  'ItemCode' => $rs->product_code,
                  'Dscription' => $rs->product_name,
                  'Quantity' => $rs->qty,
                  'unitMsr' => $this->ci->products_model->get_unit_code($rs->product_code),
                  'PriceBefDi' => round($rs->price,2),
                  'LineTotal' => round($rs->total_amount,2),
                  'ShipDate' => $doc->date_add,
                  'Currency' => $currency,
                  'Rate' => 1,
                  //--- คำนวณส่วนลดจากยอดเงินกลับมาเป็น % (เพราะบางทีมีส่วนลดหลายชั้น)
                  'DiscPrcnt' => discountAmountToPercent($rs->discount_amount, $rs->qty, $rs->price), ///--- discount_helper
                  'Price' => round(remove_vat($rs->price),2),
                  'TotalFrgn' => round($rs->total_amount,2),
                  'FromWhsCod' => $rs->warehouse_code,
                  'WhsCode' => $doc->warehouse_code,
                  'FisrtBin' => $doc->zone_code, //-- โซนปลายทาง
                  'F_FROM_BIN' => $rs->zone_code, //--- โซนต้นทาง
                  'F_TO_BIN' => $doc->zone_code, //--- โซนปลายทาง
                  'TaxStatus' => 'Y',
                  'VatPrcnt' => $vat_rate,
                  'VatGroup' => $vat_code,
                  'PriceAfVAT' => round($rs->sell,2),
                  'VatSum' => round(get_vat_amount($rs->total_amount),2),
                  'GTotal' => round($rs->total_amount, 2),
                  'TaxType' => 'Y',
                  'F_E_Commerce' => 'A',
                  'F_E_CommerceDate' => sap_date(now())
                );

                if( ! $this->ci->transfer_model->add_sap_transfer_draft_detail($arr))
                {
                  $sc = FALSE;
                  $this->error = 'เพิ่มรายการไม่สำเร็จ';
                }

                $line++;
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "ไม่พบรายการสินค้า";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "เพิ่มเอกสารไม่สำเร็จ";
          }

          if($sc === TRUE)
          {
            $this->ci->mc->trans_commit();
          }
          else
          {
            $this->ci->mc->trans_rollback();
          }
        }

      }
      else
      {
        $sc = FALSE;
        $this->error = "สถานะเอกสารไม่ถูกต้อง";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
    }
  }
  else
  {
    $sc = FALSE;
    $this->error = "ไม่พบเอกสาร {$code}";
  }

  return $sc;
}
//--- end export transfer draf





public function export_transfer($code)
{
  $sc = TRUE;
  $this->ci->load->model('inventory/transfer_model');
  $doc = $this->ci->transfer_model->get($code);
  $sap = $this->ci->transfer_model->get_sap_transfer_doc($code);

  if(!empty($doc))
  {
    if(empty($sap))
    {
      if($doc->status == 1)
      {
        //--- เช็คของเก่าก่อนว่ามีในถังกลางหรือยัง
        $middle = $this->ci->transfer_model->get_middle_transfer_doc($code);
        if(!empty($middle))
        {
          foreach($middle as $rows)
          {
            if($this->ci->transfer_model->drop_middle_exits_data($rows->DocEntry) === FALSE)
            {
              $sc = FALSE;
              $this->error = "ลบรายที่ค้างใน temp ไม่สำเร็จ";
            }
          }
        }

        if($sc === TRUE)
        {
          $currency = getConfig('CURRENCY');
          $vat_rate = getConfig('SALE_VAT_RATE');
          $vat_code = getConfig('SALE_VAT_CODE');

          $ds = array(
            'U_ECOMNO' => $doc->code,
            'DocType' => 'I',
            'CANCELED' => 'N',
            'DocDate' => sap_date($doc->date_add, TRUE),
            'DocDueDate' => sap_date($doc->date_add, TRUE),
            'CardCode' => NULL,
            'CardName' => NULL,
            'VatPercent' => 0.000000,
            'VatSum' => 0.000000,
            'VatSumFc' => 0.000000,
            'DiscPrcnt' => 0.000000,
            'DiscSum' => 0.000000,
            'DiscSumFC' => 0.000000,
            'DocCur' => $currency,
            'DocRate' => 1,
            'DocTotal' => 0.000000,
            'DocTotalFC' => 0.000000,
            'Filler' => $doc->from_warehouse,
            'ToWhsCode' => $doc->to_warehouse,
            'Comments' => $doc->remark,
            'F_E_Commerce' => 'A',
            'F_E_CommerceDate' => sap_date(now(), TRUE),
            'U_BOOKCODE' => $doc->bookcode
          );

          $this->ci->mc->trans_begin();

          $docEntry = $this->ci->transfer_model->add_sap_transfer_doc($ds);

          if($docEntry !== FALSE)
          {
            $details = $this->ci->transfer_model->get_details($code);

            if(!empty($details))
            {
              $line = 0;
              foreach($details as $rs)
              {
                $arr = array(
                  'DocEntry' => $docEntry,
                  'U_ECOMNO' => $rs->transfer_code,
                  'LineNum' => $line,
                  'ItemCode' => $rs->product_code,
                  'Dscription' => $rs->product_name,
                  'Quantity' => $rs->qty,
                  'unitMsr' => NULL,
                  'PriceBefDi' => 0.000000,
                  'LineTotal' => 0.000000,
                  'ShipDate' => sap_date($doc->date_add, TRUE),
                  'Currency' => $currency,
                  'Rate' => 1,
                  'DiscPrcnt' => 0.000000,
                  'Price' => 0.000000,
                  'TotalFrgn' => 0.000000,
                  'FromWhsCod' => $doc->from_warehouse,
                  'WhsCode' => $doc->to_warehouse,
                  'FisrtBin' => $rs->from_zone,
                  'F_FROM_BIN' => $rs->from_zone,
                  'F_TO_BIN' => $rs->to_zone,
                  'AllocBinC' => $rs->to_zone,
                  'TaxStatus' => 'Y',
                  'VatPrcnt' => 0.000000,
                  'VatGroup' => NULL,
                  'PriceAfVAT' => 0.000000,
                  'VatSum' => 0.000000,
                  'TaxType' => 'Y',
                  'F_E_Commerce' => 'A',
                  'F_E_CommerceDate' => sap_date(now(), TRUE)
                );

                if( ! $this->ci->transfer_model->add_sap_transfer_detail($arr))
                {
                  $sc = FALSE;
                  $this->error = 'เพิ่มรายการไม่สำเร็จ';
                }

                $line++;
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "ไม่พบรายการสินค้า";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "เพิ่มเอกสารไม่สำเร็จ";
          }

          if($sc === TRUE)
          {
            $this->ci->mc->trans_commit();
          }
          else
          {
            $this->ci->mc->trans_rollback();
          }
        }

      }
      else
      {
        $sc = FALSE;
        $this->error = "สถานะเอกสารไม่ถูกต้อง";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
    }

  }
  else
  {
    $sc = FALSE;
    $this->error = "ไม่พบเอกสาร {$code}";
  }

  return $sc;
}



//--- export move
public function export_move($code)
{
  $sc = TRUE;
  $this->ci->load->model('inventory/move_model');
  $doc = $this->ci->move_model->get($code);
  $sap = $this->ci->move_model->get_sap_move_doc($code);

  if(!empty($doc))
  {
    if(empty($sap))
    {
      if($doc->status == 1)
      {
        //--- เช็คของเก่าก่อนว่ามีในถังกลางหรือยัง
        $middle = $this->ci->move_model->get_middle_move_doc($code);
        if(!empty($middle))
        {
          foreach($middle as $rows)
          {
            if($this->ci->move_model->drop_middle_exits_data($rows->DocEntry) === FALSE)
            {
              $sc = FALSE;
              $this->error = "ลบรายที่ค้างใน temp ไม่สำเร็จ";
            }
          }
        }

        if($sc === TRUE)
        {
          $currency = getConfig('CURRENCY');
          $vat_rate = getConfig('SALE_VAT_RATE');
          $vat_code = getConfig('SALE_VAT_CODE');

          $ds = array(
            'U_ECOMNO' => $doc->code,
            'DocType' => 'I',
            'CANCELED' => 'N',
            'DocDate' => sap_date($doc->date_add),
            'DocDueDate' => sap_date($doc->date_add),
            'CardCode' => NULL,
            'CardName' => NULL,
            'VatPercent' => 0.000000,
            'VatSum' => 0.000000,
            'VatSumFc' => 0.000000,
            'DiscPrcnt' => 0.000000,
            'DiscSum' => 0.000000,
            'DiscSumFC' => 0.000000,
            'DocCur' => $currency,
            'DocRate' => 1,
            'DocTotal' => 0.000000,
            'DocTotalFC' => 0.000000,
            'Filler' => $doc->from_warehouse,
            'ToWhsCode' => $doc->to_warehouse,
            'Comments' => $doc->remark,
            'F_E_Commerce' => 'A' ,
            'F_E_CommerceDate' => sap_date(now(), TRUE),
            'U_BOOKCODE' => $doc->bookcode
          );

          $this->ci->mc->trans_begin();

          $docEntry = $this->ci->move_model->add_sap_move_doc($ds);

          if($docEntry !== FALSE)
          {
            $details = $this->ci->move_model->get_details($code);

            if(!empty($details))
            {
              $line = 0;
              foreach($details as $rs)
              {
                $arr = array(
                  'DocEntry' => $docEntry,
                  'U_ECOMNO' => $rs->move_code,
                  'LineNum' => $line,
                  'ItemCode' => $rs->product_code,
                  'Dscription' => $rs->product_name,
                  'Quantity' => $rs->qty,
                  'unitMsr' => NULL,
                  'PriceBefDi' => 0.000000,
                  'LineTotal' => 0.000000,
                  'ShipDate' => $doc->date_add,
                  'Currency' => $currency,
                  'Rate' => 1,
                  'DiscPrcnt' => 0.000000,
                  'Price' => 0.000000,
                  'TotalFrgn' => 0.000000,
                  'FromWhsCod' => $doc->from_warehouse,
                  'WhsCode' => $doc->to_warehouse,
                  'F_FROM_BIN' => $rs->from_zone,
                  'F_TO_BIN' => $rs->to_zone,
                  'TaxStatus' => 'Y',
                  'VatPrcnt' => 0.000000,
                  'VatGroup' => NULL,
                  'PriceAfVAT' => 0.000000,
                  'VatSum' => 0.000000,
                  'TaxType' => 'Y',
                  'F_E_Commerce' => 'A',
                  'F_E_CommerceDate' => sap_date(now(), TRUE)
                );

                if( ! $this->ci->move_model->add_sap_move_detail($arr))
                {
                  $sc = FALSE;
                  $this->error = 'เพิ่มรายการไม่สำเร็จ';
                }

                $line++;
              }

              if($sc === TRUE)
              {
                //---- set exported = 1
                $this->ci->move_model->exported($doc->code);
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "ไม่พบรายการสินค้า";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "เพิ่มเอกสารไม่สำเร็จ";
          }

          if($sc === TRUE)
          {
            $this->ci->mc->trans_commit();
          }
          else
          {
            $this->ci->mc->trans_rollback();
          }
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "สถานะเอกสารไม่ถูกต้อง";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
    }

  }
  else
  {
    $sc = FALSE;
    $this->error = "ไม่พบเอกสาร {$code}";
  }

  return $sc;
}


public function export_transform($code)
{
  $sc = TRUE;
  $this->ci->load->model('orders/orders_model');
  $this->ci->load->model('inventory/delivery_order_model');
  $this->ci->load->model('inventory/transfer_model');
  $this->ci->load->model('masters/customers_model');
  $this->ci->load->model('masters/products_model');
  $this->ci->load->helper('discount');

  $doc = $this->ci->orders_model->get($code);
  $sap = $this->ci->transfer_model->get_sap_transfer_doc($code);
  $cust = $this->ci->customers_model->get($doc->customer_code);

  if(!empty($doc))
  {
    if(empty($sap))
    {
      if($doc->status == 1)
      {
        $middle = $this->ci->transfer_model->get_middle_transfer_doc($code);
        if(!empty($middle))
        {
          foreach($middle as $rows)
          {
            if($this->ci->transfer_model->drop_middle_exits_data($rows->DocEntry) === FALSE)
            {
              $sc = FALSE;
              $this->error = "ลบรายที่ค้างใน temp ไม่สำเร็จ";
            }
          }
        }

        if($sc === TRUE)
        {
          $currency = getConfig('CURRENCY');
          $vat_rate = getConfig('SALE_VAT_RATE');
          $vat_code = getConfig('SALE_VAT_CODE');
          $total_amount = $this->ci->orders_model->get_bill_total_amount($code);
          $ds = array(
            'U_ECOMNO' => $doc->code,
            'DocType' => 'I',
            'CANCELED' => 'N',
            'DocDate' => sap_date($doc->date_add, TRUE),
            'DocDueDate' => sap_date($doc->date_add,TRUE),
            'CardCode' => $cust->code,
            'CardName' => $cust->name,
            'VatPercent' => $vat_rate,
            'VatSum' => get_vat_amount($total_amount),
            'VatSumFc' => get_vat_amount($total_amount),
            'DiscPrcnt' => 0.000000,
            'DiscSum' => 0.000000,
            'DiscSumFC' => 0.000000,
            'DocCur' => $currency,
            'DocRate' => 1,
            'DocTotal' => remove_vat($total_amount),
            'DocTotalFC' => remove_vat($total_amount),
            'Filler' => $doc->warehouse_code,
            'ToWhsCode' => getConfig('TRANSFORM_WAREHOUSE'),
            'Comments' => $doc->remark,
            'F_E_Commerce' => 'A',
            'F_E_CommerceDate' => sap_date(now(), TRUE),
            'U_BOOKCODE' => $doc->bookcode,
            'U_REQUESTER' => $doc->user,
            'U_APPROVER' => $doc->approver
          );

          $this->ci->mc->trans_begin();
          $docEntry = $this->ci->transfer_model->add_sap_transfer_doc($ds);

          if($docEntry)
          {
            $details = $this->ci->delivery_order_model->get_sold_details($code);

            if(!empty($details))
            {
              $line = 0;
              foreach($details as $rs)
              {
                $arr = array(
                  'DocEntry' => $docEntry,
                  'U_ECOMNO' => $rs->reference,
                  'LineNum' => $line,
                  'ItemCode' => $rs->product_code,
                  'Dscription' => $rs->product_name,
                  'Quantity' => $rs->qty,
                  'unitMsr' => $this->ci->products_model->get_unit_code($rs->product_code),
                  'PriceBefDi' => round($rs->price,2),
                  'LineTotal' => round($rs->total_amount,2),
                  'ShipDate' => $doc->date_add,
                  'Currency' => $currency,
                  'Rate' => 1,
                  //--- คำนวณส่วนลดจากยอดเงินกลับมาเป็น % (เพราะบางทีมีส่วนลดหลายชั้น)
                  'DiscPrcnt' => discountAmountToPercent($rs->discount_amount, $rs->qty, $rs->price), ///--- discount_helper
                  'Price' => round(remove_vat($rs->price),2),
                  'TotalFrgn' => round($rs->total_amount,2),
                  'FromWhsCod' => $rs->warehouse_code,
                  'WhsCode' => $doc->warehouse_code,
                  'FisrtBin' => $doc->zone_code, //--- zone ปลายทาง
                  'F_FROM_BIN' => $rs->zone_code, //--- โซนต้นทาง
                  'F_TO_BIN' => $doc->zone_code, //--- โซนปลายทาง
                  'TaxStatus' => 'Y',
                  'VatPrcnt' => $vat_rate,
                  'VatGroup' => $vat_code,
                  'PriceAfVAT' => round($rs->sell, 2),
                  'VatSum' => round(get_vat_amount($rs->total_amount),2),
                  'GTotal' => round($rs->total_amount, 2),
                  'TaxType' => 'Y',
                  'F_E_Commerce' => 'A',
                  'F_E_CommerceDate' => sap_date(now(), TRUE)
                );

                if( ! $this->ci->transfer_model->add_sap_transfer_detail($arr))
                {
                  $sc = FALSE;
                  $this->error = 'เพิ่มรายการไม่สำเร็จ';
                }

                $line++;
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "ไม่พบรายการสินค้า";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "เพิ่มเอกสารไม่สำเร็จ";
          }

          if($sc === TRUE)
          {
            $this->ci->mc->trans_commit();
          }
          else
          {
            $this->ci->mc->trans_rollback();
          }
        }

      }
      else
      {
        $sc = FALSE;
        $this->error = "สถานะเอกสารไม่ถูกต้อง";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
    }

  }
  else
  {
    $sc = FALSE;
    $this->error = "ไม่พบเอกสาร {$code}";
  }

  return $sc;
}


//--- Receive PO
//--- OPDN PDN1
public function export_receive($code)
{
  $sc = TRUE;
  $this->ci->load->model('inventory/receive_po_model');
  $this->ci->load->model('masters/products_model');
  $doc = $this->ci->receive_po_model->get($code);
  $sap = $this->ci->receive_po_model->get_sap_receive_doc($code);

  if(!empty($doc))
  {
    if(empty($sap))
    {
      if($doc->status == 1)
      {
        //---- ถ้ามีรายการที่ยังไม่ได้ถูกเอาเข้า SAP ให้ลบรายการนั้นออกก่อน(SAP เอาเข้าซ้ำไม่ได้)
        $middle = $this->ci->receive_po_model->get_middle_receive_po($code);
        if(!empty($middle))
        {
          //--- Delete exists details
          foreach($middle as $rows)
          {
            if($this->ci->receive_po_model->drop_sap_received($rows->DocEntry) === FALSE)
            {
              $sc = FALSE;
              $this->error = "ลบรายการที่ค้างใน Temp ไม่สำเร็จ";
            }
          }
        }

        //--- หลังจากเคลียร์รายการค้างออกหมดแล้ว
        if($sc === TRUE)
        {
          $currency = getConfig('CURRENCY');
          $vat_rate = getConfig('PURCHASE_VAT_RATE');
          $vat_code = getConfig('PURCHASE_VAT_CODE');
          $total_amount = $this->ci->receive_po_model->get_sum_amount($code);
          $ds = array(
            'U_ECOMNO' => $doc->code,
            'DocType' => 'I',
            'CANCELED' => 'N',
            'DocDate' => sap_date($doc->date_add, TRUE),
            'DocDueDate' => sap_date($doc->date_add,TRUE),
            'CardCode' => $doc->vendor_code,
            'CardName' => $doc->vendor_name,
            'NumAtCard' => $doc->invoice_code,
            'VatPercent' => $vat_rate,
            'VatSum' => get_vat_amount($total_amount),
            'VatSumFc' => get_vat_amount($total_amount),
            'DiscPrcnt' => 0.000000,
            'DiscSum' => 0.000000,
            'DiscSumFC' => 0.000000,
            'DocCur' => $currency,
            'DocRate' => 1,
            'DocTotal' => remove_vat($total_amount),
            'DocTotalFC' => remove_vat($total_amount),
            'ToWhsCode' => $doc->warehouse_code,
            'Comments' => $doc->remark,
            'F_E_Commerce' => 'A',
            'F_E_CommerceDate' => sap_date(now(),TRUE)
          );

          $this->ci->mc->trans_begin();

          $docEntry = $this->ci->receive_po_model->add_sap_receive_po($ds);


          if($docEntry !== FALSE)
          {
            $details = $this->ci->receive_po_model->get_details($code);

            if(!empty($details))
            {
              $line = 0;
              foreach($details as $rs)
              {
                $arr = array(
                  'DocEntry' => $docEntry,
                  'U_ECOMNO' => $rs->receive_code,
                  'LineNum' => $line,
                  'ItemCode' => $rs->product_code,
                  'Dscription' => $rs->product_name,
                  'Quantity' => $rs->qty,
                  'unitMsr' => $this->ci->products_model->get_unit_code($rs->product_code),
                  'PriceBefDi' => remove_vat($rs->price),
                  'LineTotal' => remove_vat($rs->amount),
                  'ShipDate' => sap_date($doc->date_add,TRUE),
                  'Currency' => $currency,
                  'Rate' => 1,
                  'Price' => remove_vat($rs->price),
                  'TotalFrgn' => remove_vat($rs->amount),
                  'WhsCode' => $doc->warehouse_code,
                  'FisrtBin' => $doc->zone_code,
                  'BaseRef' => $doc->po_code,
                  'TaxStatus' => 'Y',
                  'VatPrcnt' => $vat_rate,
                  'VatGroup' => $vat_code,
                  'PriceAfVAT' => $rs->price,
                  'VatSum' => get_vat_amount($rs->amount),
                  'TaxType' => 'Y',
                  'F_E_Commerce' => 'A',
                  'F_E_CommerceDate' => sap_date(now(), TRUE)
                );

                if( ! $this->ci->receive_po_model->add_sap_receive_po_detail($arr))
                {
                  $sc = FALSE;
                  $this->error = 'เพิ่มรายการไม่สำเร็จ';
                }

                $line++;
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "ไม่พบรายการสินค้า";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "เพิ่มเอกสารไม่สำเร็จ";
          }

          if($sc === TRUE)
          {
            $this->ci->mc->trans_commit();
          }
          else
          {
            $this->ci->mc->trans_rollback();
          }
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "สถานะเอกสารไม่ถูกต้อง";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
    }
  }
  else
  {
    $sc = FALSE;
    $this->error = "ไม่พบเอกสาร {$code}";
  }

  return $sc;
}
//--- end export Receive PO



//---- receive transform
public function export_receive_transform($code)
{
  $sc = TRUE;
  $this->ci->load->model('inventory/receive_transform_model');
  $this->ci->load->model('masters/products_model');
  $doc = $this->ci->receive_transform_model->get($code);
  $sap = $this->ci->receive_transform_model->get_sap_receive_transform($code);

  if(!empty($doc))
  {
    if(empty($sap))
    {
      if($doc->status == 1)
      {
        $middle = $this->ci->receive_transform_model->get_middle_receive_transform($code);
        if(!empty($middle))
        {
          foreach($middle as $rows)
          {
            if($this->ci->receive_transform_model->drop_middle_exits_data($rows->DocEntry) === FALSE)
            {
              $sc = FALSE;
              $this->error = "ลบรายการที่ค้างใน temp ไม่สำเร็จ";
            }
          }
        }

        if($sc === TRUE)
        {
          $currency = getConfig('CURRENCY');
          $vat_rate = getConfig('PURCHASE_VAT_RATE');
          $vat_code = getConfig('PURCHASE_VAT_CODE');
          $total_amount = $this->ci->receive_transform_model->get_sum_amount($code);
          $ds = array(
            'U_ECOMNO' => $doc->code,
            'DocType' => 'I',
            'CANCELED' => 'N',
            'DocDate' => $doc->date_add,
            'DocDueDate' => $doc->date_add,
            'DocCur' => $currency,
            'DocRate' => 1,
            'DocTotal' => remove_vat($total_amount),
            'Comments' => $doc->remark,
            'F_E_Commerce' => 'A',
            'F_E_CommerceDate' => now()
          );

          $this->ci->mc->trans_begin();

          $docEntry = $this->ci->receive_transform_model->add_sap_receive_transform($ds);

          if($docEntry !== FALSE)
          {

            $details = $this->ci->receive_transform_model->get_details($code);

            if(!empty($details))
            {
              $line = 0;
              foreach($details as $rs)
              {
                $arr = array(
                  'DocEntry' => $docEntry,
                  'U_ECOMNO' => $rs->receive_code,
                  'LineNum' => $line,
                  'ItemCode' => $rs->product_code,
                  'Dscription' => $rs->product_name,
                  'Quantity' => $rs->qty,
                  'unitMsr' => $this->ci->products_model->get_unit_code($rs->product_code),
                  'PriceBefDi' => round($rs->price,2),
                  'LineTotal' => round($rs->amount, 2),
                  'ShipDate' => $doc->date_add,
                  'Currency' => $currency,
                  'Rate' => 1,
                  'Price' => round(remove_vat($rs->price), 2),
                  'TotalFrgn' => round($rs->amount, 2),
                  'WhsCode' => $doc->warehouse_code,
                  'FisrtBin' => $doc->zone_code,
                  'BaseRef' => $doc->order_code,
                  'TaxStatus' => 'Y',
                  'VatPrcnt' => $vat_rate,
                  'VatGroup' => $vat_code,
                  'PriceAfVAT' => $rs->price,
                  'VatSum' => round(get_vat_amount($rs->amount), 2),
                  'GTotal' => round($rs->amount, 2),
                  'TaxType' => 'Y',
                  'F_E_Commerce' => 'A',
                  'F_E_CommerceDate' => now()
                );

                if( ! $this->ci->receive_transform_model->add_sap_receive_transform_detail($arr))
                {
                  $sc = FALSE;
                  $this->error = 'เพิ่มรายการไม่สำเร็จ';
                }

                $line++;
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "ไม่พบรายการสินค้า";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "เพิ่มเอกสารไม่สำเร็จ";
          }

          if($sc === TRUE)
          {
            $this->ci->mc->trans_commit();
          }
          else
          {
            $this->ci->mc->trans_rollback();
          }
        }

      }
      else
      {
        $sc = FALSE;
        $this->error = "สถานะเอกสารไม่ถูกต้อง";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
    }
  }
  else
  {
    $sc = FALSE;
    $this->error = "ไม่พบเอกสาร {$code}";
  }

  return $sc;
}
//--- end export receive transform



//---- export return order
//----
public function export_return($code)
{
  $sc = TRUE;
  $this->ci->load->model('inventory/return_order_model');
  $this->ci->load->model('masters/customers_model');
  $this->ci->load->model('masters/products_model');
  $doc = $this->ci->return_order_model->get($code);
  $cust = $this->ci->customers_model->get($doc->customer_code);
  $or = $this->ci->return_order_model->get_sap_return_order($code);
  if(!empty($doc))
  {
    if(empty($or))
    {
      if($doc->is_approve == 1 && $doc->status == 1)
      {
        $middle = $this->ci->return_order_model->get_middle_return_doc($code);
        if(!empty($middle))
        {
          foreach($middle as $rows)
          {
            if($this->ci->return_order_model->drop_middle_exits_data($rows->DocEntry) === FALSE)
            {
              $sc = FALSE;
              $this->error = "ลบรายการที่ค้างใน temp ไม่สำเร็จ";
            }
          }
        }

        if($sc === TRUE)
        {
          $currency = getConfig('CURRENCY');
          $vat_rate = getConfig('SALE_VAT_RATE');
          $vat_code = getConfig('SALE_VAT_CODE');
          $total_amount = $this->ci->return_order_model->get_total_return($code);
          $ds = array(
            'DocType' => 'I',
            'CANCELED' => 'N',
            'DocDate' => $doc->date_add,
            'DocDueDate' => $doc->date_add,
            'CardCode' => $cust->code,
            'CardName' => $cust->name,
            'VatSum' => $this->ci->return_order_model->get_total_return_vat($code),
            'DocCur' => $currency,
            'DocRate' => 1,
            'DocTotal' => $total_amount,
            'DocTotalFC' => $total_amount,
            'Comments' => $doc->remark,
            'GroupNum' => $cust->GroupNum,
            'SlpCode' => $cust->sale_code,
            'ToWhsCode' => $doc->warehouse_code,
            'U_ECOMNO' => $doc->code,
            'U_BOOKCODE' => $doc->bookcode,
            'F_E_Commerce' => 'A',
            'F_E_CommerceDate' => now(),
            'U_OLDINV' => $doc->invoice
          );

          $this->ci->mc->trans_begin();

          $docEntry = $this->ci->return_order_model->add_sap_return_order($ds);

          if($docEntry !== FALSE)
          {
            $details = $this->ci->return_order_model->get_details($code);

            if( ! empty($details))
            {
              $line = 0;
              //--- insert detail to RDN1
              foreach($details as $rs)
              {
                $arr = array(
                  'DocEntry' => $docEntry,
                  'U_ECOMNO' => $rs->return_code,
                  'LineNum' => $line,
                  'ItemCode' => $rs->product_code,
                  'Dscription' => $rs->product_name,
                  'Quantity' => $rs->qty,
                  'unitMsr' => $this->ci->products_model->get_unit_code($rs->product_code),
                  'PriceBefDi' => remove_vat($rs->price),
                  'LineTotal' => remove_vat($rs->amount),
                  'ShipDate' => $doc->date_add,
                  'Currency' => $currency,
                  'Rate' => 1,
                  'DiscPrcnt' => $rs->discount_percent,
                  'Price' => remove_vat($rs->price),
                  'TotalFrgn' => remove_vat($rs->amount),
                  'WhsCode' => $doc->warehouse_code,
                  //'BinCode' => $doc->zone_code,
                  'TaxStatus' => 'Y',
                  'VatPrcnt' => $vat_rate,
                  'VatGroup' => $vat_code,
                  'PriceAfVAT' => $rs->price,
                  'VatSum' => $rs->vat_amount,
                  'TaxType' => 'Y',
                  'F_E_Commerce' => 'A',
                  'F_E_CommerceDate' => now(),
                  'U_OLDINV' => $rs->invoice_code
                );

                if( ! $this->ci->return_order_model->add_sap_return_detail($arr))
                {
                  $sc = FALSE;
                  $this->error = 'เพิ่มรายการไม่สำเร็จ';
                }

                $line++;
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "ไม่พบรายการรับคืน";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "เพิ่มเอกสารไม่สำเร็จ";
          }



          if($sc === TRUE)
          {
            $this->ci->mc->trans_commit();
          }
          else
          {
            $this->ci->mc->trans_rollback();
          }
        }

      }
      else
      {
        $sc = FALSE;
        $this->error = "{$code} ยังไม่ได้รับการอนุมัติ";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
    }

  }
  else
  {
    $sc = FALSE;
    $this->error = "ไม่พบเอกสาร {$code}";
  }

  return $sc;
}




public function export_return_lend($code)
{
  $sc = TRUE;
  $this->ci->load->model('inventory/return_lend_model');
  $this->ci->load->model('inventory/transfer_model');
  $this->ci->load->model('masters/products_model');
  $this->ci->load->model('masters/employee_model');

  $doc = $this->ci->return_lend_model->get($code);
  $sap = $this->ci->transfer_model->get_sap_transfer_doc($code);

  if(!empty($doc))
  {
    if(empty($sap))
    {
      if($doc->status == 1)
      {
        $middle = $this->ci->transfer_model->get_middle_transfer_doc($code);
        if(!empty($middle))
        {
          foreach($middle as $rows)
          {
            if($this->ci->transfer_model->drop_middle_exits_data($rows->DocEntry) === FALSE)
            {
              $sc = FALSE;
              $this->error = "ลบรายการที่ค้างใน temp ไม่สำเร็จ";
            }
          }
        }

        if($sc === TRUE)
        {
          $currency = getConfig('CURRENCY');
          $vat_rate = getConfig('SALE_VAT_RATE');
          $vat_code = getConfig('SALE_VAT_CODE');
          $total_amount = $this->ci->return_lend_model->get_sum_amount($code);
          $ds = array(
            'U_ECOMNO' => $doc->code,
            'DocType' => 'I',
            'CANCELED' => 'N',
            'DocDate' => $doc->date_add,
            'DocDueDate' => $doc->date_add,
            'CardCode' => NULL,
            'CardName' => NULL,
            'VatPercent' => $vat_rate,
            'VatSum' => round(get_vat_amount($total_amount), 6),
            'VatSumFc' => round(get_vat_amount($total_amount), 6),
            'DiscPrcnt' => 0.000000,
            'DiscSum' => 0.000000,
            'DiscSumFC' => 0.000000,
            'DocCur' => $currency,
            'DocRate' => 1,
            'DocTotal' => remove_vat($total_amount),
            'DocTotalFC' => remove_vat($total_amount),
            'Filler' => $doc->from_warehouse,
            'ToWhsCode' => $doc->to_warehouse,
            'Comments' => $doc->remark,
            'F_E_Commerce' => 'A',
            'F_E_CommerceDate' => now(),
            'U_BOOKCODE' => $doc->bookcode,
            'U_REQUESTER' => $this->ci->employee_model->get_name($doc->empID)
          );

          $this->ci->mc->trans_begin();

          $docEntry = $sc = $this->ci->transfer_model->add_sap_transfer_doc($ds);


          if($docEntry !== FALSE)
          {

            $details = $this->ci->return_lend_model->get_details($code);

            if(!empty($details))
            {
              $line = 0;
              foreach($details as $rs)
              {
                $arr = array(
                  'DocEntry' => $docEntry,
                  'U_ECOMNO' => $rs->return_code,
                  'LineNum' => $line,
                  'ItemCode' => $rs->product_code,
                  'Dscription' => $rs->product_name,
                  'Quantity' => $rs->qty,
                  'unitMsr' => $this->ci->products_model->get_unit_code($rs->product_code),
                  'PriceBefDi' => round(remove_vat($rs->price),6),
                  'LineTotal' => round(remove_vat($rs->amount),6),
                  'ShipDate' => $doc->date_add,
                  'Currency' => $currency,
                  'Rate' => 1,
                  //--- คำนวณส่วนลดจากยอดเงินกลับมาเป็น % (เพราะบางทีมีส่วนลดหลายชั้น)
                  'DiscPrcnt' => 0.000000, ///--- discount_helper
                  'Price' => round(remove_vat($rs->price),6),
                  'TotalFrgn' => round(remove_vat($rs->amount),6),
                  'FromWhsCod' => $doc->from_warehouse,
                  'WhsCode' => $doc->to_warehouse,
                  'F_FROM_BIN' => $doc->from_zone, //-- โซนต้นทาง
                  'F_TO_BIN' => $doc->to_zone, //--- โซนปลายทาง
                  'TaxStatus' => 'Y',
                  'VatPrcnt' => $vat_rate,
                  'VatGroup' => $vat_code,
                  'PriceAfVAT' => $rs->price,
                  'VatSum' => round($rs->vat_amount,6),
                  'TaxType' => 'Y',
                  'F_E_Commerce' => 'A',
                  'F_E_CommerceDate' => now()
                );

                if( ! $this->ci->transfer_model->add_sap_transfer_detail($arr))
                {
                  $sc = FALSE;
                  $this->error = 'เพิ่มรายการไม่สำเร็จ';
                }

                $line++;
              }
            }
            else
            {
              $sc = FALSE;
              $this->error = "ไม่พบรายการสินค้า";
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "เพิ่มเอกสารไม่สำเร็จ";
          }

          if($sc === TRUE)
          {
            $this->ci->mc->trans_commit();
          }
          else
          {
            $this->ci->mc->trans_rollback();
          }
        }

      }
      else
      {
        $sc = FALSE;
        $this->error = "สถานะเอกสารไม่ถูกต้อง";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
    }
  }
  else
  {
    $sc = FALSE;
    $this->error = "ไม่พบเอกสาร {$code}";
  }

  return $sc;
}



//---- Good issue
//---- OIGE IGE1
public function export_goods_issue($code)
{
  $sc = TRUE;
  $this->ci->load->model('account/consignment_order_model');
  $doc = $this->ci->consignment_order_model->get($code);
  $sap = $this->ci->consignment_order_model->get_sap_consignment_order_doc($code);
  if(! empty($doc))
  {
    if(empty($sap))
    {
      $middle = $this->ci->consignment_order_model->get_middle_consignment_order_doc($code);
      if(!empty($middle))
      {
        foreach($middle as $rows)
        {
          if($this->ci->consignment_order_model->drop_middle_exits_data($rows->DocEntry) === FALSE)
          {
            $sc = FALSE;
            $this->error = "ลบรายการที่ค้างใน Temp ไม่สำเร็จ";
          }
        }
      }


      if($sc === TRUE)
      {
        $doc_total = $this->ci->consignment_order_model->get_sum_amount($code);
        $arr = array(
          'U_ECOMNO' => $code,
          'DocType' => 'I',
          'CANCELED' => 'N',
          'DocDate' => sap_date($doc->date_add),
          'DocDueDate' => sap_date($doc->date_add),
          'DocTotal' => $doc_total,
          'DocTotalFC' => $doc_total,
          'Comments' => $doc->remark,
          'F_E_Commerce' => 'A',
          'F_E_CommerceDate' => sap_date(now(), TRUE)
        );

        $this->ci->mc->trans_begin();

        $docEntry = $this->ci->consignment_order_model->add_sap_goods_issue($arr);

        //--- now add details
        if($docEntry !== FALSE)
        {
          $details = $this->ci->consignment_order_model->get_details($code);
          if(! empty($details))
          {
            $line = 0;
            foreach($details as $rs)
            {
              $arr = array(
                'DocEntry' => $docEntry,
                'U_ECOMNO' => $rs->consign_code,
                'LineNum' => $line,
                'ItemCode' => $rs->product_code,
                'Dscription' => $rs->product_name,
                'Quantity' => $rs->qty,
                'WhsCode' => $doc->warehouse_code,
                'FisrtBin' => $doc->zone_code,
                'DocDate' => sap_date($doc->date_add),
                'F_E_Commerce' => 'A',
                'F_E_CommerceDate' => sap_date(now(), TRUE)
              );

              $this->ci->consignment_order_model->add_sap_goods_issue_row($arr);
              $line++;
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "ไม่พบรายการสินค้า";
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "เพิ่มเอกสารไม่สำเร็จ";
        }

        if($sc === TRUE)
        {
          $this->ci->mc->trans_commit();
        }
        else
        {
          $this->ci->mc->trans_rollback();
        }
      }

    }
    else
    {
      $sc = FALSE;
      $this->error = "เอกสารถูกนำเข้า SAP แล้ว หากต้องการเปลี่ยนแปลงกรุณายกเลิกเอกสารใน SAP ก่อน";
    }
  }
  else
  {
    $sc = FALSE;
    $this->error = "ไม่พบเลขที่เอกสาร";
  }

  return $sc;
}

} //--- end class
