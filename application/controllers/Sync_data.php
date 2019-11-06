<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_data extends CI_Controller
{
  public $title = 'Sync Data';
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
    $this->mc = $this->load->database('mc', TRUE); //--- Temp Database
    $this->load->model('sync_data_model');
    $this->date = date('Y-d-m H:i:s');
  }


  public function index()
  {
    $this->load->view('sync_data_view');
  }


  public function sync_customers()
  {
    $this->load->model('masters/customers_model');
    $date = date('Y-m-d 00:00:00');
    $ds = $this->customers_model->get_update_data($date);
    if(!empty($ds))
    {
      foreach($ds as $rs)
      {
        $arr = array(
          'code' => $rs->code,
          'name' => $rs->name,
          'Tax_Id' => $rs->Tax_Id,
          'DebPayAcct' => $rs->DebPayAcct,
          'CardType' => $rs->CardType,
          'GroupCode' => $rs->GroupCode,
          'cmpPrivate' => $rs->CmpPrivate,
          'GroupNum' => $rs->GroupNum,
          'sale_code' => $rs->sale_code,
          'CreditLine' => $rs->CreditLine
        );

        if($this->customers_model->is_exists($rs->code) === TRUE)
        {
          $this->customers_model->update($rs->code, $arr);
        }
        else
        {
          $this->customers_model->add($arr);
        }
      }
    }

    echo "success";
  }


  public function export_products()
  {
    $arr = array(
      'items_date' => $this->sync_data_model->get_item_last_date()
    );
    $this->load->view('export_products_view', $arr);
  }


  public function export_products_attribute()
  {
    $this->title = "Export Product Attribute";
    $arr = array(
      'style_date' => $this->sync_data_model->get_model_last_date(),
      'color_date' => $this->sync_data_model->get_color_last_date(),
      'size_date' => $this->sync_data_model->get_size_last_date(),
      'group_date' => $this->sync_data_model->get_group_last_date(),
      'sub_group_date' => $this->sync_data_model->get_sub_group_last_date(),
      'cate_date' => $this->sync_data_model->get_cate_last_date(),
      'kind_date' => $this->sync_data_model->get_kind_last_date(),
      'type_date' => $this->sync_data_model->get_type_last_date(),
      'brand_date' => $this->sync_data_model->get_brand_last_date()
    );

    $this->load->view('export_products_attribute_view', $arr);
  }



  public function count_update_color()
  {
    $date_upd = $this->input->get('date_upd');

    $rs = $this->db->count_all_results('product_color');
    echo $rs;
  }


  public function get_update_color($offset)
  {
    $this->load->model('masters/product_color_model');

    $date_upd = $this->input->get('date_upd');
    $list = $this->db->limit($this->limit, $offset)->get('product_color');

    $count = 0;

    if($list->num_rows() > 0)
    {
      foreach($list->result() as $rs)
      {
        $ex = $this->product_color_model->is_sap_exists($rs->code);

        $arr = array(
          'Code' => $rs->code,
          'Name' => $rs->name,
          'Flag' => $ex === TRUE ? 'U' : 'A',
          'UpdateDate' => sap_date(now(), TRUE)
        );

        if($ex)
        {
          $this->product_color_model->update_sap_color($rs->code, $arr);
        }
        else
        {
          $this->product_color_model->add_sap_color($arr);
        }

        $count++;
      }
    }

    echo $count;
  }



  public function count_update_size()
  {
    $date_upd = $this->input->get('date_upd');

    $rs = $this->db->count_all_results('product_size');
    echo $rs;
  }


  public function get_update_size($offset)
  {
    $this->load->model('masters/product_size_model');

    $date_upd = $this->input->get('date_upd');
    $list = $this->db->limit($this->limit, $offset)->get('product_size');

    $count = 0;

    if($list->num_rows() > 0)
    {
      foreach($list->result() as $rs)
      {
        $ex = $this->product_size_model->is_sap_exists($rs->code);

        $arr = array(
          'Code' => $rs->code,
          'Name' => $rs->name,
          'Flag' => $ex === TRUE ? 'U' : 'A',
          'UpdateDate' => sap_date(now(), TRUE)
        );

        if($ex)
        {
          $this->product_size_model->update_sap_size($rs->code, $arr);
        }
        else
        {
          $this->product_size_model->add_sap_size($arr);
        }

        $count++;
      }
    }

    echo $count;
  }



  public function count_update_group()
  {
    $date_upd = $this->input->get('date_upd');

    $rs = $this->db->count_all_results('product_group');
    echo $rs;
  }


  public function get_update_group($offset)
  {
    $this->load->model('masters/product_group_model');

    $date_upd = $this->input->get('date_upd');
    $list = $this->db->limit($this->limit, $offset)->get('product_group');

    $count = 0;

    if($list->num_rows() > 0)
    {
      foreach($list->result() as $rs)
      {
        $ex = $this->product_group_model->is_sap_exists($rs->code);

        $arr = array(
          'Code' => $rs->code,
          'Name' => $rs->name,
          'Flag' => $ex === TRUE ? 'U' : 'A',
          'UpdateDate' => sap_date(now(), TRUE)
        );

        if($ex)
        {
          $this->product_group_model->update_sap_product_group($rs->code, $arr);
        }
        else
        {
          $this->product_group_model->add_sap_product_group($arr);
        }

        $count++;
      }
    }

    echo $count;
  }




  public function count_update_sub_group()
  {
    $date_upd = $this->input->get('date_upd');

    $rs = $this->db->count_all_results('product_sub_group');
    echo $rs;
  }


  public function get_update_sub_group($offset)
  {
    $this->load->model('masters/product_sub_group_model');

    $date_upd = $this->input->get('date_upd');
    $list = $this->db->limit($this->limit, $offset)->get('product_sub_group');

    $count = 0;

    if($list->num_rows() > 0)
    {
      foreach($list->result() as $rs)
      {
        $ex = $this->product_sub_group_model->is_sap_exists($rs->code);

        $arr = array(
          'Code' => $rs->code,
          'Name' => $rs->name,
          'Flag' => $ex === TRUE ? 'U' : 'A',
          'UpdateDate' => sap_date(now(), TRUE)
        );

        if($ex)
        {
          $this->product_sub_group_model->update_sap_major($rs->code, $arr);
        }
        else
        {
          $this->product_sub_group_model->add_sap_major($arr);
        }

        $count++;
      }
    }

    echo $count;
  }


  public function count_update_cate()
  {
    $date_upd = $this->input->get('date_upd');

    $rs = $this->db->count_all_results('product_category');
    echo $rs;
  }


  public function get_update_cate($offset)
  {
    $this->load->model('masters/product_category_model');

    $date_upd = $this->input->get('date_upd');
    $list = $this->db->limit($this->limit, $offset)->get('product_category');

    $count = 0;

    if($list->num_rows() > 0)
    {
      foreach($list->result() as $rs)
      {
        $ex = $this->product_category_model->is_sap_exists($rs->code);

        $arr = array(
          'Code' => $rs->code,
          'Name' => $rs->name,
          'Flag' => $ex === TRUE ? 'U' : 'A',
          'UpdateDate' => sap_date(now(), TRUE)
        );

        if($ex)
        {
          $this->product_category_model->update_sap_cate($rs->code, $arr);
        }
        else
        {
          $this->product_category_model->add_sap_cate($arr);
        }

        $count++;
      }
    }

    echo $count;
  }



  public function count_update_kind()
  {
    $date_upd = $this->input->get('date_upd');

    $rs = $this->db->count_all_results('product_kind');
    echo $rs;
  }


  public function get_update_kind($offset)
  {
    $this->load->model('masters/product_kind_model');

    $date_upd = $this->input->get('date_upd');
    $list = $this->db->limit($this->limit, $offset)->get('product_kind');

    $count = 0;

    if($list->num_rows() > 0)
    {
      foreach($list->result() as $rs)
      {
        $ex = $this->product_kind_model->is_sap_exists($rs->code);

        $arr = array(
          'Code' => $rs->code,
          'Name' => $rs->name,
          'Flag' => $ex === TRUE ? 'U' : 'A',
          'UpdateDate' => sap_date(now(), TRUE)
        );

        if($ex)
        {
          $this->product_kind_model->update_sap_subtype($rs->code, $arr);
        }
        else
        {
          $this->product_kind_model->add_sap_subtype($arr);
        }

        $count++;
      }
    }

    echo $count;
  }



  public function count_update_type()
  {
    $date_upd = $this->input->get('date_upd');

    $rs = $this->db->count_all_results('product_type');
    echo $rs;
  }


  public function get_update_type($offset)
  {
    $this->load->model('masters/product_type_model');

    $date_upd = $this->input->get('date_upd');
    $list = $this->db->limit($this->limit, $offset)->get('product_type');

    $count = 0;

    if($list->num_rows() > 0)
    {
      foreach($list->result() as $rs)
      {
        $ex = $this->product_type_model->is_sap_exists($rs->code);

        $arr = array(
          'Code' => $rs->code,
          'Name' => $rs->name,
          'Flag' => $ex === TRUE ? 'U' : 'A',
          'UpdateDate' => sap_date(now(), TRUE)
        );

        if($ex)
        {
          $this->product_type_model->update_sap_type($rs->code, $arr);
        }
        else
        {
          $this->product_type_model->add_sap_type($arr);
        }

        $count++;
      }
    }

    echo $count;
  }



  public function count_update_brand()
  {
    $date_upd = $this->input->get('date_upd');

    $rs = $this->db->count_all_results('product_brand');
    echo $rs;
  }


  public function get_update_brand($offset)
  {
    $this->load->model('masters/product_brand_model');

    $date_upd = $this->input->get('date_upd');
    $list = $this->db->limit($this->limit, $offset)->get('product_brand');

    $count = 0;

    if($list->num_rows() > 0)
    {
      foreach($list->result() as $rs)
      {
        $ex = $this->product_brand_model->is_sap_exists($rs->code);

        $arr = array(
          'Code' => $rs->code,
          'Name' => $rs->name,
          'Flag' => $ex === TRUE ? 'U' : 'A',
          'UpdateDate' => sap_date(now(), TRUE)
        );

        if($ex)
        {
          $this->product_brand_model->update_sap_brand($rs->code, $arr);
        }
        else
        {
          $this->product_brand_model->add_sap_brand($arr);
        }

        $count++;
      }
    }

    echo $count;
  }



  public function count_update_style()
  {
    $date_upd = $this->input->get('date_upd');

    $rs = $this->db->where('date_upd >', $date_upd)->count_all_results('product_style');
    echo $rs;
  }


  public function get_update_style($offset)
  {
    $this->load->model('masters/product_style_model');

    $date_upd = $this->input->get('date_upd');
    $list = $this->db->where('date_upd >', $date_upd)->limit($this->limit, $offset)->get('product_style');

    $count = 0;

    if($list->num_rows() > 0)
    {
      foreach($list->result() as $rs)
      {
        $ex = $this->product_style_model->is_sap_exists($rs->code);

        $arr = array(
          'Code' => $rs->code,
          'Name' => $rs->name,
          'Flag' => $ex === TRUE ? 'U' : 'A',
          'UpdateDate' => sap_date(now(), TRUE)
        );

        if($ex)
        {
          $this->product_style_model->update_sap_model($rs->code, $arr);
        }
        else
        {
          $this->product_style_model->add_sap_model($arr);
        }

        $count++;
      }
    }

    echo $count;
  }


  public function count_update_items()
  {
    $date_upd = $this->input->get('date_upd');

    $rs = $this->db->where('date_upd >', $date_upd)->count_all_results('products');
    echo $rs;
  }


  public function get_update_items($offset)
  {
    $this->load->model('masters/products_model');

    $date_upd = $this->input->get('date_upd');
    $list = $this->db->where('date_upd >', $date_upd)->limit($this->limit, $offset)->get('products');

    $count = 0;

    if($list->num_rows() > 0)
    {
      foreach($list->result() as $item)
      {
        $ds = array(
          'ItemCode' => $item->code, //--- รหัสสินค้า
          'ItemName' => $item->name, //--- ชื่อสินค้า
          'FrgnName' => NULL,   //--- ชื่อสินค้าภาษาต่างประเทศ
          'ItmsGrpCod' => getConfig('ITEM_GROUP_CODE'),  //--- กลุ่มสินค้า (ต้องตรงกับ SAP)
          'VatGourpSa' => getConfig('SALE_VATE_CODE'), //--- รหัสกลุ่มภาษีขาย
          'CodeBars' => $item->barcode, //--- บาร์โค้ด
          'VATLiable' => 'Y', //--- มี vat หรือไม่
          'PrchseItem' => 'Y', //--- สินค้าสำหรับซื้อหรือไม่
          'SellItem' => 'Y', //--- สินค้าสำหรับขายหรือไม่
          'InvntItem' => $item->count_stock, //--- นับสต้อกหรือไม่
          'SalUnitMsr' => $item->unit_code, //--- หน่วยขาย
          'BuyUnitMsr' => $item->unit_code, //--- หน่วยซื้อ
          'VatGroupPu' => getConfig('PURCHASE_VAT_CODE'), //---- รหัสกลุ่มภาษีซื้อ (ต้องตรงกับ SAP)
          'ItemType' => 'I', //--- ประเภทของรายการ F=Fixed Assets, I=Items, L=Labor, T=Travel
          'InvntryUom' => $item->unit_code, //--- หน่วยในการนับสต็อก
          'U_MODEL' => $item->style_code,
          'U_COLOR' => $item->color_code,
          'U_SIZE' => $item->size_code,
          'U_GROUP' => $item->group_code,
          'U_MAJOR' => $item->sub_group_code,
          'U_CATE' => $item->category_code,
          'U_SUBTYPE' => $item->kind_code,
          'U_TYPE' => $item->type_code,
          'U_BRAND' => $item->brand_code,
          'U_YEAR' => $item->year,
          'U_COST' => $item->cost,
          'U_PRICE' => $item->price
        );

        if($this->products_model->sap_item_exists($item->code))
        {
          $this->products_model->update_item($item->code, $ds);
        }
        else
        {
          $this->products_model->add_item($ds);
        }

        $count++;
      }
    }

    echo $count;
  }


  public function do_export($code)
  {
    $item = $this->products_model->get($code);
    $ds = array(
      'ItemCode' => $item->code, //--- รหัสสินค้า
      'ItemName' => $item->name, //--- ชื่อสินค้า
      'FrgnName' => NULL,   //--- ชื่อสินค้าภาษาต่างประเทศ
      'ItmsGrpCod' => getConfig('ITEM_GROUP_CODE'),  //--- กลุ่มสินค้า (ต้องตรงกับ SAP)
      'VatGourpSa' => getConfig('SALE_VATE_CODE'), //--- รหัสกลุ่มภาษีขาย
      'CodeBars' => $item->barcode, //--- บาร์โค้ด
      'VATLiable' => 'Y', //--- มี vat หรือไม่
      'PrchseItem' => 'Y', //--- สินค้าสำหรับซื้อหรือไม่
      'SellItem' => 'Y', //--- สินค้าสำหรับขายหรือไม่
      'InvntItem' => $item->count_stock, //--- นับสต้อกหรือไม่
      'SalUnitMsr' => $item->unit_code, //--- หน่วยขาย
      'BuyUnitMsr' => $item->unit_code, //--- หน่วยซื้อ
      'VatGroupPu' => getConfig('PURCHASE_VAT_CODE'), //---- รหัสกลุ่มภาษีซื้อ (ต้องตรงกับ SAP)
      'ItemType' => 'I', //--- ประเภทของรายการ F=Fixed Assets, I=Items, L=Labor, T=Travel
      'InvntryUom' => $item->unit_code, //--- หน่วยในการนับสต็อก
      'U_MODEL' => $item->style_code,
      'U_COLOR' => $item->color_code,
      'U_SIZE' => $item->size_code,
      'U_GROUP' => $item->group_code,
      'U_MAJOR' => $item->sub_group_code,
      'U_CATE' => $item->category_code,
      'U_SUBTYPE' => $item->kind_code,
      'U_TYPE' => $item->type_code,
      'U_BRAND' => $item->brand_code,
      'U_YEAR' => $item->year,
      'U_COST' => $item->cost,
      'U_PRICE' => $item->price
    );

    if($this->products_model->sap_item_exists($item->code))
    {
      return $this->products_model->update_item($item->code, $ds);
    }
    else
    {
      return $this->products_model->add_item($ds);
    }

  }

} //--- end class

 ?>
