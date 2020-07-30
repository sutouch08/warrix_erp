<?php $this->load->view('include/header'); ?>
<div class="row" style="margin-top:30px;">
  <div class="col-sm-4 col-sm-offset-4 padding-5">
    <input type="text" class="form-control input-sm text-center" id="search-text" placeholder="พิมพ์รหัสสินค้า 4 ตัวอักษรขึ้นไป" />
  </div>
</div>
<div class="divider-hidden"></div>
<div class="row">
  <div class="col-sm-2 col-sm-offset-4 col-xs-6 padding-5">
    <button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()">ตรวจสอบสต็อก</button>
  </div>
  <div class="col-sm-2 col-xs-6 padding-5">
    <button type="button" class="btn btn-xs btn-info btn-block" onclick="findOrder()">ตรวจสอบออเดอร์</button>
  </div>

</div>
<hr class="margin-top-15 margin-bottom-15"/>

<div class="row">
  <div class="col-sm-12" id="result">
    
  </div>
</div>


<script id="order-template" type="text/x-handlebarsTemplate">
<table class="table table-bordered">
	<thead>
		<tr class="font-size-12">
			<th class="width-20">รหัสสินค้า</th>
			<th class="width-15 text-center">เลขที่ออเดอร์</th>
			<th class="width-10 text-center">จำนวน</th>
			<th class="width-10 text-center">สถานะ</th>
			<th class="width-30 text-center">ลูกค้า</th>
			<th class="width-15 text-center">พนักงาน</th>
		</tr>
	</thead>
	<tbody>
		{{#each this}}
			{{#if nodata}}
				<tr>
					<td colspan="6" class="text-center">ไม่พบข้อมูล</td>
				</tr>
			{{else}}
				<tr class="font-size-12">
					<td class="middle">
          {{#if oldCode}}
            {{oldCode}} |
          {{/if}}

          {{ pdCode }}
          </td>
					<td class="middle text-center">{{ reference }}</td>
					<td class="middle text-center">{{ qty }}</td>
					<td class="middle text-center">{{ state }}</td>
					<td class="middle">{{ cusName }}</td>
					<td class="middle">{{ empName }}</td>
				</tr>
			{{/if}}
		{{/each}}
	</tbody>
</table>
</script>


<script id="stock-template" type="text/x-handlebarsTemplate">
<table class="table table-bordered">
	<thead>
		<tr class="font-size-12">
			<th class="width-10 text-center">รูปภาพ</th>
			<th class="width-15 text-center">รหัสสินค้า</th>
			<th class="text-center">ชื่อสินค้า</th>
			<th class="width-10 text-center">จำนวน</th>
			<th class="width-10 text-center">สถานที่</th>
		</tr>
	</thead>
	<tbody>
{{#each this}}
	{{#if nodata}}
		<tr>
			<td colspan="4" class="text-center">ไม่พบรายการ</td>
		</tr>
	{{else}}
		<tr>
			<td class="middle text-center">{{{ img }}}</td>
			<td class="middle">{{ pdCode }}</td>
			<td class="middle">{{ pdName }}</td>
			<td class="text-center middle">{{ qty }}</td>
			<td class="text-center middle">
				<button type="button"
							class="btn btn-info"
							data-container="body"
							data-toggle="popover"
							data-html="true"
							data-placement="left"
							data-trigger="focus"
							data-content="{{ stockInZone }}">
							รายละเอียด
				</button>
			</td>
		</tr>
	{{/if}}
{{/each}}
	</tbody>
</table>
</script>

<script>
var HOME = BASE_URL + 'main/';
//---- ค้นหาว่าสินค้าติดอยู่ที่ออเดอร์ไหนบ้าง
function findOrder(){
	var searchText = $.trim($('#search-text').val());
	if(searchText.length > 3){
		load_in();

		$.ajax({
			url:HOME + 'find_order',
			type:'POST',
			cache:'false',
			data:{
				'search_text' : searchText
			},
			success:function(rs){
				load_out();
				var source = $('#order-template').html();
				var data = $.parseJSON(rs);
				var output = $('#result');
				render(source, data, output);
			}
		});
	}
}



function getSearch(){
	var searchText = $.trim($('#search-text').val());

	if(searchText.length > 3 ){
		load_in();
		$.ajax({
			url:HOME + 'get_sell_items_stock',
			type:'POST',
			cache:'false',
			data:{
				'search_text' : searchText
			},
			success:function(rs){
				load_out();
				var source = $('#stock-template').html();
				var data = $.parseJSON(rs);
				var output = $('#result');
				render(source, data, output);
				popover_init();
			}
		});
	}
}

function popover_init(){
	$('[data-toggle="popover"]').popover();
}

function getViewStock(){
	window.location.href = BASE_URL + 'view_stock' ;
}
</script>

<?php $this->load->view('include/footer'); ?>
