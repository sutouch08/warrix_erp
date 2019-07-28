<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_payment extends PS_Controller
{
  public $menu_code = 'SOODSO';
	public $menu_group_code = 'SO';
  public $menu_sub_group_code = 'ORDER';
	public $title = 'ออเดอร์';
  public $filter;
  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'orders/order_payment';

  }
} //--- end class

?>
