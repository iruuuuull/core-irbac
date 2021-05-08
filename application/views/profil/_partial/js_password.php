<script type="text/javascript">
	var form_password = $("#form-password");

	$(document).on('submit', '#form-password', function(event) {
		event.preventDefault();
		formData = $(this).serialize();

		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			dataType: 'json',
			data: formData,
		})
		.done(function(data) {
			swalert({
				message: data.message,
				type: data.status
			}, () => {
				if (data.status == 'success') {
					window.location = "<?= site_url('/site/logout') ?>"
				}
			});
		})
		.fail(function(err) {
			console.error(err.error);
		})
		.always(function() {
			$('#modal-change-password').modal('hide');
		});
		
	});
</script>
