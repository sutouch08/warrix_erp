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
  $.get(BASE_URL +'masters/warehouse/syncData', function(){
    $('body').append('finish import : Warehouse ...<br/>');
    setTimeout(function(){
      syncZone();
    },1000);
  });
}


//--- 2. Sync zone
function syncZone(){
  $('body').append('start importing : Zone ...<br/>');
  $.get(BASE_URL+'masters/zone/syncData', function(){
    $('body').append('finish import : Zone ...<br/>');
    syncCustomer();
  });
}




//--- 3. Sync customer
function syncCustomer(){
  $('body').append('start importing : Customers ...<br/>');
  $.get(BASE_URL+'masters/customers/syncData', function(){
    $('body').append('finish import : Customers ...<br/>');
    window.close();
  });
}
