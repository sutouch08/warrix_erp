<script>
  var refresh_rate = 300000;
  var limit_rows = 4;
</script>
<?php

if($this->WC->can_approve)
{
  $this->load->view('notifications/consign_so');
}

if($this->WT->can_approve)
{
  $this->load->view('notifications/consign_tr');
  $this->load->view('notifications/consign_receive');
}


?>
