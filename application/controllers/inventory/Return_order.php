<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Return_order extends PS_Controller
{
  public $menu_code = 'ICRTOR';
	public $menu_group_code = 'IC';
  public $menu_sub_group_code = 'RETURN';
	public $title = 'คืนสินค้า(ลดหนี้ขาย)';
  public $filter;
  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'inventory/return_order';
    $this->load->model('inventory/return_order_model');
    $this->load->model('masters/customers_model');
    $this->load->model('masters/products_model');
  }


  public function index()
  {
    $filter = array(
      'code'    => get_filter('code', 'code', ''),
      'invoice' => get_filter('invoice', 'invoice', ''),
      'customer_code' => get_filter('customer_code', 'customer_code', ''),
      'from_date' => get_filter('from_date', 'from_date', ''),
      'to_date' => get_filter('to_date', 'to_date', '')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->return_order_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$document = $this->return_order_model->get_data($filter, $perpage, $this->uri->segment($segment));

    if(!empty($document))
    {
      foreach($document as $rs)
      {
        $rs->qty = $this->return_order_model->get_sum_qty($rs->code);
        $rs->amount = $this->return_order_model->get_sum_amount($rs->code);
        $rs->customer_name = $this->customers_model->get_name($rs->customer_code);
      }
    }

    $filter['docs'] = $document;
		$this->pagination->initialize($init);
    $this->load->view('inventory/return_order/return_order_list', $filter);
  }



  public function add_new()
  {
    $this->load->view('inventory/return_order/return_order_add');
  }


  public function add()
  {
    if($this->input->post('date_add'))
    {
      $date_add = db_date($this->input->post('date_add'), TRUE);
      $invoice = trim($this->input->post('invoice'));
      $customer_code = trim($this->input->post('customer_code'));
      $remark = trim($this->input->post('remark'));

      $code = $this->get_new_code($date_add);
      $arr = array(
        'code' => $code,
        'bookcode' => getConfig('BOOK_CODE_RETURN_ORDER'),
        'invoice' => $invoice,
        'customer_code' => $customer_code,
        'date_add' => $date_add,
        'remark' => $remark
      );

      $rs = $this->return_order_model->add($arr);
      if($rs === TRUE)
      {
        redirect($this->home.'/edit/'.$code);
      }
      else
      {
        set_error("เพิ่มเอกสารไม่สำเร็จ กรุณาลองใหม่อีกครั้ง");
        redirect($this->home.'/add_new');
      }
    }
    else
    {
      set_error("ไม่พบข้อมูลเอกสารหรือฟอร์มว่างเปล่า กรุณาตรวจสอบ");
      redirect($this->home.'/add_new');
    }
  }


  public function edit($code)
  {
    $doc = $this->return_order_model->get($code);
    $doc->customer_name = $this->customers_model->get_name($doc->customer_code);
    $details = $this->return_order_model->get_details($code);
    //--- ถ้าไม่มีรายละเอียดให้ไปดึงจากใบกำกับมา
    if(empty($details))
    {
      $details = $this->return_order_model->get_invoice_details($doc->invoice);
      if(!empty($details))
      {
        //--- ถ้าได้รายการ ให้ทำการเปลี่ยนรหัสลูกค้าให้ตรงกับเอกสาร
        $cust = $this->return_order_model->get_customer_invoice($doc->invoice);
        if(!empty($cust))
        {
          $this->return_order_model->update($doc->code, array('customer_code' => $cust->customer_code));
        }
        //--- เปลี่ยนข้อมูลที่จะแสดงให้ตรงกันด้วย
        $doc->customer_code = $cust->customer_code;
        $doc->customer_name = $cust->customer_name;
      }
    }

    if(!empty($details))
    {
      foreach($details as $rs)
      {
        $rs->barcode = $this->products_model->get_barcode($rs->product_code);
      }
    }


    $ds = array(
      'doc' => $doc,
      'details' => $details
    );

    $this->load->view('inventory/return_order/return_order_edit', $ds);
  }



  public function get_new_code($date)
  {
    $date = $date == '' ? date('Y-m-d') : $date;
    $Y = date('y', strtotime($date));
    $M = date('m', strtotime($date));
    $prefix = getConfig('PREFIX_RETURN_ORDER');
    $run_digit = getConfig('RUN_DIGIT_RETURN_ORDER');
    $pre = $prefix .'-'.$Y.$M;
    $code = $this->return_order_model->get_max_code($pre);
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


  public function clear_filter()
  {
    $filter = array('code', 'invoice', 'customer_code', 'from_date', 'to_date');
    clear_filter($filter);
  }


} //--- end class
?>
