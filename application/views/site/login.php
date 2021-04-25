<!-- <div class="social-auth-links text-center mb-3">
    <p>- OR -</p>
    <a class="btn btn-block btn-danger googleplus" data-original-title="Goole Plus" href="<?= $google_url ?>">
        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
    </a>
</div> -->

<?= form_open('', ['class' => 'login']); ?>

    <h2>Login</h2>
    <div class="box-login">
        <i class="fa fa-user"></i>
        <?= form_input('username', $model->username, [
            // 'class' => 'form-control',
            'autocomplete' => 'off',
            'placeholder' => 'Username',
        ]); ?>
    </div>  

    <div class="box-login">
        <i class="fa fa-lock"></i>
        <?= form_password('password', $model->password, [
            // 'class' => 'form-control',
            'autocomplete' => 'off',
            'placeholder' => 'Password',
        ]); ?>
    </div>

    <div class="d-flex flex-end" style="padding-bottom: 1rem;">
        <a href="#"> Forgot Password</a>
    </div>

    <button class="btn-login" type="submit"> Login </button>

<?= form_close(); ?>

<script type="text/javascript">
    sessionStorage.clear();
</script>
