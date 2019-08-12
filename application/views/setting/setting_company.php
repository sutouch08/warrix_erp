<?php
$VAT = getConfig('VAT');
$YEAR = getConfig('START_YEAR');
 ?>


<div class="tab-pane fade" id="company">
	<form id="companyForm">
  	<div class="row">
    	<div class="col-sm-3"><span class="form-control left-label">แบรนด์สินค้า</span></div>
      <div class="col-sm-9"><input type="text" class="form-control input-sm input-medium input-line padding-left-0" name="COMPANY_NAME" id="brand" value="<?php echo getConfig('COMPANY_NAME'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">ชื่อบริษัท</span></div>
      <div class="col-sm-9"><input type="text" class="form-control input-sm input-large input-line padding-left-0" name="COMPANY_FULL_NAME" id="cName" value="<?php echo getConfig('COMPANY_FULL_NAME'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">ที่อยู่</span></div>
      <div class="col-sm-9"><input type="text" class="form-control input-sm input-line padding-left-0" name="COMPANY_ADDRESS" id="cAddress" value="<?php echo getConfig('COMPANY_ADDRESS'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">รหัสไปรษณีย์</span></div>
      <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line padding-left-0" name="COMPANY_POST_CODE" id="postCode" value="<?php echo getConfig('COMPANY_POST_CODE'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">โทรศัพท์</span></div>
      <div class="col-sm-9"><input type="text" class="form-control input-sm input-medium input-line padding-left-0" name="COMPANY_PHONE" id="phone" value="<?php echo getConfig('COMPANY_PHONE'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">แฟกซ์</span></div>
      <div class="col-sm-9"><input type="text" class="form-control input-sm input-medium input-line padding-left-0" name="COMPANY_FAX_NUMBER" id="fax" value="<?php echo getConfig('COMPANY_FAX_NUMBER'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">อีเมล์</span></div>
      <div class="col-sm-9"><input type="text" class="form-control input-sm input-medium input-line padding-left-0" name="COMPANY_EMAIL" id="email" value="<?php echo getConfig('COMPANY_EMAIL'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">เลขประจำตัวผู้เสียภาษี</span></div>
      <div class="col-sm-9"><input type="text" class="form-control input-sm input-medium input-line padding-left-0" name="COMPANY_TAX_ID" id="taxID" value="<?php echo getConfig('COMPANY_TAX_ID'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">อัตราภาษีมูลค่าเพิ่ม (%)</span></div>
      <div class="col-sm-9">
        <input type="number" class="form-control input-sm input-mini input-line padding-left-0" name="VAT" id="VAT" value="<?php echo $VAT; ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">สกุลเงิน</span></div>
      <div class="col-sm-9"><input type="text" class="form-control input-sm input-mini input-line padding-left-0" name="CURRENCY" id="currency" value="<?php echo getConfig('CURRENCY'); ?>" /></div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">ปีที่เริ่มต้นกิจการ (ค.ศ.)</span></div>
      <div class="col-sm-9">
        <input type="number" class="form-control input-sm input-mini input-line padding-left-0" name="START_YEAR" id="startYear" value="<?php echo $YEAR; ?>" />
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-9 col-sm-offset-3"><button type="button" class="btn btn-sm btn-success input-mini" onClick="checkCompanySetting()"><i class="fa fa-save"></i> บันทึก</button></div>
      <div class="divider-hidden"></div>

  	</div><!--/ row -->
  </form>
</div>
