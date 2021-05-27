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

        <!-- css -->
        <link rel="stylesheet" href="<?= base_url('/web/assets/lp3i/css/style.css') ?>">
        <link rel="stylesheet" href="<?= base_url('/web/assets/lp3i/css/content.css') ?>">
        <link rel="stylesheet" href="<?= base_url('/web/assets/lp3i/css/mobile.css') ?>">

        <link rel="shortcut icon" href="favicon.ico" />
    </head>
    <!-- END HEAD -->

    <body class="bg-login">
        <?php $this->load->view($view, $data); ?>

        <!-- jQuery -->
        <script src="<?= base_url('/web/assets/plugins/jquery/jquery.min.js') ?>"></script>
        <script src="https://kit.fontawesome.com/b4bb8e09b6.js" crossorigin="anonymous"></script>
    </body>

</html>
