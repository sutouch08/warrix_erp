var HOME = BASE_URL + 'inventory/prepare';


function goBack(){
    window.location.href = HOME;
}




//---- ไปหน้าจัดสินค้า
function goPrepare(code){
    window.location.href = HOME + '/process/'+code;
}


function goProcess(){
  window.location.href = HOME + '/view_process';
}


function pullBack(id){
  $.ajax({
    url:'controller/prepareController.php?pullOrderBack',
    type:'POST',
    cache:'false',
    data:{
      'id_order' : id
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Success',
          timer: 1000,
          type:'success'
        });

        setTimeout(function(){ window.location.reload(); }, 1500);
      }else{
        swal('Error', rs, 'error');
      }
    }
  });
}




//--- ไปหน้ารายการที่กำลังจัดสินค้าอยู่
function viewProcess(){
  window.location.href = HOME + '/view_process';
}
