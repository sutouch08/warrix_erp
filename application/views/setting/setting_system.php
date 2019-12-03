<div class="tab-pane fade" id="system">
<?php
    $open     = $CLOSE_SYSTEM == 0 ? 'btn-success' : '';
    $close    = $CLOSE_SYSTEM == 1 ? 'btn-danger' : '';

    $manual_code_yes = $MANUAL_DOC_CODE == 1 ? 'btn-success' : '';
    $manual_code_no = $MANUAL_DOC_CODE == 0 ? 'btn-danger' : '';
?>

  <form id="systemForm">
    <div class="row">
  	<?php if( $cando === TRUE ): //---- ถ้ามีสิทธิ์ปิดระบบ ---//	?>
    	<div class="col-sm-3"><span class="form-control left-label">ปิดระบบ</span></div>
      <div class="col-sm-9">
      	<div class="btn-group input-medium">
        	<button type="button" class="btn btn-sm <?php echo $open; ?>" style="width:50%;" id="btn-open" onClick="openSystem()">เปิด</button>
          <button type="button" class="btn btn-sm <?php echo $close; ?>" style="width:50%;" id="btn-close" onClick="closeSystem()">ปิด</button>
        </div>
        <span class="help-block">กรณีปิดระบบจะไม่สามารถเข้าใช้งานระบบได้ในทุกส่วน โปรดใช้ความระมัดระวังในการกำหนดค่านี้</span>
      	<input type="hidden" name="CLOSE_SYSTEM" id="closed" value="<?php echo $CLOSE_SYSTEM; ?>" />
      </div>
      <div class="divider-hidden"></div>

    <?php endif; ?>

    <div class="col-sm-3"><span class="form-control left-label">ป้อนเลขที่เอกสารเอง</span></div>
    <div class="col-sm-9">
      <div class="btn-group input-medium">
        <button type="button" class="btn btn-sm <?php echo $manual_code_yes; ?>" style="width:50%;" id="btn-manual-yes" onClick="toggleManualCode(1)">เปิด</button>
        <button type="button" class="btn btn-sm <?php echo $manual_code_no; ?>" style="width:50%;" id="btn-manual-no" onClick="toggleManualCode(0)">ปิด</button>
      </div>
      <span class="help-block">เปิดการป้อนเลขที่เอกสารด้วยมือ หากปิดระบบจะรับเลขที่เอกสารให้อัตโนมัติ</span>
      <input type="hidden" name="MANUAL_DOC_CODE" id="manual-doc-code" value="<?php echo $MANUAL_DOC_CODE; ?>" />
    </div>
    <div class="divider-hidden"></div>


      <div class="col-sm-9 col-sm-offset-3">
      	<button type="button" class="btn btn-sm btn-success" onClick="updateConfig('systemForm')"><i class="fa fa-save"></i> บันทึก</button>
      </div>
      <div class="divider-hidden"></div>

    </div><!--/row-->
  </form>
</div><!--/ tab pane -->
