<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?= env('APP_FULL_NAME') ?>" name="description" />
    <meta content="<?= env('APP_COPYRIGHT') .' '. env('APP_COMPANY') ?>" name="author" />

    <title><?= $title .' | '. getEnv('APP_FULLNAME') ?></title>

    <?php $this->load->view('layouts/lp3i/main_css'); ?>
</head>

<body>
    <div class="wrapper">
        <!-- header menu start -->
        <header>
            <div class="page-header">
                <div class="container">
                    <div class="d-flex flex-space-between">
                        <div id="Menumobile">
                            <i class="fa fa-bars"></i>
                        </div>
                        <ul class="nav-menu d-flex flex-start"></ul>

                        <div class="MenuAccount d-flex">
                            <div class="Accounts">
                                <button>
                                    <i class="fa fa-user"></i>&emsp;<?= def($this->session->userdata('identity'), 'username', 'Guest'); ?>
                                </button>
                                <div class="dropdownAccount">
                                    <a href="<?= site_url('/profil') ?>"><i class="fa fa-cogs"></i> Setting</a>
                                    <a href="<?= site_url('/site/lock') ?>"><i class="fa fa-user-lock"></i> Lock</a>
                                    <a href="<?= site_url('/site/logout') ?>"><i class="fa fa-key"></i> Logout</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </header>
        <!-- header menu end -->

        <!-- side-bar menu start -->
        <div class="aside aside-menu-header">
            <div class="logo">
                <a href="#">
                    <img src="<?= base_url('/web/assets/lp3i/img/LOGO-PLJ-01.png') ?>" alt="">
                </a>

                <div class="logo-toggle">
                    <i class="fa fa-angle-double-right"></i>
                </div>

                <div class="icon-toggle-mobile">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="aside-menu-wrapper">
                <div class="side-menu scroll-menu my-1">
                    <?php $this->menuhelper->run(); ?>
                </div>
            </div>
        </div>
        <!-- side-bar menu end -->

        <div class="content" style="height: 100vh;">
            <div class="container">
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

                <?= $this->load->view($view, $data); ?>
            </div>
        </div>

        <footer>
            <div class="container">
                <div class="text-center py-1">
                    <p><?= env('APP_YEAR') ?> &copy; <?= env('APP_COPYRIGHT') ?></p>
                </div>
            </div>
        </footer>
    </div>

    <!-- MY MODAL -->
    <div id="modal-preview" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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

    <?php $this->load->view('layouts/lp3i/main_js') ?>

</body>
</html>
