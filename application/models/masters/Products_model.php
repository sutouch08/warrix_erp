<?php
class Products_model extends CI_Model
{
  public $ms;
  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE);
  }


  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return  $this->db->replace('products', $ds);
    }

    return FALSE;
  }



  public function update($code, array $ds = array())
  {
    if(!empty($ds))
    {
      $this->db->where('code', $code);
      return $this->db->update('products', $ds);
    }

    return FALSE;
  }


  public function delete($code)
  {
    return $this->db->where('code', $code)->delete('products');
  }


  public function count_rows(array $ds = array())
  {
    if(!empty($ds))
    {
      $this->db->select('code');

      foreach($ds as $field => $val)
      {
        if($val != '')
        {
          $this->db->like($field, $val);
        }
      }
      $rs = $this->db->get('products');

      return $rs->num_rows();
    }

    return 0;
  }




  public function get($code)
  {
    $rs = $this->db->where('code', $code)->get('products');
    return $rs->row();
  }



  public function get_name($code)
  {
    $rs = $this->db->select('name')->where('code', $code)->get('products');
    return $rs->row()->name;
  }



  public function get_data($ds, $perpage = '', $offset = '')
  {
    if(!empty($ds))
    {
      foreach($ds as $field => $val)
      {
        if($val != '')
        {
          $this->db->like($field, $val);
        }
      }

      if($perpage != '')
      {
        $offset = $offset === NULL ? 0 : $offset;
        $this->db->limit($perpage, $offset);
      }
      
      $rs = $this->db->get('products');

      return $rs->result();
    }

  }




  public function is_exists($code, $old_code = '')
  {
    if($old_code != '')
    {
      $this->db->where('code !=', $old_code);
    }

    $rs = $this->db->where('code', $code)->get('products');

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

    $rs = $this->db->where('name', $name)->get('products');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }



  public function get_updte_data()
  {
    $this->ms->select("CardCode, CardName");
    $this->ms->where("UpdateDate >=", from_date());
    $rs = $this->ms->get('OCRD');
    return $rs->result();
  }


}
?>
