<?php
class Employee_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }


  public function get($id)
  {
    $rs = $this->ms
    ->select('empID, lastName, firstName')
    ->where('empID', $id)->get('OHEM');
    if($rs->num_rows() === 1)
    {
      return $rs->row();
    }

    return FALSE;
  }

  
}//--- end class
 ?>
