/* AWAL SWAL ALERT */
var oTable;

function datatable(id, url) {
	var table = id;
	// var fixedHeaderOffset = 0;

 //  if (App.getViewPort().width < App.getResponsiveBreakpoint('md')) {
 //    if ($('.page-header').hasClass('page-header-fixed-mobile')) {
 //      fixedHeaderOffset = $('.page-header').outerHeight(true);
 //    } 
 //  } else if ($('.page-header').hasClass('navbar-fixed-top')) {
 //    fixedHeaderOffset = $('.page-header').outerHeight(true);
 //  } else if ($('body').hasClass('page-header-fixed')) {
 //    fixedHeaderOffset = 64; // admin 5 fixed height
 //  }

  oTable = table.DataTable({ 
  	"language": {
      "aria": {
        "sortAscending": ": activate to sort column ascending",
        "sortDescending": ": activate to sort column descending"
      },
      "emptyTable": "Data tidak tersedia",
      "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      "infoEmpty": "Data tidak ditemukan",
      // "infoFiltered": "",
      "lengthMenu": "Tampilkan _MENU_",
      "search": "Cari:",
      "zeroRecords": "Tidak ditemukan data yang cocok",
      "paginate": {
        "previous":"Sebelumnya",
        "next": "Selanjutnya",
        "last": "Terakhir",
        "first": "Pertama"
      }
    },
    "lengthMenu": [
      [20, 50, 100, -1],
      [20, 50, 100, "All"] // change per page values here
    ],
    fixedHeader: {
      header: true,
      // headerOffset: fixedHeaderOffset
    },
    "pageLength": 20,
    "destroy": true,
    "bStateSave": true,
    "processing": true, 
    "serverSide": true, 
    "order": [],
    "ajax": {
      "url": url,
      "type": "POST",
      "data": {
          [csrf_name]: csrf_hash
      },
    },
    "columnDefs": [
    { 
      "targets": [ 0 ], 
      "orderable": false, 
    },
    { className: 'text-center', targets: [0, 1] },
    ],
  });
}

function datatableReload() {
  oTable.ajax.reload();
}

/* AKHIR DATATABLE */

$(document).ready(function() {
  if (jQuery().repeater) {
    $(".mt-repeater").repeater({
      show: function () {
          $(this).slideDown();
      },
      hide: function (deleteElement) {
          $(this).slideUp(deleteElement);
      },
      ready: function (setIndexes) {},
      initEmpty: true
    });
  }

  if (jQuery().maxlength) {
    $('.max-length-default').maxlength({
      limitReachedClass: "label label-danger",
    });
  }

  if (jQuery.fn.select2) {
    $('.select2').select2({
      // theme: 'bootstrap',
      width: '100%'
    });
  }

  autoActiveSidebar();
});

/* AWAL SWAL ALERT */
function swalert(options, callback = false) {
  let message = '';
  let title = 'Informasi';
  let type = 'info';

  if (typeof options === 'string') {
    title = options;
  }

  if (options.hasOwnProperty('title')) {
    title = options.title;
  }
  if (options.hasOwnProperty('message')) {
    message = options.message;
  }
  if (options.hasOwnProperty('type')) {
    type = options.type;
  }

  return swal.fire({
    icon: type,
    title: title,
    // text: message,
    html: message
  }, callback)
}
/* AKHIR SWAL ALERT */

/* AWAL CUSTOM CONFIRMATION */
function customConfirmation(options = null, callback) {
  let message = 'Apakah anda yakin?';
  let confirm_button = 'Ya';
  let cancel_button = 'Tidak';
  let title = 'Perhatian';
  let type = 'info';

  if (options.hasOwnProperty('title')) {
    title = options.title;
  }
  if (options.hasOwnProperty('message')) {
    message = options.message;
  }
  if (options.hasOwnProperty('confirm_button')) {
    confirm_button = options.confirm_button;
  }
  if (options.hasOwnProperty('cancel_button')) {
    cancel_button = options.cancel_button;
  }
  if (options.hasOwnProperty('type')) {
    type = options.type;
  }

  return swal.fire({
    title: title,
    icon: type,
    // text: message,
    showCancelButton: true,
    confirmButtonText: confirm_button,
    cancelButtonText: cancel_button,
    customClass: 'swal-wide',
    html: message
  }). then((confirmed) => {
    callback(confirmed.value === true);
  });
}
/* AKHIR CUSTOM CONFIRMATION */

