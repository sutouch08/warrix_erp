<div class="row">
	<div class="col-sm-1 padding-5 first">
    	<label>จำนวน</label>
        <input type="number" class="form-control input-sm text-center" id="qty" value="1" />
    </div>
    <div class="col-sm-2 padding-5">
    	<label>บาร์โค้ดสินค้า</label>
        <input type="text" class="form-control input-sm text-center" id="barcode" placeholder="ยิงบาร์โค้ดเพื่อรับสินค้า" autocomplete="off"  />
    </div>
    <div class="col-sm-1 padding-5">
    	<label class="display-block not-show">ok</label>
        <button type="button" class="btn btn-xs btn-primary btn-block" onclick="doReceive()"><i class="fa fa-check"></i> ตกลง</button>
    </div>

    <div class="col-sm-2 padding-5">
      <label>รหัสสินค้า</label>
      <input type="text" class="form-control input-sm text-center" id="item_code" />
    </div>
		<div class="col-sm-1 padding-5">
			<label>ราคา</label>
			<input type="number" class="form-control input-sm text-center" id="price" readonly />
		</div>
		<div class="col-sm-1 padding-5">
      <label>GP[%]</label>
      <input type="number" class="form-control input-sm text-center" id="i-gp" value="0" />
    </div>
    <div class="col-sm-1 padding-5">
      <label>จำนวน</label>
      <input type="number" class="form-control input-sm text-center" id="i-qty" value="1" />
    </div>

    <div class="col-sm-1 padding-5">
      <label class="display-block not-show">add</label>
      <button type="button" class="btn btn-xs btn-primary btn-block" onclick="add_item()">เพิ่ม</button>
    </div>

		<div class="col-sm-2 padding-5 last">
			<label class="display-block not-show">delete</label>
			<button type="button" class="btn btn-xs btn-danger btn-block" onclick="deleteChecked()"><i class="fa fa-trash"></i> ลบรายการที่เลือก</button>
		</div>
</div>
<hr class="margin-top-15 margin-bottom-15"/>
