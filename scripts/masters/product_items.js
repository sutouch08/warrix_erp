function editItem(code){
  $('#bc-lbl-'+code).addClass('hide');
  $('#bc-'+code).removeClass('hide');
  $('#cost-lbl-'+code).addClass('hide');
  $('#cost-'+code).removeClass('hide');
  $('#price-lbl-'+code).addClass('hide');
  $('#price-'+code).removeClass('hide');
  $('#btn-edit-'+code).addClass('hide');
  $('#btn-update-'+code).removeClass('hide');

}



function updateItem(code)
{
  var barcode = $('#bc-'+code).val();
  var cost = $('#cost-'+code).val();
  var price = $('#price-'+code).val();
  if( $('.has-error').length ){
    swal({
      title:'Error!',
      text:'พบข้อผิดพลาด กรุณาแก้ไข',
      type:'error'
    });

    return false;
  }


  $.ajax({
    url: BASE_URL + 'masters/products/update_item',
    type:'POST',
    cache:false,
    data:{
      'code' : code,
      'barcode' : barcode,
      'cost' : cost,
      'price' : price
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs === 'success'){
        $('#bc-lbl-'+code).text(barcode);
        $('#bc-'+code).addClass('hide');
        $('#bc-lbl-'+code).removeClass('hide');
        $('#cost-lbl-'+code).text(cost);
        $('#cost-'+code).addClass('hide');
        $('#cost-lbl-'+code).removeClass('hide');
        $('#price-lbl-'+code).text(price);
        $('#price-'+code).addClass('hide');
        $('#price-lbl-'+code).removeClass('hide');
        $('#btn-update-'+code).addClass('hide');
        $('#btn-edit-'+code).removeClass('hide');
      }else{
        swal({
          title:'Error!',
          text:rs,
          type:'error'
        });
      }
    }
  });
}



$('.barcode').focusout(function(){
  let bc = $(this).val();
  if(bc.length > 0){
    let id = $(this).attr('id');
    let item = id.replace('bc-', '');
    checkBarcode(bc, item);
  }
});



function checkBarcode(barcode, item)
{
  var el = $('#bc-'+item);
  $.ajax({
    url: BASE_URL + 'masters/product_barcode/valid_barcode/' + barcode + '/' + item,
    type:'GET',
    cache:false,
    success:function(rs){
      if(rs === 'exists'){
      el.addClass('has-error');
      el.attr('data-original-title', 'บาร์โค้ดซ้ำ');
      el.tooltip();
      }else{
        el.removeClass('has-error');
        el.attr('data-original-title', '');
      }
    }
  })
}



function setImages()
{
	var style	= $("#style").val();
	load_in();
	$.ajax({
		url: BASE_URL + 'masters/products/get_image_items/'+style,
		type:"POST",
    cache:"false",
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( rs == 'noimage' ){
				swal('ไม่พบรูปภาพ หรือ รายการสินค้า');
			}else{
				$("#mappingBody").html(rs);
				$("#imageMappingTable").modal('show');
			}
		}
	});
}



function setBarcodeForm(){
  if($('.cost').length){
    $('#barcodeOption').modal('show');
  }

}


function startGenerate(){
  $('#barcodeOption').modal('hide');
  var style = $('#style').val();
  var barcodeType = $("input[name='barcodeType']:checked").val();
  load_in();
  $.ajax({
    url: BASE_URL + 'masters/products/generate_barcode',
    type:'POST',
    cache:false,
    data:{
      'style' : style,
      'barcodeType' : barcodeType
    },
    success:function(rs){
      load_out();
      if(rs === 'success'){
        swal({
          title:'Success',
          type:'success',
          timer:1000
        });

        setTimeout(function(){
          window.location.reload();
        }, 1200);

      }else{
        swal({
          title:'Error!',
          text:rs,
          type:'error'
        });
      }
    }
  })
}


//--- toggle can sell

$('.can-sell').click(function(){
  var el = $(this);
  var code = el.data("code");
  var url = BASE_URL + 'masters/products/toggle_can_sell/'+code;
  $.get(url, function(rs){
    if(rs == 1){
      el.html('<i class="fa fa-check green"></i>');

    }else if(rs == 0){
      el.html('<i class="fa fa-times red"></i>');

    }else{
      swal({
        title:'Error!',
        text: 'เปลี่ยนสถานะไม่สำเร็จ',
        type:'error'
      });
    }
  });
});


//--- toggle active
$('.act').click(function(){
  var el = $(this);
  var code = el.data("code");
  var url = BASE_URL + 'masters/products/toggle_active/'+code;
  $.get(url, function(rs){
    if(rs == 1){
      el.html('<i class="fa fa-check green"></i>');

    }else if(rs == 0){
      el.html('<i class="fa fa-times red"></i>');

    }else{
      swal({
        title:'Error!',
        text: 'เปลี่ยนสถานะไม่สำเร็จ',
        type:'error'
      });
    }
  });
});



//--- toggle active
$('.api').click(function(){
  var el = $(this);
  var code = el.data("code");
  var url = BASE_URL + 'masters/products/toggle_api/'+code;
  $.get(url, function(rs){
    if(rs == 1){
      el.html('<i class="fa fa-check green"></i>');

    }else if(rs == 0){
      el.html('<i class="fa fa-times red"></i>');

    }else{
      swal({
        title:'Error!',
        text: 'เปลี่ยนสถานะไม่สำเร็จ',
        type:'error'
      });
    }
  });
});



function deleteItem(item){
  swal({
    title:'Are sure ?',
    text:'ต้องการลบ ' + item + ' หรือไม่ ?',
    type:'warning',
    showCancelButton: true,
		confirmButtonColor: '#FA5858',
		confirmButtonText: 'ใช่, ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
  },function(){
    $.ajax({
      url: BASE_URL + 'masters/products/delete_item/' + item,
      type:'GET',
      cache:false,
      success:function(rs){
        if(rs === 'success'){
          swal({
            title:'Deleted',
            text:'ลบรายการสินค้าเรียบร้อยแล้ว',
            type:'success',
            timer:1000
          });

          $('#row-'+item).remove();
        }else{
          swal({
            title:'Error!',
            text:rs,
            type:'error'
          });
        }
      }
    })
  })
}
