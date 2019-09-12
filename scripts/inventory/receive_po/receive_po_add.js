// JavaScript Document

var data = [];
var poError = 0;
var invError = 0;
var zoneError = 0;


function editHeader(){
	$('.header-box').removeAttr('disabled');
	$('#btn-edit').addClass('hide');
	$('#btn-update').removeClass('hide');
}




function receiveProduct(pdCode){
	var qty = isNaN( parseInt( $("#qty").val() ) ) ? 1 : parseInt( $("#qty").val() );
	var bc = $("#barcode");
	var input = $("#receive_"+ pdCode);
	if(input.length == 1 ){
		bc.val('');
		bc.attr('disabled', 'disabled');
		var cqty = input.val() == "" ? 0 : parseInt(input.val());
		qty += cqty;
		input.val(qty);
		$("#qty").val(1);
		sumReceive();
		bc.removeAttr('disabled');
		bc.focus();
	}else{
		swal({
			title: "ข้อผิดพลาด !",
			text: "บาร์โค้ดไม่ถูกต้องหรือสินค้าไม่ตรงกับใบสั่งซื้อ",
			type: "error"},
			function(){
				setTimeout( function(){ $("#barcode")	.focus(); }, 1000 );
		});
	}
}




function save(){
	code = $('#receive_code').val();

	//--- Vendor code
	vendor_code = $('#vendor_code').val();
	vendor_name = $('#vendorName').val();

	//--- อ้างอิง PO Code
	po = $.trim($('#poCode').val());

	//--- เลขที่ใบส่งสินค้า
	invoice = $.trim($('#invoice').val());

	//--- zone id
	zone_code = $('#zone_code').val();
	zoneName = $('#zoneName').val();

	//--- approve key
	approver = $('#approver').val();

	//--- นับจำนวนรายการในใบสั่งซื้อ
	count = $(".receive-box").length;

	//--- ราคาสินค้าแต่ละตัว
	price = $('#prices').val()

	//--- ตรวจสอบความถูกต้องของข้อมูล
	if(code == '' || code == undefined){
		swal('ไม่พบเลขที่เอกสาร', 'หากคุณเห็นข้อผิดพลาดนี้มากกว่า 1 ครับ ให้ลองออกจากหน้านี้แล้วกลับเข้ามาทำรายการใหม่', 'error');
		return false;
	}

	if(vendor_code == '' || vendor_name == ''){
		swal('กรุณาระบุผู้จำหน่าย');
		return false;
	}


	//--- ใบสั่งซื้อถูกต้องหรือไม่
	if(po == ''){
		swal('กรุณาระบุใบสั่งซื้อ');
		return false;
	}

	//--- มีรายการในใบสั่งซื้อหรือไม่
	if(count = 0){
		swal('Error!', 'ไม่พบรายการรับเข้า','error');
		return false;
	}

	//--- ตรวจสอบใบส่งของ (ต้องระบุ)
	if(invoice.length == 0){
		swal('กรุณาระบุใบส่งสินค้า');
		return false;
	}

	//--- ตรวจสอบโซนรับเข้า
	if(zone_code == '' || zoneName == ''){
		swal('กรุณาระบุโซนเพื่อรับเข้า');
		return false;
	}

	ds = [
		{'name' : 'receive_code', 'value' : code},
		{'name' : 'vendor_code', 'value' : vendor_code},
		{'name' : 'vendorName', 'value' : vendor_name},
		{'name' : 'poCode', 'value' : po},
		{'name' : 'invoice', 'value' : invoice},
		{'name' : 'zone_code', 'value' : zone_code},
		{'name' : 'approver', 'value' : approver}
	];


	$('.receive-box').each(function(index, el) {
		qty = parseInt($(this).val());
		arr = $(this).attr('id').split('_');
		pdCode = arr[1];
		name = "receive["+pdCode+"]";
		backlogs = $('#limit_'+pdCode).val();
		bname = "backlogs["+pdCode+"]";
		pname = "prices["+pdCode+"]";
		price = $('#price_'+pdCode).val();
		if($(this).val() > 0 && !isNaN(qty)){
			ds.push({
				'name' : name, 'value' : qty
			});

			ds.push({
				'name' : bname, 'value' : backlogs
			});

			ds.push({
				'name' : pname, 'value' : price
			});
		}
	});

	if(ds.length < 10){
		swal('ไม่พบรายการรับเข้า');
		return false;
	}

	load_in();

	$.ajax({
		url: HOME + 'save',
		type:"POST",
		cache:"false",
		data: ds,
		success: function(rs){
			load_out();

			rs = $.trim(rs);
			if(rs == 'success'){
				swal({
					title:'Success',
					text:'บันทึกรายการเรียบร้อยแล้ว',
					type:'success',
					timer:1000
				});

				setTimeout(function(){
					update_api_stock(code);
					viewDetail(code);
				}, 1200);

			}
			else
			{
				swal("ข้อผิดพลาด !", rs, "error");
			}
		}
	});


}	//--- end save



