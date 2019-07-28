<?php
function bankLogoUrl($code)
{
  $CI =& get_instance();
  $img  = $code.'.png';
  $path	= base_url().$CI->config->item('image_path').'banks/';
  $image_path = $path.$img;
  $noimg = $path.'noimg.png';
 	$file = $CI->config->item('image_file_path').'banks/'.$img;
 	if( ! file_exists($file) )
 	{
 		return $noimg;
 	}

 	return $image_path;
}

 ?>
