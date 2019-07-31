<?php
class Zone extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('inventory/zone_model');
  }


  //--- check zone
  public function get_zone_code()
  {
    $sc = TRUE;
    if($this->input->get('barcode'))
    {
      $code = trim($this->input->get('barcode'));
      if($this->zone_model->is_exists($code) === FALSE)
      {
        $sc = FALSE;
      }
    }

    echo $sc === TRUE ? $code : 'not_exists';
  }
}

 ?>
