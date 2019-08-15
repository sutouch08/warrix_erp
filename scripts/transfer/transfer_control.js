

//-------  ดึงรายการสินค้าในโซน
function getProductInZone(){
	var id_zone   = $("#from-zone-id").val();
  var underZero = $('#underZero').val();
	if( id_zone.length > 0 ){
		$.ajax({
			url:"controller/transferController.php?getProductInZone",
			type:"GET",
      cache:"false",
      data:{
        'id_zone' : id_zone,
        'isAllowUnderZero' : underZero
      },
			success: function(rs){
				var rs = 	$.trim(rs);
				if( isJson(rs) ){
					var source = $("#zoneTemplate").html();
					var data		= $.parseJSON(rs);
					var output	= $("#zone-list");
					render(source, data, output);
					$("#transfer-table").addClass('hide');
					$("#zone-table").removeClass('hide');
				}
			}
		});
	}
}


$(document).ready(function() {
	from_zone_init();
	to_zone_init();
});

function from_zone_init(){
	var id = $('#from-warehouse-id').val();
	$("#from-zone").autocomplete({
		source: "controller/transferController.php?getTransferZone&id_warehouse="+ id,
		autoFocus: true,
		close: function(){
			var rs = $(this).val();
			var rs = rs.split(' | ');
			if( rs.length == 3 ){

				$("#from-zone-id").val(rs[1]);

	      //--- อนุญาติให้ติดลบได้มั้ย  0 = ไม่ได้  / 1 = ได้
	      $('#underZero').val(rs[2]);

	      //--- ถ้าไม่อนุญาติให้ติดลย
	      if( rs[2] == 0){
	        //--- ซ่อน checkbox อนุญาติให้ติดลยได้ทั้งหมด
	        $('#underZeroLabel').addClass('hide');

	      }else{
	        //--- ยกเลิกการซ่อน
	        $('#underZeroLabel').removeClass('hide');

	      }

	      //--- แสดงชื่อโซนใน text box
				$(this).val(rs[0]);

				//---	แสดงชื่อโซนที่ หัวตาราง
				$('#zoneName').text(rs[0]);


			}else{

				$("#from-zone-id").val('');

	      $('#underZero').val('0');

				//---	ชื่อโซนที่ หัวตาราง
				$('#zoneName').text('');

				$(this).val('');
			}
		}
	});
}



$("#from-zone").keyup(function(e) {
    if( e.keyCode == 13 ){
		setTimeout(function(){
			getProductInZone();
		}, 100);
	}
});



function to_zone_init(){
	var id = $('#to-warehouse-id').val();
	$("#to-zone").autocomplete({
		source: "controller/transferController.php?getTransferZone&id_warehouse="+ id,
		autoFocus: true,
		close: function(){
			var rs = $(this).val();
			var rs = rs.split(' | ');
			if( rs.length == 3 ){
				$("#to-zone-id").val(rs[1]);
				$(this).val(rs[0]);
				$("#btn-move-all").removeClass('not-show');
			}else{
				$("#to-zone-id").val('');
				$(this).val('');
				$("#btn-move-all").addClass('not-show');
			}
		}
	});

}



//------- สลับไปแสดงหน้า transfer_detail
function showTransferTable(){
	getTransferTable();
	hideZoneTable();
	hideTempTable();
	showControl();
	hideMoveIn();
	hideMoveOut();
	$("#transfer-table").removeClass('hide');
}



function hideTransferTable(){
	$("#transfer-table").addClass('hide');
}


function showMoveIn(){
	$(".moveIn-zone").removeClass('hide');
}


function hideMoveIn(){
	$(".moveIn-zone").addClass('hide');
}


function showMoveOut(){
	$(".moveOut-zone").removeClass('hide');
}



function hideMoveOut(){
	$(".moveOut-zone").addClass('hide');
}



function showControl(){
	$(".control-btn").removeClass('hide');
}


