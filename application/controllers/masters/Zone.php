<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Zone extends PS_Controller
{
  public $menu_code = 'DBZONE';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'WAREHOUSE';
	public $title = 'เพิ่ม/แก้ไข โซน';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/zone';
    $this->load->model('masters/zone_model');
    $this->load->helper('zone');
    $this->load->helper('warehouse');
  }

  public function index()
  {
    $filter = array(
      'code' => get_filter('code', 'code', ''),
      'name' => get_filter('name', 'name', ''),
      'warehouse' => get_filter('warehouse', 'warehouse', ''),
      'customer' => get_filter('customer', 'customer', '')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment  = 4; //-- url segment
		$rows     = $this->zone_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init = pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$list = $this->zone_model->get_list($filter, $perpage, $this->uri->segment($segment));

    if(!empty($list))
    {
      foreach($list as $rs)
      {
        $rs->customer_count = $this->zone_model->count_customer($rs->code);
      }
    }

    $filter['list'] = $list;

		$this->pagination->initialize($init);
    $this->load->view('masters/zone/zone_list', $filter);
  }



  public function edit($code)
  {
    if($this->pm->can_edit)
    {
      $zone = $this->zone_model->get($code);
      $ds['ds'] = $zone;
      $ds['customers'] = $this->zone_model->get_customers($code);
      $ds['employees'] = NULL;
      if($zone->role == 8)
      {
        $ds['employees'] = $this->zone_model->get_employee($code);
      }

      $this->load->view('masters/zone/zone_edit', $ds);
    }
    else
    {
      set_error("คุณไม่มีสิทธิ์แก้ไข");
      redirect($this->home);
    }
  }

  public function delete($code)
  {
    $sc = TRUE;
    if($this->pm->can_delete)
    {
      if($this->zone_model->count_customer($code) > 0)
      {
        $sc = FALSE;
        $this->error = "ไม่สามารถลบโซนได้เนื่องจากมีการเชื่อมโยงลูกค้าไว้";
      }
      else
      {
        if($this->zone_model->is_sap_exists($code))
        {
          $sc = FALSE;
          $this->error = "กรุณาลบโซนใน SAP ก่อน";
        }
      }

      if($sc === TRUE)
      {
        if( ! $this->zone_model->delete($code))
        {
          $sc = FALSE;
          $this->error = "ลบโซนไม่สำเร็จ";
        }
      }

    }
    else
    {
      $sc = FALSE;
      $this->error = "คุณไมมีสิทธิ์ลบโซน";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }




  public function add_customer()
  {
    $sc = TRUE;
    if($this->pm->can_edit)
    {
      if($this->input->post('zone_code') && $this->input->post('customer_code'))
      {
        $this->load->model('masters/customers_model');
        $code = $this->input->post('zone_code');
        $customer_code = $this->input->post('customer_code');
        $customer = $this->customers_model->get($customer_code);
        if(!empty($customer))
        {
          if($this->zone_model->is_exists_customer($code, $customer->code))
          {
            $sc = FALSE;
            $this->error = "มีลูกค้าในโซนนี้อยู่แล้ว";
          }
          else
          {
            $arr = array(
              'zone_code' => $code,
              'customer_code' => $customer->code,
              'customer_name' => $customer->name
            );

            if( ! $this->zone_model->add_customer($arr))
            {
              $sc = FALSE;
              $this->error = "เพิ่มลูกค้าไม่สำเร็จ";
            }
          }
        }
        else
        {
          $sc = FALSE;
          $this->error = "รหัสลูกค้าไม่ถูกต้อง";
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "ไม่พบข้อมูล";
      }

    }
    else
    {
      $sc = FALSE;
      $this->error = "คุณไม่มีสิทธิ์ในการเพิ่มข้อมูล";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function delete_customer($id)
  {
    $sc = TRUE;

    if($this->pm->can_edit)
    {
      if( ! $this->zone_model->delete_customer($id))
      {
        $sc = FALSE;
        $this->error = "ลบรายการไม่สำเร็จ";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "คุณไม่มีสิทธิ์ลบข้อมูล";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function add_employee()
  {
    $sc = TRUE;
    if($this->pm->can_edit)
    {
      if($this->input->post('zone_code') && $this->input->post('empID'))
      {
        $this->load->model('masters/employee_model');
        $code = $this->input->post('zone_code');
        $empName = $this->input->post('empName');
        $empID = $this->input->post('empID');
        $emp = $this->employee_model->get($empID);
        $zone = $this->zone_model->get($code);

        if($zone->role != 8)
        {
          $sc = FALSE;
          $this->error = "โซนนี้ไม่อยู่ในประเภทคลังยืมสินค้า";
        }

        if($sc === TRUE)
        {
          if(!empty($emp))
          {
            if($this->zone_model->is_exists_employee($code, $empID))
            {
              $sc = FALSE;
              $this->error = "มีพนักงานนี้ในโซนอยู่แล้ว";
            }
            else
            {
              $arr = array(
                'zone_code' => $code,
                'empID' => $empID,
                'empName' => $empName
              );

              if( ! $this->zone_model->add_employee($arr))
              {
                $sc = FALSE;
                $this->error = "เพิ่มพนักงานไม่สำเร็จ";
              }
            }
          }
          else
          {
            $sc = FALSE;
            $this->error = "ชื่อพนักงานไม่ถูกต้อง";
          }
        }
      }
      else
      {
        $sc = FALSE;
        $this->error = "ไม่พบข้อมูล";
      }

    }
    else
    {
      $sc = FALSE;
      $this->error = "คุณไม่มีสิทธิ์ในการเพิ่มข้อมูล";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }



  public function delete_employee($id)
  {
    $sc = TRUE;

    if($this->pm->can_edit)
    {
      if( ! $this->zone_model->delete_employee($id))
      {
        $sc = FALSE;
        $this->error = "ลบรายการไม่สำเร็จ";
      }
    }
    else
    {
      $sc = FALSE;
      $this->error = "คุณไม่มีสิทธิ์ลบข้อมูล";
    }

    echo $sc === TRUE ? 'success' : $this->error;
  }


  public function syncData()
  {
    $last_sync = $this->zone_model->get_last_sync_date();
    $newData = $this->zone_model->get_new_data($last_sync);

    if(!empty($newData))
    {
      foreach($newData as $rs)
      {
        if($this->zone_model->is_exists_id($rs->id))
        {
          $ds = array(
            'code' => $rs->code,
            'name' => is_null($rs->name) ? '' : $rs->name,
            'old_code' => $rs->old_code,
            'last_sync' => date('Y-m-d H:i:s'),
          );

          $this->zone_model->update($rs->id, $ds);
        }
        else
        {
          $ds = array(
            'id' => $rs->id,
            'code' => $rs->code,
            'name' => is_null($rs->name) ? '' : $rs->name,
            'warehouse_code' => $rs->warehouse_code,
            'last_sync' => date('Y-m-d H:i:s'),
            'old_code' => $rs->old_code
          );

          $this->zone_model->add($ds);
        }
      }
    }

    echo 'done';
  }



  //--- check zone
  public function get_zone_code()
  {
    $sc = TRUE;
    if($this->input->get('barcode'))
    {
      $barcode = trim($this->input->get('barcode'));
      $code = $this->zone_model->get_zone_code($barcode);

      if($code === FALSE)
      {
        $sc = FALSE;
      }
    }

    echo $sc === TRUE ? $code : 'not_exists';
  }



  public function get_warehouse_zone()
  {
    $sc = TRUE;
    $code = trim($this->input->get('barcode'));
    $warehouse_code = trim($this->input->get('warehouse_code'));
    if(!empty($code) && !empty($warehouse_code))
    {
      $zone = $this->zone_model->get_zone_detail_in_warehouse($code, $warehouse_code);
      if($zone === FALSE)
      {
        $sc = FALSE;
      }
    }

    echo $sc === TRUE ? json_encode($zone) : 'not_exists';
  }




  public function clear_filter()
  {
    $filter = array('code', 'name', 'customer', 'warehouse');
    clear_filter($filter);
  }

} //--- end class

 ?>
