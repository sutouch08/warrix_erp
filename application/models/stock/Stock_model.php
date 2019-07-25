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

}//--- end class