function hideControl(){
	$(".control-btn").addClass('hide');
}


function showTempTable(){
	getTempTable();
	hideTransferTable();
	hideZoneTable();
	$("#temp-table").removeClass('hide');
}



function hideTempTable(){
	$("#temp-table").addClass('hide');
}



function showZoneTable(){
	$("#zone-table").removeClass('hide');
}



function hideZoneTable(){
	$("#zone-table").addClass('hide');
}




//-------  ใส่ได้เฉพาะตัวเลขเท่านั้น
function validQty(id, qty){
	var input = $("#moveQty_"+id).val();
	if( input.length > 0 && isNaN( parseInt( input ) ) ){
		swal('กรุณาใส่ตัวเลขเท่านั้น');
		$("#moveQty_"+id).val('');
		return false;
	}

    if( $("#underZero_"+id).is(':checked') == false && ( parseInt( input ) > parseInt(qty) ) ){
		swal('จำนวนในโซนมีไม่พอ');
		$("#moveQty_"+id).val('');
		return false;
	}
}




function getMoveIn(){
	$(".moveIn-zone").removeClass('hide');
	$('#barcode-hr').removeClass('hide');

	$(".moveOut-zone").addClass('hide');
	$(".control-btn").addClass('hide');

	hideTransferTable();
	getTempTable();
	showTempTable();
	$("#toZone-barcode").focus();
}



//---	เปลี่ยนโซนปลายทาง
function newToZone(){
	$('#toZone-barcode').removeAttr('disabled');
	$('#btn-new-to-zone').attr('disabled','disabled');
	$('#zoneName-label').text('');
	$("#to-zone-id").val("");
	$("#toZone-barcode").val("");
	$("#zone-table").addClass('hide');
	$("#toZone-barcode").focus();
}





//---	ดึงข้อมูลสินค้าในโซนต้นทาง
function getZoneTo(){

	var txt = $("#toZone-barcode").val();

	if( txt.length > 0 ){
		//---	คลังปลายทาง
		var id_wh = $("#to-warehouse-id").val();

		$.ajax({
			url:"controller/transferController.php?getZone",
			type:"GET",
			cache:"false",
			data:{
				"txt" : txt,
				"id_warehouse" : id_wh
			},
			success: function(rs){

				var rs = $.trim(rs);

				if( isJson(rs) ){

					//---	รับข้อมูลแล้วแปลงจาก json
					var ds = $.parseJSON(rs);

					//---	update id โซนปลายทาง
					$("#to-zone-id").val(ds.id_zone);

					//---	update ชื่อโซน
					$("#zoneName-label").text(ds.zone_name);

					//---	disabled ช่องยิงบาร์โค้ดโซน
					$("#toZone-barcode").attr('disabled', 'disabled');

					//--- active new zone button
					$('#btn-new-to-zone').removeAttr('disabled');

					//showTempTable();

				}else{

					swal("ข้อผิดพลาด", rs, "error");

					//---	ลบไอดีโซนปลายทาง
					$("#to-zone-id").val("");

					//---	ไม่แสดงชื่อโซน
					$('#zoneName-label').val('');

					//--- disabled new zone buton
					$('#btn-new-to-zone').attr('disabled');

					//--- ซ่อนตารางสินค้าในโซน
					$("#zone-table").addClass('hide');

					beep();
				}
			}
		});
	}
}




$("#toZone-barcode").keyup(function(e) {
    if( e.keyCode == 13 ){
		getZoneTo();
		setTimeout(function(){ $("#barcode-item-to").focus(); }, 500);
	}
});



