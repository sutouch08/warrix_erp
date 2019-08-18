$('#dateAdd').datepicker({
	dateFormat:'dd-mm-yy'
});



function addNew()
{
  var date_add = $('#dateAdd').val();
	var invoice = $('#invoice').val();
	var customer_code = $('#customer_code').val();
  var remark = $('#remark').val();
  if(!isDate(date_add)){
    swal('วันที่ไม่ถูกต้อง');
    return false;
  }

	if(invoice.length == 0){
		swal('กรุณาอ้างอิงเลขที่บิล');
		return false;
	}

	if(customer_code.length == 0){
		swal('กรุณาอ้างอิงลูกค้า');
		return false;
	}

  $('#addForm').submit();
}



$('#customer_code').autocomplete({
	source:BASE_URL + 'auto_complete/get_customer_code_and_name',
	autoFocus:true,
	close:function(){
		var arr = $(this).val().split(' | ');
		if(arr.length == 2){
			$('#customer_code').val(arr[0]);
			$('#customer_name').val(arr[1]);
		}else{
			$('#customer_code').val('');
			$('#customer_name').val('');
		}
	}
});


$('#customer_name').autocomplete({
	source:BASE_URL + 'auto_complete/get_customer_code_and_name',
	autoFocus:true,
	close:function(){
		var arr = $(this).val().split(' | ');
		if(arr.length == 2){
			$('#customer_code').val(arr[0]);
			$('#customer_name').val(arr[1]);
		}else{
			$('#customer_code').val('');
			$('#customer_name').val('');
		}
	}
});
