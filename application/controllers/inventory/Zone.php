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



  public function get_warehouse_zone()
  {
    $sc = TRUE;
    if($this->input->get('barcode'))
    {
      $code = trim($this->input->get('barcode'));
      $warehouse = $this->input->get('warehouse_code');
      $zone = $this->zone_model->get_zone_detail_in_warehouse($code, $warehouse);
      if($zone === FALSE)
      {
        $sc = FALSE;
      }
    }

    echo $sc === TRUE ? json_encode($zone) : 'not_exists';
  }



} //--- end class

 ?>
