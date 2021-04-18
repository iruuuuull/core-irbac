<?php 
	$jsonListGroup = json_encode($listGroup);
?>

<script src="<?= base_url('web/assets/plugins/jquery-nestable/jquery.nestable.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
	var list_group = <?= $jsonListGroup ?>;

	$(document).ready(function(){
	    $('.dd').nestable({ /* config options */ });
	    var dataId;
	    var updateOutput = function(e){
	        var list = e.length ? e : $(e.target),
	            output = list.data('output');
	        if (window.JSON) {
	            var parent_id = $('#nestable_ol').attr('data-id');
	            var list_serialz = list.nestable('serialize');
	            list_serialz[0].parent = parseInt(parent_id);
	            var tmp = window.JSON.stringify(list_serialz);
	            AjaxCall('<?= base_url('rbac/menu/update-menu') ?>', {menus: JSON.parse(tmp)});
	        }else{
	            console.log('JSON browser support');
	        }
	    };
	    $('#nestable').nestable();
	    $('#nestable').on('change', function(event){
	        if (!event.bubbles)
	            updateOutput($('#nestable').data('output', $('#nestable-output')));
	    });
	});

	var AjaxCall = function(url, data){
	    $('#img-ajax-loader').fadeIn();
	    $.ajax({
	        url : url,
	        type : 'POST',
	        data : data,
	        dataType : 'json',
	        success : function(result){
	            $('#img-ajax-loader').hide();
	        }
	    });
	}

	$(document).on('click', '#route-select', function(){
	    if ($(this).text() == 'Select All') {
	        $('.route').prop('checked', true);
	        $(this).text('Deselect All');
	    } else {
	        $('.route').prop('checked', false);
	        $(this).text('Select All');
	    }
	});

	function randomIt(id){
	    var num = Math.floor((Math.random() * 100) + 1);
	    if($('#'+id+'-'+num).length){
	        return randomIt(id);
	    }else{
	        return id+'-'+num;
	    }
	}

	$(document).on('click','#add-group-to-menu',function(){
	    $('.route:checked').each(function() {
	        var id = randomIt('item' + $(this).data('val').replace(/[/]/g, '-'));
	        var label = $(this).data('val');
	        appendMenu(id, label, label);
	        $('.dd').trigger('change');
	    });
	});

	$(document).on('click', '#add-custom-to-menu', function(){
	    var id = randomIt('custom');
	    var url = $(this).parent().parent().parent().find('#url').val();
	    var label = $(this).parent().parent().parent().find('#label').val();
	    appendMenu(id, url, label);
	    $('.dd').trigger('change');
	});

	checkboxGroup();

	function checkboxGroup(no) {
	    var result = '';
	    result += '<div id=\'group\'>';
	    for (var key in list_group) {
	        if (list_group.hasOwnProperty(key)) {
	            result += '<div class=\'checkbox\'>';
	            result += '<label>';
	            result += '<input type=\'checkbox\' name=\'Menu[item][' + no + '][group][]\' value=\'' + list_group[key].id + '\' />';
	            result += list_group[key].label;
	            result += '</label>';
	            result += '</div>';
	        }
	    }
	    result += '</div>';
	    return result;
	}

	function appendMenu(id, url, label) {
	    var no = $('li.dd-item').length;
	    $('.dd > .dd-list').append(
	        '<li class=\'dd-item\' data-id=\'' + id + '\'>' +
	            '<button class=\'pull-right btn btn-success btn-xs fa fa-chevron-down pull-right\' aria-hidden=\'true\' data-toggle=\'collapse\' href=\'#' + id + '\' aria-expanded=\'true\' aria-controls=\'' + id + '\'></button>' +
	            '<div class=\'dd-handle\'>' +
	                '<div class=\' panel panel-success\'>' +
	                    '<div class=\'handle-head panel-heading\'>'+ label + '<span class=\'pull-right\'><i>(' + url + ')</i></span> </div>' +
	                    '<div class=\'panel-collapse collapse dd-nodrag\' id=\'' + id + '\' role=\'tabpanel\' aria-labelledby=\'' + id + '\'>' +
	                            '<div class=\'panel panel-default\'>' +
	                            '<div class=\'panel-body\'>' +
	                                '<div class=\'form-group\'>' +
	                                    '<label><i> Menu Label </i></label>' +
	                                    '<input type=\'text\' class=\'form-control input-sm\' name=\'Menu[item][' + no + '][label]\' value=\'' + label + '\' placeholder=\' Menu Label \'>' +
	                                '</div>' +
	                                '<div class=\'form-group\'>' +
	                                    '<label><i> Description </i></label>'+
	                                    '<input type=\'text\' class=\'form-control input-sm\' name=\'Menu[item][' + no + '][description]\' placeholder=\' Menu  Description \'>' +
	                                '</div>' +
	                                '<div class=\'form-group\'>' +
	                                    '<label><i> URL </i></label>' +
	                                    '<input type=\'text\' class=\'form-control input-sm\' name=\'Menu[item][' + no + '][menu_url]\' value=\'' + url + '\' placeholder=\' URL \'>' +
	                                '</div>' +
	                                '<div class=\'form-group\'>' +
	                                    '<label><i> Icon Class </i></label>' +
	                                    '<input type=\'text\' class=\'form-control input-sm\' name=\'Menu[item][' + no + '][class]\' placeholder=\' Icon Class \'>' +
	                                '</div>' +
	                                '<div class=\'form-group\'>' +
	                                    '<div style=\'position:relative;margin-left:25px\'>' +
	                                        '<label><i> Group </i></label>'+
	                                        checkboxGroup(no) +
	                                    '</div>' +
	                                '</div>' +
	                                '<div class=\'col-md-12\'>' +
	                                    '<a href=\'javascript:void(0)\' aria-hidden=\'true\' data-toggle=\'collapse\' href=\'#' + id + '\' aria-expanded=\'true\' aria-controls=\'' + id + '\' class=\'text-danger remove-menu\' data-id=\'\' >Remove</a>' +
	                                    ' | ' +
	                                    '<a aria-hidden=\'true\' data-toggle=\'collapse\' href=\'#' + id + '\' aria-expanded=\'true\' aria-controls=\'' + id + '\' class=\'text-muted\'>Cancel</a>' +
	                                '</div>' +
	                            '</div>' +
	                        '</div>' +
	                    '</div>' +
	                '</div>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][menu_parent]\' class=\'hasparent\' value=\'\'>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][dummy_id]\' class=\'dummy-id\' value=\'\'>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][menu_custom]\' value=\'1\'>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][menu_id]\' value=\'\'>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][menu_order]\' class=\'menu-order\' value=\'\'>' +
	                '<input type=\'hidden\' name=\'Menu[item][' + no + '][level]\' class=\'level\' value=\'\'>' +
	            '</div>'+
	            '<ol></ol>'+
	        '</li>');
	}

	function loopChildren(serialmenu, idParent, level) {
	    $.each(serialmenu, function(a,b) {
	        ha = $('[data-id='+b.id+'] > .dd-handle').children('.hasparent').val(idParent);
	        $('[data-id='+b.id+'] > .dd-handle').children('.dummy-id').val(b.id);
	        $('[data-id='+b.id+'] > .dd-handle').children('.menu-order').val(a);
	        $('[data-id='+b.id+'] > .dd-handle').children('.level').val(level);
	        if (b.children) {
	            loopChildren(b.children, b.id, (level+1));
	        }
	    });
	}

	$(document).on('change','.dd',function(){
	    serialmenu = $('.dd').nestable('serialize');
	        return loopChildren(serialmenu, '0',1);
	});

	$(document).on('click','.remove-menu',function(e){
	    e.preventDefault();
	    var conf = confirm('Yakin akan dihapus?');
	    if (conf) {
	        id = $(this).data('id') ? $(this).data('id') : 0;
	        type = $(this).attr('menu-type');
	        child = $(this).parent().parent().parent().parent().parent().parent().parent().children('ol').html();
	        $(child).insertAfter('[data-id=' + id + ']');
	        $(this).parent().parent().parent().parent().parent().parent().parent().remove();
	        populateDeleted(id);
	        $('.dd').trigger('change');
	    }
	});

	$(document).on('click','.btn-save-menu',function(e){
	    $('#formMenu').submit()
	});

	var delay = (function(){
	    var timer = 0;
	    return function(callback, ms){
	        clearTimeout (timer);
	        timer = setTimeout(callback, ms);
	    };
	})();

	function populateDeleted(id) {
	    var form = $('#formMenu');
	    var field = '<input type=\'hidden\' name=\'Menu[remove][]\' value=\'' + id + '\' />';
	    if (id != '') {
	        form.append(field);
	    }
	}

	$('.search-list-menu[data-target]').keyup(function(){
	    searchRoute($(this).data('target'));
	});

	function searchRoute(target){
	    var $routes = $('#list-routes');
	    $routes.html('');

	    var keyword = $('.search-list-menu').val();
	    $.each(_opts.routes, function(){
	        var r = this;
	        if (r.indexOf(keyword) >= 0) {
	            $('<label class="col-sm-12">'+
	                   '<input type="checkbox" class="route" data-val="'+ r +'" name="route[]" value=""> '+ r +
	                '</label>'
	            ).appendTo($routes);
	        }
	    });
	}
</script>