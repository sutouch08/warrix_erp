<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script>
var BASE_URL = '<?php echo base_url(); ?>';
$(document).ready(function() {
  syncData();
});


function syncData(){
  var step = [
    {'name' : 'Customers', 'url' : BASE_URL +'sync_data/sync_customers'}
  ];

  setTimeout(function(){
    importData(step, 0);
  }, 100);
}


function importData(step, index){
  var ds = step[index];

  $.ajax({
    url: ds.url,
    type:'GET',
    cache:'false',
    success:function(rs){
      var rs = $.trim(rs);
      $('body').append('import : ['+index+']' + ds.name+' => '+rs+'<br/>');
      if(index == (step.length)){
        setTimeout(function(){
          window.close();}, 30000);
      }else{
        importData(step, index);
      }
    }
  });
index++;
}

</script>
