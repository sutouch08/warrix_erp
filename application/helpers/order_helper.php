<?php
function paymentLabel($order_code, $isExists, $isPaid)
{
	$sc = "";
	if( $isExists === TRUE )
	{
    if( $isPaid == 1 )
		{
			$sc .= '<button type="button" class="btn btn-sm btn-success" onClick="viewPaymentDetail()">';
			$sc .= 'จ่ายเงินแล้ว | ดูรายละเอียด';
			$sc .= '</button>';
		}
		else
		{
			$sc .= '<button type="button" class="btn btn-sm btn-primary" onClick="viewPaymentDetail()">';
			$sc .= 'แจ้งชำระแล้ว | ดูรายละเอียด';
			$sc .= '</button>';
		}
	}

	return $sc;
}



function paymentExists($order_code)
{
  $CI =& get_instance();
  $CI->load->model('orders/order_payment_model');
  return $CI->order_payment_model->is_exists($order_code);
}


function payment_image_url($order_code)
{
  $CI =& get_instance();
	$link	= base_url().'images/payments/'.$order_code.'.jpg';
  $file = $CI->config->item('image_file_path').'payments/'.$order_code.'.jpg';
	if( ! file_exists($file) )
	{
		$link = FALSE;
	}
  
	return $link;
}

 ?>
