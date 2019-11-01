<?php
class Product_style_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return  $this->db->insert('product_style', $ds);
    }

    return FALSE;
  }



  public function update($code, array $ds = array())
  {
    if(!empty($ds))
    {
      $this->db->where('code', $code);
      return $this->db->update('product_style', $ds);
    }

    return FALSE;
  }


  public function delete($code)
  {
    $rs =  $this->db->where('code', $code)->delete('product_style');
    if($rs)
    {
      return TRUE;
    }

    return $this->db->_error_message();
  }

  public function count_sap_list($date_add, $date_upd)
  {
    $qr  = "SELECT DISTINCT U_MODEL FROM OITM WHERE U_MODEL IS NOT NULL ";
    $qr .= "AND (CreateDate >= '{$date_add}' OR UpdateDate >= '{$date_upd}') ";
    $qr .= "GROUP BY U_MODEL ";

    $rs = $this->ms->query($qr);
    //
    // $rs = $this->ms->distinct()
    // ->select('U_MODEL')
    // ->where('U_MODEL IS NOT NULL', NULL, FALSE)
    // ->where('CreateDate >', $date_add)
    // ->or_where('UpdateDate >', $date_upd)
    // ->group_by('U_MODEL')
    // ->get('OITM');
    return $rs->num_rows();
  }


  public function get_sap_list($date_add, $date_upd, $limit, $offset)
  {
    $qr  = "SELECT DISTINCT U_MODEL FROM OITM WHERE U_MODEL IS NOT NULL ";
    $qr .= "AND (CreateDate >= '{$date_add}' OR UpdateDate >= '{$date_upd}') ";
    $qr .= "GROUP BY U_MODEL ";
    $qr .= "ORDER BY U_MODEL ASC ";
    $qr .= "OFFSET {$offset} FETCH NEXT {$limit} ROWS ONLY";

    $rs = $this->ms->query($qr);
    // $rs = $this->ms->distinct()
    // ->select('U_MODEL')
    // ->where('U_MODEL IS NOT NULL', NULL, FALSE)
    // ->where('CreateDate >', $date_add)
    // ->or_where('UpdateDate >', $date_upd)
    // ->limit($limit, $offset)
    // ->get('OITM');

    if($rs->num_rows() > 0){
      return $rs->result();
    }

    return FALSE;
  }

  public function get_sap_style($code)
  {
    $rs = $this->ms
    ->select('U_MODEL, U_GROUP, U_MAJOR, U_CATE, U_TYPE, U_SUBTYPE, U_BRAND, U_YEAR, InvntryUom, InvntItem')
    ->where('U_MODEL', $code)
    ->limit(1)
    ->get('OITM');

    if($rs->num_rows() > 0)
    {
      return $rs->row();
    }

    return FALSE;
  }

  public function count_rows(array $ds = array())
  {
    $this->db->select('active');

    if(!empty($ds))
    {
      if($ds['code'] != '')
      {
        $this->db->like('code', $ds['code']);
      }

      if($ds['name'] != '')
      {
        $this->db->like('name', $ds['name']);
      }

      if($ds['group'] != '')
      {
        $this->db->where('group_code', $ds['group']);
      }

      if($ds['sub_group'] != '')
      {
        $this->db->where('sub_group_code', $ds['sub_group']);
      }

      if($ds['category'] != '')
      {
        $this->db->where('category_code', $ds['category']);
      }

      if($ds['kind'] != '')
      {
        $this->db->where('kind_code', $ds['kind']);
      }

      if($ds['type'] != '')
      {
        $this->db->where('type_code', $ds['type']);
      }

      if($ds['brand'] !='')
      {
        $this->db->where('brand_code', $ds['brand']);
      }

      if($ds['year'] != '')
      {
        $this->db->where('year', $ds['year']);
      }
    }

    $rs = $this->db->get('product_style');

    return $rs->num_rows();
  }




  public function get($code)
  {
    $rs = $this->db->where('code', $code)->get('product_style');
    return $rs->row();
  }



  public function get_name($code)
  {
    if($code === NULL OR $code === '')
    {
      return $code;
    }

    $rs = $this->db->select('name')->where('code', $code)->get('product_style');
    return $rs->row()->name;
  }




  public function get_data(array $ds = array(), $perpage = '', $offset = '')
  {
    if(!empty($ds))
    {
      if($ds['code'] != '')
      {
        $this->db->like('code', $ds['code']);
      }

      if($ds['name'] != '')
      {
        $this->db->like('name', $ds['name']);
      }

      if($ds['group'] != '')
      {
        $this->db->where('group_code', $ds['group']);
      }

      if($ds['sub_group'] != '')
      {
        $this->db->where('sub_group_code', $ds['sub_group']);
      }

      if($ds['category'] != '')
      {
        $this->db->where('category_code', $ds['category']);
      }

      if($ds['kind'] != '')
      {
        $this->db->where('kind_code', $ds['kind']);
      }

      if($ds['type'] != '')
      {
        $this->db->where('type_code', $ds['type']);
      }

      if($ds['brand'] !='')
      {
        $this->db->where('brand_code', $ds['brand']);
      }

      if($ds['year'] != '')
      {
        $this->db->where('year', $ds['year']);
      }
    }


    if($perpage != '')
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get('product_style');

    return $rs->result();
  }




  public function is_exists($code, $old_code = '')
  {
    if($old_code != '')
    {
      $this->db->where('code !=', $old_code);
    }

    $rs = $this->db->where('code', $code)->get('product_style');

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

    $rs = $this->db->where('name', $name)->get('product_style');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }



  public function count_members($code)
  {
    $this->db->select('active')->where('style_code', $code);
    $rs = $this->db->get('products');
    return $rs->num_rows();
  }


  public function get_style_last_add()
  {
    $rs = $this->db->select_max('date_add')->get('product_style');
    return $rs->row()->date_add;
  }

  public function get_style_last_update()
  {
    $rs = $this->db->select_max('date_upd')->get('product_style');
    return $rs->row()->date_upd;
  }

}
?>
