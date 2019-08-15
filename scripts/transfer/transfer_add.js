
//--- เพิ่มเอกสารโอนคลังใหม่
function addNew(){

  //--- วันที่เอกสาร
  var date_add = $('#date_add').val();

  //--- คลังต้นทาง
  var from_warehouse = $('#from-warehouse').val();

  //--- คลังปลายทาง
  var to_warehouse = $('#to-warehouse').val();

  //--- หมายเหตุ
  var remark = $('#remark').val();

  //--- ตรวจสอบวันที่
  if( ! isDate(date_add))
  {
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  //--- ตรวจสอบคลังต้นทาง
  if(from_warehouse == ''){
    swal('กรุณาเลือกคลังต้นทาง');
    return false;
  }

  //--- ตรวจสอบคลังปลายทาง
  if(to_warehouse == ''){
    swal('กรุณาเลือกคลังปลายทาง');
    return false;
  }

  //--- ตรวจสอบว่าเป็นคนละคลังกันหรือไม่ (ต้องเป็นคนละคลังกัน)
  if( from_warehouse == to_warehouse){
    swal('คลังต้นทางต้องไม่ตรงกับคลังปลายทาง');
    return false;
  }

  //--- ถ้าไม่มีข้อผิดพลาด
  $.ajax({
    url:'controller/transferController.php?addNew',
    type:'POST',
    cache:'false',
    data:{
      'date_add' : date_add,
      'from_warehouse' : from_warehouse,
      'to_warehouse' : to_warehouse,
      'remark' : remark
    },
    success:function(rs){
      //--- ถ้าสำเร็จจะได้เป็น ไอดีกลับมา
      var rs = $.trim(rs);

      //--- ตรวจสอบว่าผลลัพธ์เป็นตัวเลขหรือไม่(ไอดี)
      if( ! isNaN( parseInt(rs))){
        //--- พาไปหน้าเพิ่มรายการ
        goAdd(rs);

      }else{
        //--- แจ้ง error
        swal('Error !', rs, 'error');
      }
    }
  });
}




//--- update เอกสาร
function update(){
  //--- ไอดีเอกสาร สำหรับส่งไปอ้างอิงการแก้ไข
  var id_transfer = $('#id_transfer').val();

  //--- คลังต้นทาง
  var from_warehouse = $('#from-warehouse').val();

  //--- คลังปลายทาง
  var to_warehouse = $('#to-warehouse').val();

  //--  วันที่เอกสาร
  var date_add = $('#date_add').val();

  //--- หมายเหตุ
  var remark = $('#remark').val();

  //--- ตรวจสอบไอดี
  if(id_transfer == ''){
    swal('Error !', 'ไม่พบ ID เอกสาร', 'error');
    return false;
  }

  //--- ตรวจสอบวันที่
  if( ! isDate(date_add)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

  //--- ตรวจสอบคลังต้นทาง
  if(from_warehouse == ''){
    swal('กรุณาเลือกคลังต้นทาง');
    return false;
  }

  //--- ตรวจสอบคลังปลายทาง
  if(to_warehouse == ''){
    swal('กรุณาเลือกคลังปลายทาง');
    return false;
  }

  //--- ตรวจสอบว่าเป็นคนละคลังกันหรือไม่ (ต้องเป็นคนละคลังกัน)
  if( from_warehouse == to_warehouse){
    swal('คลังต้นทางต้องไม่ตรงกับคลังปลายทาง');
    return false;
  }


  //--- ถ้าไม่มีอะไรผิดพลาด ส่งข้อมูไป update
  $.ajax({
    url:'controller/transferController.php?update',
    type:'POST',
    cache:'false',
    data:{
      'id_transfer' : id_transfer,
      'date_add'    : date_add,
      'from_warehouse' : from_warehouse,
      'to_warehouse' : to_warehouse,
      'remark'      : remark
    },
    success:function(rs){
      var rs = $.trim(rs)
      if( rs == 'success'){
        $('#from-warehouse-id').val(from_warehouse);
        $('#to-warehouse-id').val(to_warehouse);
        $('#date_add').attr('disabled', 'disabled');
        $('#from-warehouse').attr('disabled', 'disabled');
        $('#to-warehouse').attr('disabled', 'disabled');
        $('#remark').attr('disabled', 'disabled');
        $('#btn-update').addClass('hide');
        $('#btn-edit').removeClass('hide');

        //--- inititailize search zone with new warehouse id
        from_zone_init();

        //--- inititailize search zone with new warehouse id
        to_zone_init();

        swal({
          title:'Success',
          type:'success',
          timer: 1000
        });

      }else{

        swal('Error', rs, 'error');
      }
    }
  });
}



//--- แก้ไขหัวเอกสาร
function getEdit(){
  var id = $('#id_transfer').val();
  var isExport = $('#isExport').val();
  if( isExport == 0){
    $.ajax({
      url:'controller/transferController.php?isHasDetail',
      type:'GET',
      cache:'false',
      data: {
        'id_transfer' : id
      },
      success:function(rs){
        var rs = $.trim(rs);
        if(rs == 'no_detail'){
          $('#from-warehouse').removeAttr('disabled');
          $('#to-warehouse').removeAttr('disabled');
        }
      }
    });

    $('#date_add').removeAttr('disabled');
    $('#remark').removeAttr('disabled');
    $('#btn-edit').addClass('hide');
    $('#btn-update').removeClass('hide');

  }else{

    swal('','เอกสารถูกส่งออกแล้ว ไม่อนุญาติให้แก้ไข','warning');
  }
}



//---  บันทึกเอกสาร
function save(){
  var id = $('#id_transfer').val();
  $.ajax({
    url:'controller/transferController.php?saveTransfer',
    type:'POST',
    cache:'false',
    data:{'id_transfer' : id},
    success:function(rs){
      var rs = $.trim(rs);
      if( rs == 'success'){
        //--- ส่งข้อมูลไป formula
        doExport(1);

      }else{
        swal('Error!', rs, 'error');
      }
    }
  });
}


function doExport(){
  //--- option 0 = สั่งจากปุ่มส่งออก  1 = สั่งจากการบันทึก
  var id = $('#id_transfer').val();
  $.ajax({
    url:'controller/interfaceController.php?export&TR',
    type:'POST',
    cache:'false',
    data:{
      'id_transfer' : id
    },
    success:function(rs){
      var rs = $.trim(rs);
      if( rs == 'success'){
        swal({
          title:'success',
          type:'success',
          timer: 1000
        });

        setTimeout(function(){
          goDetail(id);
        }, 1200);

      }else{

        swal({
          title:'Error!',
          text: rs,
          type: 'error'
        }, function(){
          window.location.reload();
        });
      }
    }
  });
}



$('#date_add').datepicker({
  dateFormat:'dd-mm-yy'
});
