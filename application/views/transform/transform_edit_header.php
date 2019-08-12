<div class="row">
	<div class="col-sm-1 col-1-harf padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center" value="<?php echo $order->code; ?>" disabled />
    </div>
    <div class="col-sm-1 padding-5">
    	<label>วันที่</label>
			<input type="text" class="form-control input-sm text-center edit" name="date" id="date" value="<?php echo thai_date($order->date_add); ?>" disabled />
    </div>
    <div class="col-sm-4 padding-5">
    	<label>ลูกค้า[ในระบบ]</label>
			<input type="text" class="form-control input-sm edit" id="customer" name="customer" value="<?php echo $order->customer_name; ?>" required disabled />
    </div>
    <div class="col-sm-3 col-3-harf padding-5">
    	<label>ผู้เบิก[คนสั่ง]</label>
      <input type="text" class="form-control input-sm edit" id="user_ref" name="user_ref" value="<?php echo $order->user_ref; ?>" disabled />
    </div>

		<div class="col-sm-2 padding-5">
	    <label>วัตถุประสงค์</label>
	    <select class="form-control input-sm" name="role" id="role" disabled>
	    	<option value="">โปรดเลือก</option>
				<option value="1" <?php echo is_selected(1, $order->tf_role); ?>>เพื่อขาย</option>
				<option value="2" <?php echo is_selected(2, $order->tf_role); ?>>เพื่ออภินันท์</option>
				<option value="3" <?php echo is_selected(3, $order->tf_role); ?>>เพื่อสต็อก</option>
	    </select>
	  </div>

		<div class="col-sm-11 padding-5 first">
		 	<label>หมายเหตุ</label>
		  <input type="text" class="form-control input-sm edit" name="remark" id="remark" value="<?php echo $order->remark; ?>" disabled />
		</div>
		<?php if($order->state < 8 && ($this->pm->can_add OR $this->pm->can_edit)): ?>
		<div class="col-sm-1 padding-5 last">
			<label class="display-block not-show">แก้ไข</label>
			<button type="button" class="btn btn-xs btn-warning btn-block" id="btn-edit" onclick="getEdit()"><i class="fa fa-pencil"></i> แก้ไข</i></button>
			<button type="button" class="btn btn-xs btn-success btn-block hide" id="btn-update" onclick="validUpdate()"><i class="fa fa-save"></i> บันทึก</i></button>
		</div>
		<?php endif; ?>
    <input type="hidden" name="order_code" id="order_code" value="<?php echo $order->code; ?>" />
    <input type="hidden" name="customerCode" id="customerCode" value="<?php echo $order->customer_code; ?>" />
</div>
<hr class="margin-bottom-15"/>
