<?php
function thai_date($date, $sp = '-', $time = FALSE)
{
  $sp = $sp === '' ? '-' : $sp;
  $format = $time === TRUE ? 'd'.$sp.'m'.$sp.'Y'.' H:i:s' : 'd'.$sp.'m'.$sp.'Y';

  return date($format, strtotime($date));
}


function db_date($date, $sp = '-', $time = FALSE)
{
  return $time === TRUE ? date('Y-m-d H:i:s', strtotime($date)) : date('Y-m-d', strtotime($date));
}



function from_date($date = '')
{
  if($date === '')
  {
    return date('Y-m-d 00:00:00');
  }
  else
  {
    return date('Y-m-d 00:00:00', strtotime($date));
  }
}



function to_date($date = '')
{
  if($date === '')
  {
    return date('Y-m-d 23:59:59');
  }
  else
  {
    return date('Y-m-d 23:59:59', strtotime($date));
  }
}

 ?>
