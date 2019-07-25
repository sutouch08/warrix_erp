<?php $this->load->view('include/header'); ?>
<div class="row top-row">
	<div class="col-sm-3 top-col">
    <h3 class="title">
      <i class="fa fa-shopping-basket"></i> <?php echo $this->title; ?>
    </h3>
    </div>
    <div class="col-sm-9">
    	<p class="pull-right top-p">
				<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
				<?php if($order->is_term == 0 && ($this->pm->can_add OR $this->pm->can_edit)) : ?>
				<button type="button" class="btn btn-sm btn-info" onclick="payOrder()"><i class="fa fa-credit-card"></i> แจ้งชำระเงิน</button>
				<?php endif; ?>
				<button type="button" class="btn btn-sm btn-purple" onclick="getSummary()"><i class="fa fa-bolt"></i> สรุปข้อมูล</button>
				<button type="button" class="btn btn-sm btn-default" onclick="printOrderSheet()"><i class="fa fa-print"></i> พิมพ์</button>
				<?php if($this->pm->can_delete) : ?>
				<button type="button" class="btn btn-sm btn-primary" onclick="never_expire()">ยกเว้นการหมดอายุ</button>
				<?php endif; ?>
				<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
				<button type="button" class="btn btn-sm btn-yellow" onclick="editDetail()"><i class="fa fa-pencil"></i> แก้ไขรายการ</button>
				<button type="button" class="btn btn-sm btn-success" onclick="saveOrder()"><i class="fa fa-save"></i> บันทึก</button>
				<?php endif; ?>
      </p>
    </div>
</div><!-- End Row -->
<hr class=""/>
<input type="hidden" id="order_code" value="<?php echo $order->code; ?>" />
<?php $this->load->view('orders/order_edit_header'); ?>
<?php $this->load->view('orders/order_panel'); ?>

<script src="<?php echo base_url(); ?>scripts/orders/orders.js"></script>

<?php $this->load->view('include/footer'); ?>
