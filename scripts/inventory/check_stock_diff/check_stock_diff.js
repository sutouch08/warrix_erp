var HOME = BASE_URL + 'inventory/check_stock_diff/';

function goBack(){
  window.location.href = HOME;
}


function getSearch(){
  $('#searchForm').submit();
}

function clearFilter()
{
  $.get(HOME + 'clear_filter', function(){
    goBack();
  });
}


function clearSearch(){
  $('#product_code').val('');
  getSearch();
}



function goToCheck(){
  window.location.href = HOME + 'check';
}



$('.search').keyup(function(e){
  if(e.keyCode == 13){
    var item = $('#product_code').val();
    var zone = $('#zone_code').val();
    if(item.length > 0 || zone.length > 0){
      getSearch();
    }
  }
})


$('#zone_code').keyup(function(e){
  if(e.keyCode == 13){
    set_zone();
  }
});


$('#zone_code').autocomplete({
  source:BASE_URL + 'auto_complete/get_zone_code_and_name',
  autoFocus:true,
  close:function(){
    var rs = $(this).val();
    var arr = rs.split(' | ');
    if(arr.length == 2){
      $(this).val(arr[0]);
      $('#zone-code').val(arr[0]);
      $('#zone_name').val(arr[1]);
    }
  }
})

function set_zone()
{
  var zone_code = $('#zone_code').val();
  $.ajax({
    url:HOME + 'is_exists_zone',
    type:'GET',
    cache:false,
    data:{
      'zone_code' : zone_code
    },
    success:function(rs){
      var rs = $.trim(rs);
      if(rs === 'ok'){
        $('#searchForm').submit();
      }
      else {
        swal({
          title:'Error!',
          text:'ไม่พบโซน',
          type:'error'
        });
      }
    }
  })
}



function change_zone(){
  $('#zone-code').val('');
  goToCheck();
}


function cal_diff(item){
  var zone_qty = $('#stock_'+item).val();
  var count_qty = $('#qty_'+item).val();
  var diff_qty = count_qty - zone_qty;
  $('#diff_'+item).text(addCommas(diff_qty));
}
