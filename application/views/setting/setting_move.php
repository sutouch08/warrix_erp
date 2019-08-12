
<div class="tab-pane fade" id="move">
	<form id="moveForm">
    <div class="row">
      <div class="col-sm-12">
        <h4 class="title">ตั้งค่าพื้นที่เก็บไฟล์ที่นำเข้าข้อมูลจาก FORMULA เรียบร้อยแล้ว</h4>
      </div>
    </div>
		<hr class="margin-bottom-15" />
  	<div class="row">
			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_PRODUCT_PATH" value="<?php echo getConfig('MOVE_PRODUCT_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลรุ่นสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_STYLE_PATH" value="<?php echo getConfig('MOVE_STYLE_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

    	<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลยี่ห้อสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_BRAND_PATH" value="<?php echo getConfig('MOVE_BRAND_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลขนาดสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_SIZE_PATH" value="<?php echo getConfig('MOVE_SIZE_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลสีสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_COLOR_PATH" value="<?php echo getConfig('MOVE_COLOR_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลกลุ่มสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_PRODUCT_GROUP_PATH" value="<?php echo getConfig('MOVE_PRODUCT_GROUP_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลหน่วยนับ</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_UNIT_PATH" value="<?php echo getConfig('MOVE_UNIT_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลบาร์โค้ด</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_BARCODE_PATH" value="<?php echo getConfig('MOVE_BARCODE_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลลูกค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_CUSTOMER_PATH" value="<?php echo getConfig('MOVE_CUSTOMER_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลกลุ่มลูกค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_CUSTOMER_GROUP_PATH" value="<?php echo getConfig('MOVE_CUSTOMER_GROUP_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลเขตลูกค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_CUSTOMER_AREA_PATH" value="<?php echo getConfig('MOVE_CUSTOMER_AREA_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลวงเงินเครดิต</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_CUSTOMER_CREDIT_PATH" value="<?php echo getConfig('MOVE_CUSTOMER_CREDIT_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลผู้ขาย</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_SUPPLIER_PATH" value="<?php echo getConfig('MOVE_SUPPLIER_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลกลุ่มผู้ขาย</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_SUPPLIER_GROUP_PATH" value="<?php echo getConfig('MOVE_SUPPLIER_GROUP_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลพนักงานขาย</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_SALE_MAN_PATH" value="<?php echo getConfig('MOVE_SALE_MAN_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลกลุ่มพนักงานขาย</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_SALE_GROUP_PATH" value="<?php echo getConfig('MOVE_SALE_GROUP_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลคลังสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_WAREHOUSE_PATH" value="<?php echo getConfig('MOVE_WAREHOUSE_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ใบสั่งซื้อ (PO)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_PO_PATH" value="<?php echo getConfig('MOVE_PO_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ใบลดหนี้ซื้อ (BM)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_BM_PATH" value="<?php echo getConfig('MOVE_BM_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ใบลดหนี้ขาย (SM)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line move" name="MOVE_SM_PATH" value="<?php echo getConfig('MOVE_SM_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-9 col-sm-offset-3"><button type="button" class="btn btn-sm btn-success input-mini" onClick="checkMoveSetting()"><i class="fa fa-save"></i> บันทึก</button></div>
      <div class="divider-hidden"></div>

  	</div><!--/ row -->
  </form>
</div>
