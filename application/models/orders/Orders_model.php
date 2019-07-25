<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }



  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('orders', $ds);
    }

    return FALSE;
  }



  public function add_detail(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('order_details', $ds);
    }

    return FALSE;
  }




  public function update_detail($id, array $ds = array())
  {
    return $this->db->where('id', $id)->update('order_details', $ds);
  }




  public function remove_detail($id)
  {
    return $this->db->where('id', $id)->delete('order_details');
  }




  public function is_exists_detail($order_code, $item_code)
  {
    $rs = $this->db->select('id')
    ->where('order_code', $order_code)
    ->where('product_code', $item_code)
    ->get('order_details');
    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }




  public function get_order_detail($order_code, $item_code)
  {
    $rs = $this->db
    ->where('order_code', $order_code)
    ->where('product_code', $item_code)
    ->get('order_details');
    if($rs->num_rows() == 1)
    {
      return $rs->row();
    }

    return FALSE;
  }

  public function get_order_details($code)
  {
    $rs = $this->db->where('order_code', $code)->get('order_details');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function count_rows(array $ds = array())
  {
    $this->db->select('status');
    //---- เลขที่เอกสาร
    if($ds['code'] != '')
    {
      $this->db->like('code', $ds['code']);
    }

    //--- รหัส/ชื่อ ลูกค้า
    if($ds['customer'] != '')
    {
      $customers = customer_in($ds['customer']);
      $this->db->where_in('customer_code', $customers);
    }

    //---- user name / display name
    if($ds['user'] != '')
    {
      $users = user_in($ds['user']);
      $this->db->where_in('user', $users);
    }

    //---- เลขที่อ้างอิงออเดอร์ภายนอก
    if($ds['reference'] != '')
    {
      $this->db->like('reference', $ds['reference']);
    }

    //---เลขที่จัดส่ง
    if($ds['ship_code'] != '')
    {
      $this->db->like('shipping_code', $ds['ship_code']);
    }

    //--- ช่องทางการขาย
    if($ds['channels'] != '')
    {
      $this->db->where('channels_code', $ds['channels']);
    }

    //--- ช่องทางการชำระเงิน
    if($ds['payment'] != '')
    {
      $this->db->where('payment_code', $ds['payment']);
    }

    if($ds['from_date'] != '' && $ds['to_date'] != '')
    {
      $this->db->where('date_add >=', $ds['from_date']);
      $this->db->where('date_add <=', $ds['to_date']);
    }

    $rs = $this->db->get('orders');


    return $rs->num_rows();
  }





  public function get_data(array $ds = array(), $perpage = '', $offset = '')
  {
      //---- เลขที่เอกสาร
      if($ds['code'] != '')
      {
        $this->db->like('code', $ds['code']);
      }

      //--- รหัส/ชื่อ ลูกค้า
      if($ds['customer'] != '')
      {
        $customers = customer_in($ds['customer']);
        $this->db->where_in('customer_code', $customers);
      }

      //---- user name / display name
      if($ds['user'] != '')
      {
        $users = user_in($ds['user']);
        $this->db->where_in('user', $users);
      }

      //---- เลขที่อ้างอิงออเดอร์ภายนอก
      if($ds['reference'] != '')
      {
        $this->db->like('reference', $ds['reference']);
      }

      //---เลขที่จัดส่ง
      if($ds['ship_code'] != '')
      {
        $this->db->like('shipping_code', $ds['ship_code']);
      }

      //--- ช่องทางการขาย
      if($ds['channels'] != '')
      {
        $this->db->where('channels_code', $ds['channels']);
      }

      //--- ช่องทางการชำระเงิน
      if($ds['payment'] != '')
      {
        $this->db->where('payment_code', $ds['payment']);
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

      $rs = $this->db->get('orders');

      return $rs->result();
  }





  public function get_order($code)
  {
    $rs = $this->db->where('code', $code)->get('orders');
    if($rs->num_rows() == 1)
    {
      return $rs->row();
    }

    return FALSE;
  }





  public function get_max_code($code)
  {
    $qr = "SELECT MAX(code) AS code FROM orders WHERE code LIKE '".$code."%' ORDER BY code DESC";
    $rs = $this->db->query($qr);
    return $rs->row()->code;
  }




  public function get_order_total_amount($code)
  {
    $this->db->select_sum('total_amount', 'amount');
    $this->db->where('order_code', $code);
    $rs = $this->db->get('order_details');
    return $rs->row()->amount;
  }





  public function get_reserv_stock($item_code)
  {
    $rs = $this->db->select_sum('qty')
    ->where('product_code', $item_code)
    ->where('valid', 0)
    ->where('is_expired', 0)
    ->where('is_count', 1)
    ->get('order_details');

    if($rs->num_rows() == 1)
    {
      return $rs->row()->qty;
    }

    return 0;
  }



  public function get_reserv_stock_by_style($style_code)
  {
    $rs = $this->db->select_sum('qty')
    ->where('style_code', $style_code)
    ->where('valid', 0)
    ->where('is_expired', 0)
    ->where('is_count', 1)
    ->get('order_details');
    if($rs->num_rows() == 1)
    {
      return $rs->row()->qty;
    }

    return 0;
  }


} //--- End class


 ?>