/* AWAL SCROLLBAR TOP TABLE RESPONSIVE */
function topBottomScrollbar() {
  let table = $(".table-responsive .gridview .table");

  if (table.length > 0) {
    $('<div class=\"wrapper1\"><div class=\"div1\"></div></div>').insertBefore('.table-responsive .gridview .table');
    $('.gridview .table').wrapAll('<div class=\"wrapper2\">');
    $('.gridview .table').wrapAll('<div class=\"div2\">');

    $('.wrapper1').on('scroll', function (e) {
        $('.wrapper2').scrollLeft($('.wrapper1').scrollLeft());
    }); 
    $('.wrapper2').on('scroll', function (e) {
        $('.wrapper1').scrollLeft($('.wrapper2').scrollLeft());
    });

    $('.div1').width($('.gridview .table').width());
    $('.div2').width($('.gridview .table').width());

    // Delete div table responsive to avoid repetitive div scrollbar
    // $(".table-responsive > div").unwrap();
  }
}

$(document).ready(function() {
  topBottomScrollbar();
});
/* AKHIR SCROLLBAR TOP TABLE RESPONSIVE */

/* MULAI FUNGSI PREVIEW PDF */
function previewPDF(path) {
  var embed_url = path;
  var modal = $("#modal");
  modal.find(".modal-body")
    .html(`<object type="application/pdf" data="" width="100%" height="500" style="height: 85vh;" id="pdf_preview">
      <i>Browser</i> Tidak Mendukung atau File Tidak Ada</object>`);
  modal.modal("show");

  setTimeout(() => {
    $("#pdf_preview").attr("data", path);
  }, 1000);
}
/* AKHIR FUNGSI PREVIEW PDF */

/* Ladda button submit */
// $(document).ready(function() {
//   Ladda.bind( '[type=submit]', { timeout: 2000 } );
// });
/* Ladda button submit */

/* Default Datepicker */
$(document).ready(function() {
  $('.datepicker').attr('readonly', true);
  $('.datepicker').daterangepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
  });
});
/* Default Datepicker */

// MODAL DRAGGABLE
// $(".draggable-modal").draggable({
//   handle: ".modal-header"
// });
// MODAL DRAGGABLE

/* OVERLAY LOADER */
function myLoader(show = true) {
  if (show) {
    $('#overlay').fadeIn();
  } else {
    $('#overlay').fadeOut();
  }
}
/* OVERLAY LOADER */

/* MULAI FUNGSI PREVIEW PDF */
function previewPDF(path, title=null) {
  var embed_url = path;

  var modal = $("#modal-preview");
  modal.find(".modal-body")
    .html(`<object type="application/pdf" data="" width="100%" height="500" style="height: 85vh;" id="pdf_preview">
      <i>Browser</i> Tidak Mendukung atau File Tidak Ada</object>`);
  modal.modal("show");

  if (title) {
    modal.find('.modal-title').text(title);
  }

  setTimeout(() => {
    $("#pdf_preview").attr("data", path);
  }, 1000);
}
/* AKHIR FUNGSI PREVIEW PDF */

/**
 * @link   <https://www.cambiaresearch.com/articles/39/how-can-i-use-javascript-to-allow-only-numbers-to-be-entered-in-a-textbox>
 * @param  {[type]}  evt [description]
 * @return {Boolean}     [description]
 */
function isNumberKey(evt) {
  var charCode = evt.which ? evt.which : event.keyCode;

  if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;

  return true;
}

