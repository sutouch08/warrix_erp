<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class stock_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function get_style_sell_stock($style_code, $warehouse = NULL)
  {
    $this->ms->select_sum('OIBQ.OnHandQty', 'qty')
    ->from('OBIN')
    ->join('OIBQ', 'OBIN.WhsCode = OIBQ.WhsCode AND OBIN.AbsEntry = OIBQ.BinAbs', 'left')
    ->join('OITM', 'OIBQ.ItemCode = OITM.ItemCode', 'left')
    ->join('OWHS', 'OWHS.WhsCode = OBIN.WhsCode', 'left')
    ->where('OWHS.U_MAIN', 'Y');
    if($warehouse !== NULL)
    {
      $this->ms->where('OWHS.WhsCode', $warehouse);
    }
    $this->ms->where('OITM.U_MODEL', $style_code);
    $rs = $this->ms->get();
    if($rs->num_rows() == 1)
    {
      return intval($rs->row()->qty);
    }

    return 0;
  }



  public function get_stock_zone($zone_code, $pd_code)
  {
    $this->ms->select_sum('OIBQ.OnHandQty', 'qty')
    ->from('OBIN')
    ->join('OIBQ', 'OBIN.WhsCode = OIBQ.WhsCode AND OBIN.AbsEntry = OIBQ.BinAbs', 'left')
    ->where('OIBQ.ItemCode', $pd_code)
    ->where('OBIN.BinCode', $zone_code);
    $rs = $this->ms->get();
    if($rs->num_rows() == 1)
    {
      return intval($rs->row()->qty);
    }

    return 0;
  }


  //---- ยอดรวมสินค้าในคลังที่สั่งได้ ยอดในโซน
  public function get_sell_stock($item, $warehouse = NULL, $zone = NULL)
  {
    $this->ms
    ->select_sum('OnHandQty', 'qty')
    ->from('OIBQ')
    ->join('OBIN', 'OBIN.WhsCode = OIBQ.WhsCode AND OBIN.AbsEntry = OIBQ.BinAbs', 'left')
    ->join('OWHS', 'OWHS.WhsCode = OBIN.WhsCode', 'left')
    ->where('OIBQ.ItemCode', $item);
    if($zone === NULL)
    {
      $this->ms->where('OWHS.U_MAIN', 'Y');
    }

    if($warehouse !== NULL)
    {
      $this->ms->where('OWHS.WhsCode', $warehouse);
    }

    if($zone !== NULL)
    {
      $this->ms->where('OBIN.BinCode', $zone);
    }

    $rs = $this->ms->get();
    return intval($rs->row()->qty);
  }


  //--- ยอดรวมสินค้าทั้งหมดทุกคลัง (รวมฝากขาย)
  public function get_stock($item)
  {
    $rs = $this->ms->select_sum('OnHandQty', 'qty')->where('ItemCode', $item)->get('OIBQ');
    return intval($rs->row()->qty);
  }


  //---- ยอดสินค้าคงเหลือในแต่ละโซน
  public function get_stock_in_zone($item, $warehouse = NULL)
  {
    $this->ms
    ->select('OBIN.BinCode AS code, OBIN.Descr AS name, OIBQ.OnHandQty AS qty')
    ->from('OIBQ')
    ->join('OBIN', 'OBIN.WhsCode = OIBQ.WhsCode AND OBIN.AbsEntry = OIBQ.BinAbs', 'left')
    ->join('OWHS', 'OWHS.WhsCode = OBIN.WhsCode', 'left')
    ->where('OWHS.U_MAIN', 'Y')
    ->where('ItemCode', $item);
    if($warehouse !== NULL)
    {
      $this->ms->where('OWHS.WhsCode', $warehouse);
    }
    $rs = $this->ms->get();

    $result = array();

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $stock)
      {
        $ds = new stdClass();
        $ds->code = $stock->code;
        $ds->name = $stock->name;
        $ds->qty  = $stock->qty;
        $result[] = $ds;
      }
    }

    return $result;
  }


  //---- สินค้าทั้งหมดที่อยู่ในโซน (ใช้โอนสินค้าระหว่างคลัง)
  public function get_all_stock_in_zone($zone_code)
  {
    $rs = $this->ms
    ->select('OIBQ.ItemCode AS product_code, OIBQ.OnHandQty AS qty')
    ->from('OIBQ')
    ->join('OBIN', 'OBIN.WhsCode = OIBQ.WhsCode AND OBIN.AbsEntry = OIBQ.BinAbs', 'left')
    ->where('OBIN.BinCode', $zone_code)
    ->get();

    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

}//--- end class
