<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PS_Controller extends CI_Controller
{
  public $pm;
  public $home;
  public $ms;
  public $mc;
  public function __construct()
  {
    parent::__construct();


    //--- check is user has logged in ?
    _check_login();

    $closed   = getConfig('CLOSE_SYSTEM'); //--- ปิดระบบทั้งหมดหรือไม่

    if($closed == 1)
    {
      redirect('setting/maintenance');
    }

    //--- get permission for user
    $this->pm = get_permission($this->menu_code, get_cookie('uid'), get_cookie('id_profile'));

    $this->ms = $this->load->database('ms', TRUE); //--- SAP database
    $this->mc = $this->load->database('mc', TRUE); //--- Temp Database
  }
}

?>
