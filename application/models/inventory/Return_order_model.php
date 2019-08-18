<?php
class Return_order_model extends CI_Model
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
      return $this->db->insert('return_order', $ds);
    }

    return FALSE;
  }



  public function update($code, array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->where('code', $code)->update('return_order', $ds);
    }

    return FALSE;
  }


  public function add_detail(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('return_order_detail', $ds);
    }

    return FALSE;
  }



  public function get($code)
  {
    $rs = $this->db->where('code', $code)->get('return_order');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }



  public function get_details($code)
  {
    $rs = $this->db->where('return_code', $code)->get('return_order_detail');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function get_invoice_details($invoice)
  {
    $rs = $this->ms
    ->select('LineNum, ItemCode AS product_code, Dscription AS product_name, Quantity AS qty, PriceAfVAT AS price')
    ->where('DocEntry', $invoice)
    ->get('DLN1');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function get_customer_invoice($invoice)
  {
    $rs = $this->ms->select('CardCode AS customer_code, CardName AS customer_name')->where('DocNum', $invoice)->get('ODLN');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }



  public function drop_details($code)
  {
    return $this->db->where('return_code', $code)->delete('return_order_detail');
  }



  public function cancle_details($code)
  {
    return $this->db->set('is_cancle', 1)->where('return_code', $code)->update('return_order_detail');
  }




  public function get_sum_qty($code)
  {
    $rs = $this->db->select_sum('qty', 'qty')
    ->where('return_code', $code)
    ->get('return_order_detail');

    return $rs->row()->qty === NULL ? 0 : $rs->row()->qty;
  }


  public function get_sum_amount($code)
  {
    $rs = $this->db->select_sum('amount')
    ->where('return_code', $code)
    ->get('return_order_detail');

    return $rs->row()->amount === NULL ? 0 : $rs->row()->amount;
  }



  public function set_status($code, $status)
  {
    return $this->db->set('status', $status)->where('code', $code)->update('return_order');
  }



  public function count_rows(array $ds = array())
  {
    $this->db->select('status');

    //---- เลขที่เอกสาร
    if(!empty($ds['code']))
    {
      $this->db->like('code', $ds['code']);
    }

    //---- invoice
    if(!empty($ds['invoice']))
    {
      $this->db->like('invoice', $ds['invoice']);
    }

    //--- customer
    if(!empty($ds['customer_code']))
    {
      $this->db->where_in('customer_code', $this->customer_in($ds['customer_code']));
    }


    if(!empty($ds['from_date']) && !empty($ds['to_date']))
    {
      $this->db->where('date_add >=', from_date($ds['from_date']));
      $this->db->where('date_add <=', to_date($ds['to_date']));
    }

    $rs = $this->db->get('return_order');


    return $rs->num_rows();
  }





  public function get_data(array $ds = array(), $perpage = '', $offset = '', $role = 'S')
  {
    //---- เลขที่เอกสาร
    if(!empty($ds['code']))
    {
      $this->db->like('code', $ds['code']);
    }

    //---- invoice
    if(!empty($ds['invoice']))
    {
      $this->db->like('invoice', $ds['invoice']);
    }

    //--- customer
    if(!empty($ds['customer_code']))
    {
      $this->db->where_in('customer_code', $this->customer_in($ds['customer_code']));
    }


    if(!empty($ds['from_date']) && !empty($ds['to_date']))
    {
      $this->db->where('date_add >=', from_date($ds['from_date']));
      $this->db->where('date_add <=', to_date($ds['to_date']));
    }

    if(!empty($perpage))
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get('return_order');

    return $rs->result();
  }


  public function get_max_code($code)
  {
    $rs = $this->db
    ->like('code', $code, 'after')
    ->order_by('code', 'DESC')
    ->get('return_order');

    if($rs->num_rows() == 1)
    {
      return $rs->row()->code;
    }

    return FALSE;
  }


  public function customer_in($txt)
  {
    $sc = array('0');
    $rs = $this->db
    ->select('code')->
    like('code', $txt)
    ->or_like('name', $txt)
    ->get('customers');

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $rs)
      {
        $sc[] = $rs->code;
      }
    }

    return $sc;
  }

}

 ?>
