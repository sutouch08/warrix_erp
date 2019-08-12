<div class="tab-pane fade" id="system">
<?php
    $closed   = getConfig('CLOSED'); //--- ปิดระบบทั้งหมดหรือไม่
    $open     = $closed == 0 ? 'btn-success' : '';
    $close    = $closed == 1 ? 'btn-danger' : '';
?>

  <form id="systemForm">
    <div class="row">
  	<?php if( $cando === TRUE ): //---- ถ้ามีสิทธิ์ปิดระบบ ---//	?>
    	<div class="col-sm-3"><span class="form-control left-label">ปิดระบบ</span></div>
      <div class="col-sm-9">
      	<div class="btn-group input-small">
        	<button type="button" class="btn btn-sm <?php echo $open; ?>" style="width:50%;" id="btn-open" onClick="openSystem()">เปิด</button>
          <button type="button" class="btn btn-sm <?php echo $close; ?>" style="width:50%;" id="btn-close" onClick="closeSystem()">ปิด</button>
        </div>
        <span class="help-block">กรณีปิดระบบจะไม่สามารถเข้าใช้งานระบบได้ในทุกส่วน โปรดใช้ความระมัดระวังในการกำหนดค่านี้</span>
      	<input type="hidden" name="CLOSED" id="closed" value="<?php echo $closed; ?>" />
      </div>
      <div class="divider-hidden"></div>

    <?php endif; ?>


      <div class="col-sm-3"><span class="form-control left-label">ข้อความแจ้งปิดระบบ</span></div>
      <div class="col-sm-9">
      	<textarea id="content" class="form-control input-sm input-500 input-line" rows="4" name="MAINTENANCE_MESSAGE" >
          <?php echo getConfig('MAINTENANCE_MESSAGE'); ?>
        </textarea>
        <span class="help-block">กำหนดข้อความที่จะแสดงบนหน้าเว็บเมื่อมีการปิดระบบ ( รองรับ HTML Code )</span>
			</div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">ID ของเว็บ</span></div>
      <div class="col-sm-9">
      	<input type="text" class="form-control input-sm input-mini input-line" name="ITEMS_GROUP" id="itemGroup" value="<?php echo getConfig('ITEMS_GROUP'); ?>" />
        <span class="help-block">กำหนดตัวเลข ID ของเว็บเพื่อใช้ในการระบุสินค้าว่ามาจากเว็บไหน ใช้ในกรณีที่มีการส่งออกรายการสินค้าไปนำเข้า POS (กรณีมีหลายเว็บ แต่ละเว็บห้ามซ้ำกัน)</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3"><span class="form-control left-label">รูปแบบบาร์โค้ด</span></div>
  		<div class="col-sm-9">
  			<?php $barcodeType = getConfig('BARCODE_TYPE'); ?>
  			<select class="form-control input-sm input-mini input-line" name="BARCODE_TYPE" id="barcodeType">
  					<option value="code39" <?php echo isSelected($barcodeType, 'code39'); ?>>CODE 39</option>
  						<option value="code93" <?php echo isSelected($barcodeType, 'code93'); ?>>CODE 93</option>
  						<option value="code128" <?php echo isSelected($barcodeType, 'code128'); ?>>CODE 128</option>
  						<option value="ean13" <?php echo isSelected($barcodeType, 'ean13'); ?>>EAN 13</option>
  				</select>
  				<span class="help-block">เลือก Format ของบาร์โค้ดที่ใช้กับเอกสารต่างๆ</span>
  		</div>
  		<div class="divider-hidden"></div>


      <div class="col-sm-9 col-sm-offset-3">
      	<button type="button" class="btn btn-sm btn-success input-mini" onClick="updateConfig('systemForm')"><i class="fa fa-save"></i> บันทึก</button>
      </div>
      <div class="divider-hidden"></div>


    </div><!--/row-->
  </form>
</div><!--/ tab pane -->
