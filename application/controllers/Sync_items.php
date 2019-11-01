<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_items extends CI_Controller
{
  public $title = 'Sync Items';
	public $menu_code = '';
	public $menu_group_code = '';
	public $pm;
  public $limit = 10;
  public $date;

  public function __construct()
  {
    parent::__construct();
    _check_login();
		$this->pm = new stdClass();
		$this->pm->can_view = 1;

    $this->ms = $this->load->database('ms', TRUE); //--- SAP database
    //$this->mc = $this->load->database('mc', TRUE); //--- Temp Database
    $this->date = date('Y-d-m H:i:s');
    $this->load->model('masters/product_style_model');
    $this->load->model('masters/products_model');
  }

  public function index()
  {
    $this->load->view('sync_products_view');
  }


  public function sync_items()
  {
    $this->load->model('masters/products_model');
  }


  public function sync_style()
  {
    $this->load->model('masters/product_style_model');
  }


  public function count_update_style()
  {
    $date_add = $this->input->get('date_add');
    $date_upd = $this->input->get('date_upd');
    $count = $this->product_style_model->count_sap_list($date_add, $date_upd);
    echo $count;
  }

  public function get_update_style($offset)
  {
    $date_add = $this->input->get('date_add');
    $date_upd = $this->input->get('date_upd');
    $list = $this->product_style_model->get_sap_list($date_add, $date_upd, $this->limit, $offset);
    $count = 0;

    if(!empty($list))
    {
      foreach($list as $ds)
      {
        $rs = $this->product_style_model->get_sap_style($ds->U_MODEL);
        $arr = array(
          'code' => $rs->U_MODEL,
          'name' => $rs->U_MODEL,
          'group_code' => $rs->U_GROUP,
          'sub_group_code' => $rs->U_MAJOR,
          'category_code' => $rs->U_CATE,
          'kind_code' => $rs->U_SUBTYPE,
          'type_code' => $rs->U_TYPE,
          'brand_code' => $rs->U_BRAND,
          'year' => $rs->U_YEAR,
          'cost' => 0,
          'price' => 0,
          'unit_code' => $rs->InvntryUom === NULL ? 'PCS' : $rs->InvntryUom,
          'count_stock' => $rs->InvntItem === 'Y' ? 1 :0
        );

        if($this->product_style_model->is_exists($rs->U_MODEL))
        {
          $this->product_style_model->update($rs->U_MODEL, $arr);
        }
        else
        {
          $this->product_style_model->add($arr);
        }

        $count++;
      }
    }

    echo $count;
  }


  public function count_update_items()
  {
    $date_add = $this->input->get('date_add');
    $date_upd = $this->input->get('date_upd');
    $count = $this->products_model->count_sap_update_list($date_add, $date_upd);
    echo $count;
  }


  public function get_update_items($offset)
  {
    $date_add = $this->input->get('date_add');
    $date_upd = $this->input->get('date_upd');
    $list = $this->products_model->get_sap_list($date_add, $date_upd, $this->limit, $offset);
    $count = 0;
    if(!empty($list))
    {
      foreach($list as $rs)
      {
        $arr = array(
          'code' => $rs->ItemCode,
          'name' => $rs->ItemName,
          'barcode' => $rs->CodeBars,
          'style_code' => $rs->U_MODEL,
          'color_code' => $rs->U_COLOR,
          'size_code' => $rs->U_SIZE,
          'group_code' => $rs->U_GROUP,
          'sub_group_code' => $rs->U_MAJOR,
          'category_code' => $rs->U_CATE,
          'kind_code' => $rs->U_SUBTYPE,
          'type_code' => $rs->U_TYPE,
          'brand_code' => $rs->U_BRAND,
          'year' => $rs->U_YEAR,
          'cost' => 0,
          'price' => 0,
          'unit_code' => $rs->InvntryUom === NULL ? 'PCS' : $rs->InvntryUom,
          'count_stock' => $rs->InvntItem === 'Y' ? 1 : 0,
          'update_user' => get_cookie('uname')
        );

        if($this->products_model->is_exists($rs->ItemCode))
        {
          $this->products_model->update($rs->ItemCode, $arr);
        }
        else
        {
          $this->products_model->add($arr);
        }

        $count++;
      }
    }

    echo $count;
  }


  public function get_style_last_date()
  {
    $arr = array(
      'date_add' => sap_date($this->product_style_model->get_style_last_add()),
      'date_upd' => sap_date($this->product_style_model->get_style_last_update())
    );
    echo json_encode($arr);
  }


  public function get_item_last_date()
  {
    $arr = array(
      'date_add' => sap_date($this->products_model->get_items_last_add()),
      'date_upd' => sap_date($this->products_model->get_items_last_update())
    );
    echo json_encode($arr);
  }

} //--- end class

 ?>
