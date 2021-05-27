<script src="<?= base_url('/web/assets/plugins/datatables/jquery.dataTables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>" type="text/javascript"></script>


<script type="text/javascript">
	$(document).ready(function() {
		datatable($("#table-list-siswa"), "<?= site_url('/pengelolaan-mahasiswa/get-data') ?>");
	});
</script>
