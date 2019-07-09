<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6">
    <h3 class="title">
      <i class="fa fa-users"></i> <?php echo $this->title; ?>
    </h3>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right">
				<button type="button" class="btn btn-sm btn-info" onclick="syncData()"><i class="fa fa-refresh"></i> Sync</button>
      <?php if($this->pm->can_add) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="addNew()"><i class="fa fa-plus"></i> เพิมใหม่</button>
      <?php endif; ?>
      </p>
    </div>
</div><!-- End Row -->
<hr class="title-block"/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>รหัส</label>
    <input type="text" class="width-100" name="code" id="code" value="<?php echo $code; ?>" />
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label>ชื่อ</label>
    <input type="text" class="width-100" name="name" id="name" value="<?php echo $name; ?>" />
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
    <label>กลุ่มลูกค้า</label>
    <select class="form-control filter" name="group" id="customer_group">
			<option value="">ทั้งหมด</option>
			<?php echo select_customer_group($group); ?>
		</select>
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
    <label>ประเภทลูกค้า</label>
    <select class="form-control filter" name="kind" id="customer_kind">
			<option value="">ทั้งหมด</option>
			<?php echo select_customer_kind($kind); ?>
		</select>
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
    <label>ชนิดลูกค้า</label>
    <select class="form-control filter" name="type" id="customer_type">
			<option value="">ทั้งหมด</option>
			<?php echo select_customer_type($type); ?>
		</select>
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
    <label>เกรดลูกค้า</label>
    <select class="form-control filter" name="class" id="customer_class">
			<option value="">ทั้งหมด</option>
			<?php echo select_customer_class($class); ?>
		</select>
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
    <label>เขตการขาย</label>
    <select class="form-control filter" name="area" id="customer_area">
			<option value="">ทั้งหมด</option>
			<?php echo select_customer_area($area); ?>
		</select>
  </div>

  <div class="col-sm-1 col-1-harf padding-5 last">
    <label class="display-block not-show">buton</label>
		<div class="btn-group width-100">
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
			<button type="button" class="btn btn-sm btn-warning" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
		</div>
  </div>

</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>

<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="width-5 middle text-center">ลำดับ</th>
					<th class="width-10 middle">รหัส</th>
					<th class="width-15 middle">ชื่อ</th>
					<th class="width-10 middle">กลุ่มลูกค้า</th>
					<th class="width-10 middle">ประเภทลูกค้า</th>
					<th class="width-10 middle">ชนิดลูกค้า</th>
					<th class="width-10 middle">เกรด</th>
					<th class="width-10 middle">พื้นที่ขาย</th>
          <th class="width-10 middle">ปรับปรุงล่าสุด</th>
					<th class="width-5"></th>
				</tr>
			</thead>
			<tbody>
			<?php if(!empty($data)) : ?>
				<?php $no = $this->uri->segment(4) + 1; ?>
				<?php foreach($data as $rs) : ?>
					<tr>
						<td class="middle text-center"><?php echo $no; ?></td>
						<td class="middle"><?php echo $rs->code; ?></td>
						<td class="middle"><?php echo $rs->name; ?></td>
						<td class="middle"><?php echo $rs->group; ?></td>
						<td class="middle"><?php echo $rs->kind; ?></td>
						<td class="middle"><?php echo $rs->type; ?></td>
						<td class="middle"><?php echo $rs->class; ?></td>
						<td class="middle"><?php echo $rs->area; ?></td>
            <td class="middle"><?php echo thai_date($rs->date_upd, '/', TRUE); ?></td>
						<td class="text-right">
							<?php if($this->pm->can_edit) : ?>
								<button type="button" class="btn btn-mini btn-warning" onclick="getEdit('<?php echo $rs->code; ?>')">
									<i class="fa fa-pencil"></i>
								</button>
							<?php endif; ?>
							<?php if($this->pm->can_delete) : ?>
								<button type="button" class="btn btn-mini btn-danger" onclick="getDelete('<?php echo $rs->code; ?>', '<?php echo $rs->name; ?>')">
									<i class="fa fa-trash"></i>
								</button>
							<?php endif; ?>
						</td>
					</tr>
					<?php $no++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<script src="<?php echo base_url(); ?>scripts/masters/customers.js"></script>

<?php $this->load->view('include/footer'); ?>
