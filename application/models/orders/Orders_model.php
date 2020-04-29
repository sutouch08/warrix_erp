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



  public function update($code, array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->where('code', $code)->update('orders', $ds);
    }

    return FALSE;
  }


  public function get($code)
  {
    $rs = $this->db->where('code', $code)->get('orders');
    if($rs->num_rows() == 1)
    {
      return $rs->row();
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



  public function is_exists_order($code, $old_code = NULL)
  {
    if($old_code !== NULL)
    {
      $this->db->where('code !=', $old_code);
    }

    $rs = $this->db->where('code', $code)->get('orders');
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



  public function get_detail($id)
  {
    $rs = $this->db->where('id', $id)->get('order_details');
    if($rs->num_rows() === 1)
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



  public function get_unvalid_details($code)
  {
    $rs = $this->db
    ->where('order_code', $code)
    ->where('valid', 0)
    ->where('is_count', 1)
    ->get('order_details');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }



  public function get_valid_details($code)
  {
    $qr  = "SELECT * FROM order_details
            WHERE order_code = '{$code}'
            AND (valid = 1 OR is_count = 0)";
    $rs = $this->db->query($qr);

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }


  public function get_state($code)
  {
    $rs = $this->db->select('state')->where('code', $code)->or_where('reference', $code)->get('orders');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->state;
    }

    return FALSE;
  }



  public function get_order_code_by_reference($reference)
  {
    $rs = $this->db->select('code')->where('reference', $reference)->get('orders');
    if($rs->num_rows() > 0)
    {
      return $rs->row()->code;
    }

    return FALSE;
  }



  public function valid_detail($id)
  {
    return $this->db->set('valid', 1)->where('id', $id)->update('order_details');
  }

  public function unvalid_detail($order_code, $item_code)
  {
    return $this->db->set('valid', 0)->where('order_code', $order_code)->where('product_code', $item_code)->update('order_details');
  }



  public function valid_all_details($code)
  {
    return $this->db->set('valid', 1)->where('order_code', $code)->update('order_details');
  }




  public function change_state($code, $state)
  {
    $arr = array(
      'state' => $state,
      'update_user' => get_cookie('uname')
    );

    return $this->db->where('code', $code)->update('orders', $arr);
  }




  public function update_shipping_code($code, $ship_code)
  {
    return $this->db->set('shipping_code', $ship_code)->where('code', $code)->update('orders');
  }




  public function set_never_expire($code, $option)
  {
    return $this->db->set('never_expire', $option)->where('code', $code)->update('orders');
  }


  public function un_expired($code)
  {
    $this->db->trans_start();
    $this->db->set('is_expired', 0)->where('order_code', $code)->update('order_details');
    $this->db->set('is_expired', 0)->where('code', $code)->update('orders');
    $this->db->trans_complete();

    if($this->db->trans_status() === FALSE)
    {
      return FALSE;
    }

    return TRUE;
  }


  //---- เปิดบิลใน SAP เรียบร้อยแล้ว
  public function set_complete($code)
  {
    return $this->db->set('is_complete', 1)->where('order_code', $code)->update('order_details');
  }



  public function un_complete($code)
  {
    return $this->db->set('is_complete', 0)->where('order_code', $code)->update('order_details');
  }


  public function paid($code, $paid)
  {
    $paid = $paid === TRUE ? 1 : 0;
    return $this->db->set('is_paid', $paid)->where('code', $code)->update('orders');
  }



  public function count_rows(array $ds = array(), $role = 'S')
  {
    $this->db
    ->select('orders.*')
    ->from('orders')
    ->join('customers', 'orders.customer_code = customers.code', 'left')
    ->join('zone', 'orders.zone_code = zone.code', 'left')
    ->join('user', 'orders.user = user.uname', 'left')
    ->where('role', $role);

    //---- เลขที่เอกสาร
    if( ! empty($ds['code']))
    {
      $this->db->like('orders.code', $ds['code']);
    }

    //--- รหัส/ชื่อ ลูกค้า
    if( ! empty($ds['customer']))
    {
      $this->db->group_start();
      $this->db->like('customers.code', $ds['customer']);
      $this->db->or_like('customers.name', $ds['customer']);
      $this->db->group_end();
    }

    //---- user name / display name
    if( ! empty($ds['user']))
    {
      $this->db->group_start();
      $this->db->like('user.uname', $ds['user']);
      $this->db->or_like('user.name', $ds['user']);
      $this->db->group_end();
    }

    //---- เลขที่อ้างอิงออเดอร์ภายนอก
    if( ! empty($ds['reference']))
    {
      $this->db->like('orders.reference', $ds['reference']);
    }

    //---เลขที่จัดส่ง
    if( ! empty($ds['ship_code']))
    {
      $this->db->like('orders.shipping_code', $ds['ship_code']);
    }

    //--- ช่องทางการขาย
    if( ! empty($ds['channels']))
    {
      $this->db->where('orders.channels_code', $ds['channels']);
    }

    //--- ช่องทางการชำระเงิน
    if( ! empty($ds['payment']))
    {
      $this->db->where('orders.payment_code', $ds['payment']);
    }


    if( ! empty($ds['zone_code']))
    {
      $this->db->group_start();
      $this->db->like('zone.code', $ds['zone_code']);
      $this->db->or_like('zone.name', $ds['zone_code']);
      $this->db->group_end();
    }

    if( !empty($ds['user_ref']))
    {
      $this->db->like('orders.user_ref', $ds['user_ref']);
    }

    if(!empty($ds['empName']))
    {
      $this->db->like('orders.empName', $ds['empName']);
    }


    if( ! empty($ds['from_date']) && ! empty($ds['to_date']))
    {
      $this->db->where('orders.date_add >=', from_date($ds['from_date']));
      $this->db->where('orders.date_add <=', to_date($ds['to_date']));
    }

    if(!empty($ds['warehouse']))
    {
      $this->db->where('orders.warehouse_code', $ds['warehouse']);
    }

    if(!empty($ds['notSave']))
    {
      $this->db->where('orders.status', 0);
    }

    if(!empty($ds['onlyMe']))
    {
      $this->db->where('orders.user', get_cookie('uname'));
    }

    if(!empty($ds['isExpire']))
    {
      $this->db->where('orders.is_expired', 1);
    }

    if(!empty($ds['state_list']))
    {
      $this->db->where_in('orders.state', $ds['state_list']);
    }

    return $this->db->count_all_results();
  }





  public function get_data(array $ds = array(), $perpage = '', $offset = '', $role = 'S')
  {
    $this->db
    ->select('orders.*')
    ->from('orders')
    ->join('customers', 'orders.customer_code = customers.code', 'left')
    ->join('zone', 'orders.zone_code = zone.code', 'left')
    ->join('user', 'orders.user = user.uname', 'left')
    ->where('role', $role);

    //---- เลขที่เอกสาร
    if( ! empty($ds['code']))
    {
      $this->db->like('orders.code', $ds['code']);
    }

    //--- รหัส/ชื่อ ลูกค้า
    if( ! empty($ds['customer']))
    {
      $this->db->group_start();
      $this->db->like('customers.code', $ds['customer']);
      $this->db->or_like('customers.name', $ds['customer']);
      $this->db->group_end();
    }

    //---- user name / display name
    if( ! empty($ds['user']))
    {
      $this->db->group_start();
      $this->db->like('user.uname', $ds['user']);
      $this->db->or_like('user.name', $ds['user']);
      $this->db->group_end();
    }

    //---- เลขที่อ้างอิงออเดอร์ภายนอก
    if( ! empty($ds['reference']))
    {
      $this->db->like('orders.reference', $ds['reference']);
    }

    //---เลขที่จัดส่ง
    if( ! empty($ds['ship_code']))
    {
      $this->db->like('orders.shipping_code', $ds['ship_code']);
    }

    //--- ช่องทางการขาย
    if( ! empty($ds['channels']))
    {
      $this->db->where('orders.channels_code', $ds['channels']);
    }

    //--- ช่องทางการชำระเงิน
    if( ! empty($ds['payment']))
    {
      $this->db->where('orders.payment_code', $ds['payment']);
    }


    if( ! empty($ds['zone_code']))
    {
      $this->db->group_start();
      $this->db->like('zone.code', $ds['zone_code']);
      $this->db->or_like('zone.name', $ds['zone_code']);
      $this->db->group_end();
    }

    if( !empty($ds['user_ref']))
    {
      $this->db->like('orders.user_ref', $ds['user_ref']);
    }

    if(!empty($ds['empName']))
    {
      $this->db->like('orders.empName', $ds['empName']);
    }

    if( ! empty($ds['from_date']) && ! empty($ds['to_date']))
    {
      $this->db->where('orders.date_add >=', from_date($ds['from_date']));
      $this->db->where('orders.date_add <=', to_date($ds['to_date']));
    }

    if(!empty($ds['warehouse']))
    {
      $this->db->where('orders.warehouse_code', $ds['warehouse']);
    }

    if(!empty($ds['notSave']))
    {
      $this->db->where('orders.status', 0);
    }

    if(!empty($ds['onlyMe']))
    {
      $this->db->where('orders.user', get_cookie('uname'));
    }

    if(!empty($ds['isExpire']))
    {
      $this->db->where('orders.is_expired', 1);
    }

    if(!empty($ds['state_list']))
    {
      $this->db->where_in('orders.state', $ds['state_list']);
    }

    if(!empty($ds['order_by']))
    {
      $order_by = "orders.{$ds['order_by']}";
      $this->db->order_by($order_by, $ds['sort_by']);
    }
    else
    {
      $this->db->order_by('orders.code', 'DESC');
    }
    
    if($perpage != '')
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get();
    //echo $this->db->get_compiled_select('orders');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
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


  public function get_bill_total_amount($code)
  {
    $rs = $this->db
    ->select_sum('total_amount', 'amount')
    ->where('reference', $code)
    ->get('order_sold');

    return $rs->row()->amount;
  }



  public function get_order_total_qty($code)
  {
    $this->db->select_sum('qty', 'qty');
    $this->db->where('order_code', $code);
    $rs = $this->db->get('order_details');
    return $rs->row()->qty;
  }


  //--- ใช้คำนวนยอดเครดิตคงเหลือ
  public function get_sum_not_complete_amount($customer_code)
  {
    $rs = $this->db
    ->select_sum('order_details.total_amount', 'amount')
    ->from('order_details')
    ->join('orders', 'orders.code = order_details.order_code', 'left')
    ->where_in('orders.role', array('S', 'C', 'N'))
    ->where('orders.customer_code', $customer_code)
    ->where('order_details.is_complete', 0)
    ->where('orders.is_expired', 0)
    ->get();

    if($rs->num_rows() === 1)
    {
      return $rs->row()->amount;
    }

    return 0.00;
  }



  public function get_bill_discount($code)
  {
    $rs = $this->db->select('bDiscAmount')
    ->where('code', $code)
    ->get('orders');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->bDiscAmount;
    }

    return 0;
  }


  public function get_sum_style_qty($order_code, $style_code)
  {
    $rs = $this->db->select_sum('qty')
    ->where('order_code', $order_code)
    ->where('style_code', $style_code)
    ->get('order_detils');

    return $rs->row()->qty;
  }




  public function get_reserv_stock($item_code, $warehouse = NULL, $zone = NULL)
  {
    $this->db
    ->select_sum('order_details.qty', 'qty')
    ->from('order_details')
    ->join('orders', 'order_details.order_code = orders.code', 'left')
    ->where('order_details.product_code', $item_code)
    ->where('order_details.is_complete', 0)
    ->where('order_details.is_expired', 0)
    ->where('order_details.is_count', 1);

    if($warehouse !== NULL)
    {
      $this->db->where('orders.warehouse_code', $warehouse);
    }

    if($zone !== NULL)
    {
      $this->db->where('orders.zone_code', $zone);
    }

    $rs = $this->db->get();

    if($rs->num_rows() == 1)
    {
      return $rs->row()->qty;
    }

    return 0;
  }



  public function get_reserv_stock_by_style($style_code, $warehouse = NULL)
  {
    $this->db
    ->select_sum('order_details.qty', 'qty')
    ->from('order_details')
    ->join('orders', 'order_details.order_code = orders.code', 'left')
    ->where('order_details.style_code', $style_code)
    ->where('order_details.is_complete', 0)
    ->where('order_details.is_expired', 0)
    ->where('order_details.is_count', 1);
    if($warehouse !== NULL)
    {
      $this->db->where('warehouse_code', $warehouse);
    }
    $rs = $this->db->get();
    if($rs->num_rows() == 1)
    {
      return $rs->row()->qty;
    }

    return 0;
  }


  public function set_status($code, $status)
  {
    return $this->db->set('status', $status)->where('code', $code)->update('orders');
  }



  public function update_approver($code, $user)
  {
    return $this->db->set('approver', $user)->where('code', $code)->update('orders');
  }

  //---- ระบุที่อยู่จัดส่งในออเดอร์นั้นๆ
  public function set_address_id($code, $id_address)
  {
    return $this->db->set('id_address', $id_address)->where('code', $code)->update('orders');
  }



  public function clear_order_detail($code)
  {
    return $this->db->where('order_code', $code)->delete('order_details');
  }


} //--- End class


 ?>
