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
				<button type="button" class="btn btn-sm btn-success" onclick="goToCheck()"><i class="fa fa-plus"></i> ตรวจนับสต็อก</button>
				<?php endif; ?>
      </p>
    </div>
</div><!-- End Row -->
<hr class=""/>

<form id="searchForm" method="post" action="<?php echo current_url(); ?>">
<div class="row">
  <div class="col-sm-2 padding-5 first">
    <label>รหัสสินค้า</label>
    <input type="text" class="form-control input-sm search" id="product_code" name="product_code"  value="<?php echo $product_code; ?>" />
  </div>

  <div class="col-sm-2 padding-5">
    <label>โซน</label>
    <input type="text" class="form-control input-sm search" id="zone_code" name="zone_code" value="<?php echo $zone_code; ?>" />
  </div>
	<div class="col-sm-2 padding-5">
    <label>สถานะ</label>
    <select class="form-control input-sm" id="status" name="status" onchange="getSearch()">
			<option value="all">ทั้งหมด</option>
			<option value="0" <?php echo is_selected('0', $status); ?>>ยังไม่ปรับยอด</option>
			<option value="1" <?php echo is_selected('1', $status); ?>>ปรับยอดแล้ว</option>
		</select>
  </div>
  <div class="col-sm-1 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="submit" class="btn btn-xs btn-primary btn-block"><i class="fa fa-search"></i> ค้นหา</button>
  </div>
	<div class="col-sm-1 padding-5">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
  </div>
  <div class="col-sm-1 col-1-harf padding-5 last">
    <label class="display-block not-show">buton</label>
    <button type="button" class="btn btn-xs btn-purple btn-block" onclick="doExport()"><i class="fa fa-file-excel-o"></i> Download</button>
  </div>
</div>
<hr class="margin-top-15">
</form>
<form id="exportFrom" method="post" action="<?php echo $this->home; ?>/export">
  <input type="hidden" id="product" name="product">
  <input type="hidden" id="zone" name="zone">
	<input type="hidden" id="status" name="status">
  <input type="hidden" id="token" name="token" value="<?php echo uniqid(); ?>">
</form>
<?php echo $this->pagination->create_links(); ?>
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped border-1">
      <tr>
        <th class="width-5 text-center">ลำดับ</th>
        <th class="width-25">สินค้า</th>
        <th class="width-20">รหัสโซน</th>
        <th class="width-40">ชื่อโซน</th>
        <th class="width-10 text-center">ยอดต่าง</th>
      </tr>
      <tbody>
    <?php if( !empty($data)) : ?>
    <?php $no = $this->uri->segment(4) + 1; ?>
    <?php foreach($data as $rs) : ?>
      <tr class="font-size-12">
        <td class="text-center no"><?php echo $no; ?></td>
        <td>
          <?php echo $rs->product_code; ?>
          <?php
            if(!empty($rs->old_code))
            {
              echo " | ".$rs->old_code;
            }
            ?>
        </td>
        <td><?php echo $rs->zone_code; ?></td>
        <td><?php echo $rs->zone_name; ?></td>
    		<td class="text-center"><?php echo number($rs->qty); ?></td>
      </tr>
    <?php  $no++; ?>
    <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="5" class="text-center">--- ไม่พบข้อมูล ---</td>
      </tr>
    <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="<?php echo base_url(); ?>scripts/inventory/check_stock_diff/check_stock_diff.js?<?php echo date('YmdH'); ?>"></script>
<script>
  function doExport(){
    var item = $('#item_code').val();
    var zone = $('#zone_code').val();
		var system = $('#show_system').val();
    var token = $('#token').val();
    if(item.length > 0 || zone.length > 0)
    {
      $('#item').val(item);
      $('#zone').val(zone);
			$('#system').val(system);
      get_download(token);
      $('#exportFrom').submit();
    }
  }
</script>

<?php $this->load->view('include/footer'); ?>
