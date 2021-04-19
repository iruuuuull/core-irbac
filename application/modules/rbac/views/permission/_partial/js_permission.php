<?php
$opts = json_encode([
    'routes' => $routes
], JSON_FORCE_OBJECT);
?>

<script type="text/javascript">
	var _opts = <?= $opts ?>;

	$('i.glyphicon-refresh-animate').hide();
	function updateRoutes(r) {
	    _opts.routes.available = r.available;
	    _opts.routes.assigned = r.assigned;
	    search('available');
	    search('assigned');
	}

	$('.btn-assign').click(function () {
	    var $this = $(this);
	    var target = $this.data('target');
	    var routes = $('select.list[data-target="' + target + '"]').val();

	    if (routes && routes.length) {
	        $this.children('i.glyphicon-refresh-animate').show();
	        $.post($this.attr('href'), {
	        	routes: routes,
	        	[csrf_name]: csrf_hash
	        }, function (r) {
	            updateRoutes(r);
	        }).always(function () {
	            $this.children('i.glyphicon-refresh-animate').hide();
	        });
	    }
	    return false;
	});

	$('#btn-refresh').click(function () {
	    var $icon = $(this).children('span.glyphicon');
	    $icon.addClass('glyphicon-refresh-animate');
	    $.post($(this).attr('href'), {[csrf_name]: csrf_hash}, function (r) {
	        updateRoutes(r);
	    }).always(function () {
	        $icon.removeClass('glyphicon-refresh-animate');
	    });
	    return false;
	});

	$('.search[data-target]').keyup(function () {
	    search($(this).data('target'));
	});

	function search(target) {
	    var $list = $('select.list[data-target="' + target + '"]');
	    $list.html('');
	    var q = $('.search[data-target="' + target + '"]').val();
	    $.each(_opts.routes[target], function () {
	        var r = this;
	        if (r.indexOf(q) >= 0) {
	            $('<option>').text(r).val(r).appendTo($list);
	        }
	    });
	}

	// initial
	search('available');
	search('assigned');

</script>
