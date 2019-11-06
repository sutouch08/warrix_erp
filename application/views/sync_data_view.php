<?php $this->load->view('include/header'); ?>
<div class="row">
  <div class="col-sm-6">
    <h3 class="title"><?php echo $this->title; ?></h3>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-sm-12 col-xs-12">
    <a href="<?php echo base_url(); ?>sync_data/export_products_attribute" class="btn btn-app btn-primary no-radius">Export <br/>Attrubute</a>
    <a href="<?php echo base_url(); ?>sync_data/export_products" class="btn btn-app btn-primary no-radius">Export <br/>Items</a>
  </div>
</div>
<?php $this->load->view('include/footer'); ?>
