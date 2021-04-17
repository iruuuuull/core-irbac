<script src="<?= base_url('/web/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/scripts/datatable.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/datatables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/table-datatables-responsive.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
	var modal = $("#modal-time-off");
	var tipe_cuti;

	$(document).ready(function() {
		$('.date-picker input').each(function() {
			$(this).datepicker('remove');
			$(this).datepicker({
				format: 'dd-mm-yyyy',
    			autoclose: true,
    			startDate: new Date,
    			daysOfWeekDisabled: [0]
			});
		});

		$('#id_tanggal_mulai').datepicker().on('changeDate', function(e) {
	        let selected_date = e.date;
	        let type_taken = $('#id_type_taken').val();

	        if (typeof selected_date !== 'undefined' && $('#id_type_taken').val()) {
	        	let tipe = tipe_cuti[$('#id_type_taken').val()];

	        	<?php # Cek jika tipe cuti memiliki limit, maka gunakan batasan dari leave balance ?>
		        if (tipe != null && tipe.is_limited == 1) {
		        	// if (type_taken == 1) {
		        	// 	selected_date.setDate(selected_date.getDate() + <?= $model_user->userDetail->last_leave ?> - 1);
		        	// } else if (type_taken == 2) {
		        		selected_date = new Date(new Date().getFullYear(),12,0)
		        		// selected_date.setDate(selected_date.getDate() + <?= $model_user->userDetail->current_leave ?> - 1);
		        	// } else {
		        	// 	selected_date.setDate(selected_date.getDate() + <?= $model_user->userDetail->getCutiCount() ?> - 1);
		        	// }

		        	$('#id_tanggal_akhir').datepicker('setEndDate', selected_date);
		        }

	        	<?php # Cek jika tipe cuti memiliki jangka hari, maka gunakan batasan dari jangka hari tersebut ?>
		        else if (tipe != null && tipe.range_day > 0) {
		        	let endDate = addWorkDays(selected_date, parseInt(tipe.range_day));
		        	$('#id_tanggal_akhir').datepicker('setEndDate', endDate);
		        }
	        }

	    });

		datatable2($("#table-timeoff"), "<?= site_url('/cuti/permohonan/get-data') ?>");
	});

	modal.on('hidden.bs.modal', function () {
        let form = $(this).find('#form-cuti');
        form.find(".fileinput").fileinput('clear');
        
        form.find('input').val('');
		form.find('textarea').val('');
		form.find('select').val('').trigger('change');
    });

    $(document).on('click', '.show-note', function(event) {
    	event.preventDefault();
    	/* Act on the event */

    	let note = $(this).data('note');
    	$("#modal-note").find('.modal-title').text("Catatan");
    	$("#modal-note").find('.modal-body').html(note);
    	$("#modal-note").modal('show');
    });

    $(document).on('click', '#btn-request', function(event) {
    	event.preventDefault();
    	/* Act on the event */
    	myLoader();
    	$("#info-request").text('');

    	$.ajax({
    		url: '<?= base_url('/cuti/permohonan/get-existing') ?>',
    		type: 'GET',
    		dataType: 'json',
    	})
    	.done(function(data) {
    		if (Object.keys(data.data).length > 0) {
    			$("#info-request").text('Pengajuan cuti sebelumnya belum selesai');
    		} else {
    			modal.modal('show');
    		}
    	})
    	.fail(function(xhr, status, error) {
			var err = eval("(" + xhr.responseText + ")");
			console.error(err.Message);
    	})
    	.always(function() {
    		myLoader(false);
    	});
    	

    });

    $(document).on('click', '.show-tracking', function(event) {
    	event.preventDefault();
    	/* Act on the event */

    	let id = $(this).data('id');
    	if (id) {
    		$.ajax({
    			url: '<?= base_url('/cuti/permohonan/get-tracking/') ?>' + id,
    			type: 'GET',
    			dataType: 'json',
    		})
    		.done(function(data) {
    			if (data.status == 'success') {
    				if (data.data.length > 0) {
    					$("#modal-note").find('.modal-title').text("Daftar Verifikasi");
	    				$("#modal-note").find('.modal-body').html(data.data);
	    				$("#modal-note").modal('show');
    				}
    			} else {
    				swalert(data.message);
    			}
    		})
    		.fail(function(xhr, status, error) {
				var err = eval("(" + xhr.responseText + ")");
				console.error(err.Message);
	    	})
    		.always(function() {
    		});
    		
    	}
    });

    $(document).on('click', '.reset-date', function(event) {
    	event.preventDefault();
    	/* Act on the event */

    	$(this).parent().parent().find('input').each(function(index, el) {
    		$(this).val('');
    		$(this).datepicker('setDate', null)
    	});
    });

    $(document).on('change', '#id_cuti_type, #id_type_taken', function(event) {
    	$(".reset-date").trigger('click');
    });

    $(document).on('change', '#id_cuti_type', function(event) {
    	let type_id = $(this).val();

    	if (type_id) {
    		myLoader();

	    	$.ajax({
	    		url: '<?= site_url('/cuti/permohonan/tipe-cuti/') ?>' + type_id,
	    		type: 'GET',
	    		dataType: 'json',
	    	})
	    	.done(function(res) {
	    		if (res.data) {
	    			tipe_cuti = res.meta;

	    			let dropdown = $('#id_type_taken');
					dropdown.empty();

					dropdown.append(`<option value="">- Pilih Tipe Diambil -</option>`);
					$.each(res.data, function(index, val) {
						if (isNaN(index)) {
							if (val.length == 0) return;

							let opts = `<optgroup label="${index}">`;

							$.each(val, function(index1, val1) {
								opts += `<option value="${index1}">${val1.name}</option>`;
							})

							opts += `</optgroup>`;

							dropdown.append(opts);

						} else {
							dropdown.append(`<option value="${index}">${val.name}</option>`);
						}
					});

					dropdown.focus();
	    		}
	    	})
	    	.fail(function(xhr, status, error) {
				var err = eval("(" + xhr.responseText + ")");
				console.error(err.Message);
	    	})
	    	.always(function() {
    			myLoader(false);
	    	});
    	} else {
    		$("#div-type_taken").hide().find('select').val('').trigger('change');
    	}
    });

	function disableFormModal(form, disabled = true) {
		form.find('input').prop('disabled', disabled);
		form.find('textarea').prop('disabled', disabled);
		form.find('select').prop('disabled', disabled);
		form.find('button').prop('disabled', disabled);
	}
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
					"targets": [ 0 ], 
					"orderable": false, 
				},
				{
					className: 'text-center', targets: [0, 1, 2, 3, 4, 6, 7, 8]
				},
			],
		});
	}
</script>
