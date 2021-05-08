<div class="page-lock">
    <div class="page-logo">
        <a class="brand" href="javascript:;">
            <img src="<?= base_url('/web/images/Logo_HRIS.png') ?>" alt="" /> </a>
    </div>
    <div class="page-body">
        <div class="lock-head"> Terkunci </div>
        <div class="lock-body">
            <div class="pull-left lock-avatar-block">
                <?php if (!empty($this->session->userdata('detail_identity')->profile_pic)): ?>
                <img src="<?= base_url($this->session->userdata('detail_identity')->profile_pic) ?>" class="lock-avatar" />
                <?php else: ?>
                <img src="<?= base_url('/web/assets/pages/img/no_avatar.jpg') ?>" class="lock-avatar" />
                <?php endif ?>
            </div>

            <?= form_open('', ['class' => 'lock-form pull-left']); ?>
                <h4><?= $this->session->userdata('identity')->username; ?></h4>
                <div class="form-group">
                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> </div>
                <div class="form-actions">
                    <button type="submit" class="btn red uppercase">Login</button>
                </div>
            <?= form_close(); ?>
        </div>
        <div class="lock-bottom">
            <?= $this->html->a('Bukan '. $this->session->userdata('identity')->username, '/site/logout') ?>
        </div>
    </div>
    <div class="page-footer-custom">
        <?= getenv('APP_YEAR') ?> &copy; <?= getenv('APP_COPYRIGHT') ?>
        <a target="_blank" href="<?= getenv('COMPANY_PAGE') ?>"><?= getenv('APP_COMPANY') ?></a>
    </div>
</div>