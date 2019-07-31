<?php
class Zone_model extends CI_Model
{
  public $ms;
  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE);
  }

  public function get($code)
  {
    $rs = $this->db->where('code', $code)->get('zone');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }


  public function is_exists($code)
  {
    $rs = $this->db->where('code', $code)->get('zone');
    if($rs->num_rows() > 0)
    {
      return TRUE;
    }

    return FALSE;
  }
} //--- end class

 ?>
