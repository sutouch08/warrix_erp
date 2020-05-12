<?php
class Inventory_report_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_current_stock_balance($allProduct, $pdFrom, $pdTo, $allWhouse, $warehouse)
  {
    $this->ms
    ->select('OITM.ItemCode AS product_code')
    ->select_sum('OIBQ.OnHandQty', 'qty')
    ->from('OBIN')
    ->join('OIBQ', 'OBIN.WhsCode = OIBQ.WhsCode AND OBIN.AbsEntry = OIBQ.BinAbs', 'left')
    ->join('OITM', 'OIBQ.ItemCode = OITM.ItemCode', 'left')
    ->join('OWHS', 'OWHS.WhsCode = OBIN.WhsCode', 'left');

    $this->ms->where('OIBQ.OnHandQty !=', 0, FALSE);

    if($allProduct == 0)
    {
      $this->ms->where('OITM.ItemCode >=', $pdFrom)->where('OITM.ItemCode <=', $pdTo);
    }

    if($allWhouse == 0)
    {
      if(!empty($warehouse))
      {
        $this->ms->where_in('OWHS.WhsCode', $warehouse);
      }
    }

    $this->ms->group_by('OITM.ItemCode');
    $rs = $this->ms->get();
    if($rs->num_rows() > 0)
    {
      return $rs->result();
    }

    return FALSE;

  }



}
 ?>
