<script type="text/javascript">
	$(document).on('click', '#btn-save', function(event) {
		event.preventDefault();
		/* Act on the event */

		let form = $('#form-cuti');
		let formData = new FormData(form[0]);

		let ladda_button = Ladda.create(this);

		disableFormModal(form);

		$.ajax({
			url: '<?= site_url("/cuti/permohonan/ajukan") ?>',
			type: 'POST',
			dataType: 'json',
			data: formData,
			cache:false,
	        processData: false,
	        contentType: false,
		})
		.done(function(data) {
			swalert({
                title: 'Informasi',
                message: data.message,
                type: data.type
            });

			if (data.type == 'success') {
				modal.modal('hide');
			}
		})
		.fail(function() {
			swalert({
                title: 'Gagal proses permohonan',
                message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
                type: 'error'
            });
		})
		.always(function() {
			ladda_button.stop();
			datatableReload();
			disableFormModal(form, false);
		});
		
	});

	$(document).on('click', '.btn-cancel', function(event) {
		event.preventDefault();
		/* Act on the event */
		let type_cancel = "";

		if ($(this).hasClass('btn-warning')) {
			type_cancel = 'normal_cancel';
		} else if ($(this).hasClass('btn-danger')) {
			type_cancel = 'agreed_cancel';
		} else {
			return false;
		}

		customConfirmation({
			message: 'Apakah anda yakin ingin membatalkan permohonan ini?',
			title: 'Konfirmasi',
			type: 'warning'
		}, (confirmation) => {
			if (confirmation) {
				myLoader();
				let id = $(this).data('id');
				let modal = $("#modal-action");
				modal.find('.modal-title').text('Form Pembatalan Cuti');

				$.ajax({
					url: '<?= site_url('/cuti/permohonan/get-form/') ?>' + type_cancel,
					type: 'POST',
					dataType: 'html',
					data: {
						id: id,
						[csrf_name] : csrf_hash
					},
				})
				.done(function(html) {
					if (html) {
						modal.find('.modal-body').empty().html(html);
						modal.modal("show");
					}
				})
				.fail(function(err) {
					swalert({
		                title: 'Error',
		                message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
		                type: 'error'
		            });
				})
				.always(function() {
					myLoader(false);
				});
			}
		});
		
	});

	$(document).on('click', '#btn-action-save', function(event) {
		event.preventDefault();
		/* Act on the event */
		myLoader();
		let form = $("#modal-action").find('.modal-body').find('form');
		let action = form.attr('action');
		let formData = form.serialize();

		let ladda_button = Ladda.create(this);

		$.ajax({
			url: action,
			type: 'POST',
			dataType: 'json',
			data: formData,
		})
		.done(function(data) {
			swalert({
                title: 'Informasi',
                message: data.message,
                type: data.type
            });

			if (data.type == 'success') {
				$("#modal-action").modal('hide');
			}
		})
		.fail(function(err) {
			swalert({
                title: 'Gagal proses pembatalan',
                message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
                type: 'error'
            });
		})
		.always(function() {
			myLoader(false);
			ladda_button.stop();
			datatableReload();
		});
		
	});

	$(document).on('click', '#btn-save-mass', function(event) {
		event.preventDefault();
		/* Act on the event */

		let form = $('#form-cuti-bersama');
		let formData = new FormData(form[0]);
		let modal = $("#modal-mass-leave");

		customConfirmation({
			message: `Dengan ini anda setuju untuk menerapkan cuti bersama
						ke seluruh karyawan LP3I. Apakah anda yakin akan melanjutkan?`,
			title: 'Konfirmasi',
			type: 'warning'
		}, (confirmation) => {
			if (confirmation) {
				let ladda_button = Ladda.create(this);
				disableFormModal(form);

				$.ajax({
					url: '<?= site_url("/cuti/permohonan/cuti-bersama") ?>',
					type: 'POST',
					dataType: 'json',
					data: formData,
					cache:false,
			        processData: false,
			        contentType: false,
				})
				.done(function(data) {
					swalert({
		                title: 'Informasi',
		                message: data.message,
		                type: data.type
		            });

					if (data.type == 'success') {
						modal.modal('hide');
					}
				})
				.fail(function() {
					swalert({
		                title: 'Gagal proses permohonan',
		                message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
		                type: 'error'
		            });
				})
				.always(function() {
					ladda_button.stop();
					datatableReload();
					disableFormModal(form, false);
				});
			}
		})
		
	});
</script>