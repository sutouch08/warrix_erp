
<?php if($this->WT->can_approve) : ?>
<form id="receive-form" method="post" target="_blank" action="<?php echo base_url(); ?>inventory/invoice">
  <input type="hidden" name="role" value="N">
  <input type="hidden" name="is_valid" value="0" >
</form>
<form id="consign_tr-form" method="post" target="_blank" action="<?php echo base_url(); ?>orders/consign_tr">
  <input type="hidden" name="isApprove" value="0">
</form>
<?php endif; ?>

<?php if($this->WS->can_approve) : ?>
<form id="sponsor-form" method="post" target="_blank" action="<?php echo base_url(); ?>orders/sponsor">
  <input type="hidden" name="isApprove" value="0">
</form>
<?php endif; ?>
