//---	ลบรายการที่โอนออกแล้ว
function deleteMoveItem(id_transfer_detail, product_reference){
	swal({
		title: 'คุณแน่ใจ ?',
		text: 'ต้องการลบ '+ product_reference +' หรือไม่ ?',
		type: 'warning',
		showCancelButton: true,
		comfirmButtonColor: '#DD6855',
		confirmButtonText: 'ใช่ ฉันต้องการลบ',
		cancelButtonText: 'ยกเลิก',
		closeOnConfirm: false
	}, function(){
		$.ajax({
			url:"controller/transferController.php?deletetransferDetail",
			type:"POST",
			cache:"false",
			data:{
				"id_transfer_detail" : id_transfer_detail,
				"id_transfer" : $('#id_transfer').val()
			},
			success: function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){
					swal({
						title:'Deleted',
						text: 'ลบรายการเรียบร้อยแล้ว',
						type: 'success',
						timer: 1000
					});

					$("#row-"+id_transfer_detail).remove();

				}else{

					swal("ข้อผิดพลาด", rs, "error");
				}
			}
		});
	});
}



//------------  ตาราง transfer_detail
function getTransferTable(){
	var id_transfer	= $("#id_transfer").val();
	var canAdd		= $("#canAdd").val();
	var canEdit		= $("#canEdit").val();
	$.ajax({
		url:"controller/transferController.php?getTransferTable",
		type:"GET",
    cache:"false",
    data:{
      "id_transfer" : id_transfer,
      "canAdd" : canAdd,
      "canEdit" : canEdit
    },
		success: function(rs){
			if( isJson(rs) ){
				var source 	= $("#transferTableTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#transfer-list");
				render(source, data, output);
			}
		}
	});
}




function getTempTable(){
	var id_transfer = $("#id_transfer").val();
	$.ajax({
		url:"controller/transferController.php?getTempTable",
		type:"GET",
    cache:"false",
    data:{ "id_transfer" : id_transfer },
		success: function(rs){
			if( isJson(rs) ){
				var source 	= $("#tempTableTemplate").html();
				var data		= $.parseJSON(rs);
				var output	= $("#temp-list");
				render(source, data, output);
			}
		}
	});
}








//--- เพิ่มรายการลงใน transfer detail
//---	เพิ่มลงใน transfer_temp
//---	update stock ตามรายการที่ใส่ตัวเลข
function addToTransfer(){
	var id_transfer	= $("#id_transfer").val();

	//---	โซนต้นทาง
	var id_zone		  = $("#from-zone-id").val();

	//---	โซนนี้อนุญาติให้ติดลบหรือไม่
	var underZero   = $('#underZero').val();

	//---	จำนวนช่องที่มีการป้อนตัวเลขเพื่อย้ายสินค้าออก
	var count       = countInput();

	//---	ตัวแปรสำหรับเก็บ ojbect ข้อมูล
	var ds          = [];

	ds.push(
		{"name" : 'id_transfer', "value" : id_transfer},
		{"name" : 'id_zone', "value" : id_zone},
		{"name" : "underZero", "value" : underZero}
	);


	$('.input-qty').each(function(index, element) {
	    var qty = $(this).val();
			var arr = $(this).attr('id').split('_');
			var id  = arr[1];
			var pd  = $("#id_product_"+id);
			var udz = $("#allowUnderZero_"+id);

			if( qty != '' && qty != 0 ){
				ds.push(
					{ "name" : $(this).attr('name'), "value" : qty },
					{ "name" : pd.attr('name'), "value" : pd.val() },
					{ "name" : udz.attr('name'), "value" : (udz.is(':checked') == true ? 1 : 0) }
				);
			}

    });


	if( count > 0 ){
		load_in();
		setTimeout(function(){
			$.ajax({
				url:"controller/transferController.php?addToTransfer",
				type:"POST",
				cache:"false",
				data: ds ,
				success: function(rs){
					load_out();
					var rs = $.trim(rs);
					if( rs == 'success' ){
						swal({
							title: 'success',
							text: 'เพิ่มรายการเรียบร้อยแล้ว',
							type: 'success',
							timer: 1000
						});

						setTimeout( function(){
							showTransferTable();
						}, 1200);

					}else{

						swal("ข้อผิดพลาด", rs, "error");
					}
				}
			});
		}, 500);


	}else{

		swal('ข้อผิดพลาด !', 'กรุณาระบุจำนวนในรายการที่ต้องการย้าย อย่างน้อย 1 รายการ', 'warning');

	}
}





function addAllToTransfer(){
	$('.qty-label').each(function(index, el) {
		var qty = parseInt( removeCommas($(this).text()) );
		var arr = $(this).attr('id');
		var arr = arr.split('-');
		var id = arr[2];
		$('#moveQty_'+id).val(qty);
	});
}

