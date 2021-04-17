<script type="text/javascript">
	$(document).ready(function() {
		$("#greetings").text(getGreeting());
		$("#timestamp").text(getMyDate());
	});

	function getMyDate() {
		let d = new Date;
		let months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		let days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
		let date = days[d.getDay()] +', '+ d.getDate() +' '+ months[d.getMonth()] +' '+ d.getFullYear();

		return date;
	}
</script>