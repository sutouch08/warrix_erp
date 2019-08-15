<?php
class Test_api extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->load->library('api');
    $item = 'WA-19FT53M-DD-M';
    $qty = 59;
    $this->api->send_stock($item, $qty);
  }

  public function update_stock($item, $qty)
  {
    $this->load->library('api');
    $this->api->update_stock($item, $qty);
  }


  public function test()
  {
    $data = ["stockItems" => [ "qty" => 20  ] ];
    echo json_encode($data);
  }
}

 ?>
