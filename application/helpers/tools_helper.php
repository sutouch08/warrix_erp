<?php
function is_selected($val, $select)
{
  return $val == $select ? 'selected' : '';
}



function is_checked($val1, $val2)
{
  return $val1 == $val2 ? 'checked' : '';
}



function is_active($val)
{
  return $val == 1 ? '<i class="fa fa-check green"></i>' : '<i class="fa fa-times red"></i>';
}

function get_filter($postName, $cookieName, $defaultValue = "")
{
  $CI =& get_instance();
  $sc = '';

  if($CI->input->post($postName) !== NULL)
  {
    $sc = $CI->input->post($postName);
    $CI->input->set_cookie(array('name' => $cookieName, 'value' => $sc, 'expire' => 3600 , 'path' => '/'));
  }
  else if($CI->input->cookie($cookieName))
  {
    $sc = $CI->input->cookie($cookieName);
  }
  else
  {
    $sc = $defaultValue;
  }

	return $sc;
}



function clear_filter($cookies)
{
  if(is_array($cookies))
  {
    foreach($cookies as $cookie)
    {
      delete_cookie($cookie);
    }
  }
  else
  {
    delete_cookie($cookies);
  }
}




function set_rows($value = 20)
{
  $value = $value > 300 ? 300 : $value;

  $arr = array(
    'name' => 'rows',
    'value' => $value,
    'expire' => 259200,
    'path' => '/'
  );
  $CI =& get_instance();
  return $CI->input->set_cookie($arr);
}



function get_rows()
{
  $CI =& get_instance();
  return $CI->input->cookie('rows') === NULL ? 20 : $CI->input->cookie('rows');
}


function number($val, $digit = 0)
{
  return number_format($val, $digit);
}




function getConfig($name)
{
  $CI =& get_instance();
  $rs = $CI->db->select('value')->where('name', $name)->get('config');
  if($rs->num_rows() == 1)
  {
    return $rs->row()->value;
  }

}




function set_error($message)
{
  $CI =& get_instance();
  $CI->session->set_flashdata('error', $message);
}


function set_message($message)
{
  $CI =& get_instance();
  $CI->session->set_flashdata('success', $message);
}



function pagination_config( $base_url, $total_rows = 0, $perpage = 20, $segment = 3)
{
    $rows = get_rows();
    $input_rows  = '<p class="pull-right pagination">';
    $input_rows .= 'ทั้งหมด '.$total_rows.' รายการ | แสดง';
    $input_rows .= '<input type="number" name="set_rows" id="set_rows" class="input-mini text-center margin-left-15 margin-right-15" value="'.$rows.'" />';
    $input_rows .= 'ต่อหน้า ';
    $input_rows .= '<buton class="btn btn-success btn-sm" type="submit">แสดง</button>';
    $input_rows .= '</p>';

		$config['full_tag_open'] 		= '<nav><ul class="pagination">';
		$config['full_tag_close'] 		= '</ul>'.$input_rows.'</nav><hr>';
		$config['first_link'] 				= 'First';
		$config['first_tag_open'] 		= '<li>';
		$config['first_tag_close'] 		= '</li>';
		$config['next_link'] 				= 'Next';
		$config['next_tag_open'] 		= '<li>';
		$config['next_tag_close'] 	= '</li>';
		$config['prev_link'] 			= 'prev';
		$config['prev_tag_open'] 	= '<li>';
		$config['prev_tag_close'] 	= '</li>';
		$config['last_link'] 				= 'Last';
		$config['last_tag_open'] 		= '<li>';
		$config['last_tag_close'] 		= '</li>';
		$config['cur_tag_open'] 		= '<li class="active"><a href="#">';
		$config['cur_tag_close'] 		= '</a></li>';
		$config['num_tag_open'] 		= '<li>';
		$config['num_tag_close'] 		= '</li>';
		$config['uri_segment'] 		= $segment;
		$config['per_page']			= $perpage;
		$config['total_rows']			= $total_rows != false ? $total_rows : 0 ;
		$config['base_url']				= $base_url;
		return $config;
}

 ?>
