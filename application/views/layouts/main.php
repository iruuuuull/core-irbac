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
        <meta content="<?= getenv('APP_FULL_NAME') ?>" name="description" />
        <meta content="<?= getenv('APP_COPYRIGHT') .' '. getenv('APP_COMPANY') ?>" name="author" />

        <?php $this->load->view('layouts/main_css') ?>

    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-fixed-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="<?= site_url('/site') ?>">
                            <img src="<?= base_url("/web/images/Logo_HRIS.png") ?>" alt="logo" class="logo-default" /> </a>
                        <div class="menu-toggler sidebar-toggler">
                            <span></span>
                        </div>
                    </div>
                    <!-- END LOGO -->
                    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                        <span></span>
                    </a>
                    <!-- END RESPONSIVE MENU TOGGLER -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <!-- BEGIN INBOX DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <?php $notifikasi = $this->session->userdata('notifikasi'); ?>
                            <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <i class="icon-bell"></i>

                                    <?php if ($notifikasi['new'] > 0): ?>
                                    <span class="badge badge-default"> <?= $notifikasi['new'] ?> </span>
                                    <?php endif ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="external">
                                        <h3>
                                            <span class="bold"><?= $notifikasi['new'] ?></span> Notifikasi Tertunda</h3>
                                        <a href="<?= site_url('/notifikasi') ?>">Lihat semua</a>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">

                                            <?php if ($notifikasi['all'] > 0): ?>

                                                <?php foreach ($notifikasi['list'] as $key => $value): ?>
                                                    <li>
                                                        <a href="<?= $value->redirect_url ? $value->redirect_url : 'javascript:;' ?>" data-id="<?= $value->id ?>">
                                                            <time class="time timeago" 
                                                                datetime="<?= date(DATE_ISO8601, strtotime($value->created_at)) ?>">
                                                                    <?= date('d M Y', strtotime($value->created_at)) ?></time>

                                                            <span class="details <?= $value->is_read == 0 ? 'bold' : '' ?>">
                                                                <!-- <span class="label label-sm label-icon <?= $value->priority ?>"></span> -->
                                                                <?= $value->content ?> </span>
                                                        </a>
                                                    </li>
                                                <?php endforeach ?>

                                            <?php else: ?>

                                                <li>
                                                    <a href="javascript:;">
                                                        <span class="details">
                                                            <i>Tidak ada pemberitahuan</i>
                                                        </span>
                                                    </a>
                                                </li>

                                            <?php endif; ?>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <!-- <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <i class="icon-envelope-open"></i>
                                    <span class="badge badge-default"> 4 </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="external">
                                        <h3>Anda memiliki 
                                            <span class="bold">7 Pesan</span> Baru</h3>
                                        <a href="app_inbox.html">lihat semua</a>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                                            <li>
                                                <a href="#">
                                                    <span class="photo">
                                                        <img src="<?= base_url("/web/assets/layouts/layout3/img/avatar2.jpg") ?>" class="img-circle" alt=""> </span>
                                                    <span class="subject">
                                                        <span class="from"> Lisa Wong </span>
                                                        <span class="time">Just Now </span>
                                                    </span>
                                                    <span class="message"> Vivamus sed auctor nibh congue nibh. auctor nibh auctor nibh... </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li> -->
                            <!-- END INBOX DROPDOWN -->
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <?php if (!empty($this->session->userdata('detail_identity')->profile_pic)): ?>
                                    <img src="<?= base_url($this->session->userdata('detail_identity')->profile_pic) ?>" class="img-circle" />
                                    <?php else: ?>
                                    <img src="<?= base_url('/web/assets/pages/img/no_avatar.jpg') ?>" class="img-circle" />
                                    <?php endif ?>

                                    <span class="username username-hide-on-mobile"> <?= $this->session->userdata('identity')->username; ?> </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <?= $this->html->a('<i class="icon-user"></i> Profil Saya </a>', '/profil') ?>
                                    </li>
                                    <!-- <li>
                                        <a href="app_calendar.html">
                                            <i class="icon-calendar"></i> My Calendar </a>
                                    </li>
                                    <li>
                                        <a href="app_inbox.html">
                                            <i class="icon-envelope-open"></i> My Inbox
                                            <span class="badge badge-danger"> 3 </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="app_todo.html">
                                            <i class="icon-rocket"></i> My Tasks
                                            <span class="badge badge-success"> 7 </span>
                                        </a>
                                    </li> -->
                                    <li class="divider"> </li>
                                    <li>
                                        <?= $this->html->a('<i class="icon-lock"></i> Lock Screen </a>', '/site/lock') ?>
                                    </li>
                                    <li>
                                        <?= $this->html->a('<i class="icon-key"></i> Log Out', '/site/logout') ?>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    <div class="page-sidebar navbar-collapse collapse">
                        <!-- BEGIN SIDEBAR MENU -->
                        <?php $this->menuhelper->run() ?>
                        <!-- END SIDEBAR MENU -->
                    </div>
                    <!-- END SIDEBAR -->
                </div>
                <!-- END SIDEBAR -->
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content" style="min-height: 100vh">
                        <!-- BEGIN PAGE HEADER-->
                        <?php /*<!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="index.html">Home</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <a href="#">Blank Page</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Page Layouts</span>
                                </li>
                            </ul>
                        </div>
                        <!-- END PAGE BAR --> */ ?>

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
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
            </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner"> <?= getenv('APP_YEAR') ?> &copy; <?= getenv('APP_COPYRIGHT') ?>
                    <a target="_blank" href="<?= getenv('COMPANY_PAGE') ?>"><?= getenv('APP_COMPANY') ?></a> &nbsp;
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>

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
            <img src="<?= base_url('/web/assets/global/img/ajax-loading.gif') ?>" alt="Loading" /><br/>
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
