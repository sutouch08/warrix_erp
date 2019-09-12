
<div class="tab-pane fade" id="inventory">
	<form id="inventoryForm" method="post" action="<?php echo $this->home; ?>/update_config">
  	<div class="row">
    	<div class="col-sm-3">
        <span class="form-control left-label">รับสินค้าเกินไปสั่งซื้อ(%)</span>
      </div>
      <div class="col-sm-9">
        <input type="text" class="form-control input-sm input-small" name="RECEIVE_OVER_PO"  value="<?php echo $RECEIVE_OVER_PO; ?>" />
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
        <button type="button" class="btn btn-sm btn-success input-small" onClick="updateConfig('inventoryForm')">
          <i class="fa fa-save"></i> บันทึก
        </button>
      </div>
      <div class="divider-hidden"></div>

  	</div><!--/ row -->
  </form>
</div>
