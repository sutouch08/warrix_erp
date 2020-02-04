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



  public function get_sender()
  {
    $txt = $_REQUEST['term'];
    $sc = array();
    $rs = $this->db
    ->select('id, name')
    ->like('name', $txt)
    ->limit(20)
    ->get('address_sender');

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $rd)
      {
        $sc[] = $rd->id.' | '.$rd->name;
      }
    }

    echo json_encode($sc);
  }



  public function get_customer_code_and_name()
  {
    $txt = $_REQUEST['term'];
    $sc = array();
    $rs = $this->db
    ->select('code, name')
    ->where('CardType', 'C')
    ->group_start()
    ->like('code', $txt)
    ->or_like('name', $txt)
    ->or_like('old_code', $txt)
    ->group_end()
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
  $this->db
  ->select('code, old_code')
  ->where('active', 1)
  ->where('can_sell', 1)
  ->where('is_deleted', 0)
  ->group_start()
  ->like('code', $_REQUEST['term'])
  ->or_like('old_code', $_REQUEST['term'])
  ->group_end()
  ->order_by('code', 'ASC')
  ->limit(20);
  $qs = $this->db->get('product_style');

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
    $txt = $_REQUEST['term'];
    $sc = array();
    $qr = "SELECT CardCode, CardName FROM ";
    $qr .= "OCRD WHERE CardType = 'S' ";
    $qr .= "AND (CardCode LIKE N'%{$txt}%' OR CardName LIKE N'%{$txt}%') ";
    $qr .= "ORDER BY 1 OFFSET 0 ROWS FETCH NEXT 20 ROWS ONLY";
    $vendor = $this->ms->query($qr);

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
    $txt = convert($_REQUEST['term']);

    //---- receive product if over due date or not
    $receive_due = getConfig('RECEIVE_OVER_DUE'); //--- 1 = receive , 0 = not receive

    $this->ms->select('DocNum')->where('DocStatus', 'O');
    if($vendor !== FALSE)
    {
      $this->ms->where('CardCode', $vendor);
    }

    if($txt != '*')
    {
      $this->ms->like('DocNum', $txt);
    }

    if($receive_due == 0)
    {
      //--- not receive
      $days = getConfig('PO_VALID_DAYS');
      $date = date('Y-m-d',strtotime("-{$days} day")); //--- ย้อนไป $days วัน
      $this->ms->where('DocDueDate >=', sap_date($date));
    }
    //echo $this->ms->get_compiled_select('OPOR');
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
    $this->db->select('code, name');
    if($txt != '*')
    {
      $this->db->like('code', $txt)->or_like('name', $txt);
    }

    $rs = $this->db->limit(20)->get('zone');

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $cs)
      {
        $sc[] = $cs->code.' | '.$cs->name;
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




  public function get_lend_zone($empID)
  {
    $sc = array();
    if(!empty($empID))
    {
      $txt = $_REQUEST['term'];
      $this->db
      ->select('zone.code AS code, zone.name AS name')
      ->from('zone')
      ->join('warehouse', 'warehouse.code = zone.warehouse_code', 'left')
      ->join('zone_employee', 'zone_employee.zone_code = zone.code')
      ->where('warehouse.role', 8) //--- 8 =  คลังยืมสินค้า ดู table warehouse_role
      ->where('zone_employee.empID', $empID);

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
    $txt = convert($_REQUEST['term']);
    $qr = "SELECT BpCode, BpName FROM OOAT ";
    $this->ms->select('BpCode, BpName');

    if($txt != '*')
    {
      $qr .= "WHERE BpCode LIKE N'%{$txt}%' OR BpName LIKE N'%{$txt}%' ";
    }

    $qr .= "ORDER BY 1 OFFSET 0 ROWS FETCH NEXT 20 ROWS ONLY";

    $sponsor = $this->ms->query($qr);

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
    $qr  = "SELECT CardCode, CardName FROM OCRD ";
    $qr .= "WHERE CardType = 'C' ";
    if($txt != '*')
    {
      $qr .= "AND (CardCode LIKE N'%{$txt}%' OR CardName LIKE N'%{$txt}%') ";
    }

    $qr .= "ORDER BY 1 OFFSET 0 ROWS FETCH NEXT 20 ROWS ONLY";

    $sponsor = $this->ms->query($qr);

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
    $qr  = "SELECT firstName, lastName, empID FROM OHEM ";
    if($txt != '*')
    {
      $qr .= "WHERE firstName LIKE N'%{$txt}%' OR lastName LIKE N'%{$txt}%' ";
    }

    $qr .= "ORDER BY 1 OFFSET 0 ROWS FETCH NEXT 20 ROWS ONLY";

    $emp = $this->ms->query($qr);

    if($emp->num_rows() > 0)
    {
      foreach($emp->result() as $rs)
      {
        $sc[] = $rs->firstName.' '.$rs->lastName.' | '.$rs->empID;
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
    $qr  = "SELECT WhsCode, WhsName FROM OWHS ";

    if($txt != '*')
    {
      $qr .= "WHERE WhsCode LIKE N'%{$txt}%' OR WhsName LIKE N'%{$txt}%' ";
    }

    $qr .= "ORDER BY WhsCode ASC OFFSET 0 ROWS FETCH NEXT 20 ROWS ONLY";

    $rs = $this->ms->query($qr);

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


  public function get_color_code_and_name()
  {
    $txt = $_REQUEST['term'];
    $sc = array();
    $this->db->select('code, name');
    if($txt != '*')
    {
      $this->db->like('code', $txt);
      $this->db->or_like('name', $txt);
    }
    $rs = $this->db->order_by('code', 'ASC')->limit(20)->get('product_color');

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $co)
      {
        $sc[] = $co->code.' | '.$co->name;
      }
    }
    else
    {
      $sc[] = "not_fount";
    }

    echo json_encode($sc);
  }


  public function get_size_code_and_name()
  {
    $txt = $_REQUEST['term'];
    $sc = array();
    $this->db->select('code, name');
    if($txt != '*')
    {
      $this->db->like('code', $txt, 'after');
      $this->db->or_like('name', $txt, 'after');
    }
    $rs = $this->db->order_by('position', 'ASC')->limit(20)->get('product_size');

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $co)
      {
        $sc[] = $co->code.' | '.$co->name;
      }
    }
    else
    {
      $sc[] = "not_fount";
    }

    echo json_encode($sc);
  }

} //-- end class
?>
