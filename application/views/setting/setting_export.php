
<div class="tab-pane fade" id="export">
	<form id="exportForm">
    <div class="row">
      <div class="col-sm-12">
        <h4 class="title text-center">ตั้งค่าการส่งออกข้อมูลไป FORMULA</h4>
      </div>
      <div class="divider-hidden"></div>
    </div>

  	<div class="row">
    	<div class="col-sm-3"><span class="form-control left-label">ใบปรับปรุงสต็อก (AJ)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line export" name="EXPORT_AJ_PATH" id="export-aj-path" value="<?php echo getConfig('EXPORT_AJ_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ใบรับสินค้าเข้า (BI)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line export" name="EXPORT_BI_PATH" id="export-bi-path" value="<?php echo getConfig('EXPORT_BI_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ใบรับสินค้าจากการผลิต (FR)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line export" name="EXPORT_FR_PATH" id="export-fr-path" value="<?php echo getConfig('EXPORT_FR_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ใบสั่งขาย (SO)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line export" name="EXPORT_SO_PATH" id="export-so-path" value="<?php echo getConfig('EXPORT_SO_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ใบโอนสินค้า (TR)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line export" name="EXPORT_TR_PATH" id="export-tr-path" value="<?php echo getConfig('EXPORT_TR_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3"><span class="form-control left-label">ใบเบิกสินค้าแปรสภาพ (WR)</span></div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-line export" name="EXPORT_WR_PATH" id="export-wr-path" value="<?php echo getConfig('EXPORT_WR_PATH'); ?>" />
      </div>
      <div class="divider-hidden"></div>


      <div class="col-sm-9 col-sm-offset-3"><button type="button" class="btn btn-sm btn-success input-mini" onClick="checkExportSetting()"><i class="fa fa-save"></i> บันทึก</button></div>
      <div class="divider-hidden"></div>

  	</div><!--/ row -->
  </form>
</div>
