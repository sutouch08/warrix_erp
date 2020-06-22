$(document).ready(function() {
  syncData();
});


function syncData(){
  setTimeout(function(){
    syncWarehouse();
  },1000);
}

//--1 Sync Warehouse
function syncWarehouse(){
  $('body').append('start importing : Warehouse ...<br/>');
  $.get(BASE_URL +'sync_data/syncWarehouse', function(){
    $('body').append('finish import : Warehouse ...<br/>');
    setTimeout(function(){
      syncZone();
    },1000);
  });
}


//--- 2. Sync zone
function syncZone(){
  $('body').append('start importing : Zone ...<br/>');
  $.get(BASE_URL+'sync_data/syncZone', function(){
    $('body').append('finish import : Zone ...<br/>');
    syncCustomer();
  });
}




//--- 3. Sync customer
function syncCustomer(){
  $('body').append('start importing : Customers ...<br/>');
  $.get(BASE_URL+'sync_data/syncCustomer', function(){
    $('body').append('finish import : Customers ...<br/>');
    syncGoodReceivePo();
  });
}

//--- 4. sync OPDN
function syncGoodReceivePo(){
  $('body').append('start updating Goods Receive PO .... <br/>');
  $.get(BASE_URL + 'sync_data/syncReceivePoInvCode', function(){
    $('body').append('finished update Good Recieve PO document code...<br/>');
    syncDeliveryOrder()
  })
}


//--- 5. sync ODLN
function syncDeliveryOrder(){
  $('body').append('start updating Delivery Oder .... <br/>');
  $.get(BASE_URL + 'sync_data/syncOrderInvCode', function(){
    $('body').append('finished update Delivery Orders document code...<br/>');
    window.close();
  })
}
