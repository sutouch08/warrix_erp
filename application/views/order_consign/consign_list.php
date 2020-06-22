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
    <label>ลูกค้า</label>
    <input type="text" class="form-control input-sm search" name="customer" value="<?php echo $customer; ?>" />
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
    <label>พนักงาน</label>
    <input type="text" class="form-control input-sm search" name="user" value="<?php echo $user; ?>" />
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
    <label>โซน</label>
		<input type="text" class="form-control input-sm search" name="zone" value="<?php echo $zone_code; ?>" />
  </div>

	<div class="col-sm-1 col-1-harf padding-5">
		<label>การอนุมัติ</label>
		<select class="form-control input-sm" name="isApprove" onchange="getSearch()">
			<option value="all">ทั้งหมด</option>
			<option value="0" <?php echo is_selected($isApprove, "0"); ?>>รออนุมัติ</option>
			<option value="1" <?php echo is_selected($isApprove, "1"); ?>>อนุมัติแล้ว</option>
		</select>
	</div>

	<?php if($this->menu_code == 'SOCCTR') : ?>
	<div class="col-sm-1 col-1-harf padding-5">
		<label>การยืนยัน</label>
		<select class="form-control input-sm" name="isValid" onchange="getSearch()">
			<option value="all">ทั้งหมด</option>
			<option value="0" <?php echo is_selected($isValid, "0"); ?>>ยังไม่ยืนยัน</option>
			<option value="1" <?php echo is_selected($isValid, "1"); ?>>ยืนยันแล้ว</option>
		</select>
	</div>
	<?php endif; ?>

	<div class="col-sm-2 padding-5">
    <label>วันที่</label>
    <div class="input-daterange input-group">
      <input type="text" class="form-control input-sm width-50 from-date" name="fromDate" id="fromDate" value="<?php echo $from_date; ?>" />
      <input type="text" class="form-control input-sm width-50" name="toDate" id="toDate" value="<?php echo $to_date; ?>" />
    </div>
  </div>


</div>
<div class="row margin-top-10">
	<div class="col-sm-1 padding-5 first">
		<button type="button" id="btn-state-3" class="btn btn-sm btn-block <?php echo $btn['state_3']; ?>" onclick="toggleState(3)">รอจัด</button>
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" id="btn-state-4" class="btn btn-sm btn-block <?php echo $btn['state_4']; ?>" onclick="toggleState(4)">กำลังจัด</button>
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" id="btn-state-5" class="btn btn-sm btn-block <?php echo $btn['state_5']; ?>" onclick="toggleState(5)">รอตรวจ</button>
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" id="btn-state-6" class="btn btn-sm btn-block <?php echo $btn['state_6']; ?>" onclick="toggleState(6)">กำลังตรวจ</button>
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" id="btn-state-7" class="btn btn-sm btn-block <?php echo $btn['state_7']; ?>" onclick="toggleState(7)">รอเปิดบิล</button>
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" id="btn-state-8" class="btn btn-sm btn-block <?php echo $btn['state_8']; ?>" onclick="toggleState(8)">เปิดบิลแล้ว</button>
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" id="btn-state-9" class="btn btn-sm btn-block <?php echo $btn['state_9']; ?>" onclick="toggleState(9)">ยกเลิก</button>
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" id="btn-not-save" class="btn btn-sm btn-block <?php echo $btn['not_save']; ?>" onclick="toggleNotSave()">ไม่บันทึก</button>
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" id="btn-expire" class="btn btn-sm btn-block <?php echo $btn['is_expire']; ?>" onclick="toggleIsExpire()">หมดอายุ</button>
	</div>
	<div class="col-sm-1 padding-5">
		<button type="button" id="btn-only-me" class="btn btn-sm btn-block <?php echo $btn['only_me']; ?>" onclick="toggleOnlyMe()">เฉพาะฉัน</button>
	</div>
	<div class="col-sm-1 padding-5">
    <button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
  </div>
	<div class="col-sm-1 padding-5 last">
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
</div>
<input type="hidden" name="state_1" id="state_1" value="<?php echo $state[1]; ?>" />
<input type="hidden" name="state_2" id="state_2" value="<?php echo $state[2]; ?>" />
<input type="hidden" name="state_3" id="state_3" value="<?php echo $state[3]; ?>" />
<input type="hidden" name="state_4" id="state_4" value="<?php echo $state[4]; ?>" />
<input type="hidden" name="state_5" id="state_5" value="<?php echo $state[5]; ?>" />
<input type="hidden" name="state_6" id="state_6" value="<?php echo $state[6]; ?>" />
<input type="hidden" name="state_7" id="state_7" value="<?php echo $state[7]; ?>" />
<input type="hidden" name="state_8" id="state_8" value="<?php echo $state[8]; ?>" />
<input type="hidden" name="state_9" id="state_9" value="<?php echo $state[9]; ?>" />
<input type="hidden" name="notSave" id="notSave" value="<?php echo $notSave; ?>" />
<input type="hidden" name="onlyMe" id="onlyMe" value="<?php echo $onlyMe; ?>" />
<input type="hidden" name="isExpire" id="isExpire" value="<?php echo $isExpire; ?>" />
<hr class="margin-top-15">
</form>
<?php echo $this->pagination->create_links(); ?>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="width-5 middle text-center">ลำดับ</th>
					<th class="width-10 middle text-center">วันที่</th>
					<th class="width-15 middle">เลขที่เอกสาร</th>
					<th class="middle">ลูกค้า</th>
					<th class="width-15 middle">โซน</th>
					<th class="width-10 middle">ยอดเงิน</th>
					<th class="width-10 middle">สถานะ</th>
				</tr>
			</thead>
			<tbody>
        <?php if(!empty($orders)) : ?>
          <?php $no = $this->uri->segment(4) + 1; ?>
          <?php foreach($orders as $rs) : ?>
            <tr id="row-<?php echo $rs->code; ?>" class="font-size-12" style="<?php echo state_color($rs->state, $rs->status, $rs->is_expired); ?>">
              <td class="middle text-center pointer" onclick="editOrder('<?php echo $rs->code; ?>')"><?php echo $no; ?></td>
              <td class="middle text-center pointer" onclick="editOrder('<?php echo $rs->code; ?>')"><?php echo thai_date($rs->date_add); ?></td>
              <td class="middle pointer" onclick="editOrder('<?php echo $rs->code; ?>')"><?php echo $rs->code; ?></td>
              <td class="middle pointer" onclick="editOrder('<?php echo $rs->code; ?>')"><?php echo $rs->customer_name; ?></td>
							<td class="middle pointer" onclick="editOrder('<?php echo $rs->code; ?>')"><?php echo $rs->zone_name; ?></td>
              <td class="middle pointer" onclick="editOrder('<?php echo $rs->code; ?>')"><?php echo number($rs->total_amount, 2); ?></td>
              <td class="middle pointer" onclick="editOrder('<?php echo $rs->code; ?>')"><?php echo $rs->state_name; ?></td>
              </td>
            </tr>
            <?php $no++; ?>
          <?php endforeach; ?>
        <?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<?php if($this->menu_code == 'SOCCSO') : ?>
<script src="<?php echo base_url(); ?>scripts/order_consign/consign.js"></script>
<?php else : ?>
<script src="<?php echo base_url(); ?>scripts/order_consign/consign_tr.js"></script>
<?php endif; ?>
<?php $this->load->view('include/footer'); ?>
