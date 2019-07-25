// JavaScript Document
function getProductGrid(){
	var pdCode 	= $("#pd-box").val();
	if( pdCode.length > 0  ){
		load_in();
		$.ajax({
			url: BASE_URL + 'orders/orders/get_order_grid',
			type:"GET",
			cache:"false",
			data:{
				"style_code" : pdCode
			},
			success: function(rs){
				load_out();
				var rs = rs.split(' | ');
				if( rs.length == 4 ){
					var grid = rs[0];
					var width = rs[1];
					var pdCode = rs[2];
					var style = rs[3];
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
	load_in();
	$.ajax({
		url: BASE_URL + 'orders/orders/get_order_grid',
		type:"GET",
		cache:"false",
		data:{
			"style_code" : styleCode
		},
		success: function(rs){
			load_out();
			var rs = rs.split(' | ');
			if( rs.length == 4 ){
				var grid = rs[0];
				var width = rs[1];
				var pdCode = rs[2];
				var style = rs[3];
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



function getStockGrid(id_style){
	if(id_style == undefined){
		var id_style = $('#id_style').val();
	}
	var id_branch = $('#id_branch').val();
	var branch = $('#id_branch :selected').text();

	load_in();
	$.ajax({
		url:"controller/orderController.php?getStockGrid",
		type:"GET",
		cache:"false",
		data:{
			"id_branch" : id_branch,
			"id_style" : id_style
		},
		success: function(rs){
			load_out();
			var rs = rs.split(' | ');
			if( rs.length == 4 ){
				var grid = rs[0];
				var width = rs[1];
				var pdCode = rs[2];

				$("#modal").css("width", width +"px");
				$("#modalTitle").html(pdCode+' : '+branch);
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
