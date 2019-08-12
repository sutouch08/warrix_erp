<?php
//--- ปิดใบสั่งซื้ออัตโนมัติหรือไม่
$autoClose = getConfig('PO_AUTO_CLOSE');
$btn_po_yes = $autoClose == 1 ? 'btn-success' : '';
$btn_po_no = $autoClose == 0 ? 'btn-danger' : '';
?>

<div class="tab-pane fade active in" id="general">
	<form id="generalForm">
    <div class="row">
      <div class="col-sm-3"><span class="form-control left-label">ปิดใบสั่งซื้ออัตโนมัติ</span></div>
      <div class="col-sm-9">
      	<div class="btn-group input-small">
        	<button type="button" class="btn btn-sm <?php echo $btn_po_yes; ?>" style="width:50%;" id="btn-po-yes" onClick="toggleAutoClose(1)">ใช่</button>
          <button type="button" class="btn btn-sm <?php echo $btn_po_no; ?>" style="width:50%;" id="btn-po-no" onClick="toggleAutoClose(0)">ไม่ใช่</button>
        </div>
        <span class="help-block">หากเลือก "ใช่" ใบสั่งซื้อจะถูกปิดอัตโนมัติเมื่อรับสินค้าครบตามจำนวนที่สั่งซื้อแล้ว</span>
      	<input type="hidden" name="PO_AUTO_CLOSE" id="po-auto-close" value="<?php echo $autoClose; ?>" />
      </div>
      <div class="divider-hidden"></div>


      <div class="col-sm-3"><span class="form-control left-label">รับสินค้าเกินใบสั่งซื้อ ( % )</span></div>
        <div class="col-sm-9">
        	<input type="text" class="form-control input-sm input-mini input-line" name="RECEIVE_OVER_PO" id="overPO" value="<?php echo getConfig('RECEIVE_OVER_PO'); ?>" />
          <span class="help-block">จำกัดการรับสินค้าเข้าเกินกว่ายอดในใบสั่งซื้อได้ไม่เกินกี่เปอร์เซ็น</span>
        </div>
        <div class="divider-hidden"></div>

        <div class="col-sm-9 col-sm-offset-3">
        	<button type="button" class="btn btn-sm btn-success input-mini" onClick="updateConfig('generalForm')"><i class="fa fa-save"></i> บันทึก</button>
        </div>
        <div class="divider-hidden"></div>

    </div><!--/row-->
  </form>
</div>
