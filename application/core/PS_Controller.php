<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PS_Controller extends CI_Controller
{
  public $pm;
  public $home;

  public function __construct()
  {
    parent::__construct();

    //--- check is user has logged in ?
    _check_login();

    //--- get permission for user
    $this->pm = get_permission($this->menu_code, get_cookie('uid'), get_cookie('id_profile'));

    //--- if user don't have permission to access this page then deny_page;
    _can_view_page($this->pm->can_view);
  }
}

?>
