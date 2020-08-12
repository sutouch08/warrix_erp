<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Check_stock_diff extends PS_Controller
{
  public $menu_code = 'ICSTDF';
	public $menu_group_code = 'IC';
  public $menu_sub_group_code = '';
	public $title = 'ตรวจนับสต็อก(เก็บยอดต่าง)';
  public $filter;
  public $error;
  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'inventory/check_stock_diff';
    $this->load->model('inventory/check_stock_diff_model');
    $this->load->model('inventory/buffer_model');
    $this->load->model('masters/products_model');
    $this->load->model('masters/zone_model');
  }


  public function index()
  {
    $filter = array(
      'product_code' => get_filter('product_code', 'check_product_code', ''),
      'zone_code' => get_filter('zone_code', 'check_zone_code', ''),
      'status' => get_filter('status', 'check_status', 'all'),
      'from_date' => get_filter('from_date', 'check_from_date', ''),
      'to_date' => get_filter('to_date', 'check_to_date', ''),
      'user' => get_filter('user', 'check_user', '')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->check_stock_diff_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	    = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$ds   = $this->check_stock_diff_model->get_list($filter, $perpage, $this->uri->segment($segment));

    $filter['data'] = $ds;

		$this->pagination->initialize($init);
    $this->load->view('inventory/check_stock_diff/stock_diff_view', $filter);
  }




  public function check()
  {
    //print_r($this->input->post());
    $zone_code = $this->input->post('zone_code');
    $product_code = $this->input->post('product_code');
    $zone = !empty($zone_code) ? $this->zone_model->get($zone_code) : NULL;

    if(!empty($zone))
    {
      $details = $this->check_stock_diff_model->get_stock_and_diff($zone_code, $product_code);
      if(!empty($details))
      {
        //---- loop and add diff qty
        foreach($details as $rs)
        {
          $diff_qty = $this->check_stock_diff_model->get_active_diff($zone_code, $rs->ItemCode);
          $buffer_qty = $this->buffer_model->get_buffer_zone($zone_code, $rs->ItemCode);
          $rs->diff_qty = $diff_qty;
          $rs->count_qty = ($rs->OnHandQty - $buffer_qty) + $diff_qty;
          $rs->OnHandQty = ($rs->OnHandQty - $buffer_qty);
        }
      }
    }

    $ds['zone_code'] = !empty($zone) ? $zone->code : NULL;
    $ds['product_code'] = $product_code;
    $ds['zone_name'] = !empty($zone) ? $zone->name : NULL;
    $ds['details'] = !empty($details) ? $details : NULL;

    $this->load->view('inventory/check_stock_diff/check_process', $ds);
  }


  ///----- check zone_exists or not
  public function is_exists_zone()
  {
    $zone_code = $this->input->get('zone_code');
    if($this->zone_model->is_exists($zone_code))
    {
      echo "ok";
    }
    else
    {
      echo "not_exists";
    }
  }

  public function export()
  {
    $arr = array(
      'item_code' => $this->input->post('item'),
      'zone_code' => $this->input->post('zone'),
      'show_system' => $this->input->post('system')
    );

    $token = $this->input->post('token');

    $data = $this->check_stock_diff_model->get_list($arr);
    if(!empty($data))
    {
      //--- load excel library
      $this->load->library('excel');

      $this->excel->setActiveSheetIndex(0);
      $this->excel->getActiveSheet()->setTitle('Stock Zone (SAP)');

      $this->excel->getActiveSheet()->setCellValue('A1', 'No.');
      $this->excel->getActiveSheet()->setCellValue('B1', 'ItemCode');
      $this->excel->getActiveSheet()->setCellValue('C1', 'OldCode');
      $this->excel->getActiveSheet()->setCellValue('D1', 'Description');
      $this->excel->getActiveSheet()->setCellValue('E1', 'BinCode');
      $this->excel->getActiveSheet()->setCellValue('F1', 'Bin Description');
      $this->excel->getActiveSheet()->setCellValue('G1', 'Qty');

      $no = 1;
      $row = 2;
      foreach($data as $rs)
      {
        $this->excel->getActiveSheet()->setCellValue('A'.$row, $no);
        $this->excel->getActiveSheet()->setCellValue('B'.$row, $rs->ItemCode);
        if(!empty($rs->U_OLDCODE))
        {
          $this->excel->getActiveSheet()->setCellValue('C'.$row, $rs->U_OLDCODE);
        }

        $this->excel->getActiveSheet()->setCellValue('D'.$row, $rs->ItemName);
        $this->excel->getActiveSheet()->setCellValue('E'.$row, $rs->BinCode);
        $this->excel->getActiveSheet()->setCellValue('F'.$row, $rs->Descr);
        $this->excel->getActiveSheet()->setCellValue('G'.$row, $rs->OnHandQty);
        $no++;
        $row++;
      }
    }

    setToken($token);
    $file_name = "StockZone(SAP).xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); /// form excel 2007 XLSX
    header('Content-Disposition: attachment;filename="'.$file_name.'"');
    $writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
    $writer->save('php://output');
  }


  function clear_filter(){
    $filter = array('check_product_code', 'check_zone_code', 'check_from_date', 'check_to_date', 'check_status', 'check_user');
    clear_filter($filter);
    echo 'done';
  }

} //--- end class
?>
