<div class="row top-row">
    <div class="col-sm-6 top-col">
        <h4 class="title"><i class="fa fa-shopping-basket"></i> กำลังจัดสินค้า..</h4>
    </div>
    <div class="col-sm-6">
        <p class="pull-right top-p">
          <button type="button" class="btn btn-sm btn-warning" onclick="goBack()"><i class="fa fa-arrow-left"></i>&nbsp; กลับ</button>
        </p>
    </div>

</div>
<hr/>
<?php

$sCode      = getFilter('sCode', 'sOrderCode','');
$sCus       = getFilter('sCus', 'sOrderCus', '');
$sEmp       = getFilter('sEmp', 'sOrderEmp', '');
$fromDate   = getFilter('fromDate', 'fromDate', '');
$toDate     = getFilter('toDate', 'toDate', '');
$sBranch    = getFilter('sBranch', 'sBranch', '');
$sOnline    = getFilter('sOnline', 'sOnline', 0);
$sOffline   = getFilter('sOffline', 'sOffline', 0);
$isOnline   = $sOnline == 1 ? 'btn-info' : '';
$isOffline  = $sOffline == 1 ? 'btn-info' : '';

?>

<form id="searchForm" method="post">
<div class="row">
    <div class="col-sm-2 padding-5 first">
        <label>เลขที่เอกสาร</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sCode" name="sCode" value="<?php echo $sCode; ?>"/>
    </div>

    <div class="col-sm-2 padding-5">
        <label>ลูกค้า/ผู้รับ/ผู้ยืม</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sCus" name="sCus" value="<?php echo $sCus; ?>"/>
    </div>

    <div class="col-sm-2 padding-5">
        <label>พนักงาน/ผู้เบิก</label>
        <input type="text" class="form-control input-sm text-center search-box" id="sEmp" name="sEmp" value="<?php echo $sEmp; ?>"/>
    </div>

    <div class="col-sm-2 padding-5">
      <label>สาขา</label>
      <select class="form-control input-sm search-select" id="sBranch" name="sBranch">
        <option value="">ทั้งหมด</option>
        <?php echo selectBranch($sBranch); ?>
      </select>
    </div>

    <div class="col-sm-2 padding-5">
        <label class="display-block">วันที่</label>
        <input type="text" class="form-control input-sm input-discount text-center" id="fromDate" name="fromDate" value="<?php echo $fromDate; ?>"/>
        <input type="text" class="form-control input-sm input-unit text-center" id="toDate" name="toDate" value="<?php echo $toDate; ?>"/>
    </div>
    <div class="col-sm-1 padding-5">
        <label class="display-block not-show">search</label>
        <button type="button" class="btn btn-sm btn-primary btn-block" onclick="getSearch()"><i class="fa fa-search"></i> ค้นหา</button>
    </div>
    <div class="col-sm-1 padding-5 last">
      <label class="display-block not-show">reset</label>
      <button type="button" class="btn btn-sm btn-warning btn-block" onclick="clearFilter()"><i class="fa fa-retweet"></i> Reset</button>
    </div>
    <div class="col-sm-1 padding-5 first">
        <label class="display-block not-show">Online</label>
        <button type="button" class="btn btn-sm btn-block <?php echo $isOnline; ?>" id="btn-online" onclick="toggleOnline()">Online</button>
    </div>
    <div class="col-sm-1 padding-5">
      <label class="display-block not-show">Offline</label>
      <button type="button" class="btn btn-sm btn-block <?php echo $isOffline; ?>" id="btn-offline" onclick="toggleOffline()">Offline</button>
    </div>

</div>
<input type="hidden" name="sOnline" id="sOnline" value="<?php echo $sOnline; ?>" />
<input type="hidden" name="sOffline" id="sOffline" value="<?php echo $sOffline; ?>" />
</form>

<hr class="margin-top-10"/>

<?php
createCookie('sOrderCode', $sCode);
createCookie('sOrderCus', $sCus);
createCookie('sOrderEmp', $sEmp);
createCookie('sBranch', $sBranch);
createCookie('fromDate', $fromDate);
createCookie('toDate', $toDate);
createCookie('sOnline', $sOnline);
createCookie('sOffline', $sOffline);

