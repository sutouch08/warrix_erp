<?php $this->load->view('include/header'); ?>
<div class="row">
	<div class="col-sm-6">
    <h3 class="title">
      <?php echo $this->title; ?>
    </h3>
    </div>
    <div class="col-sm-6">
    	<p class="pull-right top-p">
      <?php if($this->pm->can_add) : ?>
        <button type="button" class="btn btn-sm btn-success" onclick="addNew()"><i class="fa fa-plus"></i> เพิมใหม่</button>
      <?php endif; ?>
      </p>
    </div>
</div><!-- End Row -->
<hr class=""/>
<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-sm-1 col-1-harf padding-5 first">
    <label>เลขที่เอกสาร</label>
    <input type="text" class="form-control input-sm search" name="code"  value="<?php echo $code; ?>" />
  </div>

  <div class="col-sm-1 col-1-harf padding-5">
    <label>คลังต้นทาง</label>
    <input type="text" class="form-control input-sm search" name="from_warehouse" value="<?php echo $from_warehouse; ?>" />
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
    <label>คลังปลายทาง</label>
    <input type="text" class="form-control input-sm search" name="to_warehouse" value="<?php echo $to_warehouse; ?>" />
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
    <label>พนักงาน</label>
    <input type="text" class="form-control input-sm search" name="user" value="<?php echo $user; ?>" />
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
    <label>สถานะ</label>
    <select class="form-control input-sm" name="status" onchange="getSearch()">
			<option value="all">ทั้งหมด</option>
			<option value="0" <?php if($status == '0'){ echo 'selected'; } ?>>ยังไม่บันทึก</option>
			<option value="1" <?php echo is_selected(1, $status); ?>>บันทึกแล้ว</option>
			<option value="2" <?php echo is_selected(2, $status); ?>>ยกเลิก</option>
		</select>
  </div>

	<div class="col-sm-2 padding-5">
    <label>วันที่</label>
    <div class="input-daterange input-group">
      <input type="text" class="form-control input-sm width-50 from-date" name="fromDate" id="fromDate" value="<?php echo $from_date; ?>" />
      <input type="text" class="form-control input-sm width-50" name="toDate" id="toDate" value="<?php echo $to_date; ?>" />
    </div>

  </div>

  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-sm-1 padding-5 last">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>
<div class="row">
	<div class="col-sm-12">
		<p  class="pull-right top-p">
			ว่างๆ = ปกติ, &nbsp; <span class="blue">NC</span> = ยังไม่บันทึก, &nbsp; <span class="red">CN</span> = ยกเลิก
		</p>
		<table class="table table-striped table-hover border-1">
			<thead>
				<tr>
					<th class="width-5 middle text-center">ลำดับ</th>
					<th class="width-10 middle text-center">วันที่</th>
					<th class="width-15 middle">เลขที่เอกสาร</th>
					<th class="width-20 middle">ต้นทาง</th>
					<th class="width-20 middle">ปลายทาง</th>
					<th class="width-15 middle">พนักงาน</th>
					<th class="width-5 middle">สถานะ</th>
					<th class="middle"></th>
				</tr>
			</thead>
			<tbody>
        <?php if(!empty($docs)) : ?>
          <?php $no = $this->uri->segment(4) + 1; ?>
          <?php foreach($docs as $rs) : ?>
            <tr id="row-<?php echo $rs->code; ?>">
              <td class="middle text-center"><?php echo $no; ?></td>
              <td class="middle text-center"><?php echo thai_date($rs->date_add); ?></td>
              <td class="middle"><?php echo $rs->code; ?></td>
              <td class="middle"><?php echo $rs->from_warehouse_name; ?></td>
              <td class="middle"><?php echo $rs->to_warehouse_name; ?></td>
              <td class="middle"><?php echo $this->user_model->get_name($rs->user); ?></td>
              <td class="middle text-center">
								<?php if($rs->status == 2) : ?>
									<span class="red">CN</span>
								<?php endif; ?>
								<?php if($rs->status == 0) : ?>
									<span class="blue">NC</span>
								<?php endif; ?>
							</td>
							<td class="middle text-right">
								<button type="button" class="btn btn-minier btn-info" onclick="goDetail('<?php echo $rs->code; ?>')"><i class="fa fa-eye"></i></button>
								<?php if($rs->status == 0 && $this->pm->can_edit) : ?>
									<button type="button" class="btn btn-minier btn-warning" onclick="goEdit('<?php echo $rs->code; ?>')"><i class="fa fa-pencil"></i></button>
								<?php endif; ?>
								<?php if($rs->status == 0 && $this->pm->can_delete) : ?>
									<button type="button" class="btn btn-minier btn-danger" onclick="goDelete('<?php echo $rs->code; ?>', <?php echo $rs->status; ?>)"><i class="fa fa-trash"></i></button>
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

<script src="<?php echo base_url(); ?>scripts/transfer/transfer.js"></script>

<?php $this->load->view('include/footer'); ?>
