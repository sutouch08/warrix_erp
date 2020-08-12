<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
				<?php if($this->pm->can_add OR $this->pm->can_edit) : ?>
				<button type="button" class="btn btn-sm btn-primary" onclick="add_item()"><i class="fa fa-plus"></i> เพิ่มสินค้า</button>
				<button type="button" class="btn btn-sm btn-success" onclick="save()"><i class="fa fa-save"></i> บันทึก</button>
				<?php endif; ?>
      </p>
    </div>
</div><!-- End Row -->
<hr class=""/>
<form id="searchForm" method="post" action="<?php echo $this->home; ?>/check">
<div class="row">
	<div class="col-sm-2 padding-5 first">
		<label>รหัสโซน</label>
		<input type="text" class="form-control input-sm" id="zone_code" name="zone_code" value="<?php echo $zone_code; ?>" <?php echo (!empty($zone_code) ? 'disabled': ''); ?>>
		<input type="hidden" name="zone_code" id="zone-code" value="<?php echo $zone_code; ?>">
	</div>
	<div class="col-sm-6 padding-5">
		<label>ชื่อโซน</label>
		<input type="text" class="form-control input-sm" id="zone_name" value="<?php echo $zone_name; ?>" disabled>
	</div>
	<div class="col-sm-1 padding-5">
		<label class="display-block not-show">btn</label>
		<button type="button" class="btn btn-xs btn-info btn-block <?php echo (!empty($zone_code) ? '' : 'hide'); ?>" id="btn-change-zone" onclick="change_zone()">เปลี่ยนโซน</button>
		<button type="button" class="btn btn-xs btn-primary btn-block <?php echo (empty($zone_code) ? '' : 'hide'); ?>" id="btn-set-zone" onclick="set_zone()"> ตรวจนับ</button>
	</div>
</div>
<hr class="margin-top-15 margin-bottom-15"/>
<div class="row">
	<div class="col-sm-3 padding-5 first">
		<input type="text" class="form-control input-sm text-center search" id="product_code" name="product_code" value="<?php echo $product_code; ?>">
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" class="btn btn-xs btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearSearch()"><i class="fa fa-retweet"></i> เคลียร์</button>
	</div>
</div>
</form>
<hr class="margin-top-15 margin-bottom-15" />
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <tr>
        <th class="width-5 text-center">ลำดับ</th>
        <th class="width-50">สินค้า</th>
        <th class="width-10">ในระบบ</th>
        <th class="width-10">นับจริง</th>
				<th class="width-5 text-center"></th>
        <th class="width-10 text-center">ยอดต่าง</th>
				<th class="width-10"></th>
      </tr>
      <tbody>
		<?php if(!empty($details)) : ?>
			<?php $no = 1; ?>
			<?php foreach($details as $rs) : ?>
				<tr>
					<td class="middle text-center">
						<?php echo $no; ?>
					</td>
					<td class="middle">
						<?php echo $rs->ItemCode; ?>
						<?php if(!empty($rs->U_OLDCODE)) : ?>
							<?php  echo " | {$rs->U_OLDCODE}"; ?>
						<?php endif; ?>
					</td>
					<td class="middle text-center">
						<span><?php echo number($rs->OnHandQty); ?></span>
						<input type="hidden" id="stock_<?php echo $rs->ItemCode;?>" value="<?php echo $rs->OnHandQty; ?>">
					</td>
					<td class="middle text-center">
						<input type="number"
						class="form-control input-sm text-center count_qty"
						name="qty[<?php echo $rs->ItemCode; ?>]"
						id="qty_<?php echo $rs->ItemCode; ?>"
						value="<?php echo $rs->count_qty; ?>"
						onkeyup="cal_diff('<?php echo $rs->ItemCode; ?>')">
					</td>
					<td class="middle text-center">
						<?php if($rs->diff_qty != 0) : ?>
							<i class="fa fa-check green"></i>
						<?php endif; ?>
					</td>
					<td class="middle text-center">
						<span id="diff_<?php echo $rs->ItemCode; ?>">
						<?php echo number($rs->diff_qty); ?>
						</span>
					</td>
					<td class="middle">
						<button type="button" class="btn btn-xs btn-info btn-block" onclick="save_diff('<?php echo $rs->ItemCode; ?>')">
							<i class="fa fa-save"></i> บันทึก
						</button>
					</td>
				</tr>
				<?php $no++; ?>
			<?php endforeach; ?>
		<?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="<?php echo base_url();?>scripts/inventory/check_stock_diff/check_stock_diff.js?v=<?php echo date('YmdH'); ?>"></script>


<?php $this->load->view('include/footer'); ?>
