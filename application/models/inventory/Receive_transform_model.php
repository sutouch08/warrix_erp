<?php
class Receive_transform_model extends CI_Model
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
      return $this->db->insert('receive_transform', $ds);
    }

    return FALSE;
  }



  public function update($code, array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->where('code', $code)->update('receive_transform', $ds);
    }

    return FALSE;
  }


  public function add_detail(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('receive_transform_detail', $ds);
    }

    return FALSE;
  }



  public function get($code)
  {
    $rs = $this->db->where('code', $code)->get('receive_transform');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }



  public function get_details($code)
  {
    $rs = $this->db
    ->select('receive_transform_detail.*, products.barcode')
    ->from('receive_transform_detail')
    ->join('products', 'products.code = receive_transform_detail.product_code', 'left')
    ->where('receive_code', $code)
    ->get();
    
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function drop_details($code)
  {
    return $this->db->where('receive_code', $code)->delete('receive_transform_detail');
  }



  public function cancle_details($code)
  {
    return $this->db->set('is_cancle', 1)->where('receive_code', $code)->update('receive_transform_detail');
  }




  public function get_transform_details($order_code)
  {
    $rs = $this->db
    ->select('order_transform_detail.*, products.name, products.barcode')
    ->select_sum('order_transform_detail.sold_qty', 'sold_qty')
    ->select_sum('order_transform_detail.receive_qty', 'receive_qty')
    ->from('order_transform_detail')
    ->join('order_transform', 'order_transform.order_code = order_transform_detail.order_code', 'left')
    ->join('products', 'products.code = order_transform_detail.product_code', 'left')
    ->where('order_transform_detail.order_code', $order_code)
    ->where('order_transform.is_closed', 0)
    ->group_by('order_transform_detail.product_code')
    ->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function get_sum_qty($code)
  {
    $rs = $this->db->select_sum('qty', 'qty')
    ->where('receive_code', $code)
    ->get('receive_transform_detail');

    return intval($rs->row()->qty);
  }



  public function set_status($code, $status)
  {
    return $this->db->set('status', $status)->where('code', $code)->update('receive_transform');
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
    if($ds['order_code'] != '')
    {
      $this->db->like('order_code', $ds['order_code']);
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

    $rs = $this->db->get('receive_transform');


    return $rs->num_rows();
  }





  public function get_data(array $ds = array(), $perpage = '', $offset = '')
  {
    //---- เลขที่เอกสาร
    if($ds['code'] != '')
    {
      $this->db->like('code', $ds['code']);
    }

    //--- ใบสั่งซื้อ
    if($ds['order_code'] != '')
    {
      $this->db->like('order_code', $ds['order_code']);
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

    if($perpage != '')
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get('receive_transform');
    return $rs->result();
  }


  public function get_max_code($code)
  {
    $rs = $this->db
    ->like('code', $code, 'after')
    ->order_by('code', 'DESC')
    ->get('receive_transform');

    if($rs->num_rows() == 1)
    {
      return $rs->row()->code;
    }

    return FALSE;
  }



}

 ?>