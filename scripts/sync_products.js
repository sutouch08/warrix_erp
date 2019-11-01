var percent = 0;
//var progress = setInterval(update_progress, 1000);

var count_style = 0;
var updated_style = 0;
var count_items = 0;
var updated_items = 0;
var label = $('#txt-label');
var style_date_add;
var style_date_upd;
var item_date_add;
var item_date_upd;
var allow_sync = true;
var state;

$(document).ready(function(){
  get_style_last_date();
  get_item_last_date();
});

function get_style_last_date(){
  $.ajax({
    url:BASE_URL + 'sync_items/get_style_last_date',
    type:'GET',
    cache:false,
    success:function(rs){
      var ds = $.parseJSON(rs);
      style_date_add = ds.date_add;
      style_date_upd = ds.date_upd;
    }
  });
}


function get_item_last_date(){
  $.ajax({
    url:BASE_URL + 'sync_items/get_item_last_date',
    type:'GET',
    cache:false,
    success:function(rs){
      var ds = $.parseJSON(rs);
      item_date_add = ds.date_add;
      item_date_upd = ds.date_upd;
    }
  });
}


function syncData(){
  $("#btn-sync").addClass('hide');
  $('#btn-stop').removeClass('hide');
  $('#progress').removeClass('hide');
  $('#txt-percent').addClass('active');
  allow_sync = true;

  if(state === 'count_style'){
    count_update_style();
  }else if(state === 'update_style'){
    get_update_style();
  }else if(state === 'count_items'){
    count_update_items();
  }else if(state === 'update_items') {
    get_update_items();
  }else{
    count_update_style();
  }
}


function stopSync(){
  allow_sync = false;
}

function finish_sync(){
  $('#btn-stop').addClass('hide');
  $("#btn-sync").removeClass('hide');
  $('#txt-percent').removeClass('active');
  // count_style = 0;
  // updated_style = 0;
  // count_items = 0;
  // updated_itmes = 0;
}


function count_update_style(){
  state = 'count_style';
  label.text('Collecting Style to update');
  if(allow_sync == false){
    finish_sync();
    return false;
  }

  $.ajax({
    url:BASE_URL + 'sync_items/count_update_style',
    type:'GET',
    cache:false,
    data:{
      'date_add' : style_date_add,
      'date_upd' : style_date_upd
    },
    success:function(rs){
      if(rs == 0){
        label.text('No Style to update');
        count_update_items();
      }else{
        count_style = rs;
        label.text(rs + ' style need to update');
        get_update_style();
      }
    }
  });
}


function get_update_style(){
  state = 'update_style';
  label.text('Product Style Updating '+ updated_style+' of '+ count_style);
  if(allow_sync == false){
    finish_sync();
    return false;
  }
  if(updated_style < count_style){
    $.ajax({
      url:BASE_URL + 'sync_items/get_update_style/'+ updated_style,
      type:'GET',
      cache:false,
      data:{
        'date_add' : style_date_add,
        'date_upd' : style_date_upd
      },
      success:function(rs){
        updated_style += parseInt(rs);
        update_progress('style');
        if(updated_style == count_style){
          count_update_items();
        }else{
          get_update_style();
        }
      }
    })
  }else{
    count_update_items();
  }
}


function count_update_items(){
  state = 'count_items';
  label.text('Collecting Items to update');
  if(allow_sync == false){
    finish_sync();
    return false;
  }
  $.ajax({
    url:BASE_URL + 'sync_items/count_update_items',
    type:'GET',
    cache:false,
    data:{
      'date_add' : item_date_add,
      'date_upd' : item_date_upd
    },
    success:function(rs){
      if(rs == 0){
        label.text('No Item to update');
        finish_sync();
      }else{
        count_items = rs;
        label.text(rs + ' items need to update');
        get_update_items();
      }
    }
  });
}


function get_update_items(){
  state = 'update_items';
  label.text('Items Updating '+ updated_items +' of '+ count_items);

  if(allow_sync == false){
    finish_sync();
    return false;
  }

  if(updated_items < count_items){
    $.ajax({
      url:BASE_URL + 'sync_items/get_update_items/'+ updated_items,
      type:'GET',
      cache:false,
      data:{
        'date_add' : item_date_add,
        'date_upd' : item_date_upd
      },
      success:function(rs){
        if(!isNaN(parseInt(rs))){
          updated_items += parseInt(rs);
          update_progress('item');
          if((updated_items + 1) == count_items){
            swal({
              title:'Complete',
              text:'All items updated',
              type:'success',
              timer:1000
            });

            finish_sync();

          }else{
            get_update_items();
          }
        }else{
          swal({
            title:'Error',
            text:'Something went wrong',
            type:'error'
          });

          finish_sync();
        }

      }
    })
  }else{
    finish_sync();
  }
}


function update_progress(type){
  if(type === 'style'){
    percent = (updated_style/count_style) * 100;
  }else{
    percent = (updated_items/count_items) * 100;
  }

  var percentage;
  if(percent > 100){
    percentage = 100;
  }else{
    percentage = parseInt(percent);
  }

  $('#txt-percent').attr("data-percent", percentage + "%");
  $('#progress-bar').css("width", percentage+"%");

}


function clear_progress(){
  percent = 0;
  $('#txt-percent').attr("data-percent", percent + "%");
  $('#progress-bar').css("width", percent+"%");
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
