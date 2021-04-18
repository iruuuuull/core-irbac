<div class="login-box">
    <div class="login-logo">
        <a href="javascript:;">
            <img src="<?= base_url('/web/images/Logo_HRIS.png') ?>" alt="" /> </a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <?= form_open(''); ?>
                <div class="input-group mb-3">
                    <?= form_input('username', $model->username, [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'placeholder' => 'Username',
                    ]); ?>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <?= form_password('password', $model->password, [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                        'placeholder' => 'Password',
                    ]); ?>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8"></div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            <?= form_close(); ?>

            <div class="social-auth-links text-center mb-3">
                <p>- OR -</p>
                <a class="btn btn-block btn-danger googleplus" data-original-title="Goole Plus" href="<?= $google_url ?>">
                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
                </a>
            </div>
            <!-- /.social-auth-links -->

            <p class="mb-1">
                <!-- <a href="forgot-password.html">I forgot my password</a> -->
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<script type="text/javascript">
    sessionStorage.clear();
</script>
