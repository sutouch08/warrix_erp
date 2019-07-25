<?php $this->load->view('include/header'); ?>
<?php
$add = $this->pm->can_add;
$edit = $this->pm->can_edit;
$delete = $this->pm->can_delete;

 ?>
<div class="row top-row">
	<div class="col-sm-6 top-col">
    	<h4 class="title"><i class="fa fa-shopping-bag"></i> <?php echo $this->title; ?></h4>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">

        	<button type="button" class="btn btn-sm btn-warning" onClick="editOrder('<?php echo $order->code; ?>')"><i class="fa fa-arrow-left"></i> กลับ</button>
          <button type="button" class="btn btn-sm btn-success" id="btn-save-order" onclick="saveOrder()"><i class="fa fa-save"></i> บันทึก</button>
        </p>
    </div>
</div>
<hr class="margin-top-10 margin-bottom-15" />
<?php $this->load->view('orders/order_edit_header'); ?>

<!--  Search Product -->
<div class="row">
	<div class="col-sm-3 padding-5 first">
    	<input type="text" class="form-control input-sm text-center" id="pd-box" placeholder="ค้นรหัสสินค้า" />
  </div>
  <div class="col-sm-2 padding-5">
  	<button type="button" class="btn btn-xs btn-primary btn-block" onclick="getProductGrid()"><i class="fa fa-tags"></i> แสดงสินค้า</button>
  </div>
    <div class="col-sm-5"></div>
    <div class="col-sm-2">
    	<button type="button" class="btn btn-xs btn-info btn-block" onclick="recalDiscount()">
        	<i class="fa fa-calculator"></i> คำนวณส่วนลดใหม่</button>
        </button>
    </div>
</div>
<hr class="margin-top-15 margin-bottom-0" />
<!--- Category Menu ---------------------------------->
<div class='row'>
	<div class='col-sm-12'>
		<ul class='nav navbar-nav' role='tablist' style='background-color:#EEE'>
		<?php echo productTabMenu('order'); ?>
		</ul>
	</div><!---/ col-sm-12 ---->
</div><!---/ row -->
<hr style='border-color:#CCC; margin-top: 0px; margin-bottom:0px;' />
<div class='row'>
	<div class='col-sm-12'>
		<div class='tab-content' style="min-height:1px; padding:0px; border:0px;">
		<?php echo getProductTabs(); ?>
		</div>
	</div>
</div>
<!-- End Category Menu ------------------------------------>

<?php $this->load->view('orders/order_detail'); //include 'include/order/order_detail.php'; ?>


<form id="orderForm">
<div class="modal fade" id="orderGrid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" id="modal">
		<div class="modal-content">
  			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modalTitle">title</h4>
        <center><span style="color: red;">ใน ( ) = ยอดคงเหลือทั้งหมด   ไม่มีวงเล็บ = สั่งได้ทันที</span></center>
			 </div>
			 <div class="modal-body" id="modalBody"></div>
			 <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
				<button type="button" class="btn btn-primary" onClick="addToOrder()" >เพิ่มในรายการ</button>
			 </div>
		</div>
	</div>
</div>
</form>

<script src="<?php echo base_url(); ?>scripts/orders/orders.js"></script>
<script src="<?php echo base_url(); ?>scripts/orders/order_add.js"></script>
<script src="<?php echo base_url(); ?>scripts/orders/product_tab_menu.js"></script>
<script src="<?php echo base_url(); ?>scripts/orders/order_grid.js"></script>

<?php $this->load->view('include/footer'); ?>
