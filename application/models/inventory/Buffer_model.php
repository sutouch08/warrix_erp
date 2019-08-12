<?php
class Buffer_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }



  public function get_sum_buffer_product($order_code, $product_code)
  {
    $rs = $this->db->select_sum('qty')
    ->where('order_code', $order_code)
    ->where('product_code', $product_code)
    ->get('buffer');

    return intval($rs->row()->qty);
  }


  public function get_details($order_code, $product_code)
  {
    $rs = $this->db
    ->where('order_code', $order_code)
    ->where('product_code', $product_code)
    ->get('buffer');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function get_all_details($order_code)
  {
    $rs = $this->db->where('order_code', $order_code)->get('buffer');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('buffer', $ds);
    }

    return FALSE;
  }


  public function update($order_code, $product_code, $zone_code, $qty)
  {
    $qr = "UPDATE buffer SET qty = (qty + {$qty}) ";
    $qr .= "WHERE order_code = '{$order_code}' AND product_code = '{$product_code}' ";
    $qr .= "AND zone_code = '{$zone_code}'";

    return $this->db->query($qr);
  }


  public function delete($id)
  {
    return $this->db->where('id', $id)->delete('buffer');
  }



  public function delete_all($code)
  {
    return $this->db->where('order_code', $code)->delete('buffer');
  }


  public function is_exists($order_code, $product_code, $zone_code)
  {
    $rs = $this->db->select('id')
    ->where('order_code', $order_code)
    ->where('product_code', $product_code)
    ->where('zone_code', $zone_code)
    ->get('buffer');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }



  public function remove_zero_buffer($code)
  {
    return $this->db->where('order_code', $code)->where('qty', 0)->delete('buffer');
  }
}
 ?>
