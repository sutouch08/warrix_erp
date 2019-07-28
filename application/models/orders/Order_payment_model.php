<?php
class Order_payment_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }



  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->replace('order_payment', $ds);
    }

    return FALSE;
  }




  public function get($code)
  {
    $rs = $this->db->where('order_code', $code)->get('order_payment');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }


  public function is_exists($code)
  {
    $rs = $this->db->select('order_code')
    ->where('order_code', $code)
    ->get('order_payment');
    if($rs->num_rows() === 1)
    {
      return TRUE;
    }

    return FALSE;
  }

} //--- end class
?>