function getGreeting() {
  let thehours = new Date().getHours();
  let themessage;
  let morning = ('Selamat Pagi');
  let afternoon = ('Selamat Siang');
  let evening = ('Selamat Sore');

  if (thehours >= 0 && thehours < 12) {
    themessage = morning; 
  } else if (thehours >= 12 && thehours < 17) {
    themessage = afternoon;
  } else if (thehours >= 17 && thehours < 24) {
    themessage = evening;
  }

  return themessage;
}

/**
 * [bytesToSize description]
 * @link <https://www.script-tutorials.com/html5-image-uploader-with-jcrop/comment-page-1/>
 * @param  {[type]} bytes [description]
 * @return {[type]}       [description]
 */
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

$("input[type=file]").on('change', function(event) {
  let max_size = $(this).data('size');

  if (max_size && $(this).val().length > 0) {
    $(".file-msg").remove();

    // check for file size
    if ($(this)[0].files[0].size > 500 * 1024) {
      $(this).after(`<div class="text-danger file-msg">File berukuran terlalu besar, maksimal ukuran adalah ${max_size}KB</div>`);

      setTimeout(() => {
        $(this).val('').trigger('change');
      }, 200);

      return false;
    }
  }
});

/**
 * [capitalizeFirstLetter description]
 * @link <https://stackoverflow.com/a/33704783>
 * @return {[type]} [description]
 */
String.prototype.ucfirst = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

/**
 * [generateMap description]
 * @param  {string} container  [description]
 * @param  {Object} coordinate [description]
 * @return {void}              [description]
 */
function generateMap(container, coordinate = {}) {
  $(`#${container}`).removeClass('mapboxgl-map').empty();

  var object_init = {
    container: container,
    attributionControl: false, //need this to show a compact attribution icon (i) instead of the whole text
    zoom: 15,
    style: 'https://tiles.locationiq.com/v2/streets/vector.json?key='+key_map,
  }

  let long, lat;

  if (!jQuery.isEmptyObject(coordinate)) {
    object_init.center = [coordinate.long, coordinate.lat];

    long = coordinate.long;
    lat = coordinate.lat;
  }

  //Define the map and configure the map's theme
  var map = new mapboxgl.Map(object_init);

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

  if (jQuery.isEmptyObject(coordinate)) {
    map.addControl(geoTracker);
    geoTracker._geolocateButton.click();
  } else {
    var el = document.createElement('div');
    el.id = 'markerWithExternalCss';
    // finally, create the marker
    var markerWithExternalCss = new mapboxgl.Marker(el)
        .setLngLat([long, lat])
        .addTo(map);
  }

  map.resize();
}

function datatableManual(id) {
  var table = $(`#${id}`);
  var fixedHeaderOffset = 0;

  if (App.getViewPort().width < App.getResponsiveBreakpoint('md')) {
    if ($('.page-header').hasClass('page-header-fixed-mobile')) {
      fixedHeaderOffset = $('.page-header').outerHeight(true);
    } 
  } else if ($('.page-header').hasClass('navbar-fixed-top')) {
    fixedHeaderOffset = $('.page-header').outerHeight(true);
  } else if ($('body').hasClass('page-header-fixed')) {
    fixedHeaderOffset = 64; // admin 5 fixed height
  }

  table.DataTable({ 
    "language": {
      "aria": {
        "sortAscending": ": activate to sort column ascending",
        "sortDescending": ": activate to sort column descending"
      },
      "emptyTable": "Data tidak tersedia",
      "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
      "infoEmpty": "Data tidak ditemukan",
      // "infoFiltered": "",
      "lengthMenu": "Tampilkan _MENU_",
      "search": "Cari:",
      "zeroRecords": "Tidak ditemukan data yang cocok",
      "paginate": {
        "previous":"Sebelumnya",
        "next": "Selanjutnya",
        "last": "Terakhir",
        "first": "Pertama"
      }
    },
    "lengthMenu": [
      [20, 50, 100, -1],
      [20, 50, 100, "All"] // change per page values here
    ],
    fixedHeader: {
      header: true,
      headerOffset: fixedHeaderOffset
    },
    "pageLength": 20,
    "order": [],
    "columnDefs": [
    { 
      "targets": [ 0 ], 
      "orderable": false, 
    },
    ],
  });
}

