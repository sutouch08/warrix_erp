<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends PS_Controller
{
  public $menu_code = 'DBPROD';
	public $menu_group_code = 'DB';
  public $menu_sub_group_code = 'PRODUCT';
	public $title = 'เพิ่ม/แก้ไข รายการสินค้า';

  public function __construct()
  {
    parent::__construct();
    $this->home = base_url().'masters/products';
    $this->load->model('masters/products_model');
    $this->load->model('masters/product_group_model');
    $this->load->model('masters/product_kind_model');
    $this->load->model('masters/product_type_model');
    $this->load->model('masters/product_style_model');
    $this->load->model('masters/product_brand_model');
    $this->load->model('masters/product_category_model');
    $this->load->model('masters/product_color_model');
    $this->load->model('masters/product_size_model');
  }


  public function index()
  {
    $filter = array(
      'barcode' => get_filter('barcode', 'barcode', ''),
      'code'    => get_filter('code', 'code', ''),
      'name'    => get_filter('name', 'name', ''),
      'style'   => get_filter('style', 'style', ''),
      'color'   => get_filter('color', 'color', ''),
      'size'    => get_filter('size', 'size', ''),
      'group'   => get_filter('group', 'group', ''),
      'category' => get_filter('kind', 'kind', ''),
      'kind'    => get_filter('category', 'category', ''),
      'type'    => get_filter('type', 'type', ''),
      'brand'   => get_filter('brand', 'brand', ''),
      'year'    =>  get_filter('year', 'year', '')
    );

		//--- แสดงผลกี่รายการต่อหน้า
		$perpage = get_rows();
		//--- หาก user กำหนดการแสดงผลมามากเกินไป จำกัดไว้แค่ 300
		if($perpage > 300)
		{
			$perpage = 20;
		}

		$segment = 4; //-- url segment
		$rows = $this->products_model->count_rows($filter);
		//--- ส่งตัวแปรเข้าไป 4 ตัว base_url ,  total_row , perpage = 20, segment = 3
		$init	= pagination_config($this->home.'/index/', $rows, $perpage, $segment);
		$products = $this->products_model->get_data($filter, $perpage, $this->uri->segment($segment));
    $ds = array();
    if(!empty($products))
    {
      foreach($products as $rs)
      {
        $product = new stdClass();
        $product->code    = $rs->code;
        $product->name    = $rs->name;
        $product->barcode = $rrs->barcode;
        $product->group   = $this->product_group_model->get_name($rs->group_code);
        $product->kind    = $this->product_kind_model->get_name($rs->kind_code);
        $product->type    = $this->product_type_model->get_name($rs->type_code);
        $product->category  = $this->product_category_model->get_name($rs->category_code);
        $product->style   = $this->product_style_model->get_name($rs->style_code);
        $product->color   = $this->product_color_model->get_name($rs->color_code);
        $product->size    = $this->product_size_model->get_name($rs->size_code);
        $product->brand   = $this->product_brand_model->get_name($rs->brand_code);
        $product->date_upd = $rs->date_upd;

        $ds[] = $cust;
      }
    }

    $filter['data'] = $ds;

		$this->pagination->initialize($init);
    $this->load->view('masters/products/products_view', $filter);
  }


  public function add_new()
  {
    $data['code'] = $this->session->flashdata('code');
    $data['name'] = $this->session->flashdata('name');
    $data['group'] = $this->session->flashdata('group');
    $data['kind'] = $this->session->flashdata('kind');
    $data['type'] = $this->session->flashdata('type');
    $data['class'] = $this->session->flashdata('class');
    $data['area'] = $this->session->flashdata('area');
    $this->title = 'เพิ่ม รายชื่อลูกค้า';
    $this->load->view('masters/products/products_add_view', $data);
  }


  public function add()
  {
    if($this->input->post('code'))
    {
      $sc = TRUE;
      $code = $this->input->post('code');
      $name = $this->input->post('name');

      $ds = array(
        'code' => $code,
        'name' => $name,
        'group_code' => $this->input->post('group'),
        'kind_code' => $this->input->post('kind'),
        'type_code' => $this->input->post('type'),
        'class_code' => $this->input->post('class'),
        'area_code' => $this->input->post('area')
      );

      if($this->products_model->is_exists($code) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$code."' มีในระบบแล้ว");
      }

      if($this->products_model->is_exists_name($name) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$name."' มีในระบบแล้ว");
      }

      if($sc === TRUE && $this->products_model->add($ds))
      {
        set_message('เพิ่มข้อมูลเรียบร้อยแล้ว');
      }
      else
      {
        $sc = FALSE;
        set_error('เพิ่มข้อมูลไม่สำเร็จ');
      }

      if($sc === FALSE)
      {
        $this->session->set_flashdata('code', $code);
        $this->session->set_flashdata('name', $name);
        $this->session->set_flashdata('group', $this->input->post('group'));
        $this->session->set_flashdata('kind', $this->input->post('kind'));
        $this->session->set_flashdata('type', $this->input->post('type'));
        $this->session->set_flashdata('class', $this->input->post('class'));
        $this->session->set_flashdata('area', $this->input->post('area'));
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
    $this->title = 'แก้ไข ข้อมูลลูกค้า';
    $rs = $this->products_model->get($code);
    $data = array(
      'code' => $rs->code,
      'name' => $rs->name,
      'group' => $rs->group_code,
      'kind' => $rs->kind_code,
      'type' => $rs->type_code,
      'class' => $rs->class_code,
      'area' => $rs->area_code
    );

    $this->load->view('masters/products/products_edit_view', $data);
  }



  public function update()
  {
    $sc = TRUE;

    if($this->input->post('code'))
    {
      $old_code = $this->input->post('products_code');
      $old_name = $this->input->post('products_name');
      $code = $this->input->post('code');
      $name = $this->input->post('name');

      $ds = array(
        'code' => $code,
        'name' => $name,
        'group_code' => $this->input->post('group'),
        'kind_code' => $this->input->post('kind'),
        'type_code' => $this->input->post('type'),
        'class_code' => $this->input->post('class'),
        'area_code' => $this->input->post('area')
      );

      if($sc === TRUE && $this->products_model->is_exists($code, $old_code) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$code."' มีอยู่ในระบบแล้ว โปรดใช้รหัสอื่น");
      }

      if($sc === TRUE && $this->products_model->is_exists_name($name, $old_name) === TRUE)
      {
        $sc = FALSE;
        set_error("'".$name."' มีอยู่ในระบบแล้ว โปรดใช้ชื่ออื่น");
      }

      if($sc === TRUE)
      {
        if($this->products_model->update($old_code, $ds) === TRUE)
        {
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
      $code = $this->input->post('products_code');
    }

    redirect($this->home.'/edit/'.$code);
  }



  public function delete($code)
  {
    if($code != '')
    {
      if($this->products_model->delete($code))
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



  public function syncData()
  {
    $ds = $this->products_model->get_updte_data();
    if(!empty($ds))
    {
      foreach($ds as $rs)
      {
        $arr = array(
          'code' => $rs->CardCode,
          'name' => $rs->CardName
        );

        $this->products_model->add($arr);
      }
    }

    set_message('Sync completed');
  }


  public function clear_filter()
	{
		$this->session->unset_userdata('code');
    $this->session->unset_userdata('name');
    $this->session->unset_userdata('group');
    $this->session->unset_userdata('kind');
    $this->session->unset_userdata('type');
    $this->session->unset_userdata('class');
    $this->session->unset_userdata('area');

		echo 'done';
	}
}

?>
