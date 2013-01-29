<?php

define('TESTUSER', 'PUT_YOUR_MXITUSERID_HERE_TO_DEBUG');
define('TIMEOUT', 10);
define('IS_RESIZE_IMAGES', FALSE);
define('API_SERVER', 'http://ox-d.shinka.sh/ma/1.0/arj');

global $mxpress_options;
define('REFERER', $mxpress_options['codeShinka_APP_MXIT_ID']);
define('AdUnitID_320', $mxpress_options['codeShinka_UnitID_320']);
define('AdUnitID_216', $mxpress_options['codeShinka_UnitID_216']);
define('AdUnitID_168', $mxpress_options['codeShinka_UnitID_168']);
define('AdUnitID_120', $mxpress_options['codeShinka_UnitID_120']);
?>