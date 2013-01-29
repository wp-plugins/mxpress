<?php


include_once dirname(__FILE__) . '/functions_config.php';
include_once dirname(__FILE__) . '/functions_admin.php';
include_once dirname(__FILE__) . '/functions_admin_selectlogo.php';
include_once dirname(__FILE__) . '/functions_content_filtering.php';
include_once dirname(__FILE__) . '/functions_comments.php';
include_once dirname(__FILE__) . '/functions_template_tags.php';
include_once dirname(__FILE__) . '/functions_tracking.php';
include_once dirname(__FILE__) . '/functions_navigation.php';
//Libraries
include_once dirname(__FILE__) . '/lib/simple_html_dom.php';



// initiate default options
function mxpress_activate() {
    $mxpress_options = get_option('mxpress_options');
    if (!$mxpress_options) {
        $mxpress_default_option_vals = mxpress_get_admin_option_defaults();
        mxpress_set_default_options(false, $mxpress_default_option_vals);
    }
    return;
}

/* 
 * utility functions:
 */

//sets default/recomended options
function mxpress_set_default_options($do_section = false, $mxpress_default_option_vals) {
    //sets default/recomended options 
    update_option('mxpress_options', $mxpress_default_option_vals);
    return;
}

// use option value to craft output or return value
function mxpress_ifoption($output_prepend, $option_val, $output_append = ';"', $echo = true, $use_value = true) {
    $value = ($use_value) ? $option_val : '';
    $output = ($option_val) ? $output_prepend . $value . $output_append : '';
    if ($echo) {
        echo $output;
        return;
    } else {
        return $output;
    }
}

// prevent sql injection for MySQL DBs, accepts string or array and escapes recursively
function mxpress_escape($data, $db) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            if (is_array($data[$key]))
                $data[$key] = mxpress_escape($data[$key], $db);
            if (is_string($data[$key]))
                $data[$key] = mysql_real_escape_string($data[$key], $db);
        }
    }
    if (is_string($data))
        $data = mysqli_real_escape_string(stripslashes($data[$key], $db));
    return $data;
}

function mxpress_get_screenwidth() {
    global $_SERVER;
    $UA = $_SERVER['HTTP_UA_PIXELS'];
    $UA_ar = explode('x', $UA);
    $uwidth = intval($UA_ar[0]);

    //dev:
    $uwidth = ($uwidth) ? $uwidth : 800;
    return $uwidth;
}

?>