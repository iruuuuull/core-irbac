<!-- END QUICK NAV -->
        <!--[if lt IE 9]>
<script src="<?= base_url("/web/assets/global/plugins/respond.min.js") ?>"></script>
<script src="<?= base_url("/web/assets/global/plugins/excanvas.min.js") ?>"></script> 
<script src="<?= base_url("/web/assets/global/plugins/ie8.fix.min.js") ?>"></script> 
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="<?= base_url("/web/assets/global/plugins/jquery.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/global/plugins/bootstrap/js/bootstrap.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/global/plugins/jquery-ui/jquery-ui.min.js") ?>" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PLUGIN SCRIPT -->
<script src="<?= base_url("/web/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/global/plugins/ladda/spin.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/global/plugins/ladda/ladda.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/global/plugins/moment.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/clockface/js/clockface.js') ?>" type="text/javascript"></script>
<!-- END PLUGIN SCRIPT -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="<?= base_url("/web/assets/global/scripts/app.min.js") ?>" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="<?= base_url("/web/assets/layouts/layout/scripts/layout.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/layouts/global/scripts/quick-sidebar.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/layouts/global/scripts/quick-nav.min.js") ?>" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<script src="<?= base_url('/web/assets/pages/scripts/components-date-time-pickers.js') ?>" type="text/javascript"></script>

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

<script src="<?= base_url("/web/assets/global/scripts/my_custom.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/global/scripts/yii.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("/web/assets/global/scripts/jquery.timeago.js") ?>" type="text/javascript"></script>

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
