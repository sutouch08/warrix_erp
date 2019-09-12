<?php $this->load->view('include/header'); ?>

<div class="row">
	<div class="col-sm-6">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
	<div class="col-sm-6">
		<p class="pull-right">
			<button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i> Back</button>
		</p>
	</div>
</div><!-- End Row -->
<hr/>
<div class="row">
	<div class="col-sm-3 padding-5 first">
		<label>รหัสโซน</label>
		<input type="text" class="form-control input-sm" value="<?php echo $ds->code; ?>" readonly disabled />
	</div>

	<div class="col-sm-6 padding-5">
		<label>ชื่อโซน</label>
		<input type="text" class="form-control input-sm" value="<?php echo $ds->name; ?>" readonly disabled />
	</div>

	<div class="col-sm-3 padding-5">
		<label>คลังสินค้า</label>
		<input type="text" class="form-control input-sm" value="<?php echo $ds->warehouse_name; ?>" readonly disabled />
	</div>
</div>
<hr class="margin-top-10 margin-bottom-15">
<div class="row">
	<div class="col-sm-4 padding-5 first">
		<input type="text" class="form-control input-sm" id="search-box" placeholder="ค้นหาลูกค้า" autofocus>
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" class="btn btn-xs btn-primary" onclick="addCustomer()">
			<i class="fa fa-plus"></i> เพิ่มลูกค้า
		</button>
	</div>
</div>
<hr class="margin-top-10 margin-bottom-15">
<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped border-1">
			<thead>
				<tr>
					<th class="width-5 text-center">No.</th>
					<th class="width-15">รหัสลูกค้า</th>
					<th class="">ชิ้อลูกค้า</th>
					<th class="width-10"></th>
				</tr>
			</thead>
			<tbody id="cust-table">
<?php if(!empty($customers)) : ?>
	<?php $no = 1; ?>
	<?php foreach($customers as $rs) : ?>
				<tr id="row-<?php echo $rs->id; ?>">
					<td class="middle text-center"><?php echo $no; ?></td>
					<td class="middle"><?php echo $rs->customer_code; ?></td>
					<td class="middle"><?php echo $rs->customer_name; ?></td>
					<td class="middle text-right">
			<?php if($this->pm->can_edit) : ?>
						<button type="button" class="btn btn-xs btn-danger" onclick="deleteCustomer(<?php echo $rs->id; ?>, '<?php echo $rs->customer_code; ?>')">
							<i class="fa fa-trash"></i>
						</button>
			<?php endif; ?>
					</td>
				</tr>
		<?php $no++; ?>
	<?php endforeach; ?>
<?php else : ?>
				<tr>
					<td colspan="4" class="text-center">--- No customer ---</td>
				</tr>
<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<input type="hidden" id="customer_code" value="" >
<input type="hidden" id="zone_code" value="<?php echo $ds->code; ?>">
<script src="<?php echo base_url(); ?>scripts/masters/zone.js"></script>
<?php $this->load->view('include/footer'); ?>
