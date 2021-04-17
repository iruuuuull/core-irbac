<script src="<?= base_url('/web/assets/global/scripts/datatable.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/datatables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/table-datatables-responsive.min.js') ?>" type="text/javascript"></script>
<!-- <script src="<?= base_url('/web/assets/global/plugins/jquery-ui/jquery-ui.min.js') ?>" type="text/javascript"></script>
  <script src="<?= base_url('/web/assets/global/plugins/jquery-repeater/jquery.repeater.min.js') ?>" type="text/javascript"></script> -->

  <script src="<?= base_url('/web/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ?>" type="text/javascript"></script>
  <script src="<?= base_url('/web/assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') ?>" type="text/javascript"></script>
  <script src="<?= base_url('/web/assets/global/plugins/bootstrap-select2/js/select2.min.js') ?>" type="text/javascript"></script>

  <script type="text/javascript">
   var modal = $("#draggable");

   $(document).ready(function() {
        combo_select2();
  });

   function combo_select2(){
    let dropdown_employees = $("#id_karyawan");
    dropdown_employees.empty();
    dropdown_employees.append(`<option value="">- Select Employee -</option>`);
    dropdown_employees.append(`<option value="All">All</option>`);
    
    $.ajax({ 
      type: 'GET', 
      url:"<?php echo site_url('report/attendance/getdata-karyawan')?>",
      dataType: 'json',
      data: function(params) {
        return {
          search: params.term
        }
      },
      success: function (data) {
        $.each(data, function(index, val) {
          dropdown_employees.append(`<option value="${val.user_id}">${val.nama_depan+' '+val.nama_tengah+' '+val.nama_belakang}</option>`);
        });
      }
    });
  }

  $(document).on('click','#btn-cari-reportAtten',function(){
   let date_start = $("#id_tanggal_start").val();
   let date_end = $("#id_tanggal_end").val();
   let user_id = $("#id_karyawan").val();

   if (date_start == '' || date_end == ''){
    swalert({
     title: 'Gagal ambil data',
     message: 'Harap memilih Date Between terlebih dahulu',
     type: 'warning'
   });

  }else if (user_id == ''){
    swalert({
     title: 'Gagal ambil data',
     message: 'Harap memilih data Employee terlebih dahulu',
     type: 'warning'
   })
  }else{
    $.ajax({
     type    : 'POST',
     url     : "<?php echo site_url('report/attendance/getdata')?>",
     data    : {cari : {tanggal_awal:date_start,tanggal_akhir:date_end,id_karyawan:user_id}, [csrf_name] : csrf_hash},
     success : function(value){
      $(".data-attendance").remove();
      $(".table-container").append(value);
      $('#table_report_atten').DataTable({
       "ordering": false,
       "paging": false,
       "info":     false,
       "searching": true,
       "lengthChange": false,
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
    }
  });
  }

});


</script>
