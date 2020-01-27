// JavaScript Document
function getProductGrid(){
	var pdCode 	= $("#pd-box").val();
	var whCode = $('#warehouse').val();
	var isView = $('#view').length;
	if( pdCode.length > 0  ){
		load_in();
		$.ajax({
			url: BASE_URL + 'orders/orders/get_order_grid',
			type:"GET",
			cache:"false",
			data:{
				"style_code" : pdCode,
				"warehouse_code" : whCode,
				"isView" : isView
			},
			success: function(rs){
				load_out();
				var rs = rs.split(' | ');
				if( rs.length == 4 ){
					var grid = rs[0];
					var width = rs[1];
					var pdCode = rs[2];
					var style = rs[3];
					if(grid == 'notfound'){
						swal("ไม่พบสินค้า");
						return false;
					}
					$("#modal").css("width", width +"px");
					$("#modalTitle").html(pdCode);
					$("#id_style").val(style);
					$("#modalBody").html(grid);
					$("#orderGrid").modal('show');
				}else{
					swal("สินค้าไม่ถูกต้อง");
				}
			}
		});
	}
}



function getOrderGrid(styleCode){
	var whCode = $('#warehouse').val();
	var isView = $('#view').length;
	load_in();
	$.ajax({
		url: BASE_URL + 'orders/orders/get_order_grid',
		type:"GET",
		cache:"false",
		data:{
			"style_code" : styleCode,
			"warehouse_code" : whCode,
			"isView" : isView
		},
		success: function(rs){
			load_out();
			var rs = rs.split(' | ');
			if( rs.length == 4 ){
				var grid = rs[0];
				var width = rs[1];
				var pdCode = rs[2];
				var style = rs[3];
				if(grid == 'notfound'){
					swal("ไม่พบสินค้า");
					return false;
				}

				$("#modal").css("width", width +"px");
				$("#modalTitle").html(pdCode);
				$("#id_style").val(style);
				$("#modalBody").html(grid);
				$("#orderGrid").modal('show');
			}else{
				swal("สินค้าไม่ถูกต้อง");
			}
		}
	});
}


function valid_qty(el, qty){
	var order_qty = el.val();
	if(parseInt(order_qty) > parseInt(qty) )	{
		swal('สั่งได้ '+qty+' เท่านั้น');
		el.val('');
		el.focus();
	}
}
