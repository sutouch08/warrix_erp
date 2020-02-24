<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sync_data extends CI_Controller
{
  public $title = 'Sync Data';
	public $menu_code = '';
	public $menu_group_code = '';
	public $pm;
  public $limit = 10;
  public $date;

  public function __construct()
  {
    parent::__construct();
    $this->ms = $this->load->database('ms', TRUE); //--- SAP database
    $this->mc = $this->load->database('mc', TRUE); //--- Temp Database
    $this->load->model('sync_data_model');
    $this->date = date('Y-d-m H:i:s');
  }


  public function index()
  {
    $this->load->view('sync_data_view');
  }



  public function syncWarehouse()
  {
    $this->load->model('masters/warehouse_model');
    $last_sync = $this->warehouse_model->get_last_sync_date();
    $newData = $this->warehouse_model->get_new_data($last_sync);

    if(!empty($newData))
    {
      foreach($newData as $rs)
      {
        if($this->warehouse_model->is_exists($rs->code))
        {
          $ds = array(
            'name' => $rs->name,
            'last_sync' => date('Y-m-d H:i:s'),
            'update_user' => 'SAP'
          );

          $this->warehouse_model->update($rs->code, $ds);
        }
        else
        {
          $ds = array(
            'code' => $rs->code,
            'name' => $rs->name,
            'last_sync' => date('Y-m-d H:i:s'),
            'update_user' => 'SAP'
          );

          $this->warehouse_model->add($ds);
        }
      }
    }

    echo 'done';
  }


  public function syncZone()
  {
    $this->load->model('masters/zone_model');
    $last_sync = $this->zone_model->get_last_sync_date();
    $newData = $this->zone_model->get_new_data($last_sync);

    if(!empty($newData))
    {
      foreach($newData as $rs)
      {
        if($this->zone_model->is_exists_id($rs->id))
        {
          $ds = array(
            'code' => $rs->code,
            'name' => is_null($rs->name) ? '' : $rs->name,
            'old_code' => $rs->old_code,
            'last_sync' => date('Y-m-d H:i:s'),
          );

          $this->zone_model->update($rs->id, $ds);
        }
        else
        {
          $ds = array(
            'id' => $rs->id,
            'code' => $rs->code,
            'name' => is_null($rs->name) ? '' : $rs->name,
            'warehouse_code' => $rs->warehouse_code,
            'last_sync' => date('Y-m-d H:i:s'),
            'old_code' => $rs->old_code
          );

          $this->zone_model->add($ds);
        }
      }
    }

    echo 'done';
  }


  public function syncCustomer()
  {
    $this->load->model('masters/customers_model');
    $last_sync = $this->customers_model->get_last_sync_date();
    $ds = $this->customers_model->get_update_data($last_sync);
    if(!empty($ds))
    {
      foreach($ds as $rs)
      {
        $arr = array(
          'code' => $rs->code,
          'name' => $rs->name,
          'Tax_Id' => $rs->Tax_Id,
          'DebPayAcct' => $rs->DebPayAcct,
          'CardType' => $rs->CardType,
          'GroupCode' => $rs->GroupCode,
          'cmpPrivate' => $rs->CmpPrivate,
          'GroupNum' => $rs->GroupNum,
          'sale_code' => $rs->sale_code,
          'CreditLine' => $rs->CreditLine,
          'old_code' => $rs->old_code,
          'last_sync' => now()
        );

        if($this->customers_model->is_exists($rs->code) === TRUE)
        {
          $this->customers_model->update($rs->code, $arr);
        }
        else
        {
          $this->customers_model->add($arr);
        }
      }
    }

    echo 'done';
  }


  public function syncReceivePoInvCode()
  {
    $this->load->model('inventory/receive_po_model');
    $ds = $this->receive_po_model->get_non_inv_code(100);
    if(!empty($ds))
    {
      foreach($ds as $rs)
      {
        $inv = $this->receive_po_model->get_sap_doc_num($rs->code);
        if(!empty($inv))
        {
          $this->receive_po_model->update_inv($rs->code, $inv);
        }
      }
    }

    echo 'done';
  }


  
} //--- end class

 ?>
