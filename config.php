<?php

include_once dirname(__FILE__) . '/admin.php';
include_once dirname(__FILE__) . '/functions_content_filtering.php';
include_once dirname(__FILE__) . '/functions_template_tags.php';
include_once dirname(__FILE__) . '/functions_tracking.php';
include_once dirname(__FILE__) . '/functions_navigation.php';

// set default options
function kzmx_activate() {
    $mx_options = get_option('kzmx_options');
    if (!$mx_options) {
        kzmx_set_default_options();
    }
    return;
}

// utility functions
//sets default/recomended options
function kzmx_set_default_options() {
    //sets default/recomended options';
    $default_option_vals = array(
        'doShowTitles' => 'on',
        'doConvertBullets' => 'on',
        'doWrapListBRs' => 'on',
        'doExtraLiBRs' => 'on',
        'doDynamicSmartLinks' => 'on',
        'doStripTags' => '<a><b><br><i><em><li><h1><h2><h3><h4><h5><h6>',
        'doAutoSplitContent' => 'on',
        'splitLength' => '600',
        'doKeepManualSplits' => 'on',
        'doFixExternalLinks' => 'on',
        'isRedirExternalLinks' => 'on',
        'setBackgroundColor' => '#FFFFFF',
        'setBodyColor' => '#666666',
        'titleCol' => '#000000',
        'h1Col' => '#222222',
        'h2Col' => '#444444',
        'h3Col' => '#666666',
        'h4Col' => '#888888',
        'h5Col' => '#AAAAAA',
        'h6Col' => '#BBBBBB',
        'setGA_ACCOUNT' => ''
    );
    update_option('kzmx_options', $default_option_vals);
    return;
}

// use option value to craft output or return value
function kzmx_ifoption($output_prepend, $option_val, $output_append = ';"', $echo = true, $use_value = true) {
    $value = ($use_value) ? $option_val : '';
    $output = ($option_val) ? $output_prepend . $value . $output_append : '';
    if ($echo) {
        echo $output;
        return;
    } else {
        return $output;
    }
}

// prevent sql injection, accepts string or array and escapes recursively
function my_escape($data, $db) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            if (is_array($data[$key]))
                $data[$key] = myi_escape($data[$key], $db);
            if (is_string($data[$key]))
                $data[$key] = mysql_real_escape_string($data[$key], $db);
        }
    }
    if (is_string($data))
        $data = mysqli_real_escape_string(stripslashes($data[$key], $db));
    return $data;
}

function kzmx_get_screenwidth($_SERVER) {
    $UA = $_SERVER['HTTP_UA_PIXELS'];
    $UA_ar = explode('x', $UA);
    $uwidth = intval($UA_ar[0]);
    $uwidth = ($uwidth) ? $uwidth : 800;
    return $uwidth;
}

?>