<?php
class Receive_po_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }



  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('receive_product', $ds);
    }

    return FALSE;
  }



  public function update($code, array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->where('code', $code)->update('receive_product', $ds);
    }

    return FALSE;
  }


  public function add_detail(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('receive_product_detail', $ds);
    }

    return FALSE;
  }



  public function get($code)
  {
    $rs = $this->db->where('code', $code)->get('receive_product');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }



  public function get_details($code)
  {
    $rs = $this->db->where('receive_code', $code)->get('receive_product_detail');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function drop_details($code)
  {
    return $this->db->where('receive_code', $code)->delete('receive_product_detail');
  }



  public function cancle_details($code)
  {
    return $this->db->set('is_cancle', 1)->where('receive_code', $code)->update('receive_product_detail');
  }



  public function get_po_details($po_code)
  {
    $rs = $this->ms
    ->select('ItemCode, Dscription, Quantity, OpenQty, PriceAfVAT AS price')
    ->where('DocEntry', $po_code)
    ->where('LineStatus', 'O')
    ->get('POR1');

    if(!empty($rs))
    {
      return $rs->result();
    }

    return FALSE;
  }


  public function get_sap_receive_doc($code)
  {
    $rs = $this->mc
    ->select('CANCELED, DocStatus')
    ->where('U_ECOMNO', $code)
    ->get('OPDN');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }


  public function add_sap_receive_po(array $ds = array())
  {
    return $this->mc->insert('OPDN', $ds);
  }


  public function update_sap_receive_po($code, $ds)
  {
    return $this->mc->where('U_ECOMNO', $code)->update('OPDN', $ds);
  }


  public function add_sap_receive_po_detail(array $ds = array())
  {
    return $this->mc->insert('PDN1', $ds);
  }


  public function drop_sap_exists_details($code)
  {
    return $this->mc->where('U_ECOMNO', $code)->delete('PDN1');
  }




  public function get_sum_qty($code)
  {
    $rs = $this->db->select_sum('qty', 'qty')
    ->where('receive_code', $code)
    ->get('receive_product_detail');

    return intval($rs->row()->qty);
  }



  public function get_sum_amount($code)
  {
    $rs = $this->db->select_sum('amount')->where('receive_code', $code)->get('receive_product_detail');
    return $rs->row()->amount === NULL ? 0.00 : $rs->row()->amount;
  }




  public function set_status($code, $status)
  {
    return $this->db->set('status', $status)->where('code', $code)->update('receive_product');
  }



  public function count_rows(array $ds = array())
  {
    $this->db->select('status');

    //---- เลขที่เอกสาร
    if($ds['code'] != '')
    {
      $this->db->like('code', $ds['code']);
    }

    //--- ใบสั่งซื้อ
    if($ds['po'] != '')
    {
      $this->db->like('po_code', $ds['po']);
    }

    //---- invoice
    if($ds['invoice'] != '')
    {
      $this->db->like('invoice_code', $ds['invoice']);
    }

    if($ds['from_date'] != '' && $ds['to_date'] != '')
    {
      $this->db->where('date_add >=', from_date($ds['from_date']));
      $this->db->where('date_add <=', to_date($ds['to_date']));
    }

    $rs = $this->db->get('receive_product');


    return $rs->num_rows();
  }





  public function get_data(array $ds = array(), $perpage = '', $offset = '', $role = 'S')
  {
    //---- เลขที่เอกสาร
    if($ds['code'] != '')
    {
      $this->db->like('code', $ds['code']);
    }

    //--- ใบสั่งซื้อ
    if($ds['po'] != '')
    {
      $this->db->like('po_code', $ds['po']);
    }

    //---- invoice
    if($ds['invoice'] != '')
    {
      $this->db->like('invoice_code', $ds['invoice']);
    }


    //--- vendor
    if($ds['vendor'] != '')
    {
      $this->db->like('vendor_code', $ds['vendor']);
      $this->db->or_like('vendor_name', $ds['vendor']);
    }


    if($ds['from_date'] != '' && $ds['to_date'] != '')
    {
      $this->db->where('date_add >=', from_date($ds['from_date']));
      $this->db->where('date_add <=', to_date($ds['to_date']));
    }

    if($perpage != '')
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get('receive_product');
    return $rs->result();
  }


  public function get_max_code($code)
  {
    $rs = $this->db
    ->like('code', $code, 'after')
    ->order_by('code', 'DESC')
    ->get('receive_product');

    if($rs->num_rows() == 1)
    {
      return $rs->row()->code;
    }

    return FALSE;
  }



}

 ?>
