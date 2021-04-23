<!-- jQuery -->
<script src="<?= base_url('/web/assets/plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('/web/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('/web/assets/js/adminlte.min.js') ?>"></script>
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

<script src="<?= base_url("/web/assets/js/my_custom.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/js/jquery.timeago.js") ?>" type="text/javascript"></script>

<script type="text/javascript" id="notification-link">
	$(document).on('click', "#header_notification_bar ul li ul li a", function(event) {
		event.preventDefault();
		/* Act on the event */

		let url = $(this).attr('href');
		let id = $(this).data('id');

		if (id) {
			myLoader();

			$.ajax({
				url: '<?= site_url('/notifikasi/read/') ?>' + id,
				type: 'GET',
				dataType: 'json',
			})
			.done(function(data) {
				console.log(data);
			})
			.always(function() {
				myLoader(false);

				if (url != 'javascript:;') {
					window.location.href = url;
				}
			});
		}

	});

	$(document).ready(function() {
		$('time.timeago').timeago();
	});
</script>