function convertTZ(date, tzString) {
  return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));   
}

function getCurrentDate() {
  let d = new Date;
  let months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
  let days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
  let date = d.getDate() +' '+ months[d.getMonth()] +' '+ d.getFullYear();
  let time = ('0' + d.getHours()).slice(-2) +'<span class="blinking">:</span>'+ ('0' + d.getMinutes()).slice(-2);
  let ampm = d.getHours() >= 12 ? 'PM' : 'AM';

  return [
    
  ];
}

jQuery(document).ajaxStart(function(){
  myLoader();
});

jQuery(document).ajaxStop(function(){
  myLoader(false);
});

// START SET DROPDOWN
function setDropdown(data, id, label = '- Pilih -') {
  let dropdown = $(`#${id}`);
  dropdown.empty();

  dropdown.append(`<option value="">${label}</option>`);
  $.each(data, function(index, val) {
    if (typeof val === 'object') {
      if (val.length == 0) return;

      let opts = `<optgroup label="${index}">`;

        $.each(val, function(index1, val1) {
          opts += `<option value="${index1}">${val1}</option>`;
        })

      opts += `</optgroup>`;

      dropdown.append(opts);

    } else {
      dropdown.append(`<option value="${index}">${val}</option>`);
    }
  });

  dropdown.focus();
}
// END SET DROPDOWN

/**
 * [addWorkDays description]
 * @@link https://www.outsystems.com/forums/discussion/45772/how-to-add-days-to-date-excluding-weekends/
 * @param {Date} startDate [description]
 * @param {int}  days      [description]
 */
function addWorkDays(startDate, days) {
  if(isNaN(days)) {
    console.error("Value provided for \"days\" was not a number");
    return
  }

  if(!(startDate instanceof Date)) {
    console.error("Value provided for \"startDate\" was not a Date object");
    return
  }

  var endDate = "", noOfDaysToAdd = (days - 1), count = 0;
  while(count < noOfDaysToAdd){
      endDate = new Date(startDate.setDate(startDate.getDate() + 1));
      if(endDate.getDay() != 0){
         //Date.getDay() gives weekday starting from 0(Sunday) to 6(Saturday)
         count++;
      }
  }

  return endDate;//You can format this date as per your requirement
}

/**
 * [Set value of dropdown when dropdown has options]
 * @param {[type]} id    [description]
 * @param {[type]} value [description]
 */
function setDropdownValue(id, value) {
  let selector = $(`#${id}`)
  let count = 0;

  if (selector.length) {
    var interval = setInterval(() => {
      if ($(`#${id} option`).length > 1) {
        selector.val(value).trigger('change');

        clearInterval(interval);
      }

      if (count > 1000) {
        clearInterval(interval);
      }

      count++;
    }, 500);
  }
}

/* MULAI AUTO ACTIVE PARENT MENU */
function autoActiveSidebar() {
  let li_active = $('.nav-sidebar').find('a.active');

  if (li_active.parent().parent().length > 0) {
    li_active.parent().parent().each(function(index, el) {

      if ($(this).hasClass('nav-treeview') && !$(this).prev('a').hasClass('active')) {
        $(this).prev('a').addClass('active');
        $(this).css('display', 'block');
        $(this).parent('li.nav-item').addClass('menu-open');
        autoActiveSidebar();
      }
    });
  }
}
/* AKHIR AUTO ACTIVE PARENT MENU */

/* AWAL Custom Regex */
function stringStrip(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  key = String.fromCharCode( key );
  var regex = /^[a-z\-]+$/;

  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
/* AKHIR Custom Regex */
