
<!DOCTYPE html>
<html lang="th">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />

		<title><?php echo $this->title; ?></title>
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/chosen.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-fonts.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui-1.10.4.custom.min.css " />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/template.css"/>
		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->
		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ace-ie.css" />
		<![endif]-->
		<!-- inline styles related to this page -->
		<!-- ace settings handler -->
		<script src="<?php echo base_url(); ?>assets/js/ace-extra.js"></script>

        <!-- basic scripts -->
		<!--[if !IE]>-->
		<!--<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url(); ?>assets/js/jquery1.js'>"+"<"+"/script>");
		</script> -->
		<!-- <![endif]-->
		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->

    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
  	<script src="<?php echo base_url(); ?>assets/js/jquery-ui-1.10.4.custom.min.js"></script>
	  <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/ace/ace.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/ace/elements.fileinput.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/sweet-alert.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/handlebars-v3.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/chosen.jquery.js"></script>
	  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/sweet-alert.css">
    <style> .ui-helper-hidden-accessible { display:none; } </style>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
		<!--[if lte IE 8]>
		<script src="<?php echo base_url(); ?>assets/js/html5shiv.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/respond.js"></script>
		<![endif]-->
	</head>
	<body class="no-skin" onload="checkError()">
		<?php if($this->session->flashdata('error')) : ?>
							<input type="hidden" id="error" value="<?php echo $this->session->flashdata('error'); ?>" />
		<?php endif; ?>
		<?php if($this->session->flashdata('success')) : ?>
							<input type="hidden" id="success" value="<?php echo $this->session->flashdata('success'); ?>" />
		<?php endif; ?>
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default">
			<script type="text/javascript">
				try{
					ace.settings.check('navbar' , 'fixed')
				}catch(e){

				}

				var BASE_URL = '<?php echo base_url(); ?>';
			</script>
			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="<?php echo base_url(); ?>" class="navbar-brand">
						<small>
							FLEXI
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->

					<!-- /section:basics/navbar.toggle -->
				</div>
				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">


						<!-- #section:basics/navbar.user_menu -->
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo"
                              src="<?php if(file_exists("assets/avatars/".get_cookie('uname').".jpg")){
															echo base_url()."assets/avatars/".get_cookie('uname').".jpg";
														}else{
															echo base_url()."assets/avatars/avatar.png";
														} ?>" alt="<?php echo get_cookie('displayName'); ?> " />
								<span class="user-info">
									<small>Welcome</small>
									<?php echo get_cookie('displayName'); ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="#">
										<i class="ace-icon fa fa-cog"></i>
										ตั้งค่า
									</a>
								</li>

								<li>
									<a href="<?php echo base_url()."admin/profile"; ?>">
										<i class="ace-icon fa fa-user"></i>
										โปรไฟล์
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="<?php echo base_url(); ?>users/authentication/logout">
										<i class="ace-icon fa fa-power-off"></i>
										ออกจากระบบ
									</a>
								</li>
							</ul>
						</li>

						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>

				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{
					ace.settings.check('main-container' , 'fixed')
				}catch(e){

				}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar responsive">
						<!--- side menu  ------>
				<?php $this->load->view("include/side_menu"); ?>

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

			</div>

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
                <?php if($this->session->flashdata("error") != null) :?>
					<input type="hidden" id="error" value="<?php echo $this->session->flashdata("error"); ?>">
                <?php elseif( $this->session->flashdata("success") != null ) : ?>
                	<input type="hidden" id="success" value="<?php echo $this->session->flashdata("success"); ?>">
               <?php endif; ?>
					<div class="page-content">
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
