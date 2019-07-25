<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Discount_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }


  public function get_item_discount($item_code, $customer_code, $qty, $payment_code, $channels_code, $date_add)
  {
    $sc = array(
			'discount1' => 0, //--- ส่วนลดเป็นจำนวนเงิน (ยอดต่อหน่วย)
			'unit1' => 'percent', //--- หน่วยของส่วนลด ('percent', 'amount')
			'discLabel1' => 0, //--- ข้อความที่ใช้แสดงส่วนลด เช่น 30%, 30
			'discount2' => 0,
			'unit2' => 'percent',
			'discLabel2' => 0,
			'discount3' => 0,
			'unit3' => 'percent',
			'discLabel3' => 0,
			'amount' => 0, //--- เอายอดส่วนลดที่ได้ มา คูณ ด้วย จำนวนสั่ง เป้นส่วนลดทั้งหมด
			'id_rule' => NULL
		); //-- end array

    return $sc;
  }




} //--- end class
?>
