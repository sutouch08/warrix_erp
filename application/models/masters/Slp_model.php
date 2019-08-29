<?php
class Slp_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function get_name($code)
  {
    $rs = $this->ms->select('SlpName')->where('SlpCode', $code)->get('OSLP');
    if($rs->num_rows() === 1)
    {
      return $rs->row()->SlpName;
    }

    return NULL;
  }


} //--- End class

 ?>
