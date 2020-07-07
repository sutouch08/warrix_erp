<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends PS_Controller
{
  public $menu_code = 'ICODIV';
	public $menu_group_code = 'IC';
  public $menu_sub_group_code = 'PICKPACK';
	public $title = 'รายการเปิดบิลแล้ว';
  public $filter;
  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'inventory/invoice';
    $this->load->model('inventory/invoice_model');
    $this->load->model('orders/orders_model');
    $this->load->model('masters/customers_model');
    $this->load->model('inventory/delivery_order_model');
    $this->load->helper('order');
  }


  public function index()
  {
    $this->load->helper('channels');
    $filter = array(
      'code'          => get_filter('code', 'inv_code', ''),
      'customer'      => get_filter('customer', 'inv_customer', ''),
      'user'          => get_filter('user', 'inv_user', ''),
      'role'          => get_filter('role', 'inv_role', ''),
      'channels'      => get_filter('channels', 'inv_channels', ''),
      'from_date'     => get_filter('from_date', 'inv_from_date', ''),
      'to_date'       => get_filter('to_date', 'inv_to_date', ''),
      'order_by'      => get_filter('order_by', 'inv_order_by', ''),
      'sort_by'       => get_filter('sort_by', 'inv_sort_by', ''),
      'is_valid'      => get_filter('is_valid', 'inv_valid', '2')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->delivery_order_model->count_rows($filter, 8);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$orders   = $this->delivery_order_model->get_data($filter, $perpage, $this->uri->segment($segment), 8);

    $filter['orders'] = $orders;

		$this->pagination->initialize($init);
    $this->load->view('inventory/order_closed/closed_list', $filter);
  }



  public function view_detail($code)
  {
    $this->load->model('inventory/qc_model');
    $this->load->helper('order');
    $this->load->helper('discount');

    $order = $this->orders_model->get($code);
    $order->customer_name = $this->customers_model->get_name($order->customer_code);

    if($order->role == 'C' OR $order->role == 'N')
    {
      $this->load->model('masters/zone_model');
      $order->zone_name = $this->zone_model->get_name($order->zone_code);
    }

    $details = $this->invoice_model->get_billed_detail($code);
    $box_list = $this->qc_model->get_box_list($code);
    $ds['order'] = $order;
    $ds['details'] = $details;
    $ds['box_list'] = $box_list;
    $this->load->view('inventory/order_closed/closed_detail', $ds);
  }




  public function print_order($code, $barcode = '')
  {
    $this->load->model('masters/products_model');
    $this->load->library('printer');
    $order = $this->orders_model->get($code);
    $order->customer_name = $this->customers_model->get_name($order->customer_code);
    $details = $this->invoice_model->get_details($code); //--- รายการที่มีการบันทึกขายไป
    if(!empty($details))
    {
      foreach($details as $rs)
      {
        $rs->barcode = $this->products_model->get_barcode($rs->product_code);
      }
    }

    $ds['order'] = $order;
    $ds['details'] = $details;
    $ds['is_barcode'] = $barcode != '' ? TRUE : FALSE;
    $this->load->view('print/print_invoice', $ds);
  }



  public function clear_filter()
  {
    $filter = array(
      'inv_code',
      'inv_customer',
      'inv_user',
      'inv_role',
      'inv_channels',
      'inv_from_date',
      'inv_to_date',
      'inv_order_by',
      'inv_sort_by',
      'inv_valid'
    );
    clear_filter($filter);
  }


} //--- end class
?>
