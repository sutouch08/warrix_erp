<?php

function select_customer_group($code = '')
{
  $sc = '';
  $CI =& get_instance();
  $CI->load->model('masters/customer_group_model');
  $options = $CI->customer_group_model->get_data();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
    }
  }

  return $sc;

}


function select_customer_kind($code = '')
{
  $sc = '';
  $CI =& get_instance();
  $CI->load->model('masters/customer_kind_model');
  $options = $CI->customer_kind_model->get_data();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
    }
  }
  return $sc;
}



function select_customer_type($code = '')
{
  $sc = '';
  $CI =& get_instance();
  $CI->load->model('masters/customer_type_model');
  $options = $CI->customer_type_model->get_data();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
    }
  }
  return $sc;
}



function select_customer_class($code = '')
{
  $sc = '';
  $CI =& get_instance();
  $CI->load->model('masters/customer_class_model');
  $options = $CI->customer_class_model->get_data();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
    }
  }
  return $sc;
}



function select_customer_area($code = '')
{
  $sc = '';
  $CI =& get_instance();
  $CI->load->model('masters/customer_area_model');
  $options = $CI->customer_area_model->get_data();

  if(!empty($options))
  {
    foreach($options as $rs)
    {
      $sc .= '<option value="'.$rs->code.'" '.is_selected($code, $rs->code).'>'.$rs->name.'</option>';
    }
  }
  return $sc;
}




function customer_in($txt)
{
  $sc = "'0'";
  $CI =& get_instance();
  $CI->load->model('masters/customers_model');
  $rs = $CI->customers_model->search($txt);

  if(!empty($rs))
  {
    foreach($rs as $cs)
    {
      $sc .= ", '".$cs->code."'";
    }
  }

  return $sc;
}



 ?>
