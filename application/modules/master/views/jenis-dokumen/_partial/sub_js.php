<script src="<?= base_url('/web/assets/global/scripts/datatable.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/datatables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/table-datatables-responsive.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/jquery-ui/jquery-ui.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/ui-modals.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/jquery-repeater/jquery.repeater.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
	var modal = $("#draggable");

    $(document).ready(function() {
    	datatable($("#table-dokumen"), "<?= site_url('/master/jenis-dokumen/get-data') ?>");
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
    	modal.find('.modal-title').text('Form Master Jenis Dokumen');
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
    		url: "<?= site_url('/master/jenis-dokumen/detail/') ?>" + id,
    		type: 'GET',
    		dataType: 'json',
    	})
    	.done(function(data) {
    		if (data) {
		    	$('#field-single').attr('disabled', true).show();
	    		$('#field-multiple').hide();

	    		let form = $('#form-single');
	    		form.attr('action', '');
	    		form.find('#id_kode_dokumen').val(data.kode_dokumen);
	    		form.find('#id_label').val(data.label);
                form.find('#id_desc').val(data.desc);

	    		$('#btn-save').hide();
		    	modal.find('.modal-title').text('Detail Jenis Dokumen');
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
    		url: "<?= site_url('/master/jenis-dokumen/detail/') ?>" + id,
    		type: 'GET',
    		dataType: 'json',
    	})
    	.done(function(data) {
    		if (data) {
		    	$('#field-single').attr('disabled', false).show();
	    		$('#field-multiple').hide();

	    		let form = $('#form-single');
	    		form.attr('action', "<?= site_url('/master/jenis-dokumen/edit/') ?>" + id);
	    		form.find('#id_kode_dokumen').val(data.kode_dokumen);
	    		form.find('#id_label').val(data.label);
                form.find('#id_desc').val(data.desc);

	    		$('#btn-save').show();
		    	modal.find('.modal-title').text('Detail Jenis Dokumen');
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
		    		url: "<?= site_url('/master/jenis-dokumen/hapus/') ?>" + id,
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
