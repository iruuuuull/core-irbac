<?= form_open('', ['class' => 'login']); ?>
    <h2 class="text-center">User Lock</h2>
    <div class="d-flex flex-custom-column">
        <div class="image-profile">
            <img src="<?= $this->session->userdata('detail_identity')->profile_pic ?>" alt="">
        </div> 
        <div>
            <div class="box-lock">
                <h6><?= $this->session->userdata('identity')->username; ?></h6>
            </div>  
    
            <div class="box-lock">
                <input type="password" placeholder="Password" name="password">
            </div>
        
            <button class="btn-login" type="submit"> Login </button>
        </div>
    </div>
    

    <div class="another-account">
        <a href="<?= site_url('/site/logout') ?>">Bukan <?= $this->session->userdata('identity')->username; ?></a>
    </div>
<?= form_close(); ?>