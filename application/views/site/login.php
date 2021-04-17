<div class="login">
    <!-- BEGIN LOGO -->
    <div class="logo">
        <a href="javascript:;">
            <img src="<?= base_url('/web/images/Logo_HRIS.png') ?>" alt="" /> </a>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <?= form_open('', [
            'class' => 'login-form'
        ]); ?>
            <h3 class="form-title font-green">Sign In</h3>
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span> Masukkan username dan password. </span>
            </div>
            <div class="form-group">
                <?= form_label('Username', 'id_username', ['class' => 'control-label visible-ie8 visible-ie9']); ?>
                <?= form_input('username', $model->username, [
                    'class' => 'form-control form-control-solid placeholder-no-fix',
                    'autocomplete' => 'off',
                    'placeholder' => 'Username',
                ]); ?>
            </div>
            <div class="form-group">
                <?= form_label('Password', 'id_password', ['class' => 'control-label visible-ie8 visible-ie9']); ?>
                <?= form_password('password', $model->password, [
                    'class' => 'form-control form-control-solid placeholder-no-fix',
                    'autocomplete' => 'off',
                    'placeholder' => 'Password',
                ]); ?>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn green uppercase">Login</button>
                <a href="javascript:;" id="forget-password" class="forget-password">Lupa Password?</a>
            </div>
            <div class="login-options">
                <h4>Atau login dengan</h4>
                <ul class="social-icons">
                    <li>
                        <a class="social-icon-color googleplus" data-original-title="Goole Plus" href="<?= $google_url ?>"></a>
                    </li>
                </ul>
            </div>
            <div class="create-account">
                <p>
                    <!-- <a href="javascript:;" id="register-btn" class="uppercase">Buat akun</a> -->
                </p>
            </div>
        <?= form_close(); ?>
        <!-- END LOGIN FORM -->

        <!-- BEGIN FORGOT PASSWORD FORM -->
        <form class="forget-form" action="index.html" method="post">
            <h3 class="font-green">Lupa Password ?</h3>
            <p> Masukkan alamat e-mail dan NIP dibawah ini untuk reset password. </p>
            <div class="form-group">
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> </div>
            <div class="form-group">
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="NIP" name="nip" /> </div>
            <div class="form-actions">
                <button type="button" id="back-btn" class="btn green btn-outline">Kembali</button>
                <button type="submit" class="btn btn-success uppercase pull-right">Submit</button>
            </div>
        </form>
        <!-- END FORGOT PASSWORD FORM -->

        <?php /*<!-- BEGIN REGISTRATION FORM -->
        <form class="register-form" action="index.html" method="post">
            <h3 class="font-green">Sign Up</h3>
            <p class="hint"> Enter your personal details below: </p>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Full Name</label>
                <input class="form-control placeholder-no-fix" type="text" placeholder="Full Name" name="fullname" /> </div>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Email</label>
                <input class="form-control placeholder-no-fix" type="text" placeholder="Email" name="email" /> </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Address</label>
                <input class="form-control placeholder-no-fix" type="text" placeholder="Address" name="address" /> </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">City/Town</label>
                <input class="form-control placeholder-no-fix" type="text" placeholder="City/Town" name="city" /> </div>
            <p class="hint"> Enter your account details below: </p>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Username</label>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username" /> </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" id="register_password" placeholder="Password" name="password" /> </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Re-type Your Password" name="rpassword" /> </div>
            <div class="form-group margin-top-20 margin-bottom-20">
                <label class="mt-checkbox mt-checkbox-outline">
                    <input type="checkbox" name="tnc" /> I agree to the
                    <a href="javascript:;">Terms of Service </a> &
                    <a href="javascript:;">Privacy Policy </a>
                    <span></span>
                </label>
                <div id="register_tnc_error"> </div>
            </div>
            <div class="form-actions">
                <button type="button" id="register-back-btn" class="btn green btn-outline">Back</button>
                <button type="submit" id="register-submit-btn" class="btn btn-success uppercase pull-right">Submit</button>
            </div>
        </form>
        <!-- END REGISTRATION FORM --> */ ?>
    </div>
    <div class="copyright">
        <?= getenv('APP_YEAR') ?> &copy; <?= getenv('APP_COPYRIGHT') ?>
        <a target="_blank" href="<?= getenv('COMPANY_PAGE') ?>"><?= getenv('APP_COMPANY') ?></a>
    </div>
</div>

<script type="text/javascript">
    sessionStorage.clear();
</script>
