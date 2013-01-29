<?php

// For now customization is either through the admin options or modifying the template files 'include'-ed below.
// 
// If we are in Mxit, load, process and render, then exits

global $_query_string, $doMxpressComment, $mxpres_wpsettings;

if (mxpress_isMixit()) {
    global $wp_query, $mxpress_options, $mx_state;
    $mxpress_options = get_option('mxpress_options');
    $mx_state = array('_SERVER' => $_SERVER, 'usr_width' => mxpress_get_screenwidth(), 'links_count' => 0);
//mxpress_debug('before header');
    include dirname(__FILE__) . '/mxpress_header.php';
//mxpress_debug('after header');
    if (is_front_page()) {
        include 'template_static_front_page.php';
    } elseif (is_home()) {
        include 'template_static_home_page.php';
    } elseif (is_page()) {
        include 'template_page.php';
    } elseif (is_single()) {
        include 'template_single_post.php';
    } elseif (is_archive()) {
        include 'template_archive_cat_or_terms.php';
    } else {
        //default, that should never be required
        include 'template_static_home_page.php';
    }
//mxpress_debug('after template switch');
    include dirname(__FILE__) . '/mxpress_footer.php';
//all done
    exit;
} else {

    // we're not in mxit, and probably not in Kansas either, we do nothing. WordPress clicks its heels and proceeds normally...
}
?>