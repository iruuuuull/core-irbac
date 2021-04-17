<script src='https://api.mapbox.com/mapbox-gl-js/v1.8.0/mapbox-gl.js'></script>
<script src="https://tiles.locationiq.com/v2/js/lang-gl.js"></script>

<script src='https://tiles.locationiq.com/v2/js/liq-styles-ctrl-gl.js?v=0.1.6'></script>
<link href='https://tiles.locationiq.com/v2/css/liq-styles-ctrl-gl.css?v=0.1.6' rel='stylesheet' />
<link href='https://api.mapbox.com/mapbox-gl-js/v1.8.0/mapbox-gl.css' rel='stylesheet' />

<div class="modal fade" id="modal-detail-attendance" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Log Detail</h4>
            </div>
            <div class="modal-body">
            	<div class="row">
            		<div class="col-md-9">
            			<div id="map-location-detail" style="width: 100%;height: 50vh"></div>
            		</div>
            		<div class="col-md-3">
            			<ul class="list-unstyled">
            				<li>
            					<small>Checked time</small>
            					<p id="attendance-time" style="margin-top: 0px"></p>
            				</li>
            				<li>
            					<small>Type</small>
            					<p id="attendance-type" style="margin-top: 0px"></p>
            				</li>
            				<li>
            					<small>Date</small>
            					<p id="attendance-date" style="margin-top: 0px"></p>
            				</li>
            				<li>
            					<small>Location</small>
            					<p id="attendance-location" style="margin-top: 0px"></p>
            				</li>
            				<li>
            					<small>Notes</small>
            					<p id="attendance-note" style="margin-top: 0px"></p>
            				</li>
            			</ul>
            		</div>
            	</div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript" id="map-detail">
	$(document).on('click', '.btn-detail-in', function(event) {
		event.preventDefault();
		/* Act on the event */
		let id = $(this).data('id');
		getDetailTap('in', id);
	});

	$(document).on('click', '.btn-detail-out', function(event) {
		event.preventDefault();
		/* Act on the event */
		let id = $(this).data('id');
		getDetailTap('out', id);
	});

	function getDetailTap(type, id) {
		if ($.inArray(type, ['in', 'out']) === -1) {
			return false;
		}

		$.ajax({
			url: '<?= base_url('/live-attendance/detail/') ?>' + id,
			type: 'POST',
			dataType: 'json',
			data: {
				type:type,
				[csrf_name] : csrf_hash
			}
		})
		.done(function(data) {
			let $modal = $("#modal-detail-attendance");

			if (data) {
				let coordinate = JSON.parse(data.coordinate);
				let type = data.type;
				let time = data.time;
				let date = data.date;
				let location = data.location;
				let note = data.note;

				$modal.find('.modal-body').find('#attendance-time').text(time);
				$modal.find('.modal-body').find('#attendance-type').text(type);
				$modal.find('.modal-body').find('#attendance-date').text(date);
				$modal.find('.modal-body').find('#attendance-location').text(location);
				$modal.find('.modal-body').find('#attendance-note').text(note);

				$modal.modal('show');
				setTimeout(() => {
					generateMap('map-location-detail', {lat: coordinate.lat, long: coordinate.long});
				}, 1000);

			} else {
				swalert('Data absen tidak ditemukan.');
			}
		})
		.fail(function() {
			swalert('Proses ambil data detail absen gagal.');
		})
		.always(function() {});
	}
</script>