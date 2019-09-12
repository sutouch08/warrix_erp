<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Warehouse extends PS_Controller
{
  public $menu_code = 'DBWRHS';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'WAREHOUSE';
	public $title = 'เพิ่ม/แก้ไข คลังสินค้า';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/warehouse';
    $this->load->model('masters/warehouse_model');
    $this->load->helper('warehouse');
  }

  public function index()
  {
    $filter = array(
      'code' => get_filter('code', 'code', ''),
      'name' => get_filter('name', 'name', ''),
      'role' => get_filter('role', 'role', '')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->warehouse_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$list = $this->warehouse_model->get_list($filter, $perpage, $this->uri->segment($segment));

    if(!empty($list))
    {
      foreach($list as $rs)
      {
        $rs->zone_count = $this->warehouse_model->count_zone($rs->code);
      }
    }

    $filter['list'] = $list;

		$this->pagination->initialize($init);
    $this->load->view('masters/warehouse/warehouse_list', $filter);
  }



  public function edit($code)
  {
    if($this->pm->can_edit)
    {
      $ds['ds'] = $this->warehouse_model->get($code);
      $this->load->view('masters/warehouse/warehouse_edit', $ds);
    }
    else
    {
      set_error("คุณไม่มีสิทธิ์แก้ไขคลังสินค้า");
      redirect($this->home);
    }
  }



  public function update()
  {
    if($this->pm->can_edit)
    {
      if($this->input->post('code'))
      {
        $code = $this->input->post('code');
        $arr = array(
          'role' => $this->input->post('role'),
          'sell' => $this->input->post('sell'),
          'prepare' => $this->input->post('prepare'),
          'auz' => $this->input->post('auz'),
          'active' => $this->input->post('active'),
          'update_user' => get_cookie('uname')
        );

        if($this->warehouse_model->update($code, $arr))
        {
          set_message("Update Successfull");
          redirect($this->home.'/edit/'.$code);
        }
        else
        {
          set_error("Update Fail");
          redirect($this->home.'/edit/'.$code);
        }
      }
      else
      {
        set_error('ไม่พบรหัสคลังสินค้า');
        redirect($this->home);
      }
    }
    else
    {
      set_error('คุณไม่มีสิทธิ์แก้ไขคลังสินค้า');
      redirect($this->home);
    }
  }


  public function delete($code)
  {
    $sc = TRUE;

    if($this->pm->can_delete)
    {
      //---- count member if exists reject action
      if($this->warehouse_model->has_zone($code))
      {
        $sc = FALSE;
        $this->error = 'ไม่สามารถลบคลังได้เนื่องจากยังมีโซนอยู่';
      }
      //--- check warehouse in SAP if exists reject action
      else if($this->warehouse_model->is_sap_exists($code))
      {
        $sc = FALSE;
        $this->error = 'ไม่สามารถลบคลังได้เนื่องจากยังไม่ได้ลบคลังใน SAP';
      }
      else
      {
        if($this->warehouse_model->delete($code) === FALSE)
        {
          $sc = FALSE;
          $this->error = 'ลบคลังไม่สำเร็จ';
        }
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = 'คุณไม่มีสิทธิ์ลบคลังสินค้า';
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }


  public function syncData()
  {
    $count = $this->warehouse_model->count_rows();
    if($count > 0 )
    {
      $last_add = $this->warehouse_model->get_last_create_date();
      $last_upd = $this->warehouse_model->get_last_update_date();

      $last_add = empty($last_add) ? now() : $last_add;
      $last_upd = empty($last_upd) ? now() : $last_upd;

      $newData = $this->warehouse_model->get_new_data($last_add, $last_upd);
    }
    else
    {
      $last_add = date('1970-01-01 00:00:00');
      $newData = $this->warehouse_model->get_all_warehouse();
    }

    if(!empty($newData))
    {
      foreach($newData as $rs)
      {
        if($this->warehouse_model->is_exists($rs->code))
        {
          $ds = array(
            'name' => $rs->name,
            'sap_updateDate' => $rs->updateDate,
            'update_user' => 'SAP'
          );

          $this->warehouse_model->update($rs->code, $ds);
        }
        else
        {
          $ds = array(
            'code' => $rs->code,
            'name' => $rs->name,
            'sap_createDate' => $rs->createDate,
            'sap_updateDate' => $rs->updateDate,
            'update_user' => 'SAP'
          );

          $this->warehouse_model->add($ds);
        }
      }
    }

    echo 'done';
  }



  public function clear_filter()
  {
    $filter = array('code', 'name', 'role');
    clear_filter($filter);
  }

} //--- end class

 ?>
