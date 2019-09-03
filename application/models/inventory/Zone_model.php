<?php
class Zone_model extends CI_Model
{
  public $ms;
  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE);
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
    $rs = $this->ms->select('SL1Code', 'name')->where('BinCode', $code)->get('OBIN');
    //$rs = $this->db->select('name')->where('code', $code)->get('zone');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->name;
    }

    return NULL;
  }



  public function is_exists($code)
  {
    $rs = $this->ms->where('BinCode', $code)->get('OBIN');
    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
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

} //--- end class

 ?>
