<?php
class Customers_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }



  public function add_sap_customer(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->mc->insert('OCRD', $ds);
    }

    return FALSE;
  }



  public function update_sap_customer($code, $ds = array())
  {
    if(!empty($ds))
    {
      return $this->mc->where('CardCode', $code)->update('OCRD', $ds);
    }

    return FALSE;
  }


  public function sap_customer_exists($code)
  {
    $rs = $this->mc->where('CardCode', $code)->get('OCRD');
    if($rs->num_rows() === 1)
    {
      return TRUE;
    }

    return FALSE;
  }




  public function get_credit($code)
  {
    $rs = $this->ms
    ->select('CreditLine, Balance, DNotesBal, OrdersBal')
    ->where('CardCode', $code)
    ->get('OCRD');
    if($rs->num_rows() === 1)
    {
      $balance = $rs->row()->CreditLine - ($rs->row()->Balance + $rs->row()->DNotesBal + $rs->row()->OrdersBal);
      return $balance;
    }

    return 0.00;
  }



  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return  $this->db->insert('customers', $ds);
    }

    return FALSE;
  }



  public function update($code, array $ds = array())
  {
    if(!empty($ds))
    {
      $this->db->where('code', $code);
      return $this->db->update('customers', $ds);
    }

    return FALSE;
  }


  public function delete($code)
  {
    $rs = $this->db->where('code', $code)->delete('customers');
    if(!$rs)
    {
      return $this->db->error();
    }

    return TRUE;
  }


  public function count_rows($code = '', $name = '', $group = '', $kind = '', $type = '', $class = '', $area = '')
  {
    $this->db->select('code');

    if($code != '')
    {
      $this->db->like('code', $code);
    }

    if($name != '')
    {
      $this->db->like('name', $name);
    }


    if($group != '')
    {
      $this->db->where('group_code', $group);
    }


    if($kind != '')
    {
      $this->db->where('kind_code', $kind);
    }

    if($type != '')
    {
      $this->db->where('type_code', $type);
    }

    if($class != '')
    {
      $this->db->where('class_code', $class);
    }

    if($area != '')
    {
      $this->db->where('area_code', $area);
    }


    $rs = $this->db->get('customers');

    return $rs->num_rows();
  }




  public function get($code)
  {
    $rs = $this->db->where('code', $code)->get('customers');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }



  public function get_name($code)
  {
    $rs = $this->db->select('name')->where('code', $code)->get('customers');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->name;
    }

    return NULL;
  }



  public function get_data($code = '', $name = '', $group = '', $kind = '', $type = '', $class = '', $area = '', $perpage = '', $offset = '')
  {
    if($code != '')
    {
      $this->db->like('code', $code);
    }

    if($name != '')
    {
      $this->db->like('name', $name);
    }


    if($group != '')
    {
      $this->db->where('group_code', $group);
    }


    if($kind != '')
    {
      $this->db->where('kind_code', $kind);
    }

    if($type != '')
    {
      $this->db->where('type_code', $type);
    }

    if($class != '')
    {
      $this->db->where('class_code', $class);
    }

    if($area != '')
    {
      $this->db->where('area_code', $area);
    }

    if($perpage != '')
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get('customers');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }




  public function is_exists($code, $old_code = '')
  {
    if($old_code != '')
    {
      $this->db->where('code !=', $old_code);
    }

    $rs = $this->db->where('code', $code)->get('customers');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }



  public function is_exists_name($name, $old_name = '')
  {
    if($old_name != '')
    {
      $this->db->where('name !=', $old_name);
    }

    $rs = $this->db->where('name', $name)->get('customers');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }



  public function get_sale_code($code)
  {
    $rs = $this->db->select('sale_code')->where('code', $code)->get('customers');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->sale_code;
    }

    return NULL;
  }


  public function get_update_data($date = "")
  {
    $rs = $this->ms
    ->select("CardCode AS code")
    ->select("CardName AS name")
    ->select("LicTradNum AS Tax_Id")
    ->select("DebPayAcct, CardType")
    ->select("GroupCode, CmpPrivate")
    ->select("GroupNum, SlpCode AS sale_code")
    ->select("CreditLine")
    ->select("U_WRX_BPOLDCODE AS old_code")
    ->where('CardType', 'C')
    ->group_start()
    ->where("UpdateDate >=", sap_date($date))
    ->or_where('CreateDate >=', sap_date($date))
    ->group_end()
    ->get('OCRD');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }




  public function search($txt)
  {
    $qr = "SELECT code FROM customers WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%'";
    $rs = $this->db->query($qr);
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }
    else
    {
      return array();
    }

  }



  public function getGroupCode()
  {
    $rs = $this->ms
    ->select('GroupCode AS code, GroupName AS name')
    ->where('GroupType', 'C')
    ->get('OCRG');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }




  public function getGroupNum()
  {
    $rs = $this->ms
    ->select('GroupNum AS code, PymntGroup AS name')
    ->order_by('GroupNum', 'ASC')
    ->get('OCTG');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }


  public function getDebPayAcct()
  {
    $rs = $this->ms
    ->select('AcctCode AS code, AcctName AS name')
    ->order_by('AcctCode', 'ASC')
    ->get('OACT');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function getSlp()
  {
    $rs = $this->ms
    ->select('SlpCode AS code, SlpName AS name')
    ->where('Active', 'Y')
    ->get('OSLP');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }

}
?>
