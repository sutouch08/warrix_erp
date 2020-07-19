<?php
class Sales_report extends CI_Controller
{
  private $host = "https://report-uat.konsys.co";
  private $endpoint = "/api/v1/sales-report";
  private $url = "";
  public $app_id = "UkxsaUx4bzh0WXE4Qzg3MTNlMjR3MldIcHd1NUJhVm4=";
  public $app_secret = "bWsxaHVHNVF3Q0hOWFJhSw==";
  public $home;

  public function __construct()
  {
    parent::__construct();
    $this->url = $this->host.$this->endpoint;
    $this->home = base_url().'api/sales_report';
  }

  public function index()
  {
    $this->load->view('auto/sales_report_api');
  }


  public function do_export()
  {
    $this->load->model('api/sale_report_model');

    $limit = 100; //--- limit rows
    $role = 'S'; //--- only sale order
    $role_name = array(
      'S' => 'ขาย',
      'C' => 'ฝากขาย(SO)',
      'N' => 'ฝากขาย(TR)',
      'P' => 'สปอนเซอร์',
      'M' => 'ตัดยอดฝากขาย',
      'U' => 'อภินันท์'
    );

    $ds = array();
    $result = array();

    $data = $this->sale_report_model->get_orders($role, $limit);

    if(!empty($data))
    {
      foreach($data as $rs)
      {
        if($rs->status == 2 OR $rs->state == 9)
        {
          $arr = array(
            'reference' => $rs->code
          );

          array_push($ds, $arr);
        }
        else
        {
          $orders = $this->sale_report_model->get_sold_data($rs->code);

          if(!empty($orders))
          {
            foreach($orders as $order)
            {
              $arr = array(
                'reference' => $order->reference,
                'role_name' => $role_name[$order->role],
                'payment' => $order->payment,
                'channels' => $order->channels,
                'product_code' => $order->product_code,
                'product_name' => $order->product_name,
                'color' => $order->color,
                'size' => $order->size,
                'product_style' => $order->product_style,
                'product_group' => $order->product_group,
                'product_category' => $order->product_category,
                'product_kind' => $order->product_kind,
                'product_type' => $order->product_type,
                'brand' => $order->brand,
                'year' => $order->year,
                'price_ex' => remove_vat($order->price),
                'price_inc' => floatval($order->price),
                'sell_ex' => remove_vat($order->sell),
                'sell_inc' => floatval($order->sell),
                'qty' => floatval($order->qty),
                'discount_amount' => floatval($order->discount_amount),
                'total_amount_ex' => remove_vat($order->total_amount),
                'total_cost_ex' => remove_vat($order->total_cost),
                'margin_ex' => remove_vat($order->margin),
                'customer_name' => $order->customer_name,
                'customer_group' => $order->customer_group,
                'customer_kind' => $order->customer_kind,
                'customer_class' => $order->customer_class,
                'customer_area' => $order->customer_area,
                'sale_code' => $order->sale_code,
                'sale_name' => $order->sale_name,
                'employee_nam' => $order->employee_name,
                'date_add' => date('d/m/Y H:i:s', strtotime($order->date_add)),
                'date_upd' => date('d/m/Y H:i:s'),
                'id_zone' => $order->zone_code,
                'id_warehouse' => $order->warehouse_code
              );
              array_push($ds, $arr);
            } //--- end foreach
          } //--- end if
        } //-- end if

        $this->sale_report_model->set_report_status($rs->code, 1);

      } //--- end foreach

      $setHeaders = array(
        "Content-Type:application/json",
        "applicationID:{$this->app_id}",
        "applicationSecret:{$this->app_secret}"
      );

      $apiUrl = str_replace(" ","%20",$this->url);
      $method = 'POST';
      $data_set = array('data'=> $ds);
      $data_string = json_encode($data_set);

      //echo $data_string;

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $apiUrl);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $setHeaders);
      $response = curl_exec($ch);

      curl_close($ch);


      $res = json_decode($response);

      if($res->status->code != '0000')
      {
        foreach($data as $rs)
        {
          $this->sale_report_model->set_report_status($rs->code, 3, $res->data->error);
        }
      }

