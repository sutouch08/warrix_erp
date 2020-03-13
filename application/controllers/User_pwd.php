<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_pwd extends CI_Controller
{
  public $title = 'เปลี่ยนรหัสผ่าน';
	public $menu_code = 'change password';
	public $menu_group_code = 'SC';
	public $pm;
	public function __construct()
	{
		parent::__construct();
		_check_login();
		$this->pm = new stdClass();
		$this->pm->can_view = 1;
    $this->load->model('users/user_model');
    $this->home = base_url().'user_pwd';
	}


	public function index()
	{
    $code = get_cookie('uname');
    if(!empty($code))
    {
      $user = $this->user_model->get($code);
      if(!empty($user))
      {
        $ds['data'] = $user;
        $this->load->view('users/change_pwd', $ds);
      }
      else
      {
        //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
        redirect(base_url().'users/authentication');
      }
    }
    else
    {
      //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
  		redirect(base_url().'users/authentication');
    }

	}


  public function change($code)
	{
    if(!empty($code))
    {
      $user = $this->user_model->get($code);
      if(!empty($user))
      {
        $ds['data'] = $user;
        $this->load->view('users/change_pwd', $ds);
      }
      else
      {
        //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
        redirect(base_url().'users/authentication');
      }
    }
    else
    {
      //--- ถ้าไม่มีข้อมูล ให้ไป login ใหม่
  		redirect(base_url().'users/authentication');
    }
	}


  public function change_password()
	{
		if($this->input->post('user_id'))
		{
			$id = $this->input->post('user_id');
			$pwd = password_hash($this->input->post('pwd'), PASSWORD_DEFAULT);
			$rs = $this->user_model->change_password($id, $pwd);

			if($rs === TRUE)
			{
				$this->session->set_flashdata('success', 'Password changed');
			}
			else
			{
				$this->session->set_flashdata('error', 'Change password not successfull, please try again');
			}
		}

		redirect($this->home);
	}
}
 ?>
