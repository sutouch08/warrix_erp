<?php
class Cancle_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }



  public function get_sum_cancle_product($order_code, $product_code)
  {
    $rs = $this->db->select_sum('qty')
    ->where('order_code', $order_code)
    ->where('product_code', $product_code)
    ->get('cancle');

    return intval($rs->row()->qty);
  }


  public function get_details($order_code, $product_code)
  {
    $rs = $this->db
    ->where('order_code', $order_code)
    ->where('product_code', $product_code)
    ->get('cancle');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function get_all_details($order_code)
  {
    $rs = $this->db->where('order_code', $order_code)->get('cancle');
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
      return $this->db->insert('cancle', $ds);
    }

    return FALSE;
  }



  public function update($order_code, $product_code, $zone_code, $qty)
  {
    $qr = "UPDATE cancle SET qty = (qty + {$qty}) ";
    $qr .= "WHERE order_code = '{$order_code}' AND product_code = '{$product_code}' ";
    $qr .= "AND zone_code = '{$zone_code}'";

    return $this->db->query($qr);
  }


  public function delete($id)
  {
    return $this->db->where('id', $id)->delete('cancle');
  }


  public function restore_buffer($code)
  {
    $rs = $this->db->where('order_code', $code)->get('cancle');
    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $rd)
      {
        if($this->is_buffer_exists($rd->order_code, $rd->product_code, $rd->zone_code) === TRUE)
        {
          $qr = "UPDATE buffer
                  SET qty = (qty + {$rs->qty})
                  WHERE order_code = '{$rd->order_code}'
                  AND product_code = '{$rd->product_code}'
                  AND zone_code = '{$rd->zone_code}'
                  AND user = '{$rd->user}'";
          $this->db->query($qr);
        }
        else
        {
          $arr = array(
            'order_code' => $rd->order_code,
            'product_code' => $rd->product_code,
            'warehouse_code' => $rd->warehouse_code,
            'zone_code' => $rd->zone_code,
            'qty' => $rd->qty,
            'user' => $rd->user
          );

          $this->db->insert('buffer', $arr);
          $this->delete($rd->id);
        }
      }
    }
  }


  public function is_buffer_exists($code, $pd_code, $zone_code)
  {
    $rs = $this->db->select('id')
    ->where('order_code', $code)
    ->where('product_code', $pd_code)
    ->where('zone_code', $zone_code)
    ->get('buffer');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }

}
 ?>
