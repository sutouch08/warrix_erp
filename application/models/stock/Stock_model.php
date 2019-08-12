<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class stock_model extends CI_Model
{
  public $ms;
  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE);
  }


  public function get_style_sell_stock($style_code)
  {
    $this->ms->select_sum('OIBQ.OnHandQty', 'qty')
    ->from('OBIN')
    ->join('OIBQ', 'OBIN.WhsCode = OIBQ.WhsCode AND OBIN.AbsEntry = OIBQ.BinAbs', 'left')
    ->join('OITM', 'OIBQ.ItemCode = OITM.ItemCode', 'left')
    ->join('OWHS', 'OWHS.WhsCode = OBIN.WhsCode', 'left')
    ->where('OWHS.U_WH_MAIN', 'Y')
    ->where('OITM.U_MODEL', $style_code);
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
    ->join('OITM', 'OIBQ.ItemCode = OITM.ItemCode', 'left')
    ->where('OITM.ItemCode', $pd_code)
    ->where('OBIN.BinCode', $zone_code);
    $rs = $this->ms->get();
    if($rs->num_rows() == 1)
    {
      return intval($rs->row()->qty);
    }

    return 0;
  }


  //---- ยอดรวมสินค้าในคลังที่สั่งได้ ยอดในโซน
  public function get_sell_stock($item)
  {
    $rs = $this->ms
    ->select_sum('OnHandQty', 'qty')
    ->from('OIBQ')
    ->join('OBIN', 'OBIN.WhsCode = OIBQ.WhsCode AND OBIN.AbsEntry = OIBQ.BinAbs', 'left')
    ->join('OWHS', 'OWHS.WhsCode = OBIN.WhsCode', 'left')
    ->where('OIBQ.ItemCode', $item)
    ->where('OWHS.U_WH_MAIN', 'Y')
    ->get();
    return intval($rs->row()->qty);
  }


  //--- ยอดรวมสินค้าทั้งหมดทุกคลัง (รวมฝากขาย)
  public function get_stock($item)
  {
    $rs = $this->ms->select_sum('OnHandQty', 'qty')->where('ItemCode', $item)->get('OIBQ');
    return intval($rs->row()->qty);
  }


  //---- ยอดสินค้าคงเหลือในแต่ละโซน
  public function get_stock_in_zone($item)
  {
    $rs = $this->ms
    ->select('OBIN.BinCode AS code, OIBQ.OnHandQty AS qty')
    ->from('OIBQ')
    ->join('OBIN', 'OBIN.WhsCode = OIBQ.WhsCode AND OBIN.AbsEntry = OIBQ.BinAbs', 'left')
    ->join('OWHS', 'OWHS.WhsCode = OBIN.WhsCode', 'left')
    ->where('OWHS.U_WH_MAIN', 'Y')
    ->where('ItemCode', $item)
    ->get();

    $result = array();

    if($rs->num_rows() > 0)
    {
      foreach($rs->result() as $stock)
      {
        $ds = new stdClass();
        $ds->code = $stock->code;
        $ds->name = $stock->code;
        $ds->qty  = $stock->qty;
        $result[] = $ds;
      }
    }

    return $result;
  }

}//--- end class