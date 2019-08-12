<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Configs extends PS_Controller
{
  public $menu_code = 'SCCONF';
	public $menu_group_code = 'SC';
  public $menu_sub_group_code = 'CONFIG';
	public $title = 'การกำหนดค่า';
  public $error = '';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'setting/configs';
    $this->load->model('setting/config_model');

  }



  public function index()
  {
    $groups = $this->config_model->get_group();
    $ds = array(
      'tabs' => $groups
    );

    $this->load->view('setting/configs', $ds);
  }

}
