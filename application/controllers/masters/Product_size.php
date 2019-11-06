<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_size extends PS_Controller
{
  public $menu_code = 'DBPDSI';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'เพิ่ม/แก้ไข ไซส์';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/product_size';
    $this->load->model('masters/product_size_model');
  }


  public function index()
  {
		$code = get_filter('code', 'code', '');
		$name = get_filter('name', 'name', '');

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_filter('set_rows', 'rows', 20);
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = get_filter('rows', 'rows', 300);
		}

		$segment = 4; //-- url segment
		$rows = $this->product_size_model->count_rows($code, $name);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$size = $this->product_size_model->get_data($code, $name, $perpage, $this->uri->segment($segment));

    $data = array();

    if(!empty($size))
    {
      foreach($size as $rs)
      {
        $arr = new stdClass();
        $arr->code = $rs->code;
        $arr->name = $rs->name;
        $arr->position = $rs->position;
        $arr->menber = $this->product_size_model->count_members($rs->code);

        $data[] = $arr;
      }
    }


    $ds = array(
      'code' => $code,
      'name' => $name,
			'data' => $data
    );

		$this->pagination->initialize($init);
    $this->load->view('masters/product_size/product_size_view', $ds);
  }


  public function add_new()
  {
    $data['code'] = $this->session->flashdata('code');
    $data['name'] = $this->session->flashdata('name');
    $data['position'] = $this->session->flashdata('position');
    $this->title = 'เพิ่ม ไซส์';
    $this->load->view('masters/product_size/product_size_add_view', $data);
  }


  public function add()
  {
    $code = $this->input->post('code');
    if($code !== '' && $code !== NULL)
    {
      $sc = TRUE;
      $name = $this->input->post('name');
      $pos = $this->input->post('position') ? $this->input->post('position') : 1;
      $ds = array(
        'code' => $code,
        'name' => $name,
        'position' => $pos
      );

      if($this->product_size_model->is_exists($code) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$code."' มีในระบบแล้ว");
      }

      if($this->product_size_model->is_exists_name($name) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$name."' มีในระบบแล้ว");
      }

      if($sc === TRUE)
      {
        if($this->product_size_model->add($ds))
        {
          //-- export to sap
          $this->export_to_sap($code, $code);

          set_message('เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        else
        {
          $sc = FALSE;
          set_error('เพิ่มข้อมูลไม่สำเร็จ');
        }
      }


      if($sc === FALSE)
      {
        $this->session->set_flashdata('code', $code);
        $this->session->set_flashdata('name', $name);
        $this->session->set_flashdata('position', $pos);
      }
    }
    else
    {
      set_error('ไม่พบข้อมูล');
    }

    redirect($this->home.'/add_new');
  }



  public function edit($code)
  {
    $this->title = 'แก้ไข ไซส์';
    $rs = $this->product_size_model->get($code);
    $data = array(
      'code' => $rs->code,
      'name' => $rs->name,
      'position' => $rs->position
    );

    $this->load->view('masters/product_size/product_size_edit_view', $data);
  }



  public function update()
  {
    $sc = TRUE;
    $code = $this->input->post('code');
    if($code !== '')
    {
      $old_code = $this->input->post('product_size_code');
      $old_name = $this->input->post('product_size_name');
      $name = $this->input->post('name');
      $pos = $this->input->post('position');

      $ds = array(
        'code' => $code,
        'name' => $name,
        'position' => $pos
      );

      if($sc === TRUE && $this->product_size_model->is_exists($code, $old_code) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$code."' มีอยู่ในระบบแล้ว โปรดใช้รหัสอื่น");
      }

      if($sc === TRUE && $this->product_size_model->is_exists_name($name, $old_name) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$name."' มีอยู่ในระบบแล้ว โปรดใช้ชื่ออื่น");
      }

      if($sc === TRUE)
      {
        if($this->product_size_model->update($old_code, $ds) === TRUE)
        {
          //--- expor to sap
          $this->export_to_sap($code, $old_code);

          set_message('ปรับปรุงข้อมูลเรียบร้อยแล้ว');
        }
        else
        {
          $sc = FALSE;
          set_error('ปรับปรุงข้อมูลไม่สำเร็จ');
        }
      }

    }
    else
    {
      $sc = FALSE;
      set_error('ไม่พบข้อมูล');
    }

    if($sc === FALSE)
    {
      $code = $this->input->post('product_size_code');
    }

    redirect($this->home.'/edit/'.$code);
  }



  public function delete($code)
  {
    if($code != '')
    {
      if($this->product_size_model->delete($code))
      {
        set_message('ลบข้อมูลเรียบร้อยแล้ว');
      }
      else
      {
        set_error('ลบข้อมูลไม่สำเร็จ');
      }
    }
    else
    {
      set_error('ไม่พบข้อมูล');
    }

    redirect($this->home);
  }



  public function export_to_sap($code, $old_code)
  {
    $rs = $this->product_size_model->get($code);
    if(!empty($rs))
    {
      $ext = $this->product_size_model->is_sap_exists($old_code);

      $arr = array(
        'Code' => $rs->code,
        'Name' => $rs->name,
        'UpdateDate' => sap_date(now(), TRUE)
      );

      if($ext)
      {
        $arr['Flag'] = 'U';
        if($code !== $old_code)
        {
          $arr['OLDCODE'] = $old_code;
        }

        return $this->product_size_model->update_sap_size($old_code, $arr);
      }
      else
      {
        $arr['Flag'] = 'A';

        return $this->product_size_model->add_sap_size($arr);
      }
    }

    return FALSE;
  }



  public function clear_filter()
	{
		$this->session->unset_userdata('code');
    $this->session->unset_userdata('name');
		echo 'done';
	}

}//--- end class
 ?>
