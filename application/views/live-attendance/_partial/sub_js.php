<script src="<?= base_url('/web/assets/global/scripts/datatable.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/datatables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/table-datatables-responsive.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/jquery-repeater/jquery.repeater.min.js') ?>" type="text/javascript"></script>

<script src="<?= base_url('/web/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/bootstrap-select2/js/select2.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">

    var modal_update = $("#modal-update");
    var action_attendance = "<?= base_url('/live-attendance/save-logsupdate') ?>";
    $(document).ready(function() {
        combo_select2();
        datatableManual("table-attendance2");
    });

     function combo_select2(){
    let dropdown_employees = $("#id_karyawan");
    dropdown_employees.empty();
    dropdown_employees.append(`<option value="">- Select Employee -</option>`);
        
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

    modal_update.on('hidden.bs.modal', function () {
      let forms = $(this).find('#form-update-attendance');

      $.each(forms, function(index, val) {
          // Deleting repeater
          $.each($(this).find('[name^=Attendance]'), function(index, val) {
            $(this).val('');
          });
        });

      modal_update.find('#field-Attendance').attr('disabled', false);
    });



    $(document).on('click','#btn-cari-logs', function(){
      let date_start = $("#id_tanggal_start").val();
      let date_end = $("#id_tanggal_end").val();
      let user_id = $("#id_karyawan").val();

      if (date_start == '' || date_end == ''){
        swalert({
          title: 'Gagal ambil data',
          message: 'Harap memilih data Group Unit terlebih dahulu',
          type: 'warning'
        });

      }else if (user_id == ''){
        swalert({
          title: 'Gagal ambil data',
          message: 'Harap memilih data Unit terlebih dahulu',
          type: 'warning'
        })
      }else{
        refreshPage(user_id,date_start,date_end);    
      }

    })

    function refreshPage(user_id,date_start,date_end){
      $.ajax({
        type    : 'POST',
        url     : "<?php echo site_url('live-attendance/getdata-logsupdate')?>",
        data    : {cari : {tanggal_awal:date_start,tanggal_akhir:date_end,id_karyawan:user_id}, [csrf_name] : csrf_hash},
        success : function(value){
          $(".data-attendance").remove();
          $(".table-container").append(value);
        }
      });
    }


    $(document).on('click', '#btn-update', function(event) {
      var id = $(this).attr('data-id');
      var date = $(this).attr('date');

      if(id){
        getdata(id,date);
      }
      modal_update.find('#form-update-attendance').attr('action', action_attendance);
      modal_update.modal('show');

    });

    function getdata(id,date){


      let forms = modal_update.find('#form-update-attendance');


        $.ajax({
          url: "<?= site_url('live-attendance/getdetail-logsupdate/') ?>" +id+'/'+date,
          type: 'GET',
          dataType: 'json',
        })
        .done(function(data) {

          if (data.check_in != "") {
           forms.find('#checkin').attr("disabled", true);
          } else {
            forms.find('#checkin').attr("disabled", false);
          }

          if (data.check_out != "") {
             forms.find('#checkout').attr("disabled", true);
          } else {
            forms.find('#checkout').attr("disabled", false);
          }

          if(data.check_in == "" || data.check_out == "" || data.keterangan == ""){
            modal_update.find('#btn-update-attendance').show();
          }else{
            modal_update.find('#btn-update-attendance').hide();
          }

          forms.find('#userid').val(data.id);
          forms.find('#id_date').val(data.date);
          forms.find('#id_employe').val(data.name);
          forms.find('#checkin').val(data.check_in);
          forms.find('#checkout').val(data.check_out);                
          setTimeout(() => {
            forms.find('#id_status').val(data.keterangan).trigger('change');
          }, 500);                
        })
        .fail(function(err) {
          console.error(err);
        });
      }

     $(document).on('submit', '#form-update-attendance', function(event) {
       event.preventDefault();
       
       // data filter
       let date_start = $("#id_tanggal_start").val();
       let date_end = $("#id_tanggal_end").val();
       let user_id = $("#id_karyawan").val();
       // end

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
            modal_update.modal('hide');
            refreshPage(user_id,date_start,date_end);
        })

     });

</script>
