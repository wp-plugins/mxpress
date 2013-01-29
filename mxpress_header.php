<html><head><?php global $_SERVER; ?><meta name="mxit" content="clear_on_new,<?php
if ((stripos(' ' . $_SERVER['HTTP_X_DEVICE_USER_AGENT'], 'ipad')) || (stripos(' ' . $_SERVER['HTTP_X_DEVICE_USER_AGENT'], 'iphone'))) {
    // keep prefix for iphones
} else {
   ?>no_prefix,<?php }
?>show_progress" />
    </head>
    <body <?php mxpress_body_style(); ?>>
        <?php mxpress_get_shinka_banner_ad('Mast',true,'','<br />'); ?>