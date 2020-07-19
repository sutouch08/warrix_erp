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
    $('body').append('start  importing : Warehouse ...<br/>');
  $.get(BASE_URL +'sync_data/syncWarehouse', function(){
    $('body').append('finish import : Warehouse ...<br/>');
    $('body').append('============================================ <br/>');
    setTimeout(function(){
      syncZone();
    },1000);
  });
}


//--- 2. Sync zone
function syncZone(){
    $('body').append('start  importing : Zone ...<br/>');
  $.get(BASE_URL+'sync_data/syncZone', function(){
    $('body').append('finish import : Zone ...<br/>');
    $('body').append('============================================ <br/>');
    syncCustomer();
  });
}




//--- 3. Sync customer
function syncCustomer(){
    $('body').append('start  importing : Customers ...<br/>');
  $.get(BASE_URL+'sync_data/syncCustomer', function(){
    $('body').append('finish import : Customers ...<br/>');
    $('body').append('============================================ <br/>');
    syncGoodReceivePo();
  });
}

//--- 4. sync OPDN
function syncGoodReceivePo(){
    $('body').append('start    updating WR .... <br/>');
  $.get(BASE_URL + 'sync_data/syncReceivePoInvCode', function(rs){
    $('body').append('finished update WR : '+rs+'<br/>');
    $('body').append('============================================ <br/>');
    syncReceiveTranformInvCode();
  })
}


//--- 4. sync OIGN
function syncReceiveTranformInvCode(){
    $('body').append('start    updating RT .... <br/>');
  $.get(BASE_URL + 'sync_data/syncReceiveTransformInvCode', function(rs){
    $('body').append('finished update RT : ' + rs + ' <br/>');
    $('body').append('============================================ <br/>');
    syncDeliveryOrder();
  })
}


//--- 5. sync ODLN
function syncDeliveryOrder(){
    $('body').append('start    updating WO .... <br/>');
  $.get(BASE_URL + 'sync_data/syncOrderInvCode', function(rs){
    $('body').append('finished update WO : '+ rs +' <br/>');
    $('body').append('============================================ <br/>');
    syncDeliverySponsor();
  })
}


//--- 5. sync ODLN
function syncDeliverySponsor(){
    $('body').append('start    updating WS, WU .... <br/>');
  $.get(BASE_URL + 'sync_data/syncSponsorInvCode', function(rs){
    $('body').append('finished update WS, WU '+ rs +' <br/>');
    $('body').append('============================================ <br/>');
    syncDeliveryConsignment();
  })
}


//--- 5. sync ODLN
function syncDeliveryConsignment(){
    $('body').append('start    updating WC .... <br/>');
  $.get(BASE_URL + 'sync_data/syncConsignmentInvCode', function(rs){
    $('body').append('finished update WC '+ rs +' <br/>');
    $('body').append('============================================ <br/>');
    syncOrderTransfer();
  })
}


//--- 6. sync OWTR (WT, WQ, WV)
function syncOrderTransfer(){
  $('body').append('start    updating WT, WQ, WV .... <br/>');
  $.get(BASE_URL + 'sync_data/syncOrderTransferInvCode', function(rs){
    $('body').append('finished update WT, WQ, WV : '+ rs + ' <br/>');
    $('body').append('============================================ <br/>');
    syncTransfer();
  })
}


//--- 7. sync OWTR (WW)
function syncTransfer(){
    $('body').append('start    updating WW .... <br/>');
  $.get(BASE_URL + 'sync_data/syncTransferInvCode', function(rs){
    $('body').append('finished update WW : '+ rs + ' <br/>');
    $('body').append('============================================ <br/>');
    syncMoveInvCode();
  })
}


//--- 8. sync OWTR (MV)
function syncMoveInvCode(){
    $('body').append('start    updating MV .... <br/>');
  $.get(BASE_URL + 'sync_data/syncMoveInvCode', function(rs){
    $('body').append('finished update MV : '+ rs + ' <br/>');
    $('body').append('============================================ <br/>');
    $('body').append('All Done!!');
    window.close();
  })
}
