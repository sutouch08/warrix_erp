<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Consign extends PS_Controller
{
  public $menu_code = 'ACCSOD';
	public $menu_group_code = 'AC';
  public $menu_sub_group_code = '';
	public $title = 'ตัดยอดขาย';
  public $filter;
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {

  }

  
} //---- end class
 ?>
