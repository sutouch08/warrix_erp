<?php
class Customers_model extends CI_Model
{
  public $ms;
  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE);
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
    return $this->db->where('code', $code)->delete('customers');
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
    return $rs->row();
  }



  public function get_name($code)
  {
    $rs = $this->db->select('name')->where('code', $code)->get('customers');
    return $rs->row()->name;
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

    return $rs->result();
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


  public function get_updte_data()
  {
    $this->ms->select("CardCode, CardName, CardType, SlpCode");
    //$this->ms->where("UpdateDate >=", from_date());
    $rs = $this->ms->get('OCRD');
    return $rs->result();
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

}
?>
