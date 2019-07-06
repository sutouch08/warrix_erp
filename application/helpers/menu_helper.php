<?php

//--- check menu level 1 that open or not
function is_open($menu_group_code)
{
  $CI =& get_instance();
  return $menu_group_code === $CI->menu_group_code ? 'open' : '';
}



function active_menu($menu_code)
{
	$CI =& get_instance();
  return ($menu_code == $CI->menu_code) ? 'active' : '';
}


function side_menu($code, $url, $name)
{
  $menu = '';
  $menu .= '<li class="'.active_menu($code).'">';
  $menu .= '<a href="'.base_url().$url.'">';
  $menu .= '<span class="menu-text">'.$name.'</span>';
  $menu .= '</a>';
  $menu .= '</li>';

  return $menu;
}

 ?>
