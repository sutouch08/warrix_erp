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


function select_years($se="0000")
{
	$sc 		= '';
	$length	= 5;
	$startYear = getConfig('START_YEAR');
	//$se 		= ($se == '' OR $se == '0000') ? $startYear : $se;
	$year = ($se - $length) < $startYear ? $startYear : $se - $length;
	$lastYear = date('Y') + $length;
	while( $year <= $lastYear )
	{
		$sc .= '<option value="'.$year.'" '.is_selected($year, $se).'>'.$year.'</option>';
		$year++;
	}
	return $sc;
}
 ?>
