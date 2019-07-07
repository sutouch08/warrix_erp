<?php
class Payment_methods_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return  $this->db->insert('payment_method', $ds);
    }

    return FALSE;
  }



  public function update($code, array $ds = array())
  {
    if(!empty($ds))
    {
      $this->db->where('code', $code);
      return $this->db->update('payment_method', $ds);
    }

    return FALSE;
  }


  public function delete($code)
  {
    return $this->db->where('code', $code)->delete('payment_method');
  }


  public function count_rows($c_code = '', $c_name = '', $term = '')
  {
    $this->db->select('code');

    if($term == 1)
    {
      $this->db->where('has_term', 1);
    }


    if($c_code != '')
    {
      $this->db->like('code', $c_code);
    }

    if($c_name != '')
    {
      $this->db->like('name', $c_name);
    }

    $rs = $this->db->get('payment_method');

    return $rs->num_rows();
  }




  public function get_payment_methods($code)
  {
    $rs = $this->db->where('code', $code)->get('payment_method');
    return $rs->row();
  }




  public function get_data($c_code = '', $c_name = '', $term = '', $perpage = '', $offset = '')
  {
    if($term == 1)
    {
      $this->db->where('has_term', 1);
    }

    if($c_code != '')
    {
      $this->db->like('code', $c_code);
    }

    if($c_name != '')
    {
      $this->db->like('name', $c_name);
    }

    if($perpage != '')
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get('payment_method');

    return $rs->result();
  }




  public function is_exists($code, $old_code = '')
  {
    if($old_code != '')
    {
      $this->db->where('code !=', $old_code);
    }

    $rs = $this->db->where('code', $code)->get('payment_method');

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

    $rs = $this->db->where('name', $name)->get('payment_method');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }



}
?>
