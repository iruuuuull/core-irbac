<script src="<?= base_url('/web/assets/plugins/datatables/jquery.dataTables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>" type="text/javascript"></script>

<script src="<?= base_url('/web/assets/plugins/jcrop/js/jquery.color.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/jcrop/js/jquery.Jcrop.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
    //action pendidikan
    var action_pendidikan = "<?= base_url('/profil/simpan-history-pendidikan') ?>";
    var action_edit_pendidikan = "<?= base_url('/profil/simpan-history-pendidikan/_id_') ?>";
    //action keluarga
    var action_keluarga = "<?= base_url('/profil/simpan-info-keluarga') ?>";
    var action_edit_keluarga = "<?= base_url('/profil/simpan-info-keluarga/_id_') ?>";
    //action sertifikasi
    var action_sertfikasi = "<?= base_url('/profil/simpan-sertifikasi') ?>";
    var action_edit_sertfikasi = "<?= base_url('/profil/simpan-sertifikasi/_id_') ?>";
    //modal
	var modal_keluarga = $("#modal-keluarga");
	var modal_pendidikan = $("#modal-pendidikan");
	var modal_training = $("#modal-training");

    $(document).ready(function() {
        id = $("#user_id").val();

        $('#dob').datetimepicker({
            format: 'L'
        });
    });

    $(document).on('click', '#btn_biodata', function() {
        if (id) {
        	datatable($("#table-keluarga"), "<?= site_url('profil/getdata-info-keluarga/') ?>" + id);

            action_keluarga += `?id=${id}`;
            action_edit_keluarga += `?id=${id}`;
        } else {
            datatable($("#table-keluarga"), "<?= site_url('profil/getdata-info-keluarga') ?>");
        }
    });

    $(document).on('click', '#btn_sertifikasi', function() {
        if (id) {
        	datatable($("#table-sertifikasi"), "<?= site_url('profil/getdata-sertifikasi/') ?>" + id);

            action_sertfikasi += `?id=${id}`;
            action_edit_sertfikasi += `?id=${id}`;
        } else {
            datatable($("#table-sertifikasi"), "<?= site_url('profil/getdata-sertifikasi') ?>");
        }
    });

    $(document).on('click', '#btn_pendidikan', function() {
        if (id) {
        	datatable($("#table-pendidikan"), "<?= site_url('profil/getdata-history-pendidikan/') ?>" + id);

            action_pendidikan += `?id=${id}`;
            action_edit_pendidikan += `?id=${id}`;
        } else {
            datatable($("#table-pendidikan"), "<?= site_url('profil/getdata-history-pendidikan') ?>");
        }
    });

    modal_pendidikan.on('hidden.bs.modal', function () {
        let forms = $(this).find('#form-add-pendidikan');
        $(".close.fileinput-exists").trigger('click');

        $.each(forms, function(index, val) {
          // Deleting repeater
          $.each($(this).find('[name^=Pendidikan]'), function(index, val) {
            $(this).val('');
          });
        });

        modal_pendidikan.find('#field-pendidikan').attr('disabled', false);
        modal_pendidikan.find('#btn-simpan-pendidikan').show();
        $('#file-ijazah').hide();
    });

    modal_keluarga.on('hidden.bs.modal', function () {
        let forms = $(this).find('#form-add-keluarga');
        // $(".close.fileinput-exists").trigger('click');

        $.each(forms, function(index, val) {
          // Deleting repeater
          $.each($(this).find('[name^=Keluarga]'), function(index, val) {
            $(this).val('');
          });
        });

        modal_keluarga.find('#field-keluarga').attr('disabled', false);
        modal_keluarga.find('#btn-simpan-keluarga').show();
        // $('#file-ijazah').hide();
    });

    modal_training.on('hidden.bs.modal', function () {
        let forms = $(this).find('#form-add-sertifikasi');
        // $(".close.fileinput-exists").trigger('click');

        $.each(forms, function(index, val) {
          // Deleting repeater
          $.each($(this).find('[name^=Sertifikasi]'), function(index, val) {
            $(this).val('');
          });
        });

        modal_training.find('#field-sertifikasi').attr('disabled', false);
        modal_training.find('#btn-simpan-sertifikasi').show();
        $('#file-exist-sertifikasi').hide();
    });

    $(document).on('click', '#btn-add-keluarga', function(event) {
    	event.preventDefault();

        modal_keluarga.find('#form-add-keluarga').attr('action', action_keluarga);
        modal_keluarga.modal('show');
    });

    $(document).on('click', '.btn-edit-keluarga', function(event) {
    	var id = $(this).attr('data-id');

        getkeluarga(id);
        modal_keluarga.find('#form-add-keluarga').attr('action', action_edit_keluarga.replace('_id_', id));
        modal_keluarga.modal('show');
    
    });

