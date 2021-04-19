<?php
$opts = json_encode([
    'permissions' => $permissions
], JSON_FORCE_OBJECT);
?>

<script type="text/javascript">
	var _opts = <?= $opts ?>;

	$('i.glyphicon-refresh-animate').hide();
	function updateRoutes(r) {
	    _opts.permissions.available = r.available;
	    _opts.permissions.assigned = r.assigned;
	    search('available');
	    search('assigned');
	}

	<?php /*$('#btn-new').click(function () {
	    var $this = $(this);
	    var route = $('#inp-route').val().trim();
	    if (route != '') {
	        $this.children('i.glyphicon-refresh-animate').show();
	        $.post($this.attr('href'), {route: route}, function (r) {
	            $('#inp-route').val('').focus();
	            updateRoutes(r);
	        }).always(function () {
	            $this.children('i.glyphicon-refresh-animate').hide();
	        });
	    }
	    return false;
	}); */?>

	$('.btn-assign').click(function () {
	    var $this = $(this);
	    var target = $this.data('target');
	    var permissions = $('select.list[data-target="' + target + '"]').val();

	    if (permissions && permissions.length) {
	        $this.children('i.glyphicon-refresh-animate').show();
	        $.post($this.attr('href'), {
	        	permissions: permissions,
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
	    $.each(_opts.permissions[target], function () {
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
