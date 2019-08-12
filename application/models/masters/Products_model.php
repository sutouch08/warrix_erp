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





  public function get_status($field, $item)
  {
    $rs = $this->db->select($field)->where('code', $item)->get('products');
    if($rs->num_rows() == 1)
    {
      return $rs->row()->$field;
    }

    return 0;
  }



  public function get_barcode($code)
  {
    $rs = $this->db->select('barcode')->where('code', $code)->get('products');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->barcode;
    }

    return NULL;
  }



  public function get_product_by_barcode($barcode)
  {
    $rs = $this->db->where('barcode', $barcode)->get('products');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }




  public function set_status($field, $item, $val)
  {
    return $this->db->set($field, $val)->where('code', $item)->update('products');
  }







  public function delete_item($code)
  {
    $rs = $this->db->where('code', $code)->delete('products');
    if($rs)
    {
      return TRUE;
    }

    return $this->db->_error_message();
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



  public function get_style_code($code)
  {
    $rs = $this->db->select('style_code')->where('code', $code)->get('products');
    return $rs->row()->style_code;
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



  public function get_style_items($code)
  {
    $qr = "SELECT p.* FROM products AS p
          LEFT JOIN product_color AS c ON p.color_code = c.code
          LEFT JOIN product_size AS s ON p.size_code = s.code
          WHERE style_code = '$code'
          ORDER BY c.code ASC, s.position ASC";

    $rs = $this->db->query($qr);
    return $rs->result();
  }



  public function get_items_by_color($style, $color)
  {
    $rs = $this->db->where('style_code', $style)->where('color_code', $color)->get('products');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return array();
  }




  public function get_item_by_color_and_size($style, $color, $size)
  {
    $rs = $this->db
    ->where('style_code', $style)
    ->where('color_code', $color)
    ->where('size_code', $size)
    ->limit(1)
    ->get('products');

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return array();
  }




  public function countAttribute($style_code)
	{
		$color = $this->db->where('style_code', $style_code)->where('color_code is NOT NULL')->where('color_code !=', '')->group_by('style_code')->get('products');
		$size  = $this->db->where('style_code', $style_code)->where('size_code is NOT NULL')->where('size_code !=', '')->group_by('style_code')->get('products');
		return $color->num_rows() + $size->num_rows();
	}


  public function get_unbarcode_items($style)
  {
    $this->db->select('code');
    $this->db->where('style_code', $style);
    $this->db->where('barcode IS NULL', NULL, FALSE);
    $rs = $this->db->get('products');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return array();
  }



  public function update_barcode($code, $barcode)
  {
    $this->db->set('barcode', $barcode);
    return $this->db->where('code', $code)->update('products');
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




  public function is_exists_style($style)
  {
    $rs = $this->db->select('code')->where('style_code', $style)->get('products');
    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }




  public function is_disactive_all($style_code)
  {
    $this->db->select('code')->where('style_code', $style_code)->where('active', 1);
    $rs = $this->db->get('products');
    if($rs->num_rows() > 0)
    {
      return FALSE;
    }

    return TRUE;
  }


  public function get_updte_data()
  {
    $this->ms->select("CardCode, CardName");
    $this->ms->where("UpdateDate >=", from_date());
    $rs = $this->ms->get('OCRD');
    return $rs->result();
  }




  public function count_color($style_code)
  {
    $this->db->select('color_code')
    ->where('style_code', $style_code)
    ->where('color_code is NOT NULL')
    ->where('color_code != ', '')
    ->group_by('color_code');
    $rs = $this->db->get('products');

    return $rs->num_rows();
  }



  public function count_size($style_code)
  {
    $this->db->select('size_code')
    ->where('style_code', $style_code)
    ->where('size_code is NOT NULL')
    ->where('size_code != ', '')
    ->group_by('size_code');
    $rs = $this->db->get('products');

    return $rs->num_rows();
  }



  public function get_all_colors($style_code)
  {
    $qr = "SELECT c.code, c.name FROM products AS p
          LEFT JOIN product_color AS c ON p.color_code = c.code
          WHERE p.style_code = '".$style_code."'
          GROUP BY p.color_code
          ORDER BY p.color_code ASC";
    $rs = $this->db->query($qr);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }


  public function get_all_sizes($style_code)
  {
    $qr = "SELECT s.code, s.name FROM products AS p
           LEFT JOIN product_size AS s ON p.size_code = s.code
           WHERE p.style_code = '".$style_code."'
           GROUP BY p.size_code
           ORDER BY s.position ASC";
    $rs = $this->db->query($qr);
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }


}
?>
