<script src="<?= base_url('/web/assets/plugins/datatables/jquery.dataTables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
	var modal = $("#draggable");

	$(document).ready(function() {
		datatable($("#table-unit"), "<?= site_url('/master/unit/get-data') ?>");
	});

	modal.on('hidden.bs.modal', function () {
		let forms = $(this).find('#form-unit');
		$(".close.fileinput-exists").trigger('click');

		$.each(forms, function(index, val) {
          // Deleting repeater
          $.each($(this).find('[name^=Unit]'), function(index, val) {
          	$(this).val('');
          });
      });

		modal.find('#field-unit').attr('disabled', false);
	});

	$(document).on('submit', '#form-unit', function(event) {
		event.preventDefault();
		
		let formData = new FormData(this);
		let action = $(this).attr('action');

		var ladda = Ladda.create(this);

		$.ajax({
			url: action,
			type: 'POST',
			dataType: 'json',
			data: formData,
			cache:false,
			processData: false,
			contentType: false,
		})
		.done(function(data) {
			swalert(data.message);
		})
		.fail(function() {
			swalert({
				title: 'Gagal simpan',
				message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
				type: 'error'
			});
		})
		.always(function() {
			datatableReload();
			modal.modal('hide')
			ladda.stop();
		});

	});

	$(document).on('click', '#btn-add', function(event) {
		event.preventDefault();

		modal.find('#form-unit').attr('action', "<?= site_url('/master/unit/tambah/') ?>");
		modal.modal('show');
	});


	$(document).on('click', '.btn-preview', function(event) {
		event.preventDefault();

		let id = $(this).data('id');

		$.ajax({
			url: "<?= site_url('/master/unit/detail/') ?>" + id,
			type: 'GET',
			dataType: 'json',
		})
		.done(function(data) {
			if (data) {
				$('#field-unit').attr('disabled', true).show();

				let form = $('#form-unit');
				form.attr('action', '');
				
				setTimeout(() => {
					form.find('#id_kode_induk').val(data.kode_induk).trigger('change');
				}, 300); 

				form.find('#id_kode_unit').val(data.kode_unit);
				form.find('#id_nama_unit').val(data.nama_unit);

				setTimeout(() => {
					form.find('#id_unit_level').val(data.unit_level).trigger('change');
				}, 300);

				setTimeout(() => {
					form.find('#id_owner_unit').val(data.kode_owner).trigger('change');
				}, 300);

				setTimeout(() => {
					form.find('#id_group_unit').val(data.sbu_id).trigger('change');
				}, 300);

				setTimeout(() => {
					form.find('#id_timezone_unit').val(data.timezone).trigger('change');
				}, 300);


				$('#btn-save').hide();
				modal.find('.modal-title').text('Detail Unit');
				modal.modal('show');
			} else {
				swalert('Data tidak ditemukan');
			}
		})
		.fail(function() {
			swalert({
				title: 'Gagal ambil data',
				message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
				type: 'error'
			});
		})
		.always(function() {
		});
	});

	$(document).on('click', '.btn-edit', function(event) {
		event.preventDefault();

		let id = $(this).data('id');

		$.ajax({
			url: "<?= site_url('/master/unit/detail/') ?>" + id,
			type: 'GET',
			dataType: 'json',
		})
		.done(function(data) {
			if (data) {
				$('#field-unit').attr('disabled', false).show();

				let form = $('#form-unit');
				form.attr('action', "<?= site_url('/master/unit/edit/') ?>" + id);
				
				setTimeout(() => {
					form.find('#id_kode_induk').val(data.kode_induk).trigger('change');
				}, 300); 

				form.find('#id_kode_unit').val(data.kode_unit);
				form.find('#id_nama_unit').val(data.nama_unit);

				setTimeout(() => {
					form.find('#id_unit_level').val(data.unit_level).trigger('change');
				}, 300);

				setTimeout(() => {
					form.find('#id_owner_unit').val(data.kode_owner).trigger('change');
				}, 300);

				setTimeout(() => {
					form.find('#id_group_unit').val(data.sbu_id).trigger('change');
				}, 300);

				setTimeout(() => {
					form.find('#id_timezone_unit').val(data.timezone).trigger('change');
				}, 300);


				$('#btn-save').show();
				modal.find('.modal-title').text('Detail Edit Unit');
				modal.modal('show');
			} else {
				swalert('Data tidak ditemukan');
			}
		})
		.fail(function() {
			swalert({
				title: 'Gagal ambil data',
				message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
				type: 'error'
			});
		})
		.always(function() {
		});
	});

	$(document).on('click', '.btn-delete', function(event) {
		event.preventDefault();

		let id = $(this).data('id');
		
		customConfirmation({type:'warning'}, (confirmation) => {
			console.log(confirmation);
			if (confirmation) {
				$.ajax({
					url: "<?= site_url('/master/unit/hapus/') ?>" + id,
					type: 'POST',
					dataType: 'json',
					data: {
						[csrf_name] : csrf_hash
					}
				})
				.done(function(data) {
					swalert(data.message);
				})
				.fail(function() {
					swalert({
						title: 'Gagal ambil data',
						message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
						type: 'error'
					});
				})
				.always(function() {
					datatableReload();
				});
			}
		})

	});


</script>