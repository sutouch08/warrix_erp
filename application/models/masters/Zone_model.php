<?php
class Zone_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  //--- add new zone (use with sync only)
  public function add(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('zone', $ds);
    }

    return FALSE;
  }


  //--- update zone with sync only
  public function update($id, $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->where('id', $id)->update('zone', $ds);
    }

    return FALSE;
  }


  //--- add new customer to zone
  public function add_customer(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('zone_customer', $ds);
    }

    return FALSE;
  }



  //--- remove customer from connected zone
  public function delete_customer($id)
  {
    return $this->db->where('id', $id)->delete('zone_customer');
  }


  //---- delete zone  must use only mistake on sap and delete zone in SAP already
  public function delete($code)
  {
    return $this->db->where('code', $code)->delete('zone');
  }

  //--- check zone exists or not
  public function is_exists($code)
  {
    if($this->db->where('code', $code)->count_all_results('zone') > 0)
    {
      return TRUE;
    }

    return FALSE;
  }


  //--- check zone exists by id
  public function is_exists_id($id)
  {
    if($this->db->where('id', $id)->count_all_results('zone') > 0)
    {
      return TRUE;
    }

    return FALSE;
  }


  //--- check customer exists in zone or not
  public function is_exists_customer($zone_code, $customer_code)
  {
    $rs = $this->db
    ->where('zone_code', $zone_code)
    ->where('customer_code', $customer_code)
    ->count_all_results('zone_customer');

    if($rs > 0)
    {
      return TRUE;
    }

    return FALSE;
  }


  public function is_sap_exists($code)
  {
    if($this->ms->where('BinCode', $code)->count_all_results('OBIN') > 0)
    {
      return TRUE;
    }

    return FALSE;
  }



  public function count_rows(array $ds = array())
  {
    if(!empty($ds['customer']))
    {
      return $this->count_rows_customer($ds);
    }

    if(!empty($ds['code']))
    {
      $this->db->like('code', $ds['code']);
    }

    if(!empty($ds['name']))
    {
      $this->db->like('name', $ds['name']);
    }

    if(!empty($ds['warehouse']))
    {
      $this->db->where('warehouse_code', $ds['warehouse']);
    }

    return $this->db->count_all_results('zone');
  }




  private function count_rows_customer(array $ds = array())
  {
    $this->db
    ->from('zone_customer')
    ->join('zone', 'zone.code = zone_customer.zone_code')
    ->join('customers', 'zone_customer.customer_code = customers.code')
    ->like('customers.code', $ds['customer'])
    ->or_like('customers.name', $ds['customer']);

    if(!empty($ds['code']))
    {
      $this->db->like('zone.code', $ds['code']);
    }

    if(!empty($ds['name']))
    {
      $this->db->like('zone.name', $ds['name']);
    }

    if(!empty($ds['warehouse']))
    {
      $this->db->where('zone.warehouse_code', $ds['warehouse']);
    }

    return $this->db->count_all_results();
  }





  public function get_list(array $ds = array(), $perpage = NULL, $offset = NULL)
  {
    //--- if search for customer
    if(!empty($ds['customer']))
    {
      return $this->get_list_customer($ds);
    }

    $this->db
    ->select('zone.code AS code, zone.name AS name, warehouse.name AS warehouse_name')
    ->from('zone')
    ->join('warehouse', 'warehouse.code = zone.warehouse_code', 'left');

    if(!empty($ds['code']))
    {
      $this->db->like('zone.code', $ds['code']);
    }

    if(!empty($ds['name']))
    {
      $this->db->like('zone.name', $ds['name']);
    }

    if(!empty($ds['warehouse']))
    {
      $this->db->where('zone.warehouse_code', $ds['warehouse']);
    }

    if(!empty($perpage))
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }






  private function get_list_customer(array $ds = array(), $perpage = NULL, $offset = NULL)
  {
    $this->db
    ->select('zone.code AS code, zone.name AS name, warehouse.name AS warehouse_name')
    ->select('customers.code AS customer_code, customers.name AS customer_name')
    ->from('zone_customer')
    ->join('zone', 'zone.code = zone_customer.zone_code')
    ->join('customers', 'zone_customer.customer_code = customers.code')
    ->join('warehouse', 'zone.warehouse_code = warehouse.code', 'left')
    ->like('customers.code', $ds['customer'])
    ->or_like('customers.name', $ds['customer']);

    if(!empty($ds['code']))
    {
      $this->db->like('zone.code', $ds['code']);
    }

    if(!empty($ds['name']))
    {
      $this->db->like('zone.name', $ds['name']);
    }

    if(!empty($ds['warehouse']))
    {
      $this->db->where('zone.warehouse_code', $ds['warehouse']);
    }

    $this->db->group_by('zone.code');

    if(!empty($perpage))
    {
      $offset = $offset === NULL ? 0 : $offset;
      $this->db->limit($perpage, $offset);
    }

    $rs = $this->db->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }






  public function count_customer($code)
  {
    return $this->db->where('zone_code', $code)->count_all_results('zone_customer');
  }


  public function get_customers($zone_code)
  {

    $rs = $this->db->where('zone_code', $zone_code)->get('zone_customer');

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }




  public function get($code)
  {
    $rs = $this->ms
    ->select('OBIN.BinCode AS code, OBIN.SL1Code AS name, OWHS.WhsCode AS warehouse_code, OWHS.WhsName AS warehouse_name')
    ->from('OBIN')
    ->join('OWHS', 'OWHS.WhsCode = OBIN.WhsCode', 'left')
    ->where('OBIN.BinCode', $code)
    ->get();

    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }






  public function get_warehouse_code($zone_code)
  {
    $rs = $this->ms->select('WhsCode')->where('BinCode', $zone_code)->get('OBIN');
    if($rs->num_rows() == 1)
    {
      return $rs->row()->WhsCode;
    }

    return FALSE;
  }






  public function get_name($code)
  {
    $rs = $this->ms->select('SL1Code AS name')->where('BinCode', $code)->get('OBIN');
    //$rs = $this->db->select('name')->where('code', $code)->get('zone');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->name;
    }

    return NULL;
  }



  public function get_zone_detail_in_warehouse($code, $warehouse)
  {
    $rs = $this->ms
    ->select('BinCode AS code, SL1Code AS name, WhsCode AS warehouse_code')
    ->where('WhsCode', $warehouse)
    ->where('BinCode', $code)
    ->get('OBIN');

    //$rs = $this->db->where('warehouse_code', $warehouse)->where('code', $code)->get('zone');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }


  public function search($txt, $warehouse_code)
  {
    if($warehouse_code != '')
    {
      $this->ms->where('WhsCode', $warehouse_code);
    }

    $rs = $this->ms->like('BinCode', $txt)->get('OBIN');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }


  public function get_last_create_date()
  {
    $rs = $this->db->select_max('sap_createDate', 'create_date')->get('zone');
    return $rs->row()->create_date;
  }


  public function get_last_update_date()
  {
    $rs = $this->db->select_max('sap_updateDate', 'update_date')->get('zone');
    return $rs->row()->update_date;
  }


  public function get_new_data($last_add, $last_upd)
  {
    $this->ms->select('AbsEntry AS id, BinCode AS code, SL1Code AS name, WhsCode AS warehouse_code');
    $this->ms->select('createDate, updateDate');
    $this->ms->where('SysBin', 'N');
    $this->ms->where('createDate >', $last_add);
    $this->ms->or_where('updateDate >', $last_upd);
    $rs = $this->ms->get('OBIN');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }


  public function get_all_zone()
  {
    $this->ms->select('AbsEntry AS id, BinCode AS code, SL1Code AS name, WhsCode AS warehouse_code');
    $this->ms->select('createDate, updateDate');
    $this->ms->where('SysBin', 'N');
    $rs = $this->ms->get('OBIN');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;
  }

} //--- end class

 ?>
