<?php
class Transfer_model extends CI_Model
{
  public $ms;
  public $mc;
  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE);
  }


  
}

 ?>
