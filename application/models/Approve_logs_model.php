<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Approve_logs_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
  }


  public function add($code, $state, $user)
  {
    $arr = array(
      'order_code' => $code,
      'approve' => $state,
      'approver' => $user
    );

    return $this->db->insert('order_approve', $arr);
  }


  public function get($code)
  {
    $rs = $this->db->where('order_code', $code)->get('order_approve');
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return NULL;
  }

} //--- End class

?>
