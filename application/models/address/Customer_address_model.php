<?php
class Customer_address_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_customer_bill_to_address($customer_code)
  {
    $rs = $this->db->where('customer_code', $customer_code)->get('address_bill_to');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }

  public function add_bill_to(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('address_bill_to', $ds);
    }

    return FALSE;
  }


  public function update_bill_to($customer_code, array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->where('customer_code', $customer_code)->update('address_bill_to', $ds);
    }

    return FALSE;
  }



  public function get_max_line_num($code)
  {
    $rs = $this->ms->select_max('LineNum')->where('CardCode', $code)->get('CRD1');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->LineNum;
    }

    return 0;
  }


  public function is_sap_bill_to_exists($code)
  {
    $rs = $this->mc
    ->where('CardCode', $code)
    ->where('AdresType', 'B')
    ->get('CRD1');
    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }


} //--- end class

 ?>
