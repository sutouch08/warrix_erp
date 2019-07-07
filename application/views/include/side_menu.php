<!--   Side menu Start --->
<ul class="nav nav-list">
	<li class="<?php echo is_open('IC'); ?>">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-home"></i>
			<span class="menu-text">ระบบคลังสินค้า</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<ul class="submenu">
			<?php echo side_menu('ICTRFM',  'inventory/transform', 'เบิกสินค้าเพื่อแปรสภาพ'); ?>
			<?php echo side_menu('ICSUPP',  'inventory/support', 'เบิกสินค้าอภินันท์'); ?>
			<?php echo side_menu('ICLEND',  'inventory/lend', 'เบิกยืมสินค้า'); ?>
			<?php echo side_menu('ICTRRC',  'inventory/receive_transform', 'รับสินค้าจากการแปรสภาพ'); ?>
			<?php echo side_menu('ICPURC',  'inventory/receive_po', 'รับสินค้าจากการสั่งซื้อ'); ?>
			<?php echo side_menu('ICTRWH',  'inventory/transfer', 'โอน/ย้าย สินค้า'); ?>
			<?php echo side_menu('ICODPR',  'inventory/prepare', 'จัดสินค้า'); ?>
			<?php echo side_menu('ICODQC',  'inventory/qc', 'ตรวจสินค้า'); ?>
			<?php echo side_menu('ICODDO',  'inventory/delivery', 'ออเดอร์รอการจัดส่ง'); ?>
			<?php echo side_menu('ICODIV',  'inventory/invoice', 'ออเดอร์จัดส่งแล้ว'); ?>
			<?php echo side_menu('ICCKBF',  'inventory/buffer', 'ตรวจสอบ BUFFER'); ?>
			<?php echo side_menu('ICCKCN',  'inventory/cancle', 'ตรวจสอบ CANCLE'); ?>
			<?php echo side_menu('ICCKMV',  'inventory/movement', 'ตรวจสอบ MOVEMENT'); ?>
		</ul>
	</li>


	<li class="<?php echo is_open('SO'); ?>">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-cogs"></i>
			<span class="menu-text">ระบบขาย</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<ul class="submenu">
			<?php echo side_menu('SOODSO',  'orders/order', 'ออเดอร์'); ?>
			<?php echo side_menu('SOODSP',  'orders/sponsor', 'สปอนเซอร์'); ?>
			<?php echo side_menu('SOCCSO',  'orders/consign_so', 'ฝากขาย(ใบกำกับ)'); ?>
			<?php echo side_menu('SOCCTR',  'orders/consign_tr', 'ฝากขาย(โอนคลัง)'); ?>

		</ul>
	</li>


	<li class="<?php echo is_open('SC'); ?>">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-cogs"></i>
			<span class="menu-text">การกำหนดค่า</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<ul class="submenu">
			<?php echo side_menu('SCCONF', 'setting/configs', 'การกำหนดค่า');  ?>
			<?php echo side_menu('SCUSER', 'users/users', 'เพิ่ม/แก้ไข ชื่อผู้ใช้งาน');  ?>
			<?php echo side_menu('SCPORF', 'users/profiles', 'เพิ่ม/แก้ไข โปรไฟล์'); ?>
			<?php echo side_menu('SCPERM', 'users/permission', 'กำหนดสิทธิ์'); ?>
			<?php echo side_menu('SCPOLI', 'discount/discount_policy', 'นโยบายส่วนลด'); ?>
			<?php echo side_menu('SCRULE', 'discount/discount_rule', 'เงื่อนไขส่วนลด'); ?>
			<?php echo side_menu('SCBGSP', 'budget/sponsor_budget', 'งบประมาณสปอนเซอร์'); ?>
			<?php echo side_menu('SCBGSU', 'budget/support_budget', 'งบประมาณอภินันท์'); ?>
		</ul>
	</li>

	<li class="<?php echo is_open('DB'); ?>">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-database"></i>
			<span class="menu-text">ระบบฐานข้อมูล</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<ul class="submenu">
			<?php echo side_menu('DBPROD', 'masters/products','เพิ่ม/แก้ไข รายการสินค้า'); ?>
			<?php echo side_menu('DBCUST', 'masters/customers','เพิ่ม/แก้ไข รายชื่อลูกค้า'); ?>
			<?php echo side_menu('DBCARE', 'masters/customer_area','เพิ่ม/แก้ไข เขตการขาย'); ?>
			<?php echo side_menu('DBCLAS', 'masters/customer_class','เพิ่ม/แก้ไข เกรดลูกค้า'); ?>
			<?php echo side_menu('DBCGRP', 'masters/customer_group','เพิ่ม/แก้ไข กลุ่มลูกค้า'); ?>
			<?php echo side_menu('DBCKIN', 'masters/customer_kind','เพิ่ม/แก้ไข ประเภทลูกค้า'); ?>
			<?php echo side_menu('DBCTYP', 'masters/customer_type','เพิ่ม/แก้ไข ชนิดลูกค้า'); ?>
			<?php echo side_menu('DBCHAN', 'masters/channels','เพิ่ม/แก้ไข ช่องทางการขาย'); ?>
			<?php echo side_menu('DBPAYM', 'masters/payment_methods','เพิ่ม/แก้ไข ช่องทางการชำระเงิน'); ?>
		</ul>
	</li>
</ul><!-- /.nav-list -->
