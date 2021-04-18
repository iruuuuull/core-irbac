<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title><?= $title .' | '. getEnv('APP_FULLNAME') ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="<?= env('APP_FULL_NAME') ?>" name="description" />
        <meta content="<?= env('APP_COPYRIGHT') .' '. env('APP_COMPANY') ?>" name="author" />
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?= base_url('/web/assets/plugins/fontawesome-free/css/all.min.css') ?>">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="<?= base_url('/web/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?= base_url('/web/assets/css/adminlte.min.css') ?>">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
        <style type="text/css">
            .login-page {
                background: url('<?= base_url('/web/images/login-bg.jpg') ?>')no-repeat center center fixed;
                background-size: cover;
            }
            .logo, .page-logo {
                margin-top: 150px !important;
            }
            .login {
                background-color: transparent !important;
            }
        </style>
        <link rel="shortcut icon" href="favicon.ico" />
    </head>
    <!-- END HEAD -->

    <body class="hold-transition login-page">
        <?php $this->load->view($view, $data); ?>

        <!-- jQuery -->
        <script src="<?= base_url('/web/assets/plugins/jquery/jquery.min.js') ?>"></script>
        <!-- Bootstrap 4 -->
        <script src="<?= base_url('/web/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
        <!-- AdminLTE App -->
        <script src="<?= base_url('/web/assets/js/dist/js/adminlte.min.js') ?>"></script>
    </body>

</html>
