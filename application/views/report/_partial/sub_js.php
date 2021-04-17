<script src="<?= base_url('/web/assets/global/scripts/datatable.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/datatables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/table-datatables-responsive.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/jquery-ui/jquery-ui.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/ui-modals.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/jquery-repeater/jquery.repeater.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">

    var csrf_name = '<?= $this->security->get_csrf_token_name() ?>';
    var csrf_hash = '<?= $this->security->get_csrf_hash() ?>';

    $(document).ready(function() {
      $('#admin_1').DataTable({
        "ordering": false,
        "info":    false,
        "dom": 'Bflrtip',
        buttons: [
        {
            extend: 'excelHtml5',
            text: 'Export Excel',
        },
        {
            extend: 'pdfHtml5',
            messageTop: 'Report',
            text: 'Export Pdf',
            orientation: 'landscape',
            pageSize: 'LEGAL',
        }
        ]
    });
      admin = $("#group_id").val();
      if(admin == 'admin'){
          $(".table-toolbar").hide();
      }else{
          $(".table-toolbar").show();
          $("#table-admin").hide();
      }
  });
    // CARI UNIT
    $('#id_group_unit').change(function(){
      $('.c_unit').remove();
      $('.c_bagian').remove();
      dataGroup = $(this).val();
      $.ajax({
        type : "POST",
        data : {group: dataGroup, [csrf_name] : csrf_hash},
        dataType : "json",
        url : "<?php echo site_url('report/karyawan/unit')?>",
        success : function (dataGroup){
            $.each(dataGroup,function(key,val){
                $("#id_unit").append("<option value='"+val.id+'@'+val.sbu_id+'@'+val.unit_level+'@'+val.kode_unit+"' class='c_unit' >"+val.nama_unit+"</option>");
            });
        },
        error : function(){
                // alert("error");
            } 
        });
  });	

    // CARI BAGIAN
    $('#id_unit').change(function(){
        $('.c_bagian').remove();
        // dataGroup = $('#id_group_unit').val();
        dataGroup = $(this).val();

        $.ajax({
            type : "POST",
            data : {group: dataGroup, [csrf_name] : csrf_hash},
            dataType : "json",
            url : "<?php echo site_url('report/karyawan/bagian')?>",
            success : function (dataGroup){
                $.each(dataGroup,function(key,val){
                    $("#id_bagian").append("<option value='"+val.id+"' class='c_bagian' >"+val.department+"</option>");
                });
            },
            error : function(){
                // alert("error");
                swalert({
                    title: 'Gagal ambil data',
                    message: 'Maaf data tidak dapat ditampilkan',
                    type: 'alert'
                });
            } 
        });
    }); 

     //PROSES TAMPIL TABEL DATA KARYAWAN
     $("#btn-cari-data").click(function(){
        // $(".table-container").empty();
        group_unit = $('#id_group_unit').val();
        unit = $('#id_unit').val();
        bagian = $('#id_bagian').val();

        if (group_unit == ''){
            swalert({
                title: 'Gagal ambil data',
                message: 'Harap memilih data Group Unit terlebih dahulu',
                type: 'warning'
            });
            
        }else if (unit == ''){
            swalert({
                title: 'Gagal ambil data',
                message: 'Harap memilih data Unit terlebih dahulu',
                type: 'warning'
            });

        }else{

            $.ajax({
                type : "POST",
                data : {cari:{v_group:group_unit,v_unit:unit,v_bagian:bagian}, [csrf_name] : csrf_hash},
                url : "<?php echo site_url('report/karyawan/ambil-data')?>",
                success : function (value){
                    $(".table-data").remove();
                    $(".table-container").append(value);
                    $('#admin_2').DataTable({
                        "ordering": false,
                        "info":    false,
                        "dom": 'Bflrtip',
                        buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Export Excel',
                        },
                        {
                            extend: 'pdfHtml5',
                            messageTop: 'Report',
                            text: 'Export Pdf',
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                        }
                        ]
                    });
                },
                error : function(value){

                }
            });
        }

    });

</script>