$("#barcode-item-to").keyup(function(e) {
    if( e.keyCode == 13 ){

		//---	บาร์โค้ดสินค้าที่ยิงมา
		var barcode = $(this).val();

		//---	ไอดีโซนปลายทาง
		var id_zone	= $("#to-zone-id").val();

		//---	ไอดีเอกสาร
		var id_transfer = $("#id_transfer").val();

		//---	ไอดีของรายการโอนสินค้า
		var id_transfer_detail = $("#row_"+barcode).val();


		if( id_zone.length == 0 ){
			swal("กรุณาระบุโซนปลายทาง");
			return false;
		}

		var qty = parseInt($("#qty-to").val());

		var curQty	= parseInt($("#qty-"+barcode).val());

		$(this).val('');

		if( isNaN(curQty) ){
			swal("สินค้าไม่ถูกต้อง");
			return false;
		}



		if( qty != '' && qty != 0 ){
			if( qty <= curQty ){
				$.ajax({
					url:"controller/transferController.php?moveBarcodeToZone",
					type:"POST",
					cache:"false",
					data:{
						"id_transfer_detail" : id_transfer_detail,
						"id_transfer" : id_transfer,
						"id_zone" : id_zone,
						"qty" : qty,
						"barcode" : barcode
					},
					success: function(rs){
						var rs = $.trim(rs);
						if( rs == 'success'){
							curQty = curQty - qty;
							if(curQty == 0 ){
								$("#row-temp-"+id_transfer_detail).remove();
							}else{
								$("#qty-label-"+barcode).text(curQty);
								$("#qty-"+barcode).val(curQty);
							}
							$("#qty-to").val(1);
							$("#barcode-item-to").focus();
						}else{
							swal("ข้อผิดพลาด", rs, "error");
						}
					}
				});
			}else{
				swal("จำนวนในโซนไม่เพียงพอ");
			}
		}
	}
});






//-------	เปิดกล่องควบคุมสำหรับยิงบาร์โค้ดโซนต้นทาง
function getMoveOut(){

	$(".moveIn-zone").addClass('hide');
	$(".control-btn").addClass('hide');
	$("#moveIn-input").addClass('hide');
	$("#transfer-table").addClass('hide');

	$('#barcode-hr').removeClass('hide');
	$(".moveOut-zone").removeClass('hide');
	$("#fromZone-barcode").focus();
}



//---	เปลี่ยนโซนต้นทาง
function newFromZone(){
	$("#id_zone_from").val("");
	$("#fromZone-barcode").val("");
	$("#zone-table").addClass('hide');
	$("#fromZone-barcode").focus();
}




//---	ดึงข้อมูลสินค้าในโซนต้นทาง
function getZoneFrom(){

	var txt = $("#fromZone-barcode").val();

	if( txt.length > 0 ){
		//---	คลังต้นทาง
		var id_wh = $("#from-warehouse-id").val();

		$.ajax({
			url:"controller/transferController.php?getZone",
			type:"GET",
			cache:"false",
			data:{
				"txt" : txt,
				"id_warehouse" : id_wh
			},
			success: function(rs){

				var rs = $.trim(rs);

				if( isJson(rs) ){

					//---	รับข้อมูลแล้วแปลงจาก json
					var ds = $.parseJSON(rs);

					//---	update id โซนต้นทาง
					$("#from-zone-id").val(ds.id_zone);

					//---	update ชื่อโซน
					$("#zoneName").text(ds.zone_name);

					//---	update allowUnderZero
					$('#underZero').val(ds.isAllowUnderZero);

					//--- ถ้าไม่อนุญาติให้ติดลบ
					if( ds.isAllowUnderZero == 0){

						//---	ซ่อน checkbox ติดลบได้
						$('#underZeroLabel').addClass('hide');

					}else{

						//---	แสดง checkbox ติดลบได้
						$('#underZeroLabel').removeClass('hide');

					}

					$("#fromZone-barcode").val("");

					//---	แสดงรายการสินค้าในโซน
					getProductInZone();

				}else{
					swal("ข้อผิดพลาด", rs, "error");

					//---	ลบไอดีโซนต้นทาง
					$("#from-zone-id").val("");

					//---	ไม่แสดงชื่อโซน
					$('#zoneName').val('');

					//---	ไม่ให้ติดลบ
					$('#underZero').val(0);

					//---	แสดง checkbox ติดลบได้
					$('#underZeroLabel').removeClass('hide');

					$("#zone-table").addClass('hide');
					beep();
				}
			}
		});
	}
}



