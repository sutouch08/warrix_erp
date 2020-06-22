<?php $this->load->view('include/header'); ?>
<div class="row">
  <?php if($WT->can_approve) : ?>
  <div class="col-sm-6">
  <?php $this->load->view('dashboard/consign_tr_panel'); ?>
  </div>
  <?php endif; ?>

  <?php if($WT->can_approve) : ?>
  <div class="col-sm-6">
  <?php $this->load->view('dashboard/consign_tr_receive_panel'); ?>
  </div>
  <?php endif; ?>
</div>
<div class="row">
  <?php if($WC->can_approve) : ?>
  <div class="col-sm-6">
  <?php $this->load->view('dashboard/consign_panel'); ?>
  </div>
  <?php endif; ?>
</div>

<script src="<?php echo base_url(); ?>scripts/dashboard/dashboard.js?token=<?php echo date('Ymd'); ?>"></script>

<?php $this->load->view('include/footer'); ?>
