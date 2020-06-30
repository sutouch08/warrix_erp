<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends PS_Controller
{
	public $title = 'Welcome';
	public $menu_code = '';
	public $menu_group_code = '';
	public $pm;
	public function __construct()
	{
		parent::__construct();
		_check_login();
		$this->pm = new stdClass();
		$this->pm->can_view = 1;
	}


	public function index()
	{
		$uid = get_cookie('uid');
		//$id_profile = get_cookie('id_profile');
		$WC = get_permission('SOCCSO', $uid);
		$WT = get_permission('SOCCTR', $uid);
		$WS = get_permission('SOODSP', $uid);
		$WU = get_permission('ICSUPP', $uid);
		$WQ = get_permission('ICTRFM', $uid);

		$ds = array(
			'WC' => $WC,
			'WT' => $WT,
			'WS' => $WS,
			'WU' => $WU,
			'WQ' => $WQ,
			'refresh_rate' => 300000,
			'limit_rows' => 10
		);


		$this->load->view('main_view', $ds);
	}
}
