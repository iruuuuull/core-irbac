<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8" />
		<title>
			<?= $title .' | '. getEnv('APP_FULLNAME') ?>
		</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<meta content="<?= env('APP_FULL_NAME') ?>" name="description" />
		<meta content="<?= env('APP_COPYRIGHT') .' '. env('APP_COMPANY') ?>" name="author" />

		<?php $this->load->view('layouts/main_css') ?>
	</head>
	<!-- END HEAD -->

	<body class="hold-transition sidebar-mini">
		<!-- Site wrapper -->
		<div class="wrapper">
			<!-- Navbar -->
			<nav class="main-header navbar navbar-expand navbar-white navbar-light">
				<!-- Left navbar links -->
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
					</li>
				</ul>

				<!-- Right navbar links -->
				<ul class="navbar-nav ml-auto">
					<!-- Notifications Dropdown Menu -->
					<?php $notifikasi = $this->session->userdata('notifikasi'); ?>
					<li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#">
							<i class="far fa-bell"></i>

							<?php if ($notifikasi['new'] > 0): ?>
                            <span class="badge badge-warning navbar-badge"> <?= $notifikasi['new'] ?> </span>
                            <?php endif ?>
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<span class="dropdown-item dropdown-header"><?= $notifikasi['all'] ?> Notifikasi</span>
							<div class="dropdown-divider"></div>

							<?php if ($notifikasi['all'] > 0): ?>

                                <?php foreach ($notifikasi['list'] as $key => $value): ?>
                                    <a href="<?= $value->redirect_url ? $value->redirect_url : 'javascript:;' ?>" data-id="<?= $value->id ?>" class="dropdown-item">
										<!-- Message Start -->
										<div class="media">
											<div class="media-body">
												<p class="text-sm <?= $value->is_read == 0 ?: 'bold' ?>"><?= $value->content ?></p>
												<time class="time timeago" 
		                                            datetime="<?= date(DATE_ISO8601, strtotime($value->created_at)) ?>">
		                                                <?= date('d M Y', strtotime($value->created_at)) ?></time>
												<!-- <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p> -->
											</div>
										</div>
										<!-- Message End -->
									</a>
                                <?php endforeach ?>

                            <?php else: ?>

                                <a href="javascript:;" class="dropdown-item">
									<!-- Message Start -->
									<div class="media">
										<div class="media-body">
											<p class="text-sm">Tidak ada pemberitahuan</p>
										</div>
									</div>
									<!-- Message End -->
								</a>

                            <?php endif; ?>
							<div class="dropdown-divider"></div>
							<a href="<?= site_url('/notifikasi') ?>" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
						</div>
					</li>
					<li class="nav-item dropdown user-menu">
				        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<?php if (!empty($this->session->userdata('detail_identity')->profile_pic)): ?>
                            <img src="<?= $this->session->userdata('detail_identity')->profile_pic ?>"
                            	class="user-image img-circle elevation-2" alt="User Image" />
                            <?php else: ?>
                            <img src="<?= base_url('/web/images/no_avatar.jpg') ?>"
                            	class="user-image img-circle elevation-2" alt="User Image" />
                            <?php endif ?>

							<span class="d-none d-md-inline"><?= $this->session->userdata('identity')->username; ?></span>
				        </a>
						<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<!-- User image -->
							<li class="user-header bg-primary">
								<?php if (!empty($this->session->userdata('detail_identity')->profile_pic)): ?>
	                            <img src="<?= $this->session->userdata('detail_identity')->profile_pic ?>"
	                            	class="img-circle elevation-2" alt="User Image" />
	                            <?php else: ?>
	                            <img src="<?= base_url('/web/assets/pages/img/no_avatar.jpg') ?>"
	                            	class="img-circle elevation-2" alt="User Image" />
	                            <?php endif ?>

								<p>
									<?= $this->session->userdata('identity')->username; ?>
									<!-- <small>Member since Nov. 2012</small> -->
								</p>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<a href="<?= site_url('/profil') ?>" class="btn btn-default btn-flat float-left">Profile</a>
								<a href="<?= site_url('/site/logout') ?>" class="btn btn-default btn-flat float-right">Sign out</a>
							</li>
						</ul>
			      	</li>
				</ul>
		  	</nav>
		  	<!-- /.navbar -->

			<!-- Main Sidebar Container -->
			<aside class="main-sidebar sidebar-dark-primary elevation-4">
				<!-- Brand Logo -->
				<a href="<?= site_url('/site/') ?>" class="brand-link">
					<img src="<?= base_url('/web/images/Logo.png') ?>"
						alt="AdminLTE Logo"
						class="brand-image img-circle elevation-3"
						style="opacity: .8">
					<span class="brand-text font-weight-light"><?= env('APP_NAME') ?></span>
				</a>

				<!-- Sidebar -->
				<div class="sidebar">
					<!-- Sidebar Menu -->
					<nav class="mt-2">
						<?php $this->menuhelper->run() ?>
				  	</nav>
				  	<!-- /.sidebar-menu -->
				</div>
			<!-- /.sidebar -->
		 	</aside>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<?php /*<section class="content-header">
					<div class="container-fluid">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1><?= $title ?></h1>
							</div>
							<div class="col-sm-6">
								<ol class="breadcrumb float-sm-right">
									<li class="breadcrumb-item"><a href="#">Home</a></li>
									<li class="breadcrumb-item active">Blank Page</li>
								</ol>
							</div>
						</div>
					</div><!-- /.container-fluid -->
				</section> */ ?><br/>

				<!-- Main content -->
				<section class="content">

					<!-- BEGIN ALERT FLASHDATA -->
                    <?php if ($this->session->flashdata('danger')): ?>
                        <div class="alert alert-danger alert-dismissible" style="margin-top: 10px">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?= $this->session->flashdata('danger'); ?>
                        </div>
                    <?php endif ?>

                    <?php if ($this->session->flashdata('info')): ?>
                        <div class="alert alert-info alert-dismissible" style="margin-top: 10px">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?= $this->session->flashdata('info'); ?>
                        </div>
                    <?php endif ?>

                    <?php if ($this->session->flashdata('warning')): ?>
                        <div class="alert alert-warning alert-dismissible" style="margin-top: 10px">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?= $this->session->flashdata('warning'); ?>
                        </div>
                    <?php endif ?>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible" style="margin-top: 10px">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?= $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif ?>
                    <!-- END ALERT FLASHDATA -->

                    <?php $this->load->view($view, $data); ?>

				</section>
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->

			<footer class="main-footer">
				<div class="float-right d-none d-sm-block">
				<b>Version</b> <?= env('APP_VERSION') ?>
				</div>
				<?= env('APP_YEAR') ?> &copy; <?= env('APP_COPYRIGHT') ?>
			</footer>

		</div>
		<!-- ./wrapper -->

		<!-- MY MODAL -->
        <div id="modal-preview" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">File tidak ditemukan atau browser tidak support.</div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MY MODAL -->

        <!-- OVERLAY LOADER -->
        <div id="overlay" style="display: none;">
            <img src="<?= base_url('/web/images/ajax-loading.gif') ?>" alt="Loading" /><br/>
            Memuat...
        </div> 
        <!-- OVERLAY LOADER -->

        <script type="text/javascript" id="csrf">
            var csrf_name = '<?= $this->security->get_csrf_token_name() ?>';
            var csrf_hash = '<?= $this->security->get_csrf_hash() ?>';
            var key_map = '<?= getEnv('LOCATIONIQ_API_KEY') ?>';
        </script>

        <?php $this->load->view('layouts/main_js') ?>
	</body>
</html>
