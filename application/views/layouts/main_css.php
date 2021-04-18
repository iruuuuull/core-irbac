<!-- Font Awesome -->
<link rel="stylesheet" href="<?= base_url('/web/assets/plugins/fontawesome-free/css/all.min.css') ?>">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="<?= base_url('/web/assets/css/adminlte.min.css') ?>">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

<style type="text/css">
	.page-content {
	    background: #eef1f5;
	}
	.label-required::after {
    	content: ' * ';
	    color: red;
	}
	.page-header.navbar .page-logo .logo-default {
	    margin: 6px 0 0 !important;
	}
	#overlay {
		background: rgba(236, 240, 241, 0.5);
		color: #666666;
		position: fixed;
		height: 100%;
		width: 100%;
		z-index: 10050;
		top: 0;
		left: 0;
		float: left;
		text-align: center;
		padding-top: 25%;
	}
	/*Example 1, all the CSS is defined here and not in JS*/
    #markerWithExternalCss {
        background-image: url('<?= base_url('/web/images/marker.png') ?>');
        background-size: cover;
        width: 22px;
        height: 32px;
        cursor: pointer;
    }

    .modal-open .colorpicker, .modal-open .datepicker, .modal-open .daterangepicker {
	     /*z-index: 2!important; */
	}
	.sweet-alert, .sweet-overlay {
		z-index: 10050!important;
	}
	.align-items-center {
		-ms-flex-align: center !important;
		align-items: center !important;
	}
	.justify-content-center {
		-ms-flex-pack: center !important;
		justify-content: center !important;
	}

/*    .modal-open .colorpicker, .modal-open .datepicker, .modal-open .daterangepicker {
	     z-index: 2!important; 
	}*/
</style>

<?php 
# Load self-made file views for setting css
if (!empty($view_css)) {
	if (is_string($view_css)) {
		$this->load->view($view_css);

	} elseif (is_array($view_css)) {
		foreach ($view_css as $key => $css) {
			$this->load->view($css);
		}

	}

}
?>

<link rel="shortcut icon" href="<?= base_url('/web/images/favicon.png') ?>" /> </head>