$qr  = "SELECT DISTINCT o.* FROM tbl_order AS o ";
$qr .= "JOIN tbl_order_state AS s ON o.id = s.id_order AND o.state = s.id_state ";

$qr .= "WHERE state = 4 AND status = 1 ";

if( ! $supervisor )
{
  $qr .= "AND s.id_employee = ".getCookie('user_id')." ";
}

if( $sCode != "")
{

    $qr .= "AND (reference LIKE'%".$sCode."%' OR ref_code LIKE '%".$sCode."%') ";
}



if( $sCus != "")
{

    $qr .= "AND id_customer IN(".getCustomerIn($sCus).") ";
}


if( $sEmp != '')
{

  $qr .= "AND id_employee IN(".getEmployeeIn($sEmp).") ";
}


if( $sBranch != '')
{

  $qr .= "AND id_branch = '".$sBranch."' ";
}


if( $fromDate != "" && $toDate != "" )
{

    $qr .= "AND date_add >= '".fromDate($fromDate)."' AND date_add <= '". toDate($toDate)."' ";
}

if($sOnline == 1 && $sOffline == 0)
{
  $qr .= "AND isOnline = 1 ";
}

if($sOffline == 1 && $sOnline == 0)
{
  $qr .= "AND isOnline = 0 ";
}


$qr .= "ORDER BY s.id_employee ASC, date_add ASC";

$qs = dbQuery($qr);

?>

<div class="row">
    <div class="col-sm-12">
        <table class="table border-1">
            <thead>
                <tr class="font-size-12">
                    <th class="width-5 text-center">No.</th>
                    <th class="width-20">เลขที่เอกสาร</th>
                    <th class="">ลูกค้า</th>
                    <th class="width-8 text-center">จำนวน</th>
                    <th class="width-8 text-center">รูปแบบ</th>
                    <th class="width-10 text-center">พนักงาน</th>
                    <th class="width-10 text-center">วันที่</th>
                    <th class="width-15"></th>
                </tr>
            </thead>
            <tbody id="list-table">

<?php if( dbNumRows($qs) > 0) : ?>
<?php   $no = row_no(); ?>
<?php   $state = new state(); ?>
<?php   $order = new order(); ?>
<?php   while( $rs = dbFetchObject($qs)) : ?>
<?php    $hilight = $rs->isOnline == 1 ? 'background-color:#DEFFF9;' : ''; ?>
            <tr class="font-size-12" id="order-<?php echo $rs->id; ?>" style="<?php echo $hilight; ?>">
                <td class="middle text-center"><?php echo $no; ?></td>
                <td class="middle">
                  <?php echo $rs->reference; ?>
                  <?php echo ($rs->ref_code != '' ? ' ['.$rs->ref_code.']' : ''); ?>
                </td>
                <td class="middle"><?php echo customerName($rs->id_customer); ?></td>
                <td class="middle text-center"><?php echo number($order->getTotalQty($rs->id)); ?></td>
                <td class="middle text-center"><?php echo roleName($rs->role); ?></td>
                <td class="middle text-center"><?php echo employeeName($state->getLastStateEmployee($rs->id, 4)); ?></td>
                <td class="middle text-center"><?php echo thaiDate($rs->date_add); ?></td>
                <td class="middle text-right">
                  <?php if( $add OR $edit) : ?>
                    <button type="button" class="btn btn-xs btn-default" onclick="goPrepare(<?php echo $rs->id; ?>)">จัดสินค้า</button>
                  <?php endif; ?>
                  <?php if( $supervisor) : ?>
                    <button type="button" class="btn btn-xs btn-warning" onclick="pullBack(<?php echo $rs->id; ?>)">ดึงกลับ</button>
                  <?php endif; ?>
                </td>
            </tr>
<?php       $no++; ?>
<?php   endwhile; ?>

<?php else : ?>

            <tr>
                <td colspan="8" class="text-center">
                    <h4>ไม่พบรายการ</h4>
                </td>
            </tr>
<?php endif; ?>

            </tbody>
        </table>
    </div>
</div>

<script src="script/prepare/prepare_list.js?<?php echo date('Ymd'); ?>"></script>
