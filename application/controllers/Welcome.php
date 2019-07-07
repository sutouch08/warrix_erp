<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public $menu_code = '';
	public $menu_group_code = '';
	public $title = 'Welcome';

	public function __construct()
	{
		parent::__construct();
		_check_login();
	}


	public function index()
	{
		$this->load->view('welcome_message');
	}
}
