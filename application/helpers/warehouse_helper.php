<?php
function select_warehouse_role($se = 0)
{
  $sc = '';
  $CI =& get_instance();
  $CI->load->model('masters/warehouse_model');
  $options = $CI->warehouse_model->get_all_role();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->id.'" '.is_selected($se, $rs->id).'>'.$rs->name.'</option>';
    }
  }

  return $sc;
}


function select_warehouse($se = 0)
{
  $sc = '';
  $CI =& get_instance();
  $CI->load->model('masters/warehouse_model');
  $options = $CI->warehouse_model->get_list();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($se, $rs->code).'>'.$rs->name.'</option>';
    }
  }

  return $sc;
}

 ?>
