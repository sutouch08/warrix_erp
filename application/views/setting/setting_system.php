<div class="tab-pane fade" id="system">
<?php
    $open     = $CLOSE_SYSTEM == 0 ? 'btn-success' : '';
    $close    = $CLOSE_SYSTEM == 1 ? 'btn-danger' : '';
    $freze    = $CLOSE_SYSTEM == 2 ? 'btn-warning' : '';

    $manual_code_yes = $MANUAL_DOC_CODE == 1 ? 'btn-success' : '';
    $manual_code_no = $MANUAL_DOC_CODE == 0 ? 'btn-danger' : '';

    $use_api_yes = $WEB_API == 1 ? 'btn-success' : '';
    $use_api_no  = $WEB_API == 0 ? 'btn-danger' : '';
?>

  <form id="systemForm">
    <div class="row">
  	<?php if( $cando === TRUE ): //---- ถ้ามีสิทธิ์ปิดระบบ ---//	?>
    	<div class="col-sm-3"><span class="form-control left-label">ปิดระบบ</span></div>
      <div class="col-sm-9">
      	<div class="btn-group input-xlarge">
        	<button type="button" class="btn btn-sm <?php echo $open; ?>" style="width:33%;" id="btn-open" onClick="openSystem()">เปิด</button>
          <button type="button" class="btn btn-sm <?php echo $close; ?>" style="width:33%;" id="btn-close" onClick="closeSystem()">ปิด</button>
          <button type="button" class="btn btn-sm <?php echo $freze; ?>" style="width:34%" id="btn-freze" onclick="frezeSystem()">ดูอย่างเดียว</button>
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


    <div class="col-sm-3"><span class="form-control left-label">ใช้งาน Web API</span></div>
    <div class="col-sm-9">
      <div class="btn-group input-medium">
        <button type="button" class="btn btn-sm <?php echo $use_api_yes; ?>" style="width:50%;" id="btn-api-yes" onClick="toggleWebApi(1)">เปิด</button>
        <button type="button" class="btn btn-sm <?php echo $use_api_no; ?>" style="width:50%;" id="btn-api-no" onClick="toggleWebApi(0)">ปิด</button>
      </div>
      <span class="help-block">เปิดใช้งาน WEB API หรือไม่</span>
      <input type="hidden" name="WEB_API" id="web-api" value="<?php echo $WEB_API; ?>" />
    </div>
    <div class="divider-hidden"></div>


    <div class="col-sm-3">
      <span class="form-control left-label">Web API Host</span>
    </div>
    <div class="col-sm-9">
      <input type="text" class="form-control input-sm" name="WEB_API_HOST" id="web-api-host" placeholder="https://api.xxxx.com/rest/V1/" value="<?php echo $WEB_API_HOST; ?>" />
      <span class="help-block">URL สำหรับ API gate way</span>
    </div>
    <div class="divider-hidden"></div>


    <div class="col-sm-3">
      <span class="form-control left-label">Web API Access Token</span>
    </div>
    <div class="col-sm-5">
      <input type="text" class="form-control input-sm" name="WEB_API_ACCESS_TOKEN"  value="<?php echo $WEB_API_ACCESS_TOKEN; ?>" />
      <span class="help-block">Api access token</span>
    </div>
    <div class="divider-hidden"></div>


      <div class="col-sm-9 col-sm-offset-3">
        <?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
      	<button type="button" class="btn btn-sm btn-success" onClick="updateConfig('systemForm')"><i class="fa fa-save"></i> บันทึก</button>
        <?php endif; ?>
      </div>
      <div class="divider-hidden"></div>

    </div><!--/row-->
  </form>
</div><!--/ tab pane -->