function update_api_stock(code)
{
	$.post(BASE_URL + 'api/update_receive_stock/'+code);
}


function checkLimit(){
	var limit = $("#overLimit").val();
	var over = 0;
	$(".barcode").each(function(index, element) {
    var arr = $(this).attr("id").split('_');
		var barcode = arr[1];
		var limit = parseInt($("#limit_"+barcode).val() );
		var qty = parseInt($("#receive_"+barcode).val() );
		if( ! isNaN(limit) && ! isNaN( qty ) ){
			if( qty > limit ){
				over++;
				}
			}
    });

	if( over > 0 ){
		getApprove();
	}else{
		save();
	}
}






$("#sKey").keyup(function(e) {
    if( e.keyCode == 13 ){
		doApprove();
	}
});





function getApprove(){
	$("#approveModal").modal("show");
}





$("#approveModal").on('shown.bs.modal', function(){ $("#sKey").focus(); });



function validate_credentials(){
	var s_key = $("#s_key").val();
	var menu 	= $("#validateTab").val();
	var field = $("#validateField").val();
	if( s_key.length != 0 ){
		$.ajax({
			url:BASE_URL + 'users/validate_credentials/get_permission',
			type:"GET",
			cache:"false",
			data:{
				"menu" : menu,
				"s_key" : s_key,
				"field" : field
			},
			success: function(rs){
				var rs = $.trim(rs);
				if( isJson(rs) ){
					var data = $.parseJSON(rs);
					$("#approverName").val(data.approver);
					closeValidateBox();
					callback();
					return true;
				}else{
					showValidateError(rs);
					return false;
				}
			}
		});
	}else{
		showValidateError('Please enter your secure code');
	}
}


function doApprove(){
	var s_key = $("#sKey").val();
	var menu = 'APOVPO'; //-- อนุมัติรับสินค้าเกินใบสั่งซื้อ
	var field = '';

	if( s_key.length > 0 )
	{
		$.ajax({
			url:BASE_URL + 'users/validate_credentials/get_permission',
			type:"GET",
			cache:"false",
			data:{
				"menu" : menu,
				"s_key" : s_key,
				"field" : field
			},
			success: function(rs){
				var rs = $.trim(rs);
				if( isJson(rs) ){
					var data = $.parseJSON(rs);
					$("#approver").val(data.approver);
					$("#approveModal").modal('hide');
					save();
				}else{
					$('#approvError').text(rs);
					return false;
				}
			}
		});
	}
}





function leave(){
	swal({
		title: 'ยกเลิกข้อมูลนี้ ?',
		type: 'warning',
		showCancelButton: true,
		cancelButtonText: 'No',
		confirmButtonText: 'Yes',
		closeOnConfirm: false
	}, function(){
		goBack();
	});

}


function changePo(){
	swal({
		title: 'ยกเลิกข้อมูลนี้ ?',
		type: 'warning',
		showCancelButton: true,
		cancelButtonText: 'No',
		confirmButtonText: 'Yes',
		closeOnConfirm: false
	}, function(){
		$("#receiveTable").html('');
		$('#btn-change-po').addClass('hide');
		$('#btn-get-po').removeClass('hide');
		$('#poCode').val('');
		$('#poCode').removeAttr('disabled');
		swal({
			title:'Success',
			text:'ยกเลิกข้อมูลเรียบร้อยแล้ว',
			type:'success',
			timer:1000
		});
		setTimeout(function(){
			$('#poCode').focus();
		}, 1200);
	});
}


