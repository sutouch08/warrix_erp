<?php
class Invoice_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_billed_detail($code)
  {
    $qr = "SELECT o.id, o.product_code, o.product_name, o.qty AS order_qty, o.is_count, ";
    $qr .= "o.price, o.discount1, o.discount2, o.discount3, ";
    $qr .= "(o.discount_amount / o.qty) AS discount_amount, ";
    $qr .= "(o.total_amount/o.qty) AS final_price, ";
    $qr .= "(SELECT SUM(qty) FROM prepare WHERE order_code = '{$code}' AND product_code = o.product_code) AS prepared, ";
    $qr .= "(SELECT SUM(qty) FROM qc WHERE order_code = '{$code}' AND product_code = o.product_code) AS qc, ";
    $qr .= "(SELECT SUM(qty) FROM order_sold WHERE reference = '{$code}' AND product_code = o.product_code) AS sold ";
    $qr .= "FROM order_details AS o ";
    $qr .= "WHERE o.order_code = '{$code}' GROUP BY o.product_code";

    $rs = $this->db->query($qr);
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }


  public function get_details($code)
  {
    $rs = $this->db->where('reference', $code)->get('order_sold');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function get_total_sold_qty($code)
  {
    $rs = $this->db->select_sum('qty')->where('reference', $code)->get('order_sold');
    return intval($rs->row()->qty);
  }



  public function drop_sold($id)
  {
    return $this->db->where('id', $id)->delete('order_sold');
  }



  public function is_over_due($customer_code)
  {
    $control_day = getConfig('OVER_DUE_DATE');
    $rs = $this->ms
    ->select('DocEntry', FALSE)
    ->where('CardCode', $customer_code)
    ->where('DocTotal >', 'PaidToDate', FALSE)
    ->where("DATEADD(day,{$control_day}, DocDueDate) < ", "GETDATE()", FALSE)
    ->get('OINV');

    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }

} //--- end class

 ?>
