<script src="<?= base_url('/web/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/scripts/datatable.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/datatables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/table-datatables-responsive.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
	var modal = $("#modal-action");

	$(document).ready(function() {
		datatable2($("#table-timeoff"), "<?= site_url('/cuti/verifikasi/get-data') ?>");
	});

	modal.on('hidden.bs.modal', function () {
        let form = $(this).find('#form-verifikasi');
		let action = form.attr('action');

        form.find('input').val('');
		form.find('textarea').val('');
		form.find('select').val('').trigger('change');

		form.attr('action', action.replace(/\/([^\/]*)$/, '/_id_'));
    });

    $(document).on('click', '.show-note', function(event) {
    	event.preventDefault();
    	/* Act on the event */

    	let note = $(this).data('note');
    	$("#modal-note").find('.modal-body').html(note);
    	$("#modal-note").modal('show');
    });

    $(document).on('click', '.btn-verify', function(event) {
    	event.preventDefault();
    	let id = $(this).data('id');

    	myLoader();

    	if (id) {
    		let form = $("#form-verifikasi");
			let action = form.attr('action').replace('_id_', id);
			form.attr('action', action);

			$.ajax({
				url: '<?= base_url('/cuti/verifikasi/get/') ?>' + id,
				type: 'GET',
				dataType: 'json',
			})
			.done(function(data) {
				if (data) {
					$("#id_status").val(data.status).trigger('change');
					$("#id_note").val(data.note);
				}
			})
			.fail(function(xhr, status, error) {
				var err = eval("(" + xhr.responseText + ")");
				console.error(err.Message);
			})
			.always(function() {
    			myLoader(false);
			});

    		modal.modal('show');
    	}
    });

    $("#btn-save").click(function(event) {
		let form = $("#form-verifikasi");
		let action = form.attr('action');
		let ladda_button = Ladda.create(this);

		$.ajax({
			url: action,
			type: 'POST',
			dataType: 'json',
			data: form.serialize(),
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
		.fail(function(xhr, status, error) {
			swalert({
                title: 'Gagal proses verifikasi',
                message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
                type: 'error'
            });

            var err = eval("(" + xhr.responseText + ")");
				console.error(err.Message);
		})
		.always(function() {
			ladda_button.stop();
			datatableReload();
		});
		
	});

	$(document).on('click', '.btn-cancel', function(event) {
		event.preventDefault();
		/* Act on the event */

		myLoader();
		let id = $(this).data('id');
		let modal = $("#modal-cancel");
		modal.find('.modal-title').text('Form Pembatalan Cuti');

		$.ajax({
			url: '<?= site_url('/cuti/verifikasi/get-cancel-form/') ?>' + id,
			type: 'GET',
			dataType: 'html',
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
		
	});

	$(document).on('click', '#btn-action-save', function(event) {
		event.preventDefault();
		/* Act on the event */
		myLoader();
		let form = $("#modal-cancel").find('.modal-body').find('form');
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
				$("#modal-cancel").modal('hide');
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
</script>

<script type="text/javascript" id="datatable-script">
	function datatable2(id, url) {
		var table = id;
		var fixedHeaderOffset = 0;

		if (App.getViewPort().width < App.getResponsiveBreakpoint('md')) {
		if ($('.page-header').hasClass('page-header-fixed-mobile')) {
		fixedHeaderOffset = $('.page-header').outerHeight(true);
		} 
		} else if ($('.page-header').hasClass('navbar-fixed-top')) {
		fixedHeaderOffset = $('.page-header').outerHeight(true);
		} else if ($('body').hasClass('page-header-fixed')) {
		fixedHeaderOffset = 64; // admin 5 fixed height
		}

		oTable = table.DataTable({ 
			"language": {
				"aria": {
					"sortAscending": ": activate to sort column ascending",
					"sortDescending": ": activate to sort column descending"
				},
				"emptyTable": "Data tidak tersedia",
				"info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
				"infoEmpty": "Data tidak ditemukan",
				// "infoFiltered": "",
				"lengthMenu": "Tampilkan _MENU_",
				"search": "Cari:",
				"zeroRecords": "Tidak ditemukan data yang cocok",
				"paginate": {
					"previous":"Sebelumnya",
					"next": "Selanjutnya",
					"last": "Terakhir",
					"first": "Pertama"
				}
			},
			"lengthMenu": [
				[20, 50, 100, -1],
				[20, 50, 100, "All"] // change per page values here
			],
			fixedHeader: {
				header: true,
				headerOffset: fixedHeaderOffset
			},
			"pageLength": 20,
			"destroy": true,
			"bStateSave": true,
			"processing": true, 
			"serverSide": true, 
			"order": [],
			"ajax": {
				"url": url,
				"type": "POST",
				"data": {
					[csrf_name]: csrf_hash
				},
			},
			"columnDefs": [
				{ 
					"targets": [ 0, 5, 8, 9 ], 
					"orderable": false, 
				},
				{
					className: 'text-center', targets: [0, 2, 3, 4, 6, 7, 8]
				},
			],
		});
	}
</script>
