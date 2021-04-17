<script type="text/javascript">
    $(document).on('change', '#id_unit', function(event) {
        event.preventDefault();

        let id = $(this).val();

        if (id) {
            $.ajax({
                url: '<?= site_url('/rbac/user/get-department/') ?>'+ id,
                type: 'GET',
                dataType: 'json',
            })
            .done(function(data) {
                let dropdown_dep = $("#id_department");

                dropdown_dep.empty();

                dropdown_dep.append(`<option value="">- Pilih Department -</option>`);

                $.each(data, function(index, val) {
                    dropdown_dep.append(`<option value="${index}">${val}</option>`);
                });
            })
            .fail(function() {
                console.error("Get data department failed");
            });
        }

    });

    $(document).on('change', '#id_unit, #id_department, #id_grade, #id_golongan', function(event) {
        event.preventDefault();

        let id_unit = $("#id_unit").val();
        let id_department = $("#id_department").val();
        let id_grade = $("#id_grade").val();
        let golongan = $("#id_golongan").val();

        if (
            id_unit.length > 0 
            && id_department.length > 0 
            && id_grade
            && golongan
        ) {
            $.ajax({
                url: '<?= base_url("/rbac/user/get-atasan") ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    id_unit: id_unit,
                    id_department: id_department,
                    id_grade: id_grade,
                    golongan: golongan,
                    [csrf_name] : csrf_hash
                },
            })
            .done(function(data) {
                // START SET DROPDOWN ATASAN
                let dropdown_atasan = $("#id_atasan");
                dropdown_atasan.empty();

                dropdown_atasan.append(`<option value="">- Pilih Atasan -</option>`);
                $.each(data, function(index, val) {
                    dropdown_atasan.append(`<option value="${index}">${val}</option>`);
                });
                // END SET DROPDOWN ATASAN
            })
            .fail(function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                console.error(err.Message);
            })
            .always(function() {
            });
            
        }
    });

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