      echo $response;
    }
    else
    {
      echo "no data to send";
    }

  }



  public function export_error()
  {
    $this->load->model('api/sale_report_model');

    $limit = 3; //--- limit rows
    $role = 'S'; //--- only sale order
    $role_name = array(
      'S' => 'ขาย',
      'C' => 'ฝากขาย(SO)',
      'N' => 'ฝากขาย(TR)',
      'P' => 'สปอนเซอร์',
      'M' => 'ตัดยอดฝากขาย',
      'U' => 'อภินันท์'
    );

    $ds = array();
    $result = array();

    $data = $this->sale_report_model->get_error_orders($role, $limit);

    if(!empty($data))
    {
      foreach($data as $rs)
      {
        if($rs->status == 2 OR $rs->state == 9)
        {
          $arr = array(
            'reference' => $rs->code
          );

          array_push($ds, $arr);
        }
        else
        {
          $orders = $this->sale_report_model->get_sold_data($rs->code);

          if(!empty($orders))
          {
            foreach($orders as $order)
            {
              $arr = array(
                'reference' => $order->reference,
                'role_name' => $role_name[$order->role],
                'payment' => $order->payment,
                'channels' => $order->channels,
                'product_code' => $order->product_code,
                'product_name' => $order->product_name,
                'color' => $order->color,
                'size' => $order->size,
                'product_style' => $order->product_style,
                'product_group' => $order->product_group,
                'product_category' => $order->product_category,
                'product_kind' => $order->product_kind,
                'product_type' => $order->product_type,
                'brand' => $order->brand,
                'year' => $order->year,
                'price_ex' => remove_vat($order->price),
                'price_inc' => floatval($order->price),
                'sell_ex' => remove_vat($order->sell),
                'sell_inc' => floatval($order->sell),
                'qty' => floatval($order->qty),
                'discount_amount' => floatval($order->discount_amount),
                'total_amount_ex' => remove_vat($order->total_amount),
                'total_cost_ex' => remove_vat($order->total_cost),
                'margin_ex' => remove_vat($order->margin),
                'customer_name' => $order->customer_name,
                'customer_group' => $order->customer_group,
                'customer_kind' => $order->customer_kind,
                'customer_class' => $order->customer_class,
                'customer_area' => $order->customer_area,
                'sale_code' => $order->sale_code,
                'sale_name' => $order->sale_name,
                'employee_nam' => $order->employee_name,
                'date_add' => date('d-m-Y H:i:s', strtotime($order->date_add)),
                'date_upd' => date('d-m-Y H:i:s'),
                'id_zone' => $order->zone_code,
                'id_warehouse' => $order->warehouse_code
              );
              array_push($ds, $arr);
            } //--- end foreach
          } //--- end if
        } //-- end if

        $this->sale_report_model->set_report_status($rs->code, 1);

      } //--- end foreach

      $setHeaders = array(
        "Content-Type:application/json",
        "applicationID:{$this->app_id}",
        "applicationSecret:{$this->app_secret}"
      );

      $apiUrl = str_replace(" ","%20",$this->url);
      $method = 'POST';
      $data_set = array('data'=> $ds);
      $data_string = json_encode($data_set);

      //echo $data_string;

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $apiUrl);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $setHeaders);
      $response = curl_exec($ch);

      curl_close($ch);


      $res = json_decode($response);

      if(empty($res) OR $res->status->code != '0000')
      {
        foreach($data as $rs)
        {
          if(empty($res->data->error))
          {
            $error_message = $response;
          }
          else
          {
            $error_message = $res->data->error;
          }

          $this->sale_report_model->set_report_status($rs->code, 3, $error_message);
        }
      }

      echo $response;
    }
    else
    {
      echo "no data to send";
    }

  }




  public function test()
  {
    $res = '{
      "status": {"code": "0000","namespace": "SAP"},
      "data": {"success": true}
      }';
    $rs = json_decode($res);
    if($rs->status->code != "0000")
    {
      echo "Error";
    }
    else
    {
      echo "OK";
    }

  }


}
?>
