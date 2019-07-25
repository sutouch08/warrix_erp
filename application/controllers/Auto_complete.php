<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_complete extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }


  public function get_customer_code_and_name()
  {
    if(isset($_REQUEST['term']))
    {
      $txt = $_REQUEST['term'];
      $sc = array();
      $qr = "SELECT code, name FROM customers WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%' LIMIT 50";
      $cust = $this->db->query($qr);
      if($cust->num_rows() > 0)
      {
        foreach($cust->result() as $rs)
        {
          $sc[] = $rs->code.' | '.$rs->name;
        }
      }

      echo json_encode($sc);
    }
  }



public function get_style_code()
{
  $sc = array();

	$qr  = "SELECT code FROM product_style WHERE code LIKE '%".$_REQUEST['term']."%' ";
	$qr .= "AND active = 1 AND can_sell = 1 AND is_deleted = 0 ORDER BY code ASC";
  $qs = $this->db->query($qr);

  if($qs->num_rows() > 0)
  {
    foreach($qs->result() as $rs)
    $sc[] = $rs->code;
  }
  
	echo json_encode($sc);
}


} //-- end class
?>
