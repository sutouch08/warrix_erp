<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_complete extends CI_Controller
{
  public $ms;
  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE);
  }


  // public function get_customer_code_and_name()
  // {
  //   $txt = $_REQUEST['term'];
  //   $sc = array();
  //   $rs = $this->ms
  //   ->select('CardCode, CardName')
  //   ->where('CardType', 'C')
  //   ->like('CardCode', $txt)
  //   ->or_like('CardName', $txt)
  //   ->get('OCRD');
  //
  //   if($rs->num_rows() > 0)
  //   {
  //     foreach($rs->result() as $rd)
  //     {
  //       $sc[] = $rd->CardCode.' | '.$rd->CardName;
  //     }
  //   }
  //
  //   echo json_encode($sc);
  // }


  public function get_customer_code_and_name()
  {
    $txt = $_REQUEST['term'];
    $sc = array();
    $rs = $this->db
    ->select('code, name')
    ->where('CardType', 'C')
    ->like('code', $txt)
    ->or_like('name', $txt)
    ->limit(20)
    ->get('customers');

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $rd)
      {
        $sc[] = $rd->code.' | '.$rd->name;
      }
    }

    echo json_encode($sc);
  }




public function get_style_code()
{
  $sc = array();

	$qr  = "SELECT code FROM product_style WHERE code LIKE '%".$_REQUEST['term']."%' ";
	$qr .= "AND active = 1 AND can_sell = 1 AND is_deleted = 0 ORDER BY code ASC";
  $qs = $this->db->query($qr);

  if($qs->num_rows() > 0)
  {
    foreach($qs->result() as $rs)
    $sc[] = $rs->code;
  }

	echo json_encode($sc);
}




  public function sub_district()
  {
    $sc = array();
    $adr = $this->db->like('tumbon', $_REQUEST['term'])->limit(20)->get('address_info');
    if($adr->num_rows() > 0)
    {
      foreach($adr->result() as $rs)
      {
        $sc[] = $rs->tumbon.'>>'.$rs->amphur.'>>'.$rs->province.'>>'.$rs->zipcode;
      }
    }

    echo json_encode($sc);
  }


  public function district()
  {
    $sc = array();
    $adr = $this->db->select("amphur, province, zipcode")
    ->like('amphur', $_REQUEST['term'])
    ->group_by('amphur')
    ->group_by('province')
    ->limit(20)->get('address_info');
    if($adr->num_rows() > 0)
    {
      foreach($adr->result() as $rs)
      {
        $sc[] = $rs->amphur.'>>'.$rs->province.'>>'.$rs->zipcode;
      }
    }

    echo json_encode($sc);
  }




  public function get_vendor_code_and_name()
  {
    $sc = array();
    $vendor = $this->ms
    ->select('CardCode, CardName')
    ->where('CardType', 'S')
    ->like('CardCode', $_REQUEST['term'])
    ->or_like('CardName', $_REQUEST['term'])
    ->limit(20)
    ->get('OCRD');

    if($vendor->num_rows() > 0)
    {
      foreach($vendor->result() as $rs)
      {
        $sc[] = $rs->CardCode.' | '.$rs->CardName;
      }
    }

    echo json_encode($sc);
  }



  //---- ค้นหาใบเบิกสินค้าแปรสภาพ
  //---- $all : TRUE => ทุกสถานะ
  //---- $all : FALSE => เฉพาะที่ยังไม่ปิด
  public function get_transform_code($all = FALSE)
  {
    $txt = $_REQUEST['term'];
    $sc = array();

    if($all === FALSE)
    {
      $this->db->where('is_closed', 0);
    }

    if($txt != '*')
    {
      $this->db->like('order_code', $txt);
    }

    $this->db->limit(20);
    $code = $this->db->get('order_transform');
    if($code->num_rows() > 0)
    {
      foreach($code->result() as $rs)
      {
        $sc[] = $rs->order_code;
      }
    }
    else
    {
      $sc[] = 'ไม่พบข้อมูล';
    }

    echo json_encode($sc);
  }



  public function get_po_code($vendor = FALSE)
  {
    $sc = array();
    $txt = $_REQUEST['term'];
    $this->ms->select('DocNum')->where('DocStatus', 'O');
    if($vendor !== FALSE)
    {
      $this->ms->where('CardCode', $vendor);
    }

    if($txt != '*')
    {
      $this->ms->like('DocNum', $txt);
    }

    $po = $this->ms->get('OPOR');

    if(!empty($po))
    {
      foreach($po->result() as $rs)
      {
        $sc[] = 'PO | '.$rs->DocNum;
      }
    }

    echo json_encode($sc);
  }



  public function get_valid_lend_code($customer_code = '')
  {
    $sc = array();
    $txt = $_REQUEST['term'];
    $this->db->select('order_code');
    if($txt != '*')
    {
      $this->db->like('order_code', $txt);
    }

    if(!empty($customer_code))
    {
      $this->db->where('customer_code', $customer_code);
    }

    $this->db->where('valid' , 0)->group_by('order_code')->limit(20);
    $rs = $this->db->get('order_lend_detail');
    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $ds)
      {
        $sc[] = $ds->order_code;
      }
    }

    echo json_encode($sc);
  }


  public function get_zone_code_and_name($warehouse = '')
  {
    $sc = array();
    $txt = $_REQUEST['term'];
    $this->db->select('code, name');
    
    if(!empty($warehouse))
    {
      $this->db->where('warehouse_code', $warehouse);
    }

    $this->db->like('code', $txt);
    $this->db->or_like('name', $txt);
    $rs = $this->db->get('zone');

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $zone)
      {
        $sc[] = $zone->code.' | '.$zone->name;
      }
    }
    else
    {
      $sc[] = 'ไม่พบรายการ';
    }

    echo json_encode($sc);
  }



  public function get_zone_code()
  {
    $sc = array();
    $txt = $_REQUEST['term'];
    $this->ms
    ->select('OBIN.BinCode')
    ->from('OBIN')
    ->join('OWHS', 'OWHS.WhsCode = OBIN.WhsCode', 'left')
    ->where('OWHS.U_WH_MAIN', 'Y')
    ->where('OBIN.SysBin', 'N');
    if($txt != '*')
    {
      $this->ms->like('OBIN.BinCode', $txt);
    }

    $this->ms->limit(20);
    $zone = $this->ms->get();
    if(!empty($zone))
    {
      foreach($zone->result() as $rs)
      {
        $sc[] = $rs->BinCode;
      }
    }


    echo json_encode($sc);
  }



  public function get_transform_zone()
  {
    $sc = array();
    $txt = $_REQUEST['term'];
    $this->db
    ->select('zone.code AS code, zone.name AS name')
    ->from('zone')
    ->join('warehouse', 'warehouse.code = zone.warehouse_code', 'left')
    ->where('warehouse.role', 6); //--- 6 =  คลังแปรสภาพ ดู table warehouse_role

    if($txt != '*')
    {
      $this->db->like('zone.code', $txt);
      $this->db->or_like('zone.name', $txt);
    }

    $this->db->limit(20);

    $zone = $this->db->get();

    if($zone->num_rows() > 0)
    {
      foreach($zone->result() as $rs)
      {
        $sc[] = $rs->code.' | '.$rs->name;
      }
    }
    else
    {
      $sc[] = "not found";
    }

    echo json_encode($sc);
  }




  public function get_lend_zone($customer_code = '')
  {
    $sc = array();
    if(!empty($customer_code))
    {
      $txt = $_REQUEST['term'];
      $this->db
      ->select('zone.code AS code, zone.name AS name')
      ->from('zone')
      ->join('warehouse', 'warehouse.code = zone.warehouse_code', 'left')
      ->join('zone_customer', 'zone_customer.zone_code = zone.code')
      ->where('warehouse.role', 8) //--- 8 =  คลังยืมสินค้า ดู table warehouse_role
      ->where('zone_customer.customer_code', $customer_code);

      if($txt != '*')
      {
        $this->db->like('zone.code', $txt);
        $this->db->or_like('zone.name', $txt);
      }

      $this->db->limit(20);

      $zone = $this->db->get();

      if($zone->num_rows() > 0)
      {
        foreach($zone->result() as $rs)
        {
          $sc[] = $rs->code.' | '.$rs->name;
        }
      }
      else
      {
        $sc[] = "not found";
      }
    }
    else
    {
      $sc[] = "กรุณาระบุผู้ยืม";
    }

    echo json_encode($sc);
  }





  public function get_sponsor()
  {
    $sc = array();
    $txt = $_REQUEST['term'];
    $this->ms->select('BpCode, BpName');

    if($txt != '*')
    {
      $this->ms->like('BpCode', $txt)->or_like('BpName', $txt);
    }

    $this->ms->limit(20);

    $sponsor = $this->ms->get('OOAT');

    if($sponsor->num_rows() > 0)
    {
      foreach($sponsor->result() as $rs)
      {
        $sc[] = $rs->BpCode.' | '.$rs->BpName;
      }
    }
    else
    {
      $sc[] = 'ไม่พบรายการ';
    }

    echo json_encode($sc);
  }



  public function get_support()
  {
    $sc = array();
    $txt = $_REQUEST['term'];
    $this->ms->select('CardCode, CardName')->where('CardType', 'C');
    if($txt != '*')
    {
      $this->ms->like('CardCode', $txt)->or_like('CardName', $txt);
    }
    $this->ms->limit(20);

    $sponsor = $this->ms->get('OCRD');

    if($sponsor->num_rows() > 0)
    {
      foreach($sponsor->result() as $rs)
      {
        $sc[] = $rs->CardCode.' | '.$rs->CardName;
      }
    }
    else
    {
      $sc[] = 'ไม่พบรายการ';
    }

    echo json_encode($sc);
  }




  public function get_employee()
  {
    $sc = array();
    $txt = $_REQUEST['term'];
    $this->ms->select('CardCode, CardName')->where('CardType', 'C');
    if($txt != '*')
    {
      $this->ms->like('CardCode', $txt)->or_like('CardName', $txt);
    }
    $this->ms->limit(20);

    $sponsor = $this->ms->get('OCRD');

    if($sponsor->num_rows() > 0)
    {
      foreach($sponsor->result() as $rs)
      {
        $sc[] = $rs->CardCode.' | '.$rs->CardName;
      }
    }
    else
    {
      $sc[] = 'ไม่พบรายการ';
    }

    echo json_encode($sc);
  }



  public function get_user()
  {
    $sc = array();
    $txt = $_REQUEST['term'];
    $this->db->select('uname, name');
    if($txt != '*')
    {
      $this->db->like('uname', $txt)->or_like('name', $txt);
    }
    $this->db->limit(20);

    $sponsor = $this->db->get('user');

    if($sponsor->num_rows() > 0)
    {
      foreach($sponsor->result() as $rs)
      {
        $sc[] = $rs->uname.' | '.$rs->name;
      }
    }
    else
    {
      $sc[] = 'ไม่พบรายการ';
    }

    echo json_encode($sc);
  }


  public function get_consign_zone($customer_code = '')
  {
    if($customer_code == '')
    {
      echo json_encode(array('เลือกลูกค้าก่อน'));
    }
    else
    {
      $this->db
      ->select('zone.code, zone.name')
      ->from('zone_customer')
      ->join('zone', 'zone.code = zone_customer.zone_code', 'left')
      ->join('warehouse', 'zone.warehouse_code = warehouse.code', 'left')
      ->where('warehouse.role', 2) //--- 2 = คลังฝากขาย
      ->where('zone_customer.customer_code', $customer_code);

      if($_REQUEST['term'] != '*')
      {
        $this->db->like('zone.code', $_REQUEST['term']);
        $this->db->or_like('zone.name', $_REQUEST['term']);
      }

      $this->db->limit(20);
      $rs = $this->db->get();

      if($rs->num_rows() > 0)
      {
        $ds = array();
        foreach($rs->result() as $rd)
        {
          $ds[] = $rd->code.' | '.$rd->name;
        }

        echo json_encode($ds);
      }
      else
      {
        echo json_encode(array('ไม่พบโซน'));
      }
    }
  }



  public function get_product_code()
  {
    $sc = array();
    $txt = $_REQUEST['term'];
    $rs = $this->db
    ->select('code')
    ->like('code', $txt)
    ->limit(20)
    ->get('products');

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $pd)
      {
        $sc[] = $pd->code;
      }
    }
    else
    {
      $sc[] = 'no item found';
    }

    echo json_encode($sc);
  }


  public function get_warehouse_code_and_name()
  {
    $txt = $_REQUEST['term'];
    $sc  = array();
    $this->ms->select('WhsCode, WhsName');
    if($txt != '*')
    {
      $this->ms->like('WhsCode', $txt);
      $this->ms->or_like('WhsName', $txt);
    }
    $rs = $this->ms->limit(20)->get('OWHS');

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $wh)
      {
        $sc[] = $wh->WhsCode.' | '.$wh->WhsName;
      }
    }
    else
    {
      $sc[] = 'not found';
    }

    echo json_encode($sc);
  }

} //-- end class
?>
