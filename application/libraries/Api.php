<?php
class Api
{
  private $web_url;
  private $userData = array('username' => 'user', 'password' => 'W@rr1X$p0rt');
  private $token_url;
  private $token;
  protected $ci;
  protected $attribute_set_id = 9;
  public function __construct()
  {
    $this->token = getConfig('WEB_API_ACCESS_TOKEN');
    $this->web_url = getConfig('WEB_API_HOST');
    // $this->token_url = "{$this->web_url}integration/admin/token";
    // $this->get_token();
  }

  private function get_token()
  {
    // $ch = curl_init($this->token_url);
    // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->userData));
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($this->userData))));
    //
    // $this->token = trim(curl_exec($ch), '""');
    //$this->token = 'xekjymeqd2i15ozg3kytfcsseb7s1uj9';

  }

  public function update_web_stock($item, $qty)
  {
    $token = $this->token;
    $url = $this->web_url."products/{$item}/stockItems/1";
    //$url = $this->web_url."mi/stockItems";
    $setHeaders = array("Content-Type:application/json","Authorization:Bearer {$token}");
    $apiUrl = str_replace(" ","%20",$url);
    $method = 'PUT';
    //$data = ["inventory_list" => ["SKU" => $item, "qty" => intval($qty)]];
    $data = ["stockItem" => ["qty" => $qty]];
    $data_string = json_encode($data);
    //echo $data_string;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $setHeaders);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }


  public function create_products($item, $qty)
  {
    $token = $this->token;
    $url = $this->web_url."products";
    $setHeaders = array("Content-Type:application/json","Authorization:Bearer {$token}");
    $apiUrl = str_replace(" ","%20",$url);
    $method = 'POST';
    $data = array(
      'product' => array(
        'sku' => $item->code,
        'name' => $item->name,
        'attribute_set_id' => $this->attribute_set_id,
        'price' => $item->price,
        'status' => 1,
        'visibility' => 1,
        'type_id' => 'simple',
        'extension_attributes' => array(
          // 'category_links' => array(
          //   array('position' => 0, 'category_id' => '41'),
          //   array('position' => 1, 'category_id' => '12'),
          //   array('position' => 2, 'category_id' => '13')
          // ),
          'stock_item' => array(
            'qty' => $qty,
            'is_in_stock' => true
          ),
        ),
        'custom_attributes' => array(
          array('attribute_code' => 'color', 'value' => $item->color_code),
          array('attribute_code' => 'size', 'value' => $item->size_code)
        )
      )
    );

    $data_string = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $setHeaders);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }


} //-- end class

 ?>