/*
//---	เพิ่มรายการลงใน transfer detail
//---	เพิ่มลงใน transfer_temp
//---	update stock รายการทั้งหมด
function addAllToTransfer(){
	var id_transfer 	 = $("#id_transfer").val();
	var id_zone		     = $("#from-zone-id").val();
	var allowUnderZero = ( $("#allowUnderZero").is(':checked') == true ? 1 : 0 );
	var count		       = countUnderZero();

	if( count > 0 && allowUnderZero == 0 ){
		swal("ข้อผิดพลาด !", "พบรายการที่ติดลบ ไม่สามารถดำเนินการต่อได้", "warning");
		return false;
	}

	if( count == 0 || allowUnderZero == 1 ){
		$.ajax({
			url:"controller/transferController.php?addAllToTransfer",
			type:"GET",
      cache:"false",
      data:{
        "id_transfer" : id_transfer,
        "id_zone" : id_zone,
        "allowUnderZero" : allowUnderZero
       },
			success:function(rs){
				var rs = $.trim(rs);
				if( rs == 'success' ){

					swal({
            title: 'success',
            text: 'เพิ่มรายการเรียบร้อยแล้ว',
            type: 'success',
            timer: 1000
          });

					setTimeout( function(){
            showTransferTable();
          }, 1200);

				}else{

					swal("ข้อผิดพลาด", rs, "error");

				}
			}
		});
	}
}
*/




//---  ย้ายสินค้าจาก temp เข้าโซนปลายทางทีเดียวทั้งหมด
function move_in_all(){
	//---	ไอดีโซนปลายทาง
	var to_zone	= $("#to-zone-id").val();

	//---	ไอดีเอกสาร
	var id_transfer = $("#id_transfer").val();

	//---	 ตรวจสอบว่า กำหนดโซนปลายทางแล้วหรือยัง
	if( to_zone.length == 0 ){
		swal("กรุณาระบุโซนปลายทาง");
		return false;
	}

	//---	ตรวจสอบว่าโซนต้นทางกับโซนปลายทางเป็นโซนเดียวกันหรือไม่
	//---	ต้องไม่ใช่โซนเดียวกัน
	var sameZone = countSameZone();

	//---	ถ้าโซนต้นทาง กับ ปลายทางเหมือนกัน (ที่ถูกต้อง ต้องไม่เหมือนกัน)
	if( sameZone > 0 ){
		swal("ข้อผิดพลาด !", "พบรายการที่โซนต้นทางตรงกับโซนปลายทาง "+sameZone+" รายการ", "warning");
		return false;
	}

	load_in();

	$.ajax({
		url:"controller/transferController.php?moveAllToZone",
		type:"GET",
		cache:"false",
		data:{
				"id_transfer" : id_transfer,
				"to_zone" : to_zone
		},
		success: function(rs){
			load_out();

			var rs = $.trim(rs);

			if( rs == 'success' ){

				//---	update transfer table
				getTransferTable();

				swal({
					title: 'Success',
					text: 'ย้ายสินค้าเข้าเรียบร้อยแล้ว',
					type: 'success',
					timer: 1000
				});

			}else{

				swal("ข้อผิดพลาด !", rs, "error");
			}
		}
	});
}






//---  ย้ายสินค้าจาก temp เข้าโซนปลายทางทีละรายการ
function move_in(id_transfer_detail, from_zone_id){
	var to_zone_id	= $("#to-zone-id").val();
	var id_transfer = $("#id_transfer").val();

	//--- ตรวจสอบโซนปลายทาง มีการกำหนดไว้แล้วหรือยัง
	if( to_zone_id.length == 0 ){
		swal("ข้อผิดพลาด", "กรุณาระบุโซนปลายทาง", "warning");
		return false;
	}

	//----- ตรวจสอบโซนปลายทาง ต้องไม่ตรง กับโซนต้นทาง
	if( from_zone_id == to_zone_id ){
		swal("ข้อผิดพลาด", "โซนปลายทางต้องไม่ใช่โซนเดียวกันกับโซนต้นทาง", "warning");
		return false;
	}


	$.ajax({
		url:"controller/transferController.php?moveToZone",
		type:"GET",
		cache:"false",
		data:{
				"id_transfer_detail" : id_transfer_detail,
				"id_transfer" : id_transfer,
				"from_zone" : from_zone_id,
				"to_zone" : to_zone_id
		},
		success: function(rs){

			var rs = $.trim(rs);

			if( rs == 'success' ){

				$("#row-label-"+id_transfer_detail).text($('#to-zone').val());

				updateMoved(id_transfer_detail);

			}else{

				swal("ข้อผิดพลาด !", rs, "error");
			}
		}
	});
}




function updateMoved(id){
	$.ajax({
		url:'controller/transferController.php?getMovedQty',
		type:'POST',
		cache:'false',
		data:{
			'id_transfer_detail' : id
		},
		success:function(rs){
			var rs = $.trim(rs);
			$('#qty-'+id).text(rs);
		}
	});
}





//----- นับจำนวน ช่องที่มีการใส่ตัวเลข
function countInput(){
	var count = 0;
	$(".input-qty").each(function(index, element) {
        count += ($(this).val() == "" ? 0 : 1 );
    });
	return count;
}



//------ นับจำนวนรายการที่ยอดติดลบ
function countUnderZero(){
	var count = 0;
	$(".qty-label").each(function(index, element) {
        count += (parseInt($(this).text()) < 0 ? 1 : 0);
    });
	return count;
}



//---	นับจำนวนโซนที่เหมือนกัน
function countSameZone(){
	var count = 0;
	var to	  = $("#to-zone-id").val();
	$(".row-zone-from").each(function(index, element) {
        count += ($(this).val() == to ? 1 : 0);
    });
	return count;

}
