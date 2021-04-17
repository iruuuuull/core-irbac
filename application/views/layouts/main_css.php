<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
<link href="<?= base_url("/web/assets/global/plugins/font-awesome/css/font-awesome.min.css") ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url("/web/assets/global/plugins/simple-line-icons/simple-line-icons.min.css") ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url("/web/assets/global/plugins/bootstrap/css/bootstrap.min.css") ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url("/web/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css") ?>" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PLUGIN STYLE -->
<link href="<?= base_url("/web/assets/global/plugins/bootstrap-sweetalert/sweetalert.css") ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url("/web/assets/global/plugins/ladda/ladda-themeless.min.css") ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('/web/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('/web/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('/web/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('/web/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url('/web/assets/global/plugins/clockface/css/clockface.css') ?>" rel="stylesheet" type="text/css" />

<!-- END PLUGIN STYLE -->
<!-- BEGIN THEME GLOBAL STYLES -->
<link href="<?= base_url("/web/assets/global/css/components.min.css") ?>" rel="stylesheet" id="style_components" type="text/css" />
<link href="<?= base_url("/web/assets/global/css/plugins.min.css") ?>" rel="stylesheet" type="text/css" />
<!-- END THEME GLOBAL STYLES -->
<!-- BEGIN THEME LAYOUT STYLES -->
<link href="<?= base_url("/web/assets/layouts/layout/css/layout.min.css") ?>" rel="stylesheet" type="text/css" />
<link href="<?= base_url("/web/assets/layouts/layout/css/themes/darkblue.min.css") ?>" rel="stylesheet" type="text/css" id="style_color" />
<link href="<?= base_url("/web/assets/layouts/layout/css/custom.min.css") ?>" rel="stylesheet" type="text/css" />
<!-- END THEME LAYOUT STYLES -->

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
