<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6">
    	<h3 class="title" >
        <?php echo $this->title; ?>
      </h3>
	</div>
    <div class="col-sm-6">
      	<p class="pull-right top-p">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> กลับ</button>
        </p>
    </div>
</div>
<hr />

<form id="editForm" action="<?php echo $this->home.'/update'; ?>" method="post">
<div class="row">
    <div class="col-sm-1 col-1-harf padding-5 first">
    	<label>เลขที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center eidt" value="<?php echo $doc->code; ?>" disabled />
    </div>
		<div class="col-sm-1 col-1-harf padding-5">
    	<label>วันที่</label>
      <input type="text" class="form-control input-sm text-center" name="date_add" id="dateAdd" value="<?php echo thai_date($doc->date_add, FALSE); ?>" readonly disabled/>
    </div>
		<div class="col-sm-2 padding-5">
			<label>เลขที่บิล[SAP]</label>
			<input type="text" class="form-control input-sm text-center" name="invoice" id="invoice" value="<?php echo $doc->invoice; ?>" disabled />
		</div>
		<div class="col-sm-2 padding-5">
			<label>รหัสลูกค้า</label>
			<input type="text" class="form-control input-sm text-center" name="customer_code" id="customer_code" value="<?php echo $doc->customer_code; ?>" disabled/>
		</div>
		<div class="col-sm-5 padding-5 last">
			<label>ชื่อลูกค้า</label>
			<input type="text" class="form-control input-sm" name="customer_name" id="customer_name" value="<?php echo $doc->customer_name; ?>" disabled />
		</div>
    <div class="col-sm-11 padding-5 first">
    	<label>หมายเหตุ</label>
        <input type="text" class="form-control input-sm" name="remark" id="remark" placeholder="ระบุหมายเหตุเอกสาร (ถ้ามี)" value="<?php echo $doc->remark; ?>" disabled />
    </div>
		<div class="col-sm-1 padding-5 last">
			<label class="display-block not-show">save</label>
			<?php 	if($this->pm->can_add OR $this->pm->can_edit) : ?>
							<button type="button" class="btn btn-xs btn-warning btn-block" onclick="editHeader()"><i class="fa fa-pencil"></i> แก้ไข</button>
							<button type="button" class="btn btn-xs btn-success btn-block hide" onclick="updateHeader()"><i class="fa fa-save"></i> บันทึก</button>
			<?php	endif; ?>
		</div>
</div>

<input type="hidden" id="return_code" value="<?php echo $doc->code; ?>" />
</form>
<hr class="margin-top-15"/>
<div class="row">
	<div class="col-sm-12">

		<table class="table table-striped border-1">
			<thead>
				<tr>
					<th class="width-5 text-center">ลำดับ</th>
					<th class="width-15">บาร์โค้ด</th>
					<th class="">สินค้า</th>
					<th class="width-10 text-right">ราคา</th>
					<th class="width-10 text-right">จำนวน</th>
					<th class="width-15 text-right">มูลค่า</th>
					<th class="width-5"></th>
				</tr>
			</thead>
<?php if(!empty($details)) : ?>
<?php  $no = 1; ?>
<?php  foreach($details as $rs) : ?>
				<tr id="row-<?php echo $rs->LineNum; ?>">
					<td class="middle text-center no"><?php echo $no; ?></td>
					<td class="middle"><?php echo $rs->barcode; ?></td>
					<td class="middle"><?php echo $rs->product_code .' : '.$rs->product_name; ?></td>
					<td class="middle">
						<input type="number" class="form-control input-sm text-right" id="price_<?php echo $rs->product_code; ?>" value="<?php echo number($rs->price, 2); ?>" />
					</td>
					<td class="middle">
						<input type="number" class="form-control input-sm text-right" id="qty_<?php echo $rs->product_code; ?>" value="<?php echo number($rs->qty); ?>" />
					</td>
					<td class="middle text-right"><?php echo number(($rs->price * $rs->qty),2); ?></td>
					<td class="middle text-center">
						<button type="button" class="btn btn-minier btn-danger" onclick="removeRow(<?php echo $rs->LineNum; ?>)"><i class="fa fa-trash"></i></button>
					</td>
				</tr>
<?php  	$no++; ?>
<?php  endforeach; ?>
<?php endif; ?>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/inventory/return_order/return_order.js"></script>
<script src="<?php echo base_url(); ?>scripts/inventory/return_order/return_order_add.js"></script>
<?php $this->load->view('include/footer'); ?>
