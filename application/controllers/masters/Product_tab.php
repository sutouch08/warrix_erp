<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_tab extends PS_Controller
{
  public $menu_code = 'DBPTAB';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'เพิ่ม/แก้ไข แถบแสดงสินค้า';
  public $error = '';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/products';
    //--- load model
    $this->load->model('masters/products_model');
    $this->load->model('masters/product_tab_model');
    $this->load->model('masters/product_style_model');

    $this->load->helper('product_images');
  }

  

}//--- end class
?>
