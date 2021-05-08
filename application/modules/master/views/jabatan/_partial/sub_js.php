<script src="<?= base_url('/web/assets/plugins/datatables/jquery.dataTables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/jquery-repeater/jquery.repeater.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
	var modal = $("#draggable");

    $(document).ready(function() {
    	datatable($("#table-jabatan"), "<?= site_url('/master/jabatan/get-data') ?>");
    });

    modal.on('hidden.bs.modal', function () {
	    let forms = $(this).find('form');

	    $.each(forms, function(index, val) {
	    	// Deleting repeater
	    	$.each($(this).find('.mt-repeater-delete'), function(index, val) {
	    		$(this).trigger('click');
	    	});

	    	// Reset input
	    	$.each($(this).find('input'), function(index, val) {
	    		if ($(this).attr('name') != 'csrf_lpei_hris') {
	    			$(this).val('');
	    		}
	    	});
	    });
	});

    $(document).on('click', '#btn-add', function(event) {
    	$('#field-multiple').show();
    	$('#field-single').hide();

    	$('#btn-save').show();
    	modal.find('.modal-title').text('Form Master Jabatan');
    	modal.modal('show');
    });

    $(document).on('click', '#btn-save', function(event) {
    	event.preventDefault();
    	
    	let active_fieldset = modal.find('fieldset:visible');
    	let active_form = active_fieldset.find('form');
    	let form_data = active_form.serialize();
    	let form_action = active_form.attr('action');

    	// var ladda = Ladda.create(this);

    	$.ajax({
    		url: form_action,
    		type: 'POST',
    		dataType: 'json',
    		data: form_data,
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
    		// ladda.stop();
    	});

    });

    $(document).on('click', '.btn-preview', function(event) {
    	event.preventDefault();

    	let id = $(this).data('id');

    	$.ajax({
    		url: "<?= site_url('/master/jabatan/detail/') ?>" + id,
    		type: 'GET',
    		dataType: 'json',
    	})
    	.done(function(data) {
    		if (data) {
		    	$('#field-single').attr('disabled', true).show();
	    		$('#field-multiple').hide();

	    		let form = $('#form-single');
	    		form.attr('action', '');
	    		form.find('#id_kode_jabatan').val(data.kode_jabatan);
	    		form.find('#id_nama_jabatan').val(data.nama_jabatan);

	    		$('#btn-save').hide();
		    	modal.find('.modal-title').text('Detail Jabatan');
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
    		url: "<?= site_url('/master/jabatan/detail/') ?>" + id,
    		type: 'GET',
    		dataType: 'json',
    	})
    	.done(function(data) {
    		if (data) {
		    	$('#field-single').attr('disabled', false).show();
	    		$('#field-multiple').hide();

	    		let form = $('#form-single');
	    		form.attr('action', "<?= site_url('/master/jabatan/edit/') ?>" + id);
	    		form.find('#id_kode_jabatan').val(data.kode_jabatan);
	    		form.find('#id_nama_jabatan').val(data.nama_jabatan);

	    		$('#btn-save').show();
		    	modal.find('.modal-title').text('Detail Jabatan');
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
		    		url: "<?= site_url('/master/jabatan/hapus/') ?>" + id,
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
