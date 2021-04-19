<script src="<?= base_url('/web/assets/plugins/datatables/jquery.dataTables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
	var modal = $("#draggable");

    $(document).ready(function() {
    	datatable($("#table-group"), "<?= site_url('/rbac/permission/get-data') ?>");
    });

    modal.on('hidden.bs.modal', function () {
	    let forms = $(this).find('form');

	    $.each(forms, function(index, val) {
	    	// Reset input
	    	$.each($(this).find('input, textarea'), function(index, val) {
	    		if ($(this).attr('name') != 'csrf_lpei_hris') {
	    			$(this).val('');
	    		}
	    	});
	    });
	});

    $(document).on('click', '#btn-add', function(event) {
    	$('#btn-save').show();
        $('#field-single').attr('disabled', false);

        let form = $('#form-single');
        form.attr('action', "<?= site_url('/rbac/permission/simpan/') ?>");

    	modal.find('.modal-title').text('Form Master Permission');
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
    		modal.modal('hide');
    		// ladda.stop();
    	});

    });

    $(document).on('click', '.btn-preview', function(event) {
    	event.preventDefault();
        $('#field-single').attr('disabled', true);

    	let id = $(this).data('id');

    	$.ajax({
    		url: "<?= site_url('/rbac/permission/detail/') ?>" + id,
    		type: 'GET',
    		dataType: 'json',
    	})
    	.done(function(data) {
    		if (data) {
	    		let form = $('#form-single');
	    		form.attr('action', '');
	    		form.find('#id_name').val(data.name);
	    		form.find('#id_description').val(data.description);

	    		$('#btn-save').hide();
		    	modal.find('.modal-title').text('Detail Permission');
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
        $('#field-single').attr('disabled', false);

    	let id = $(this).data('id');

    	$.ajax({
    		url: "<?= site_url('/rbac/permission/detail/') ?>" + id,
    		type: 'GET',
    		dataType: 'json',
    	})
    	.done(function(data) {
    		if (data) {
	    		let form = $('#form-single');
	    		form.attr('action', "<?= site_url('/rbac/permission/simpan/') ?>" + id);
	    		form.find('#id_name').val(data.name);
	    		form.find('#id_description').val(data.description);

	    		$('#btn-save').show();
		    	modal.find('.modal-title').text('Detail Permission');
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
	    	if (confirmation) {
		    	$.ajax({
		    		url: "<?= site_url('/rbac/permission/hapus/') ?>" + id,
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
