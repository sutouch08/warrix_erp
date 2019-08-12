
<div class="tab-pane fade" id="import">
	<form id="importForm">
    <div class="row">
      <div class="col-sm-12">
        <h4 class="title">ตั้งค่าการนำเข้าข้อมูลจาก FORMULA</h4>
      </div>
    </div>
		<hr class="margin-bottom-15" />
  	<div class="row">
			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_PRODUCT_PATH" value="<?php echo getConfig('IMPORT_PRODUCT_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลรุ่นสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_STYLE_PATH" value="<?php echo getConfig('IMPORT_STYLE_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

    	<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลยี่ห้อสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_BRAND_PATH" value="<?php echo getConfig('IMPORT_BRAND_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลขนาดสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_SIZE_PATH" value="<?php echo getConfig('IMPORT_SIZE_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลสีสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_COLOR_PATH" value="<?php echo getConfig('IMPORT_COLOR_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลกลุ่มสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_PRODUCT_GROUP_PATH" value="<?php echo getConfig('IMPORT_PRODUCT_GROUP_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลหน่วยนับ</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_UNIT_PATH" value="<?php echo getConfig('IMPORT_UNIT_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลบาร์โค้ด</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_BARCODE_PATH" value="<?php echo getConfig('IMPORT_BARCODE_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลลูกค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_CUSTOMER_PATH" value="<?php echo getConfig('IMPORT_CUSTOMER_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลกลุ่มลูกค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_CUSTOMER_GROUP_PATH" value="<?php echo getConfig('IMPORT_CUSTOMER_GROUP_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลเขตลูกค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_CUSTOMER_AREA_PATH" value="<?php echo getConfig('IMPORT_CUSTOMER_AREA_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลวงเงินเครดิต</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_CUSTOMER_CREDIT_PATH" value="<?php echo getConfig('IMPORT_CUSTOMER_CREDIT_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลผู้ขาย</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_SUPPLIER_PATH" value="<?php echo getConfig('IMPORT_SUPPLIER_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลกลุ่มผู้ขาย</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_SUPPLIER_GROUP_PATH" value="<?php echo getConfig('IMPORT_SUPPLIER_GROUP_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลพนักงานขาย</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_SALE_MAN_PATH" value="<?php echo getConfig('IMPORT_SALE_MAN_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลกลุ่มพนักงานขาย</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_SALE_GROUP_PATH" value="<?php echo getConfig('IMPORT_SALE_GROUP_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ฐานข้อมูลคลังสินค้า</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_WAREHOUSE_PATH" value="<?php echo getConfig('IMPORT_WAREHOUSE_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ใบสั่งซื้อ (PO)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_PO_PATH" value="<?php echo getConfig('IMPORT_PO_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ใบลดหนี้ซื้อ (BM)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_BM_PATH" value="<?php echo getConfig('IMPORT_BM_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ใบลดหนี้ขาย (SM)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_SM_PATH" value="<?php echo getConfig('IMPORT_SM_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">LOG File</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line import" name="IMPORT_LOG_PATH" value="<?php echo getConfig('IMPORT_LOG_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-9 col-sm-offset-3"><button type="button" class="btn btn-sm btn-success input-mini" onClick="checkImportSetting()"><i class="fa fa-save"></i> บันทึก</button></div>
      <div class="divider-hidden"></div>

  	</div><!--/ row -->
  </form>
</div>
