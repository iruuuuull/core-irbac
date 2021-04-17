<script type="text/javascript" id="script-maps">
	var maps = document.getElementById("map-location-preview");
	var long, lat;
	var mapsLoaded = false;
	var L = mapboxgl;
	// API token goes here
	var key = '<?= getEnv('LOCATIONIQ_API_KEY') ?>';

	$(document).ready(function() {
		initAttendance();
		myLoader();
	});

	function initAttendance(reset = false) {
		if (reset === true) {
			long = lat = null;
			mapsLoaded = false;
		}

		setInterval(() => {
			if (long == null || lat == null) {
				getLocation();
			} else {
				if (mapsLoaded === false) {
					initMap();
				}
			}

		}, 1000);
		reset = false;
	}

	function initMap() {
		//Define the map and configure the map's theme
        var map = new mapboxgl.Map({
            container: 'map-location-preview',
            attributionControl: false, //need this to show a compact attribution icon (i) instead of the whole text
            zoom: 15,
            style: 'https://tiles.locationiq.com/v2/streets/vector.json?key='+key,
            center: [long, lat]
        });

        //Default language is set to German
        map.setLanguage('id'); 

        //Add Navigation controls to the map to the top-right corner of the map
        var nav = new mapboxgl.NavigationControl();
        map.addControl(nav, 'top-right');

        //Add Geolocation control to the map (will only render when page is opened over HTTPS)
        let geoTracker = new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            trackUserLocation: true
        });
        map.addControl(geoTracker);

        geoTracker.on('geolocate', function(e) {
			long = e.coords.longitude;
			lat = e.coords.latitude
		});

        var el = document.createElement('div');
        el.id = 'markerWithExternalCss';
        // finally, create the marker
        var markerWithExternalCss = new mapboxgl.Marker(el)
            .setLngLat([long, lat])
            .addTo(map);

        mapsLoaded = true;
	}

	function getLocation() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showPosition);
		} else { 
			maps.innerHTML = "Geolocation tidak mendukung pada perambanan ini.";
		}
	}

	function showPosition(position) {
		lat = position.coords.latitude;
		long = position.coords.longitude;

		let coordinate = {
			lat: lat,
			long: long,
		};

		$('#attendance-coordinate').val(JSON.stringify(coordinate));

		getGeocode({lat:lat,long:long});
	}

	function getGeocode(coordinate) {
		$.ajax({
			url: `https://us1.locationiq.com/v1/reverse.php?key=${key}&lat=${coordinate.lat}&lon=${coordinate.long}&format=json`,
			type: 'GET',
			dataType: 'json',
		})
		.done(function(data) {
			$("#attend-location").text(data.display_name);

			$('#attendance-location').val(data.display_name);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			myLoader(false);
		});
		
	}
</script>

<script type="text/javascript" id="script-general">
	$(document).ready(function() {

		getCurrentTime();

		setInterval(() => {
			getCurrentTime();
		}, 1000 * 60);
	});

	function getCurrentTime() {
		$tZ = $("#timezone").val();
		let d = convertTZ(new Date, $tZ);
		let months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		let days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
		let date = d.getDate() +' '+ months[d.getMonth()] +' '+ d.getFullYear();
		let time = ('0' + d.getHours()).slice(-2) +'<span class="blinking">:</span>'+ ('0' + d.getMinutes()).slice(-2);
		let ampm = d.getHours() >= 12 ? 'PM' : 'AM';
		$(".attend-date").html(days[d.getDay()] +', '+date);
		$(".attend-mini-date").html(`Jadwal, ${date}`);
		$(".attend-time").html(time +` ${ampm}`);
	}
</script>