$(document).on('click', '.btn-preview-keluarga', function(event) {
        var id = $(this).attr('data-id');

    getkeluarga(id);
    modal_keluarga.find('#btn-simpan-keluarga').hide();
    modal_keluarga.find('#field-keluarga').attr('disabled', true);
    modal_keluarga.modal('show');
    
});

     $(document).on('submit', '#form-add-keluarga', function(event) {
        event.preventDefault();

       let formData = new FormData(this);
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
            modal_keluarga.modal('hide');
            datatableReload();
        })

     });

     function getkeluarga(id){
        let forms = modal_keluarga.find('#form-add-keluarga');

        $.ajax({
            url: "<?= site_url('profil/detail-info-keluarga/') ?>" + id,
            type: 'GET',
            dataType: 'json',
        })
         .done(function(data) {
               
        
                forms.find('#id_nama_lengkap').val(data.nama_lengkap);
                forms.find('#id_tempat_lahir_keluarga').val(data.tempat_lahir);              
                forms.find('#id_tanggal_lahir_keluarga').val(data.tanggal_lahir);
                forms.find('#id_agama_keluarga').val(data.agama);                
                forms.find('#id_alamat_keluarga').val(data.alamat);                
                forms.find('#id_pekerjaan').val(data.pekerjaan);
                forms.find('#pendidikan_id').val(data.pendidikan_id);               
                forms.find('#id_relation_id').val(data.relation_id);
                forms.find('#id_no_ktp').val(data.no_ktp);

      // let field_paklaring = $('#file-ijazah');

    //   if (data.ijazah != null) {
    //     field_paklaring.find('a').attr('onclick', 'return previewPDF("<?= base_url() ?>'+ data.ijazah +'")');
    //     field_paklaring.find('a').text(data.ijazah.split('/').pop());
    //     field_paklaring.show();
    //   } else {
    //     field_paklaring.find('a').removeAttr('onclick');
    //     field_paklaring.find('a').text('');
    //     field_paklaring.hide();
    //   }
    })
    .fail(function(err) {
      console.error(err);
    });
     }


     $(document).on('click', '.btn-delete-keluarga', function(event) {
    	event.preventDefault();

    	let id = $(this).attr('data-id');
    	
    	customConfirmation({type:'warning'}, (confirmation) => {
    		console.log(confirmation);
	    	if (confirmation) {
		    	$.ajax({
		    		url: "<?= site_url('profil/hapus-info-keluarga/') ?>" + id,
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


$(document).on('click', '#btn-add-pendidikan', function(event) {
    	event.preventDefault();;

        modal_pendidikan.find('#form-add-pendidikan').attr('action', action_pendidikan);
        modal_pendidikan.modal('show');
});

$("#id_ijazah").change(function() {
       
        var file = this.files[0];
        var fileType = file.type;
        var match = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
        if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]))){
            alert('Sorry, only PDF, JPG, JPEG, & PNG files are allowed to upload.');
        $("#id_ijazah").val('');
            return false;
         }
});

$(document).on('click', '.btn-edit-pendidikan', function(event) {
        event.preventDefault();
        let id = $(this).attr('data-id');
       
        detailPendidikan(id);
        modal_pendidikan.find('#form-add-pendidikan').attr('action', action_edit_pendidikan.replace('_id_', id));
        modal_pendidikan.modal('show');

});

$(document).on('click', '.btn-preview-pendidikan', function(event) {
    event.preventDefault();

    let id = $(this).attr('data-id');
    detailPendidikan(id);

    modal_pendidikan.find('#btn-simpan-pendidikan').hide();
    modal_pendidikan.find('#field-pendidikan').attr('disabled', true);
    modal_pendidikan.modal('show');
});


    $(document).on('submit', '#form-add-pendidikan', function(event){
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
            modal_pendidikan.modal('hide');
            datatableReload();
        })
    });

    

    $(document).on('click', '.btn-delete-pendidikan', function(event) {
    	event.preventDefault();

    	let id = $(this).attr('data-id');
    	
    	customConfirmation({type:'warning'}, (confirmation) => {
    		console.log(confirmation);
	    	if (confirmation) {
		    	$.ajax({
		    		url: "<?= site_url('profil/delete-history-pendidikan/') ?>" + id,
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

    function detailPendidikan(id){
        let forms = modal_pendidikan.find('#form-add-pendidikan');
      
        $.ajax({
          url: '<?= site_url('profil/detail-history-pendidikan/') ?>'+ id,
          type: 'GET',
          dataType: 'json',
        })
    .done(function(data) {
                
        forms.find('#id_jenjang_pendidikan').val(data.m_pendidikan_id);
        forms.find('#id_nama_sekolah').val(data.nama_sekolah);              
        forms.find('#id_lokasi').val(data.lokasi);
        forms.find('#id_tahun_masuk').val(data.tahun_masuk);                
        forms.find('#id_tahun_keluar').val(data.tahun_keluar);
        forms.find('#id_jurusan').val(data.jurusan);                
        forms.find('#id_nilai_akhir').val(data.nilai_akhir);

      let field_paklaring = $('#file-ijazah');

      if (data.ijazah != null) {
        field_paklaring.find('a').attr('onclick', 'return previewPDF("<?= base_url() ?>'+ data.ijazah +'")');
        field_paklaring.find('a').text(data.ijazah.split('/').pop());
        field_paklaring.show();
      } else {
        field_paklaring.find('a').removeAttr('onclick');
        field_paklaring.find('a').text('');
        field_paklaring.hide();
      }
    })
    .fail(function(err) {
      console.error(err);
    });

    }

    $(document).on('click', '#btn-add-sertifikasi', function(event) {
    	event.preventDefault();

        modal_training.find('#form-add-sertifikasi').attr('action', action_sertfikasi);
        modal_training.modal('show');
    });

       $(document).on('click', '.btn-edit-sertifikasi', function(event) {
        event.preventDefault();
        let id = $(this).attr('data-id');

        getSertifikasi(id);

        modal_training.find('#form-add-sertifikasi').attr('action', action_edit_sertfikasi.replace('_id_', id));
        modal_training.modal('show');
    });

    $(document).on('click', '.btn-preview-sertifikasi', function(event) {
    event.preventDefault();

    let id = $(this).attr('data-id');
    getSertifikasi(id);

    modal_training.find('#field-sertifikasi').attr('disabled', true);
    modal_training.find('#btn-simpan-sertifikasi').hide();
    modal_training.modal('show');
    });


    $(document).on('submit', '#form-add-sertifikasi', function(event) {
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
            modal_training.modal('hide');
            datatableReload();
        })

    });




    function getSertifikasi(id){
        let forms = modal_training.find('#form-add-sertifikasi');
      
      $.ajax({
            url: "<?= site_url('profil/detail-sertifikasi/') ?>" + id,
            type: 'GET',
            dataType: 'json',
          })

    .done(function(data) {
    
        forms.find('#id_nama_pelatihan').val(data.nama_pelatihan);
        forms.find('#id_penyelenggara').val(data.penyelenggara);                
        forms.find('#id_lokasi_sertifikasi').val(data.lokasi);
        forms.find('#id_tanggal_mulai_sertifikasi').val(data.tanggal_mulai);                
        forms.find('#id_tanggal_selesai_sertifikasi').val(data.tanggal_selesai);

       let field_sertifikasi = $('#file-exist-sertifikasi');

       if (data.file_sertifikasi != null) {
         field_sertifikasi.find('a').attr('onclick', 'return previewPDF("<?= base_url() ?>'+ data.file_sertifikasi +'")');
         field_sertifikasi.find('a').text(data.file_sertifikasi.split('/').pop());
         field_sertifikasi.show();
       } else {
         field_sertifikasi.find('a').removeAttr('onclick');
         field_sertifikasi.find('a').text('');
         field_sertifikasi.hide();
       }
    })
    .fail(function(err) {
      console.error(err);
    });

    }

   		

    $(document).on('click', '.btn-delete-sertifikasi', function(event) {
    	event.preventDefault();

    	let id = $(this).attr('data-id');
    	
    	customConfirmation({type:'warning'}, (confirmation) => {
    		console.log(confirmation);
	    	if (confirmation) {
		    	$.ajax({
		    		url: "<?= site_url('profil/delete-sertifikasi/') ?>" + id,
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

    $(document).on('change', '#id_group', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();
         $.ajax({
             url: '<?= site_url('profil/get-units/') ?>'+ id,
             type: 'GET',
             dataType: 'json',
         })
         .done(function(data) {
             let dropdown_dep = $("#id_unit");
             dropdown_dep.empty();

             dropdown_dep.append(`<option value="">- Pilih Units -</option>`);

             $.each(data, function(index, val) {
                 dropdown_dep.append(`<option value="${index}">${val}</option>`);
             });
         })
         .fail(function() {
             console.error("Get data Units failed");
         });

    });

    $(document).on('change', '#id_unit', function(event) {
        event.preventDefault();
        /* Act on the event */
        let id = $(this).val();
         $.ajax({
             url: '<?= site_url('profil/get-depart/') ?>'+ id,
             type: 'GET',
             dataType: 'json',
         })
         .done(function(data) {
             let dropdown_dep = $("#id_departement");
             dropdown_dep.empty();

             dropdown_dep.append(`<option value="">- Pilih Department -</option>`);

             $.each(data, function(index, val) {
                 dropdown_dep.append(`<option value="${index}">${val}</option>`);
             });
         })
         .fail(function() {
             console.error("Get data department failed");
         });

    });
 
</script>

<script type="text/javascript" id="script-experience">
  var modal_experience = $("#modal-experience");
  var action_experience = "<?= base_url('/profil/simpan-pengalaman') ?>";
  var action_edit_experience = "<?= base_url('/profil/simpan-pengalaman/_id_') ?>";

  $(document).on('click', '#btn_pengalaman', function() {
    if (id) {
        datatable($("#table-pengalaman"), "<?= site_url('profil/get-data-pengalaman/') ?>"+ id);
        action_experience += `?id=${id}`;
        action_edit_experience += `?id=${id}`;
    } else {
        datatable($("#table-pengalaman"), "<?= site_url('profil/get-data-pengalaman/') ?>");
    }
  });

  $(document).on('click', '#btn-add-experience', function(event) {
    event.preventDefault();

    modal_experience.find('#form-experience').attr('action', action_experience);
    modal_experience.modal('show');
  });

  $(document).on('click', '.btn-edit-experience', function(event) {
    event.preventDefault();

    let id = $(this).data('id');
    detailPengalaman(id);

    modal_experience.find('#form-experience').attr('action', action_edit_experience.replace('_id_', id));
    modal_experience.modal('show');
  });

  $(document).on('click', '.btn-preview-experience', function(event) {
    event.preventDefault();

    let id = $(this).data('id');
    detailPengalaman(id);

    modal_experience.find('#field-experience').attr('disabled', true);
    modal_experience.find('#btn-save-experience').hide();
    modal_experience.modal('show');
  });

  modal_experience.on('hidden.bs.modal', function () {
    let forms = $(this).find('#form-experience');
    $(".close.fileinput-exists").trigger('click');

    $.each(forms, function(index, val) {
      // Deleting repeater
      $.each($(this).find('[name^=Pengalaman]'), function(index, val) {
        $(this).val('');
      });
    });

    modal_experience.find('#field-experience').attr('disabled', false);
    modal_experience.find('#btn-save-experience').show();
    $('#file-paklaring').hide();

  });

  $(document).on('submit', '#form-experience', function(event) {
    event.preventDefault();

    let formData = new FormData(this);
    let action = $(this).attr('action');

    $.ajax({
      url: action,
      type: 'POST',
      dataType: 'json',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
    })
    .done(function(data) {
      swalert(data.message);
    })
    .fail(function(err) {
      console.error(err);
    })
    .always(function() {
      modal_experience.modal('hide');
      datatableReload();
    });
  });

  $(document).on('click', '.btn-delete-experience', function(event) {
    event.preventDefault();

    let id = $(this).data('id');

    customConfirmation({type:'warning'}, (confirmation) => {
      if (confirmation) {
        $.ajax({
          url: "<?= site_url('/profil/hapus-pengalaman/') ?>" + id,
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

  function detailPengalaman(id) {
    let forms = modal_experience.find('#form-experience');

    $.ajax({
      url: '<?= site_url('/profil/detail-pengalaman/') ?>'+ id,
      type: 'GET',
      dataType: 'json',
    })
    .done(function(data) {
      $.each(forms.find('input, textarea'), function(index, val) {
        if (typeof $(this).attr('id') !== 'undefined') {
          let field = $(this).attr('id').replace('id_', '');

          $(this).val(data[field]);
        }
      });

      let field_paklaring = $('#file-paklaring');

      if (data.paklaring != null) {
        field_paklaring.find('a').attr('onclick', 'return previewPDF("<?= base_url() ?>'+ data.paklaring +'")');
        field_paklaring.find('a').text(data.paklaring.split('/').pop());
        field_paklaring.show();
      } else {
        field_paklaring.find('a').removeAttr('onclick');
        field_paklaring.find('a').text('');
        field_paklaring.hide();
      }
    })
    .fail(function(err) {
      console.error(err);
    });

  }
</script>

<script type="text/javascript" id="script-upload-profile">
    // update info by cropping (onChange and onSelect events handler)
    function updateInfo(e) {
        $('#x1').val(e.x);
        $('#y1').val(e.y);
        $('#x2').val(e.x2);
        $('#y2').val(e.y2);
        $('#wh').val(e.w +' x '+ e.h).trigger('change');
        $('#w').val(e.w);
        $('#h').val(e.h);
    };

    // clear info by cropping (onRelease event handler)
    function clearInfo() {
        $('#wh').val('').trigger('change');
        $('#w').val('');
        $('#h').val('');
        $('#x1').val('');
        $('#y1').val('');
        $('#x2').val('');
        $('#y2').val('');
    };

    // Create variables (in this scope) to hold the Jcrop API and image size
    var jcrop_api, boundx, boundy;

    function fileSelectHandler() {
        // get selected file
        var oFile = $('#file-profile')[0].files[0];

        // hide all errors
        $('.error').hide();
        $('.jcrop-info').hide();
        $('#profile-jcrop').removeAttr('src');
        $('#old_w').val('');
        $('#old_h').val('');

        // destroy Jcrop if it is existed
        if (jcrop_api != null) {
            jcrop_api.destroy();
            jcrop_api = null;
            $('#profile-jcrop').prop('style', 'margin: 0 auto;display:none;');
        }

        if (typeof oFile === 'undefined') {
            return false;
        }

        // check for image type (jpg and png are allowed)
        var rFilter = /^(image\/jpeg|image\/png)$/i;
        if (! rFilter.test(oFile.type)) {
            $('.error').html('File yang dipilih harus berupa gambar (jpeg/png)').show();
            return;
        }

        // preview element
        var oImage = document.getElementById('profile-jcrop');
        // prepare HTML5 FileReader
        var oReader = new FileReader();
            oReader.onload = function(e) {
            // e.target.result contains the DataURL which we can use as a source of the image
            oImage.src = e.target.result;
            oImage.onload = function () { // onload event handler
                // display step 2
                $('.step2').fadeIn(500);
                $('.jcrop-info').show();
                $('#profile-jcrop').show();

                // display some basic image info
                var sResultFileSize = bytesToSize(oFile.size);
                $('#filesize').val(sResultFileSize);
                $('#filetype').val(oFile.type);
                $('#filedim').val(oImage.naturalWidth + ' x ' + oImage.naturalHeight);

                setTimeout(function(){
                    // initialize Jcrop
                    $('#profile-jcrop').Jcrop({
                        minSize: [32, 32], // min crop size
                        aspectRatio : 1, // keep aspect ratio 1:1
                        bgFade: true, // use fade effect
                        bgOpacity: .3, // fade opacity
                        onChange: updateInfo,
                        onSelect: updateInfo,
                        onRelease: clearInfo,
                        setSelec: [0,0,110,110],
                        trueSize: [oImage.naturalWidth, oImage.naturalHeight]
                    }, function(){
                        // use the Jcrop API to get the real image size
                        var bounds = this.getBounds();
                        boundx = bounds[0];
                        boundy = bounds[1];
                        // Store the Jcrop API in the jcrop_api variable
                        jcrop_api = this;
                    });
                }, 100);
            };
        };
        // read selected file as DataURL
        oReader.readAsDataURL(oFile);
    }

    $(document).on('change', "#wh", function(event) {
        if ($(this).val().length > 0) {
            $("#btn-simpan-foto").show();
        } else {
            $("#btn-simpan-foto").hide();
        }
    });

    $(document).on('submit', '#form-photo', function(event) {
        event.preventDefault();
        /* Act on the event */

        formData = new FormData($(this)[0]);
        // var blob = dataURLtoBlob(canvas.toDataURL('image/png'));
        // formData.append("cropped_image", blob, 'blob.png');

        $.ajax({
            url: '<?= base_url("/profil/ubah-foto?id=") ?>'+id,
            type: 'POST',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
        })
        .done(function(data) {
            if (data.success == true) {
                customConfirmation({message: 'Ada perubahan foto profil, muat ulang halaman?'}, (confirmed) => {
                    if (confirmed) {
                        window.location.reload(true);
                    }
                })

                $("#img-profile").attr('src', "<?= base_url() ?>"+data.images);
            } else {
                swalert(data.message);
            }
        })
        .fail(function(err) {
            console.error(err.error);
        })
        .always(function() {
            $('#modal-profile').modal('hide');
        });
        
    });

    $('#modal-profile').on('hidden.bs.modal', function () {
        $('#file-profile').val('').trigger('change');
    });
</script>
