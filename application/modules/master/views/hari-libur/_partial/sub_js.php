<script src="<?= base_url('/web/assets/global/scripts/datatable.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/datatables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/table-datatables-responsive.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/ui-modals.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
	var modal = $("#draggable");
    var min_date = "<?= $min_date ?>";
    var max_date = "<?= $max_date ?>";

    $(document).ready(function() {
    	datatable($("#table-dokumen"), "<?= site_url('/master/hari-libur/get-data') ?>");

        $('#id_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            startDate: min_date,
            endDate: max_date,
            todayHighlight: true,
        });
    });

    modal.on('hidden.bs.modal', function () {
	    let forms = $(this).find('form');

	    $.each(forms, function(index, val) {
	    	$.each($(this).find('input'), function(index, val) {
	    		if ($(this).attr('name') != 'csrf_lpei_hris') {
	    			$(this).val('');
	    		}
	    	});

            $.each($(this).find('textarea'), function(index, val) {
                $(this).val('');
            });
	    	// Reset input
	    });
	});

    $(document).on('click', '#btn-add', function(event) {
        let form = $('#form-single');
        form.attr('action', "<?= site_url('/master/hari-libur/tambah') ?>");

    	$('#btn-save').show();
    	modal.find('.modal-title').text('Form Master Hari Libur');
    	modal.modal('show');
    });

    $(document).on('click', '#btn-save', function(event) {
    	event.preventDefault();
    	
    	let active_fieldset = modal.find('fieldset:visible');
    	let active_form = active_fieldset.find('form');
    	let form_data = active_form.serializeArray();
        let action = active_form.attr('action');

        ajaxAddEvent(form_data, action, this);

    });

    $(document).on('click', '.btn-preview', function(event) {
    	event.preventDefault();

    	let id = $(this).data('id');
    	ajaxDetailEvent(id);
    });

    $(document).on('click', '.btn-edit', function(event) {
    	event.preventDefault();

    	let id = $(this).data('id');
        ajaxDetailEvent(id, true);
    });

    $(document).on('click', '.btn-delete', function(event) {
    	event.preventDefault();

    	let id = $(this).data('id');
    	
    	customConfirmation({type:'warning'}, (confirmation) => {
	    	if (confirmation) {
		    	$.ajax({
		    		url: "<?= site_url('/master/hari-libur/hapus/') ?>" + id,
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

    function ajaxAddEvent(data_form, action, button = null) {
        if (button !== null) {
            // var ladda = Ladda.create(button);
        }

        $.ajax({
            url: action,
            type: 'POST',
            dataType: 'json',
            data: data_form,
        })
        .done(function(data) {
            if (data.is_validate == true) {
                customConfirmation({
                    type:'info',
                    message:data.message
                }, (confirmation) => {
                    if (confirmation) {
                        data_form.push({name: 'validate', value: true});

                        ajaxAddEvent(data_form, button);
                    }
                })

            } else {
                swalert(data.message);
            }
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

            if (button !== null) {
                // ladda.stop();
            }
        });
    }

    function ajaxDetailEvent(id, update = false) {
        $.ajax({
            url: "<?= site_url('/master/hari-libur/detail/') ?>" + id,
            type: 'GET',
            dataType: 'json',
        })
        .done(function(data) {
            if (data) {
                let form = $('#form-single');

                if (update === false) {
                    $('#field-single').attr('disabled', true);

                    form.attr('action', '');
                    $('#btn-save').hide();
                } else {
                    $('#field-single').attr('disabled', false);

                    form.attr('action', "<?= site_url('/master/hari-libur/edit/') ?>" + id);
                    $('#btn-save').show();
                }

                form.find('#id_date').val(data.date);
                form.find('#id_event').val(data.event);
                modal.find('.modal-title').text('Detail Hari Libur');
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
    }

</script>
