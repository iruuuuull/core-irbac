<script src="<?= base_url('/web/assets/global/scripts/datatable.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/datatables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/table-datatables-responsive.min.js') ?>" type="text/javascript"></script>
<!-- <script src="<?= base_url('/web/assets/global/plugins/jquery-ui/jquery-ui.min.js') ?>" type="text/javascript"></script> -->
<script src="<?= base_url('/web/assets/pages/scripts/ui-modals.min.js') ?>" type="text/javascript"></script> 
<script src="<?= base_url('/web/assets/global/plugins/jquery-repeater/jquery.repeater.min.js') ?>" type="text/javascript"></script>

<script src="<?= base_url('/web/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ?>" type="text/javascript"></script>


<script type="text/javascript">
    var modal_create = $("#modal_create");
    var modal_list = $("#modal_list");

    var action_crate = "<?= base_url('/live-letter/simpan-letter') ?>";
    var action_edit_create = "<?= base_url('live-letter/simpan-letter/_id_') ?>";

    $(document).ready(function() {
        datatable($("#table-create"), "<?= site_url('/live-letter/get-data-create') ?>");
        datatable($("#table-list"), "<?= site_url('/live-letter/get-data-list') ?>");
    });

    modal_create.on('hidden.bs.modal', function () {
        let forms = $(this).find('#form-create');
        $(".close.fileinput-exists").trigger('click');
        let letter = forms.find("#id_no_surat").attr('format');
        let date_letter = forms.find("#id_tangal_surat").attr('date');

        $.each(forms, function(index, val) {
          // Deleting repeater
          $.each($(this).find('[name^=letter]'), function(index, val) {
            $(this).val('');
        });
      });
        
        forms.find("#id_no_surat").val(letter);
        forms.find("#id_tangal_surat").val(date_letter);

        modal_create.find('#file-upload').hide();
        modal_create.find('#field-create').attr('disabled', false);
        modal_create.find('#surat_date').show();
        modal_create.find('#desc_surat').show();
        modal_create.find('#from_surat').show();
        modal_create.find('#to_surat').show();
        $('#file-surat').hide();
    });

    $(document).on('click', '#btn-add-create', function(event) {
        event.preventDefault();

        $is_attach = $("#is_attach").val();

        if($is_attach == 0){

            swalert({
                title: 'Silahkan Untuk Mengupload Dahulu',
                message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
                type: 'warning'
            });
        }else if($is_attach == 1){
            swalert({
                title: 'Silahkan Untuk Mengisi Data Unit',
                message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
                type: 'warning'
            });
        }else{
            modal_create.find('#file-upload').hide();
            modal_create.find('#form-create').attr('action', action_crate);
            modal_create.modal('show');
        }
    });

    $(document).on('click', '.btn-edit-create', function(event) {
        event.preventDefault();
        $('#id_dari_surat').attr('disabled',false);

        let id = $(this).attr('data-id');
        detailCreate(id);

        modal_create.find('#form-create').attr('action', action_edit_create.replace('_id_', id));
        modal_create.find('#file-upload').hide();
        modal_create.modal('show');
    });

    $(document).on('click', '.btn-upload-create', function(event) {
        event.preventDefault();
        $('#id_dari_surat').attr('disabled',false);


        let id = $(this).attr('data-id');
        detailCreate(id);

        modal_create.find('#file-upload').show();
        modal_create.find('#form-create').attr('action', action_edit_create.replace('_id_', id));
        modal_create.find('#surat_date').hide();
        modal_create.find('#desc_surat').hide();
        modal_create.find('#from_surat').hide();
        modal_create.find('#to_surat').hide();
        modal_create.modal('show');
    });


    function detailCreate(id){
        let forms = modal_create.find('#form-create');
        $.ajax({
          url: '<?= site_url('live-letter/detail-create/') ?>'+ id,
          type: 'GET',
          dataType: 'json',
      })
        .done(function(data) {
          
            forms.find('#id_tangal_surat').val(data.letter_date);
            forms.find('#id_untuk_surat').val(data.letter_to);                           
            forms.find('#id_no_surat').val(data.letter_no);              
            forms.find('#id_dari_surat').val(data.sbu_code2);              
            forms.find('#id_keterangan_surat').val(data.kode_sku);    
            let explode_letter = data.letter_no.split("/");


            let kode_type_letter = explode_letter[2];

            let field_surat = $('#file-surat');
            if (data.attachment != null) {
                let attach = data.attachment.substring(20);
                let explode_attach = attach.split("-");
                let kode_type_attach = explode_attach[2];

                if(kode_type_letter == kode_type_attach){
                    field_surat.find('a').attr('onclick', 'return previewPDF("<?= base_url() ?>'+ data.attachment +'")');
                    field_surat.find('a').text(data.attachment.split('/').pop());
                    field_surat.find('a').attr('style', '');
                    field_surat.show();
                }else{
                    field_surat.find('a').removeAttr('onclick');
                    field_surat.find('a').attr('style', 'color:red');
                    field_surat.find('a').text('*Silahkan Untuk Upload Kembali');
                    field_surat.show();       
                }
            } else {
                field_surat.find('a').removeAttr('onclick');
                field_surat.find('a').text('');
                field_surat.hide();
            }
           
        })
        .fail(function(err) {
          console.error(err);
      });

    }

    $(document).on('change','#id_dari_surat',function(event){
        event.preventDefault();
        
        let ket = $("#id_keterangan_surat").val();
        let tanggal = $("#id_tangal_surat").val();
        let sbu = $("#id_dari_surat").val();
        let letter = $("#id_no_surat").val();


        $.ajax({
          url: '<?= site_url('live-letter/get-code/') ?>',
          data : {sbu: sbu, letter:letter, tanggal:tanggal,ket:ket, [csrf_name] : csrf_hash},
          type: 'POST',
          dataType: 'json',
      })
        .done(function(data) {
            $("#id_no_surat").val(data);   

        })
        .fail(function(err) {
            console.error(err);
        });
        
    });

    $(document).on('change','#id_tangal_surat',function(event){
      event.preventDefault();     
      let tanggal = $("#id_tangal_surat").val();
      let letter = $("#id_no_surat").val();
      let sbu = $("#id_dari_surat").val();
      $.ajax({
          url: '<?= site_url('live-letter/get-romawi/') ?>',
          data : {tanggal: tanggal, letter:letter , sbu:sbu ,  [csrf_name] : csrf_hash},
          type: 'POST',
          dataType: 'json',
      })
      .done(function(data) {
        $("#id_no_surat").val(data);     
    })
      .fail(function(err) {
        console.error(err);
    });

  });

    
    $(document).on('change','#id_keterangan_surat',function(event){
        event.preventDefault();

        let ket = $("#id_keterangan_surat").val();
        let letter = $("#id_no_surat").val();

        let explode_letter = letter.split("/");
        let desc = letter.replace(explode_letter[2], ket);

        $("#id_no_surat").val(desc);      
    });


    $(document).on('submit', '#form-create', function(event) {
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
            modal_create.modal('hide');
            datatableReload();
            window.location.reload(true);
        })

     });


    modal_list.on('hidden.bs.modal', function () {
        let forms = $(this).find('#form-list');
        $(".close.fileinput-exists").trigger('click');

        $('#file-surat').hide();
    });


     $(document).on('click', '.btn-view-list', function(event) {
        event.preventDefault();

        let id = $(this).attr('data-id');

        if(id){
            detailList(id);
            modal_list.find('#field-list').attr('disabled', true);
            modal_list.modal('show');       
        }else{
            swalert({
                title: 'File tidak tersedia, Silahkan untuk mengupload file surat',
                message: 'Terjadi kesalahan server, silahkan coba beberapa saat lagi atau lapor ke administrator',
                type: 'error'
            });
        }
    });


    function detailList(id){
        let forms = modal_list.find('#form-list');
        $.ajax({
          url: '<?= site_url('live-letter/detail-list/') ?>'+ id,
          type: 'GET',
          dataType: 'json',
      })
        .done(function(data) {    
            let explode_letter = data.letter_no.split("/");


            let kode_type_letter = explode_letter[2];

            let field_surat = $('#file-surat');
            if (data.attachment != null) {
                let attach = data.attachment.substring(20);
                let explode_attach = attach.split("-");
                let kode_type_attach = explode_attach[2];
                if(kode_type_letter == kode_type_attach){
                    field_surat.find('a').attr('onclick', 'return previewPDF("<?= base_url() ?>'+ data.attachment +'")');
                    field_surat.find('a').text(data.attachment.split('/').pop());
                    field_surat.find('a').attr('style', '');
                    field_surat.show();
                }else{
                    field_surat.find('a').removeAttr('onclick');
                    field_surat.find('a').attr('style', 'color:red');
                    field_surat.find('a').text('*Silahkan Untuk Upload Kembali');
                    field_surat.show();       
                }
            } else {
                field_surat.find('a').removeAttr('onclick');
                field_surat.find('a').text('');
                field_surat.hide();
            }
        })
        .fail(function(err) {
          console.error(err);
      });

    }



</script>
