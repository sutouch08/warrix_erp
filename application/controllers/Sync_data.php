<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_data extends CI_Controller
{
  public $title = 'Sync Data';
	public $menu_code = '';
	public $menu_group_code = '';
	public $pm;
  public $limit = 10;
  public $date;

  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE); //--- SAP database
    $this->mc = $this->load->database('mc', TRUE); //--- Temp Database
    $this->load->model('sync_data_model');
    $this->date = date('Y-d-m H:i:s');
  }


  public function index()
  {
    $this->load->view('sync_data_view');
  }

} //--- end class

 ?>
