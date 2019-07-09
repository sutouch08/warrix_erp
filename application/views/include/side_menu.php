<?php
$menu_sub_group_code = isset($this->menu_sub_group_code) ? $this->menu_sub_group_code : NULL;
$menu = $this->menu_code;
$menu_group = $this->menu_group_code;
?>
<!--   Side menu Start --->
<ul class="nav nav-list">
	<li class="<?php echo isActiveOpenMenu($menu_group, 'IC'); ?>">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-home"></i>
			<span class="menu-text">ระบบคลังสินค้า</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<ul class="submenu">
			<?php echo side_menu($menu, 'ICTRFM',  'inventory/transform', 'เบิกสินค้าเพื่อแปรสภาพ'); ?>
			<?php echo side_menu($menu, 'ICSUPP',  'inventory/support', 'เบิกสินค้าอภินันท์'); ?>
			<?php echo side_menu($menu, 'ICLEND',  'inventory/lend', 'เบิกยืมสินค้า'); ?>
			<?php echo side_menu($menu, 'ICTRRC',  'inventory/receive_transform', 'รับสินค้าจากการแปรสภาพ'); ?>
			<?php echo side_menu($menu, 'ICPURC',  'inventory/receive_po', 'รับสินค้าจากการสั่งซื้อ'); ?>
			<?php echo side_menu($menu, 'ICTRWH',  'inventory/transfer', 'โอน/ย้าย สินค้า'); ?>
			<?php echo side_menu($menu, 'ICODPR',  'inventory/prepare', 'จัดสินค้า'); ?>
			<?php echo side_menu($menu, 'ICODQC',  'inventory/qc', 'ตรวจสินค้า'); ?>
			<?php echo side_menu($menu, 'ICODDO',  'inventory/delivery', 'ออเดอร์รอการจัดส่ง'); ?>
			<?php echo side_menu($menu, 'ICODIV',  'inventory/invoice', 'ออเดอร์จัดส่งแล้ว'); ?>
			<?php echo side_menu($menu, 'ICCKBF',  'inventory/buffer', 'ตรวจสอบ BUFFER'); ?>
			<?php echo side_menu($menu, 'ICCKCN',  'inventory/cancle', 'ตรวจสอบ CANCLE'); ?>
			<?php echo side_menu($menu, 'ICCKMV',  'inventory/movement', 'ตรวจสอบ MOVEMENT'); ?>
		</ul>
	</li>


	<li class="<?php echo isActiveOpenMenu($menu_group, 'SO'); ?>">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-cogs"></i>
			<span class="menu-text">ระบบขาย</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<ul class="submenu">
			<?php echo side_menu($menu, 'SOODSO',  'orders/order', 'ออเดอร์'); ?>
			<?php echo side_menu($menu, 'SOODSP',  'orders/sponsor', 'สปอนเซอร์'); ?>
			<?php echo side_menu($menu, 'SOCCSO',  'orders/consign_so', 'ฝากขาย(ใบกำกับ)'); ?>
			<?php echo side_menu($menu, 'SOCCTR',  'orders/consign_tr', 'ฝากขาย(โอนคลัง)'); ?>

		</ul>
	</li>


	<li class="<?php echo isActiveOpenMenu($menu_group, 'SC'); ?>">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-cogs"></i>
			<span class="menu-text">การกำหนดค่า</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<ul class="submenu">
			<?php echo side_menu($menu, 'SCCONF', 'setting/configs', 'การกำหนดค่า');  ?>
			<?php echo side_menu($menu, 'SCUSER', 'users/users', 'เพิ่ม/แก้ไข ชื่อผู้ใช้งาน');  ?>
			<?php echo side_menu($menu, 'SCPORF', 'users/profiles', 'เพิ่ม/แก้ไข โปรไฟล์'); ?>
			<?php echo side_menu($menu, 'SCPERM', 'users/permission', 'กำหนดสิทธิ์'); ?>
			<?php echo side_menu($menu, 'SCPOLI', 'discount/discount_policy', 'นโยบายส่วนลด'); ?>
			<?php echo side_menu($menu, 'SCRULE', 'discount/discount_rule', 'เงื่อนไขส่วนลด'); ?>
			<?php echo side_menu($menu, 'SCBGSP', 'budget/sponsor_budget', 'งบประมาณสปอนเซอร์'); ?>
			<?php echo side_menu($menu, 'SCBGSU', 'budget/support_budget', 'งบประมาณอภินันท์'); ?>
		</ul>
	</li>

	<li class="<?php echo isActiveOpenMenu($menu_group, 'DB'); ?>">
		<a href="#" class="dropdown-toggle">
			<i class="menu-icon fa fa-database"></i>
			<span class="menu-text">ระบบฐานข้อมูล</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<ul class="submenu">
			<li class="<?php echo isActiveOpenMenu($menu_sub_group_code, 'PRODUCT'); ?>">
				<a href="#" class="dropdown-toggle"><i class="menu-icon fa fa-caret-right"></i> ฐานข้อมูลสินค้า <b class="arrow fa fa-angle-down"></b></a>
				<ul class="submenu">
					<?php echo side_menu($menu, 'DBPROD', 'masters/products','เพิ่ม/แก้ไข รายการสินค้า'); ?>
					<?php echo side_menu($menu, 'DBPDST', 'masters/product_style','เพิ่ม/แก้ไข รุ่นสินค้า'); ?>
					<?php echo side_menu($menu, 'DBPDGP', 'masters/product_group','เพิ่ม/แก้ไข กลุ่มสินค้า'); ?>
					<?php echo side_menu($menu, 'DBPDCR', 'masters/product_category','เพิ่ม/แก้ไข หมวดหมู่สินค้า'); ?>
					<?php echo side_menu($menu, 'DBPDKN', 'masters/product_kind','เพิ่ม/แก้ไข ประเภทสินค้า'); ?>
					<?php echo side_menu($menu, 'DBPDTY', 'masters/product_type','เพิ่ม/แก้ไข ชนิดสินค้า'); ?>
					<?php echo side_menu($menu, 'DBPDCL', 'masters/product_color','เพิ่ม/แก้ไข สี'); ?>
					<?php echo side_menu($menu, 'DBPDSI', 'masters/product_size','เพิ่ม/แก้ไข ไซส์'); ?>
					<?php echo side_menu($menu, 'DBPDBR', 'masters/product_brand','เพิ่ม/แก้ไข ยี่ห้อสินค้า'); ?>
				</ul>
			</li>
			<li class="<?php echo isActiveOpenMenu($menu_sub_group_code, 'CUSTOMER'); ?>">
				<a href="#" class="dropdown-toggle"><i class="menu-icon fa fa-caret-right"></i> ฐานข้อมูลลูกค้า <b class="arrow fa fa-angle-down"></b></a>
				<b class="arrow"></b>
				<ul class="submenu">
					<?php echo side_menu($menu, 'DBCUST', 'masters/customers','เพิ่ม/แก้ไข รายชื่อลูกค้า'); ?>
					<?php echo side_menu($menu, 'DBCARE', 'masters/customer_area','เพิ่ม/แก้ไข เขตการขาย'); ?>
					<?php echo side_menu($menu, 'DBCLAS', 'masters/customer_class','เพิ่ม/แก้ไข เกรดลูกค้า'); ?>
					<?php echo side_menu($menu, 'DBCGRP', 'masters/customer_group','เพิ่ม/แก้ไข กลุ่มลูกค้า'); ?>
					<?php echo side_menu($menu, 'DBCKIN', 'masters/customer_kind','เพิ่ม/แก้ไข ประเภทลูกค้า'); ?>
					<?php echo side_menu($menu, 'DBCTYP', 'masters/customer_type','เพิ่ม/แก้ไข ชนิดลูกค้า'); ?>
				</ul>
			</li>

			<?php echo side_menu($menu, 'DBCHAN', 'masters/channels','เพิ่ม/แก้ไข ช่องทางการขาย'); ?>
			<?php echo side_menu($menu, 'DBPAYM', 'masters/payment_methods','เพิ่ม/แก้ไข ช่องทางการชำระเงิน'); ?>
		</ul>
	</li>
</ul><!-- /.nav-list -->
