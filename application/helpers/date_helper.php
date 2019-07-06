<?php
function thai_date($date, $sp = '-', $time = FALSE)
{
  $sp = $sp === '' ? '-' : $sp;
  $diff = getConfig('DATE_FORMAT') === 'BE' ? 543 : 0;
  $Year = date('Y', strtotime($date)) + $diff;
  $format = $time === TRUE ? 'd'.$sp.'m'.$sp.$Year.' H:i:s' : 'd'.$sp.'m'.$sp.$Year;

  return date($format, strtotime($date));
}


function db_date($date, $sp = '-', $time = FALSE)
{
  $sp = $sp === '' ? '-' : $sp;
  $diff = getConfig('DATE_FORMAT') === 'BE' ? 543 : 0;
  $Year = date('Y', strtotime($date)) - $diff;
  return $time === TRUE ? date($Year.$sp.'m'.$sp.'d H:i:s', strtotime($date)) : date($Year.$sp.'m'.$sp.'d', strtotime($date));
}

 ?>