$("#fromZone-barcode").keyup(function(e) {
    if( e.keyCode == 13 ){
		getZoneFrom();
		setTimeout(function(){ $("#barcode-item-from").focus(); }, 500);
	}
});


//------------------------------------- ยิงบาร์โค้ดสินค้า

$("#barcode-item-from").keyup(function(e) {
  if( e.keyCode == 13 ){
		//---	โซนต้นทาง
		var id_zone	= $("#from-zone-id").val();

		//---	ID เอกสาร
		var id_transfer = $("#id_transfer").val();

		//---	ตรวจสอบว่ายิงบาร์โค้ดโซนมาแล้วหรือยัง
		if( id_zone.length == 0 ){
			swal("กรุณาระบุโซนปลายทาง");
			return false;
		}

		//---	จำนวนที่ป้อนมา
		var qty = parseInt($("#qty-from").val());

		//---	โซนนี้ติดลบได้หรือไม่
		var underZero = $('#underZero').val();

		//---	อนุญาติให้ติดลบได้หรือไม่ (ถ้าได้ต้องติ๊กไว้)
		var udz = ($("#allowUnderZeroAll").is(':checked') == true ? 1 : 0 );

		//---	บาร์โค้ดสินค้า
		var barcode = $(this).val();

		//---	จำนวนที่เพิ่มไปแล้ว
		var curQty	= parseInt($("#qty_"+barcode).val());

		//---	เคลียร์ช่องให้พร้อมยิงตัวต่อไป
		$(this).val('');

		//---	เมื่อมีการใส่จำนวนมาตามปกติ
		if( qty != '' && qty != 0 ){

			//---	ถ้าจำนวนที่ใส่มา น้อยกว่าหรือเท่ากับ จำนวนที่มีอยู่
			//---	หรือ โซนนี้สามารถติดลบได้และติ๊กว่าให้ติดลบได้
			//---	หากโซนนี้ไม่สามารถติดลบได้ ถึงจะติ๊กให้ติดลบได้ก็ไม่สามารถให้ติดลบได้
			if( qty <= curQty || (underZero == 1 && udz == 1 ) ){

				//---	ลดยอดสต็อกโซนต้นทาง
				//---	เพิ่มรายการเข้า transfer_detail
				//---	เพิ่มรายการเข้า temp
				//---	บันทึก movement
				$.ajax({
					url:"controller/transferController.php?addBarcodeToTransfer",
					type:"POST",
					cache:"false",
					data:{
						"id_transfer" : id_transfer,
						"from_zone" : id_zone,
						"qty" : qty,
						"barcode" : barcode,
						"isAllowUnderZero" : underZero,
						"underZero" : udz
					},
					success: function(rs){

						var rs = $.trim(rs);

						if( rs == 'success'){

							//--- ลดยอดสินค้าคงเหลือในโซนบนหน้าเว็บ (ในฐานข้อมูลถูกลดแล้ว)
							curQty = curQty - qty;

							//---	แสดงผลยอดสินค้าคงเหลือในโซน
							$("#qty-label_"+barcode).text(curQty);

							//---	ปรับยอดคงเหลือในโซน สำหรับใช้ตรวจสอบการยิงครั้งต่อไป
							$("#qty_"+barcode).val(curQty);

							//---	reset จำนวนเป็น 1
							$("#qty-from").val(1);

							//---	focus ที่ช่องยิงบาร์โค้ด รอการยิงต่อไป
							$("#barcode-item-from").focus();

						}else{

							swal("ข้อผิดพลาด", rs, "error");
						}
					}
				});
			}else{
				swal("จำนวนในโซนไม่เพียงพอ");
			}
		}
	}
});
