<div class="row">
	<div class="col-sm-2 padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <label class="form-control input-sm text-center" disabled><?php echo $order->code; ?></label>
    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>วันที่</label>
			<input type="text" class="form-control input-sm text-center edit" name="date" id="date" value="<?php echo thai_date($order->date_add); ?>" disabled />
    </div>
    <div class="col-sm-5 padding-5">
    	<label>ลูกค้า</label>
			<input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" disabled />
    </div>
    <div class="col-sm-3 col-3-harf padding-5 last">
    	<label>พนักงาน</label>
      <label class="form-control input-sm" disabled><?php echo $order->user; ?></label>
    </div>
    <div class="col-sm-1 col-1-harf padding-5 first">
    	<label>ช่องทางขาย</label>
			<select class="form-control input-sm edit" name="channels" id="channels" required disabled>
				<option value="">เลือกรายการ</option>
				<?php echo select_channels($order->channels_code); ?>
			</select>

    </div>
    <div class="col-sm-1 col-1-harf padding-5">
    	<label>การชำระเงิน</label>
			<select class="form-control input-sm edit" name="payment" id="payment" required disabled>
				<option value="">เลือกรายการ</option>
				<?php echo select_payment_method($order->payment_code); ?>
			</select>
    </div>
		<div class="col-sm-1 col-1-harf padding-5">
			<label>อ้างอิง</label>
		  <input type="text" class="form-control input-sm text-center edit" value="<?php echo $order->reference; ?>" disabled />
		</div>
		<div class="col-sm-6 col-6-harf padding-5">
		 	<label>หมายเหตุ</label>
		  <input type="text" class="form-control input-sm edit" value="<?php echo $order->remark; ?>" disabled />
		</div>
		<?php if($this->pm->can_add OR $this->pm->can_edit): ?>
		<div class="col-sm-1 padding-5 last">
			<label class="display-block not-show">แก้ไข</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</i></button>
			<button type="button" class="btn btn-xs btn-success btn-block hide" id="btn-update" onclick="updateOrder()"><i class="fa fa-save"></i> บันทึก</i></button>
		</div>
		<?php endif; ?>
    <input type="hidden" name="order_code" id="order_code" value="<?php echo $order->code; ?>" />
    <input type="hidden" name="customerCode" id="customerCode" value="<?php echo $order->customer_code; ?>" />
</div>
<hr class="margin-bottom-15"/>
