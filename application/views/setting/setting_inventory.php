<?php $receive_due_yes = $RECEIVE_OVER_DUE == 1 ? 'btn-success' : ''; ?>
<?php $receive_due_no  = $RECEIVE_OVER_DUE == 0 ? 'btn-danger' : ''; ?>
<?php $auz_no = $ALLOW_UNDER_ZERO == 0 ? 'btn-success' : ''; ?>
<?php $auz_yes = $ALLOW_UNDER_ZERO == 1 ? 'btn-danger' : ''; ?>
<div class="tab-pane fade" id="inventory">
	<form id="inventoryForm" method="post" action="<?php echo $this->home; ?>/update_config">
  	<div class="row">
			<div class="col-sm-3">
        <span class="form-control left-label">สต็อกติดลบได้</span>
      </div>
      <div class="col-sm-9">
				<div class="btn-group input-medium">
        	<button type="button" class="btn btn-sm <?php echo $auz_no; ?>" style="width:50%;" id="btn-auz-no" onClick="toggleAuz(0)">ไม่ได้</button>
          <button type="button" class="btn btn-sm <?php echo $auz_yes; ?>" style="width:50%;" id="btn-auz-yes" onClick="toggleAuz(1)">ได้</button>
        </div>
        <span class="help-block">อนุญาติให้สต็อกติดลบได้</span>
        <input type="hidden" name="ALLOW_UNDER_ZERO" id="allow-under-zero" value="<?php echo $ALLOW_UNDER_ZERO; ?>" />
      </div>
      <div class="divider-hidden"></div>

    	<div class="col-sm-3">
        <span class="form-control left-label">รับสินค้าเกินไปสั่งซื้อ(%)</span>
      </div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-small text-center" name="RECEIVE_OVER_PO"  value="<?php echo $RECEIVE_OVER_PO; ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3">
        <span class="form-control left-label">การรับสินค้าเกิน Due</span>
      </div>
      <div class="col-sm-9">
				<div class="btn-group input-medium">
        	<button type="button" class="btn btn-sm <?php echo $receive_due_yes; ?>" style="width:50%;" id="btn-receive-yes" onClick="toggleReceiveDue(1)">รับ</button>
          <button type="button" class="btn btn-sm <?php echo $receive_due_no; ?>" style="width:50%;" id="btn-receive-no" onClick="toggleReceiveDue(0)">ไม่รับ</button>
        </div>
        <span class="help-block">รับหรือไม่รับสินค้าจากใบสั่งซื้อที่เกิน Due date ในใบสั่งซื้อ</span>
      	<input type="hidden" name="RECEIVE_OVER_DUE" id="receive-over-due" value="<?php echo $RECEIVE_OVER_DUE; ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3">
        <span class="form-control left-label">เกินกำหนดรับได้(วัน)</span>
      </div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-small text-center" name="PO_VALID_DAYS"  value="<?php echo $PO_VALID_DAYS; ?>" />
				<span class="help-block">รับสินค้าเกิน Due date ในใบสั่งซื้อได้ไม่เกินจำนวนวันที่กำหนด เช่น กำหนด 30 วัน กำหนดรับวันที่ 30/09 จะรับสินค้าได้ไม่เกินวันที่ 30/10</span>
      </div>
      <div class="divider-hidden"></div>

      <div class="col-sm-3">
        <span class="form-control left-label">รหัสคลังสินค้าระหว่างทำ</span>
      </div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-small" name="TRANSFORM_WAREHOUSE" value="<?php echo $TRANSFORM_WAREHOUSE; ?>" />
      </div>
      <div class="divider-hidden"></div>

			<div class="col-sm-3">
        <span class="form-control left-label">รหัสคลังยืมสินค้า</span>
      </div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-small" name="LEND_WAREHOUSE" value="<?php echo $LEND_WAREHOUSE; ?>" />
      </div>
      <div class="divider-hidden"></div>


      <div class="col-sm-9 col-sm-offset-3">
				<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
        <button type="button" class="btn btn-sm btn-success input-small" onClick="updateConfig('inventoryForm')">
          <i class="fa fa-save"></i> บันทึก
        </button>
				<?php endif; ?>
      </div>
      <div class="divider-hidden"></div>

  	</div><!--/ row -->
  </form>
</div>
