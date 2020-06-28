var exported = 0;
var i = 1;
var limit = 0;
var ds;
$(document).ready(function() {
  doExport();
});


function addlog(text){
  var el = document.createElement('P');
  el.innerHTML = (text);
  $('#result').prepend(el);
}

function clearlog(){
  $('#result').html('');
}

var data = [
'WO-200103098',
'WO-200103352',
'WO-200301498',
'WO-200214816',
'WO-200210890',
'WO-200209334',
'WO-200207755',
'WO-200204021',
'WO-200202822',
'WO-200202830',
'WO-200202832',
'WO-200201938',
'WO-200202221',
'WO-200117618',
'WO-200118430',
'WO-200117272',
'WO-200117624',
'WO-200110371',
'WO-200108639',
'WO-200108645'
];

function doExport(){
  data.forEach(confirmBill)
}


function confirmBill(value, index, array){
    $.ajax({
  		url: BASE_URL + 'inventory/delivery_order/manual_export/'+value,
  		type:'POST',
  		cache:'false',
  		// data:{
  		// 	'order_code' : value
  		// },
  		success:function(rs){
  			var rs = $.trim(rs);
        let no = i + 1;
  			addlog(no + " : " + value + " : " +rs);
        update_stat();
  		}
  	});
}


function update_stat(){
  exported++;
  $('#stat-qty').text(exported);
}


function isJson(str){
	try{
		JSON.parse(str);
	}catch(e){
		return false;
	}
	return true;
}
