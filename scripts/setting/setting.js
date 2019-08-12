CKEDITOR.replace( 'content',{
	toolbarGroups: [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'links' },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'tools' },
		{ name: 'others' },
		{ name: 'about' }
	]
});

//------------  UPDATE TEXT AREA BEFORE SERIALIZE ---------------//
function CKupdate()
{
    for ( instance in CKEDITOR.instances )
	{
        CKEDITOR.instances[instance].updateElement();
	}
}



function updateConfig(formName)
{
	load_in();
	if(formName == 'systemForm'){
		CKupdate();
	}

	var formData = $("#"+formName).serialize();
	$.ajax({
		url:"controller/settingController.php?updateConfig",
		type:"POST",
    cache:"false",
    data: formData,
		success: function(rs){
			load_out();
      rs = $.trim(rs);
      if(rs == 'success'){
        swal({
          title:'Updated',
          type:'success',
          timer:1000
        });
      }else{
        swal('Error!', rs, 'error');
      }
		}
	});
}



function openSystem()
{
	$("#closed").val(0);
	$("#btn-close").removeClass('btn-danger');
	$("#btn-open").addClass('btn-success');
}



function closeSystem()
{
	$("#closed").val(1);
	$("#btn-open").removeClass('btn-success');
	$("#btn-close").addClass('btn-danger');
}



function toggleCreditLimit(option)
{
	$('#creditLimit').val(option);
	if(option == 1){
		$('#btn-credit-yes').addClass('btn-success');
		$('#btn-credit-no').removeClass('btn-danger');
		return;
	}

	if(option == 0){
		$('#btn-credit-yes').removeClass('btn-success');
		$('#btn-credit-no').addClass('btn-danger');
		return;
	}
}


function toggleEditDiscount(option)
{
	$('#allow-edit-discount').val(option);
	if(option == 1){
		$('#btn-disc-yes').addClass('btn-success');
		$('#btn-disc-no').removeClass('btn-danger');
		return;
	}

	if(option == 0){
		$('#btn-disc-yes').removeClass('btn-success');
		$('#btn-disc-no').addClass('btn-danger');
		return;
	}
}


function toggleEditPrice(option){
	$('#allow-edit-price').val(option);

	if(option == 1){
		$('#btn-price-yes').addClass('btn-success');
		$('#btn-price-no').removeClass('btn-danger');
		return;
	}

	if(option == 0){
		$('#btn-price-yes').removeClass('btn-success');
		$('#btn-price-no').addClass('btn-danger');
		return;
	}
}


function toggleEditCost(option){
	$('#allow-edit-cost').val(option);

	if(option == 1){
		$('#btn-cost-yes').addClass('btn-success');
		$('#btn-cost-no').removeClass('btn-danger');
		return;
	}

	if(option == 0){
		$('#btn-cost-yes').removeClass('btn-success');
		$('#btn-cost-no').addClass('btn-danger');
		return;
	}
}



function toggleAutoClose(option){
	$('#po-auto-close').val(option);

	if(option == 1){
		$('#btn-po-yes').addClass('btn-success');
		$('#btn-po-no').removeClass('btn-danger');
		return;
	}

	if(option == 0){
		$('#btn-po-yes').removeClass('btn-success');
		$('#btn-po-no').addClass('btn-danger');
		return;
	}
}


function checkCompanySetting(){
	vat = parseFloat($('#VAT').val());
	year = parseInt($('#startYear').val());

	if(isNaN(vat)){
		swal('อัตราภาษีมูลค่าเพิ่มไม่ถูกต้อง');
		return false;
	}

	if(vat < 0 || vat > 99 ){
		swal('อัตราภาษีมูลค่าเพิ่มไม่ถูกต้อง');
		return false;
	}

	if(isNaN(year)){
		swal('ปีที่เริ่มต้นกิจการไม่ถูกต้อง');
		return false;
	}

	if(year < 1970){
		swal('ปีที่เริ่มต้นกิจการไม่ถูกต้อง');
		return false;
	}

	if(year > 2100){
		year = year - 543;
		$('#startYear').val(year);
	}


	updateConfig('companyForm');
}


function checkExportSetting(){
	error = 0;
	message = 'จำเป็นต้องระบุ PATH';

	$('.export').each(function(index, el){
		if($(this).val() == ''){
			$(this).addClass('has-error');
			error++;
		}else{
			$(this).removeClass('has-error');
		}
	});

	if(error > 0){
		swal('Error', message, 'error');
		return false;
	}

	updateConfig('exportForm');
}


function checkImportSetting(){
	error = 0;
	message = 'จำเป็นต้องระบุ PATH';

	$('.import').each(function(index, el){
		if($(this).val() == ''){
			$(this).addClass('has-error');
			error++;
		}else{
			$(this).removeClass('has-error');
		}
	});

	if(error > 0 ){
		swal('Error', message, 'error');
		return false;
	}

	updateConfig('importForm');
}



function checkMoveSetting(){
	error = 0;
	message = 'จำเป็นต้องระบุ PATH';

	$('.move').each(function(index, el){
		if($(this).val() == ''){
			$(this).addClass('has-error');
			error++;
		}else{
			$(this).removeClass('has-error');
		}
	});

	if(error > 0 ){
		swal('Error', message, 'error');
		return false;
	}

	updateConfig('moveForm');
}
