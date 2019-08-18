<?php
class Warehouse_model extends CI_Model
{
  public $ms;
  public $mc;

  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE);
    $this->mc = $this->load->database('mc', TRUE);
  }

  public function get_name($code)
  {
    $rs = $this->ms->select('WhsName')->where('WhsCode', $code)->get('OWHS');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->WhsName;
    }

    return NULL;
  }

} //--- end class

 ?>
