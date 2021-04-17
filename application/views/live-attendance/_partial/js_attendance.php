<script type="text/javascript">
	$(document).on('click', '#btn-check-in', function(event) {
		event.preventDefault();

		doTap('in', this);
	});

	$(document).on('click', '#btn-check-out', function(event) {
		event.preventDefault();
		
		doTap('out', this);
	});

	function doTap(type, button) {
		if ($.inArray(type, ['in', 'out']) === -1) {
			return false;
		}

		getLocation();

		var l = Ladda.create(button);
	 	l.start();

		$.ajax({
			url: '<?= site_url('/live-attendance/check/') ?>' + type,
			type: 'POST',
			dataType: 'json',
			data: $("#form-attendance").serialize(),
		})
		.done(function(data) {
			swalert(data.message);
			$("#table-attendance").empty();
			$("#table-attendance").html(generateLog(data.attendances));
			$("#attendance-catatan").val("");
		})
		.fail(function(e) {
			console.log("error");
		})
		.always(function(data) {
			l.stop();

			if (data.check_in == true) {
				$('#btn-check-in').prop('disabled', true);
			} else {
				$('#btn-check-in').prop('disabled', false);
			}

			if (data.check_out == true) {
				$('#btn-check-out').prop('disabled', true);
			} else {
				$('#btn-check-out').prop('disabled', false);
			}
		});
	}

	function generateLog(attendance) {
		logs = '';

		if (jQuery.isEmptyObject(attendance)) {
			logs += '<tr><td><i>Belum ada aktifitas absen.</i></td></tr>';
		} else {
			if (attendance.check_in != null) {
				logs += '<tr>';
					logs += '<td>'+ attendance.check_in +'</td>';
					logs += '<td>Check In</td>';
					logs += '<td><a href="#" class="btn-detail-in" data-id="'+ attendance.id +'">Detail</a></td>';
				logs += '</tr>';
			}

			if (attendance.check_out != null) {
				logs += '<tr>';
					logs += '<td>'+ attendance.check_out +'</td>';
					logs += '<td>Check Out</td>';
					logs += '<td><a href="#" class="btn-detail-out" data-id="'+ attendance.id +'">Detail</a></td>';
				logs += '</tr>';
			}
		}

		return logs;
	}
</script>
