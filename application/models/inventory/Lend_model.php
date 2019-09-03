<?php
class Lend_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function add_detail(array $ds = array())
  {
    if(!empty($ds))
    {
      return $this->db->insert('order_lend_detail', $ds);
    }

    return FALSE;
  }

} //--- End class 


 ?>
