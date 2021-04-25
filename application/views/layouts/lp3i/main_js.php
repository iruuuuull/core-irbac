<script src="<?= base_url('/web/assets/plugins/jquery/jquery.min.js') ?>"></script>

<script src="https://kit.fontawesome.com/b4bb8e09b6.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous" defer></script>
<script src="<?= base_url('/web/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<script src="<?= base_url('/web/assets/plugins/daterangepicker/moment.min.js') ?>"></script>
<script src="<?= base_url('/web/assets/plugins/daterangepicker/daterangepicker.js') ?>"></script>
<script src="<?= base_url('/web/assets/plugins/sweetalert2/sweetalert2.js') ?>"></script>
<script src="<?= base_url('/web/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>

<?php 
# Load self-made file views for setting js
if (!empty($view_js)) {
	if (is_string($view_js)) {
		$this->load->view($view_js);

	} elseif (is_array($view_js)) {
		foreach ($view_js as $key => $js) {
			$this->load->view($js);
		}

	}

}
?>

<script src="<?= base_url('/web/assets/lp3i/js/script.js') ?>" defer></script>
<script src="<?= base_url("/web/assets/js/my_custom.js") ?>" type="text/javascript"></script>
