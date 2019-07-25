function addNew(){
  window.location.href = BASE_URL + 'orders/orders/add_new';
}



function goBack(){
  window.location.href = BASE_URL + 'orders/orders';
}



function editDetail(){
  var code = $('#order_code').val();
  window.location.href = BASE_URL + 'orders/orders/edit_detail/'+ code;
}


function editOrder(code){
  window.location.href = BASE_URL + 'orders/orders/edit_order/'+ code;
}



function clearFilter(){
  var url = BASE_URL + 'orders/orders/clear_filter';
  $.get(url, function(rs){ goBack(); });
}



function getSearch(){
  $('#searchForm').submit();
}



$('.search').keyup(function(e){
  if(e.keyCode == 13){
    getSearch();
  }
});


$("#fromDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#toDate").datepicker("option", "minDate", ds);
	}
});

$("#toDate").datepicker({
	dateFormat: 'dd-mm-yy',
	onClose: function(ds){
		$("#fromDate").datepicker("option", "maxDate", ds);
	}
});
