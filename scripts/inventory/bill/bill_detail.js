
var chk = setInterval(function () { checkState(); }, 10000);



function checkState(){
  var order_code = $("#order_code").val();
  $.ajax({
    url: HOME + 'get_state',
    type: 'GET',
    data: {
      'order_code' : order_code
    },
    success: function(rs){
      var rs = $.trim(rs);
      if( rs == 'state changed'){
        $("#btn-confirm-order").remove();
        clearInterval(chk);
      }
    }
  });
}



function confirmOrder(){
  var order_code = $("#order_code").val();
  load_in();
  $.ajax({
    url: HOME + 'confirm_order',
    type:'POST',
    cache:'false',
    data:{
      'order_code' : order_code
    },
    success:function(rs){
      load_out();
      var rs = $.trim(rs);
      if( rs == 'success'){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        },1200);

        // $.ajax({
        //   url: 'controller/interfaceController.php?export&order',
        //   type:'POST',
        //   cache: 'false',
        //   data: {'order_code' : order_code},
        //   success: function(rs){
        //     var rs = $.trim(rs);
        //     if(rs == 'success'){
        //       swal({title: 'Success', type:'success', timer:1000})
        //       setTimeout(function(){ window.location.reload(); }, 1200);
        //     }else{
        //       swal({title:'Warning !', text: 'บันทึกขายสำเร็จ แต่ส่งข้อมูลไป Formula ไม่สำเร็จ คุณต้องส่งข้อมูลไป Formula ด้วยตัวเอง', type: 'warning'});
        //       setTimeout(function(){ window.location.reload(); }, 60000);
        //     }
        //   }
        // });

      }else {
        swal('Error!', rs, 'error');
      }
    }
  });
}