function getData(){
	var po = $("#poCode").val();
	load_in();
	$.ajax({
		url: HOME + 'get_po_detail', //"controller/receiveProductController.php?getPoData",
		type:"GET",
		cache:"false",
		data:{
			"po_code" : po
		},
		success: function(rs){
			load_out();
			var rs = $.trim(rs);
			if( isJson(rs) ){
				data = $.parseJSON(rs);
				var source = $("#template").html();
				var output = $("#receiveTable");
				render(source, data, output);
				$("#poCode").attr('disabled', 'disabled');
				$(".receive-box").keyup(function(e){
    				sumReceive();
				});

				$('#btn-get-po').addClass('hide');
				$('#btn-change-po').removeClass('hide');
				setTimeout(function(){
					$('#invoice').focus();
				},1000);

			}else{
				swal("ข้อผิดพลาด !", rs, "error");
				$("#receiveTable").html('');
			}
		}
	});
}






$("#vendorName").autocomplete({
	source: BASE_URL + 'auto_complete/get_vendor_code_and_name',
	autoFocus: true,
	close: function(){
		var rs = $(this).val();
		var arr = rs.split(' | ');
		if( arr.length == 2 ){
			$(this).val(arr[1]);
			$("#vendor_code").val(arr[0]);
			$('#poCode').focus();
		}else{
			$(this).val('');
			$("#vendor_code").val('');
		}
	}
});



$('#vendorName').focusout(function(event) {
	if($(this).val() == ''){
		$('#vendor_code').val('');
	}
	poInit();
});




$(document).ready(function() {
	poInit();
});


function poInit(){
	var vendor_code = $('#vendor_code').val();
	if(vendor_code == ''){
		$("#poCode").autocomplete({
			source: BASE_URL + 'auto_complete/get_po_code',
			autoFocus: true,
			close:function(){
				var code = $(this).val();
				var arr = code.split(' | ');
				if(arr.length == 2){
					$(this).val(arr[1]);
				}
			}
		});
	}else{
		$("#poCode").autocomplete({
			source: BASE_URL + 'auto_complete/get_po_code/'+vendor_code,
			autoFocus: true,
			close:function(){
				var code = $(this).val();
				var arr = code.split(' | ');
				if(arr.length == 2){
					$(this).val(arr[1]);
				}
			}
		});
	}
}




$('#poCode').keyup(function(e) {
	if(e.keyCode == 13){
		if($(this).val().length > 0){
			getData();
		}
	}
});






$("#zoneName").autocomplete({
	source: BASE_URL + 'auto_complete/get_zone_code', //"controller/receiveProductController.php?search_zone",
	autoFocus: true,
	close: function(){
		var rs = $(this).val();
		if(rs.length == ''){
			$('#zone_code').val('');
			$('#zoneName').val('');
		}else{
			$('#zone_code').val(rs);
			$('#zoneName').val(rs);
		}
	}
});





$("#dateAdd").datepicker({ dateFormat: 'dd-mm-yy'});






function checkBarcode(){
	barcode = $('#barcode').val();

	if($('#'+barcode).length == 1){
		pdCode = $('#'+barcode).val();
		receiveProduct(pdCode);
	}else{
		$('#barcode').val('');
		swal({
			title: "ข้อผิดพลาด !",
			text: "บาร์โค้ดไม่ถูกต้องหรือสินค้าไม่ตรงกับใบสั่งซื้อ",
			type: "error"
		},
			function(){
				setTimeout( function(){ $("#barcode")	.focus(); }, 1000 );
			});
	}
}



$("#barcode").keyup(function(e) {
  if( e.keyCode == 13 ){
		checkBarcode();
	}
});




function sumReceive(){

	var qty = 0;
	$(".receive-box").each(function(index, element) {
    	var cqty = isNaN( parseInt( $(this).val() ) ) ? 0 : parseInt( $(this).val() );
			qty += cqty;
    });
	$("#total-receive").text( addCommas(qty) );
}
