<script src="<?= base_url('/web/assets/global/scripts/datatable.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/datatables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/table-datatables-responsive.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/pages/scripts/ui-modals.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">

	$(document).ready(function() {
    // datatable($("#table-employee"), "<?= site_url('/report/sum-employee/get-data') ?>");
    $('#table-employee').DataTable({
    	"dom": '<"top"<"pull-right"B><"pull-left"l>>rt<"bottom"<"pull-left"i><"pull-right"p>>',
    	"ordering": true,
    	"paging": true,
    	"info":     false,
    	"searching": false,
    	"lengthChange": true,
        buttons: [
       {
        extend: 'excelHtml5',
        text: 'Export Excel',
      },
      {
        extend: 'pdfHtml5',
        messageTop: 'Report',
        text: 'Export Pdf',
        orientation: 'Potrait',
        pageSize: 'LEGAL'
      }
      ]
    });
});


</script>
