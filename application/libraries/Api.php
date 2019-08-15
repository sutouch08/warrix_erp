<?php
class Api
{
  private $web_token = 'kdjx8mi8izkk1xriha4xi5qqnelrl87h';
  private $web_url = 'http://34.97.150.198/rest/V1/';
  public function __construct()
  {

  }

  public function update_web_stock($item, $qty)
  {
    $url = $this->web_url."products/{$item}/stockItems/1";
    $setHeaders = array('Content-Type:application/json','Authorization:Bearer '.$this->web_token);
    $apiUrl = str_replace(" ","%20",$url);
    $method = 'PUT';
    $data = ["stockItem" => ["qty" => $qty]];

    $data_string = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $setHeaders);
    $token = curl_exec($ch);

    $response = json_decode( curl_exec($ch), TRUE);
    curl_close($ch);
  }

  
}

 ?>
