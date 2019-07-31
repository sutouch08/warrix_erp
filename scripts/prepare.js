function goBack(){
    window.location.href = "index.php?content=prepare";
}





//---- ไปหน้าจัดสินค้า
function goPrepare(id){
    window.location.href = "index.php?content=prepare&process=Y&id_order="+id;
}


function goProcess(){
  window.location.href = "index.php?content=prepare&viewProcess=Y";
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
  window.location.href = "index.php?content=prepare&viewProcess=Y";
}
