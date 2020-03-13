var validPwd = true;
function changePassword(){
  var id = $('#user_id').val();
  var pwd = $('#pwd').val();
  var cmp = $('#pwd').val();
  if(pwd.length == 0 || cmp.length == 0){
    validPWD();
  }

  if(! validPwd){
    return false;
  }

  $('#resetForm').submit();
}



function validPWD(){
  var pwd = $('#pwd').val();
  var cmp = $('#cm-pwd').val();
  if(pwd.length > 0 && cmp.length > 0){
    if(pwd != cmp){
      $('#cm-pwd-error').text('Password missmatch!');
      $('#pwd').addClass('has-error');
      $('#cm-pwd').addClass('has-error');
      validPwd = false;
    }else{
      $('#cm-pwd-error').text('');
      $('#pwd').removeClass('has-error');
      $('#cm-pwd').removeClass('has-error');
      validPwd = true;
    }
  }else{
    $('#cm-pwd-error').text('Password is required!');
    $('#pwd').addClass('has-error');
    $('#cm-pwd').addClass('has-error');
    validPwd = false;
  }
}


$('#pwd').focusout(function(){
  validPWD();
})



$('#pwd').keyup(function(e){
  validPWD();
});



$('#cm-pwd').keyup(function(e){
  validPWD(e);
})
