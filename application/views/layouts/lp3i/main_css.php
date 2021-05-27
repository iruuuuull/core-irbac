<!-- css -->
<link rel="stylesheet" href="<?= base_url('/web/assets/lp3i/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('/web/assets/lp3i/css/style.css') ?>">
<link rel="stylesheet" href="<?= base_url('/web/assets/lp3i/css/content.css') ?>">
<link rel="stylesheet" href="<?= base_url('/web/assets/lp3i/css/mobile.css') ?>">

<link rel="stylesheet"  href="<?= base_url('/web/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('/web/assets/plugins/daterangepicker/daterangepicker.css') ?>">
<link rel="stylesheet" href="<?= base_url('/web/assets/plugins/sweetalert2/sweetalert2.css') ?>">

<?php 
# Load self-made file views for setting css
if (!empty($view_css)) {
	if (is_string($view_css)) {
		$this->load->view($view_css);

	} elseif (is_array($view_css)) {
		foreach ($view_css as $key => $css) {
			$this->load->view($css);
		}

	}

}
?>
