<script type="text/javascript" id="script_career">
	var action_karir_lp3i = "<?= base_url('/profil/simpan-karir') ?>";
    var action_edit_karir_lp3i = "<?= base_url('/profil/simpan-karir/_id_') ?>";
    var modal_karir = $("#modal-karir");

    $(document).on('click', '#btn_karir_lp3i', function() {
        if (id) {
            datatable($("#table-karir-lp3i"), "<?= site_url('profil/getdata-karir-lp3i/') ?>" + id);

            action_karir_lp3i += `?id=${id}`;
            action_edit_karir_lp3i += `?id=${id}`;
        } else {
            datatable($("#table-karir-lp3i"), "<?= site_url('profil/getdata-karir-lp3i') ?>");
        }
    });

    modal_karir.on('hidden.bs.modal', function () {
        let forms = $(this).find('#form-add-karir');
        // $(".close.fileinput-exists").trigger('click');

        $.each(forms, function(index, val) {
          // Deleting repeater
          $.each($(this).find('[name^=Karir]'), function(index, val) {
            $(this).val('');
          });
        });

        modal_karir.find('#field-karir').attr('disabled', false);
        modal_karir.find('#btn-simpan-karir').show();
        $('#file-exist-karir').hide();
    });

    $(document).on('click', '#btn-add-karir', function(event) {
    	event.preventDefault();
        modal_karir.find('#form-add-karir').attr('action', action_karir_lp3i);
        modal_karir.modal('show');
    });

    $(document).on('click', '.btn-edit-karir-lp3i', function(event) {
        event.preventDefault();
        var id = $(this).attr('data-id');
        
        getKarir(id)
        modal_karir.find('#form-add-karir').attr('action', action_edit_karir_lp3i.replace('_id_', id));
        modal_karir.modal('show');
    });

    $(document).on('click', '.btn-preview-karir-lp3i', function(event) {
        event.preventDefault();
        let id = $(this).attr('data-id');

        getKarir(id);

        modal_karir.find('#field-karir').attr('disabled', true);
        modal_karir.find('#btn-simpan-karir').hide();
        modal_karir.modal('show');  
    });

    $(document).on('submit', '#form-add-karir', function(event) {
        event.preventDefault();
        
        var formData = new FormData(this);
        let action = $(this).attr('action');

        $.ajax({
        url  : action,
        type : 'POST',
        dataType: 'json',
        data :  formData ,
        cache:false,
        processData: false,
        contentType: false,
    })
        .done(function(data){
            swalert(data.message);
        })
        .fail(function(err) {
            console.error(err);
        })
        .always(function() {
            modal_karir.modal('hide');
            datatableReload();
        })
    });

    function getKarir(id) {
        let forms = modal_karir.find('#form-add-karir');
      
        $.ajax({
            url: "<?= site_url('profil/detail-karir/') ?>" + id,
            type: 'GET',
        	dataType: 'json',
        })
    	.done(function(data) {
	        forms.find('#id_group').val(data.sbu_id).trigger('change');
            forms.find('#id_unit').val(data.unit_id).trigger('change');
	        forms.find('#id_level').val(data.grade_id);
	        forms.find('#id_job_title').val(data.Job_title);
	        forms.find('#id_tanggal_sk').val(data.tanggal_sk);
	        forms.find('#id_tanggal_berakhir').val(data.tanggal_berakhir);

	        setTimeout(() => {
	           forms.find('#id_departement').val(data.department_id);
	        }, 500);

            forms.find('#id_jenis_grading').val(data.jenis_grading).trigger('change');
            forms.find('#id_grade').val(data.grade_id).trigger('change');

            setTimeout(() => {
                forms.find('#id_golongan').val(data.golongan).trigger('change');

                setTimeout(() => {
                    $("#id_grading_type_id").val(data.grading_type_id).trigger('change');

                    setTimeout(() => {
                        $("#id_designation").val(data.designation_id).trigger('change');
                    }, 1000);

                }, 1000);

            }, 500);

			let field_file_sk = $('#file-exist-karir');

			if (data.file_sk != null) {
		        field_file_sk.find('a').attr('onclick', 'return previewPDF("<?= base_url() ?>'+ data.file_sk +'")');
		        field_file_sk.find('a').text(data.file_sk.split('/').pop());
		        field_file_sk.show();
			} else {
		        field_file_sk.find('a').removeAttr('onclick');
		        field_file_sk.find('a').text('');
		        field_file_sk.hide();
			}
	    })
	    .fail(function(err) {
			console.error(err);
	    });
    }

    $(document).on('click', '.btn-delete-karir-lp3i', function(event) {
        event.preventDefault();

        let id = $(this).attr('data-id');
        
        customConfirmation({type:'warning'}, (confirmation) => {
            console.log(confirmation);
            if (confirmation) {
                $.ajax({
                    url: "<?= site_url('profil/delete-karir/') ?>" + id,
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

<script type="text/javascript" id="script-career-form">
	$(document).on('change', '#id_jenis_grading', function(event) {
        let id = $(this).val();

        if (id) {

            $.ajax({
                url: '<?= site_url('/rbac/user/get-grade') ?>',
                type: 'GET',
                dataType: 'json',
                data: {grading_type: id},
            })
            .done(function(res) {
                // START SET DROPDOWN KELOMPOK
                let dropdown_kelompok = $("#id_grading_type_id");
                dropdown_kelompok.empty();

                dropdown_kelompok.append(`<option value="">- Pilih Kelompok -</option>`);
                $.each(res.data, function(index, val) {
                    dropdown_kelompok.append(`<option value="${index}">${val}</option>`);
                });

                dropdown_kelompok.focus();
                // END SET DROPDOWN KELOMPOK
            })
            .fail(function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.error(err.Message);
            })
            .always(function() {
            });
        }
    });

    var designations = '';

    $(document).on('change', '#id_grading_type_id', function(event) {
        let id = $(this).val();

        if (id) {

            $.ajax({
                url: '<?= site_url('/rbac/user/get-designation') ?>',
                type: 'GET',
                dataType: 'json',
                data: {kelompok: id},
            })
            .done(function(res) {
                designations = res.data;

                // START SET DROPDOWN DESIGNATION
                let dropdown_designation = $("#id_designation");
                dropdown_designation.empty();

                dropdown_designation.append(`<option value="">- Pilih Designation -</option>`);
                $.each(res.data, function(index, val) {
                    dropdown_designation.append(`<option value="${index}">${val.name}</option>`);
                });

                dropdown_designation.focus();
                // END SET DROPDOWN DESIGNATION
            })
            .fail(function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.error(err.Message);
            })
            .always(function() {
            });
        }
    });

    var jabatan = '';

    $(document).on('change', '#id_jenis_grading, #id_grade, #id_golongan', function(event) {
        let grading_type = $('#id_jenis_grading').val();
        let grade = $('#id_grade').val();
        let golongan = $('#id_golongan').val();

        if (grading_type && grade && golongan) {

            $.ajax({
                url: '<?= site_url('/rbac/user/get-kelas-jabatan') ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    grading_type: grading_type,
                    grade: grade,
                    golongan: golongan
                },
            })
            .done(function(res) {
                $("#id_kelas_jabatan").val(res.data.kelas);
                $("#id_job_title").val(res.data.jabatan).trigger('change');

                jabatan = res.data.jabatan;
            })
            .fail(function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.error(err.Message);
            })
            .always(function() {
            });
        }
    });

    $(document).on('change', '#id_job_title, #id_designation', function(event) {
        setTimeout(() => {
            let designation_selected = $('#id_designation').val();
            let new_job_title = jabatan;

            if (designation_selected) {
                let designation = designations[designation_selected];

                if (designation.combined == 1) {
                    new_job_title = jabatan +' '+ designation.name;
                } else if (designation.combined == 0) {
                    new_job_title = designation.name;
                }
            }

            $('#id_job_title').val(new_job_title.trim());
        }, 500);
    });

    $(document).on('change', '#id_grade', function(event) {
        let grade = $(this).val();

        if (grade) {
            $.ajax({
                url: '<?= site_url("/rbac/user/get-golongan/") ?>' + grade,
                type: 'GET',
                dataType: 'json',
            })
            .done(function(res) {
                setDropdown(res.data, 'id_golongan', '- Pilih Golongan -')
            })
            .fail(function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.error(err.Message);
            });
        }
        
    });
</script>