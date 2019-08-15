var HOME = BASE_URL + 'inventory/transfer/';

function goBack(){
  window.location.href = HOME;
}



function goAdd(){
  window.location.href = HOME + 'add_new';
}




function goDetail(code){
  window.location.href = HOME + 'view_detail/'+code;
}




//--- สลับมาใช้บาร์โค้ดในการคีย์สินค้า
function goUseBarcode(){
  var code = $('#code').val();
  window.location.href = HOME + 'edit/'+code+'/barcode';
}




//--- สลับมาใช้การคื่ย์มือในการย้ายสินค้า
function goUseKeyboard(){
  var code = $('#code').val();
  window.location.href = HOME + 'edit/'+code;
}




function printTransfer(){
	var center = ($(document).width() - 800) /2;
  var code = $('#code').val();
  var target = HOME + 'print_transfer/'+code;
  window.open(target, "_blank", "width=800, height=900, left="+center+", scrollbars=yes");
}
