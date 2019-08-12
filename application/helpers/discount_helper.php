<?php
function showDiscountByProductGroup($id_customer, $id_product_group)
{
	$sc = 0.00;
	$qs = dbQuery("SELECT discount FROM tbl_customer_discount WHERE id_customer = '".$id_customer."' AND id_product_group = '".$id_product_group."'");
	if( dbNumRows($qs) > 0 )
	{
		list( $sc ) = dbFetchArray($qs);
	}
	return $sc;
}


function getDiscountLabel($p_disc = 0, $a_disc = 0)
{
	return $p_disc > 0 ? $p_disc .' %' : $a_disc;
}


function getDiscountAmount($p_disc, $a_disc, $price)
{
	$disc = $p_disc > 0 ? ($p_disc * 0.01) * $price : $a_disc;
	return $disc;
}



//--- แสดงป้ายส่วนลด
function discountLabel($disc = 0, $disc2 = 0, $disc3 = 0)
{
	$label = '';
	$label = $disc == 0 ? 0 : getDiscLabel($disc);
	$label .= $disc2 == 0 ? '' : '+'.getDiscLabel($disc2);
	$label .= $disc3 == 0 ? '' : '+'.getDiscLabel($disc3);
	return $label;
}


function getDiscLabel($disc)
{
	$arr = explode('%', $disc);
	if( count($arr) > 1)
	{
		return trim($arr[0]).'%';
	}
	return $arr[0];
}


?>
