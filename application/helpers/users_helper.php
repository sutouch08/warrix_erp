<?php
function _check_login()
{
  $CI =& get_instance();
  $uid = get_cookie('uid');
  if($uid === NULL OR $CI->user_model->verify_uid($uid) === FALSE)
  {
    redirect(base_url().'users/authentication');
  }
}


function get_permission($menu, $uid, $id_profile)
{
  $CI =& get_instance();
  return $CI->user_model->get_permission($menu, $uid, $id_profile);
}


function _can_view_page($can_view)
{
  if( ! $can_view)
  {
    $CI =& get_instance();
    $CI->load->view('deny_page');
    //redirect('deny_page');
  }
}


function profile_name_in($text)
{
  if($text !== '')
  {
    $CI =& get_instance();
    $CI->db->select('id');
  }
}



 ?>
