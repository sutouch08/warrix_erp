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
    //$rs = $this->ms->select_sum('onhand')->where('styleCode')->get('OITM');
    //return $rs->row()->onhand;
    return 100;
  }



  public function get_stock_zone($zone_code, $pd_code)
  {
    // $this->ms->select('onhand')
    // ->where('zoneCode', $zone_code)
    // ->where('ItemCode', $pd_code);
    // $rs = $this->ms->get('OITM');
    // return $rs->row()->onhand;
    return 100;
  }


  //---- ยอดรวมสินค้าที่สั่งได้ ยอดในโซน - ยอดที่สั่งมาแล้ว
  public function get_sell_stock($item)
  {
    return 100;
  }



  //--- ยอดรวมสินค้าทั้งหมดทุกคลัง (รวมฝากขาย)
  public function get_stock($item)
  {
    return 100;
  }


  //---- ยอดสินค้าคงเหลือในแต่ละโซน
  public function get_stock_in_zone($item)
  {
    // $rs = $this->ms
    // ->select('zoneCode, zoneName, qty')
    // ->where('ItemCode', $item_code)
    // ->where('warehouse', xxx) //--- warehouse ต้องไม่ใช่คลังฝากขาย

    //--- จำลองผลลัพธ์
    $result = array();
    $arr = array(
      array('code' => 'C001', 'name' => 'C-001', 'qty' => 20),
      array('code' => 'C002', 'name' => 'C-002', 'qty' => 13),
      array('code' => 'C003', 'name' => 'C-003', 'qty' => 51)
    );

    foreach($arr as $stock)
    {
      $ds = new stdClass();
      $ds->code = $stock['code'];
      $ds->name = $stock['name'];
      $ds->qty = $stock['qty'];
      $result[] = $ds;
    }

    return $result;
  }

}//--- end class
