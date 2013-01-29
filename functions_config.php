<?php

// config inits:

add_action('admin_init', 'mxpress_admin_init');
add_action('admin_menu', 'mxpress_add_admin_page'); // see admin.php
add_filter('media_row_actions', 'mxpress_media_row_actions', 10, 3);
add_action('save_post', 'update_mxpress_content');
register_activation_hook(__FILE__, 'mxpress_activate');
add_action('wp', 'mxpress_render'); // does the translation
add_action('pre_get_posts', 'mxpress_cpage_intercept');

// typical sizes:

add_image_size('mxptmb432', 432, 432); // w x h for 480
add_image_size('mxptmb288', 288, 288); // 320
add_image_size('mxptmb216', 216, 216); // 240 
add_image_size('mxptmb108', 108, 108); // 120

function mxpress_media_row_actions($actions, $post) {
    // Add capability check
    if (!current_user_can('edit_post', $post->ID)) {
        return $actions;
    }

    $actions['mxpress'] = "<a class='' href='" . get_bloginfo('wpurl') . "/wp-admin/themes.php?page=mxpress_plugin&logo_selected_id=" . $post->ID . "' title='Select as MXPress Logo' >" . _('MXPress Logo') . "</a>";
    //class thickbox to make popup
    return $actions;
}

function update_mxpress_content() {
    mxpress_activate();
    $post_id = $_POST['post_ID'];
    $post = get_post($post_id);
    $paginated_content_ar = mxpress_paginate($post->post_content);
    $content_serlz = serialize(mxpress_paginate($post->post_content));
    update_post_meta($post_id, '_mxpress_content', $content_serlz) . ']'; // $content_serlz
    return;
}

function mxpress_debug($message) {
    global $_SERVER;
    if (($_GET['mxpress_debug']) || (stristr($_SERVER['HTTP_REFERER'], 'localhost'))) { // ||(1==1) ||($_SERVER['HTTP_X_MXIT_USERID_R'])  )
        if (!is_array($message)) {
            echo '<br />Debug:[_' . $message . '_]<br />';
        } else {
            echo '<br>Debug:<pre>';
            var_dump($message);
            echo '</pre><br>';
        }
    }
}

function mxpress_isMixit() {
    global $_SERVER, $_query_string, $doMxpressComment, $mxpres_wpsettings;
    $_query_string = $_SERVER['QUERY_STRING'];
    $doMxpressComment = $_GET['doMxpressComment'];
    $_mixitversion = stripos($_query_string, 'mxitversion');
    if (($_SERVER['HTTP_X_MXIT_USERID_R'] || ($_mixitversion !== false) || ($_GET['debug_mxp'] == '1') ) // 
            && (!is_admin())) {
        return true;
    } else {
        return false;
    }
}

if (!function_exists('get_attachment_id')) {

    /**
     * Get the Attachment ID for a given image URL.
     * @link   http://wordpress.stackexchange.com/a/7094
     * @param  string $url
     * @return boolean|integer
     */
    function get_attachment_id($url) {
        $dir = wp_upload_dir();
        // baseurl never has a trailing slash
        if (false === strpos($url, $dir['baseurl'] . '/')) {
            // URL points to a place outside of upload directory
            return false;
        }
        $file = basename($url);
        $query = array(
            'post_type' => 'attachment',
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'value' => $file,
                    'compare' => 'LIKE',
                ),
            )
        );
        $query['meta_query'][0]['key'] = '_wp_attached_file';
        // query attachments
        $ids = get_posts($query);
        if (!empty($ids)) {
            foreach ($ids as $id) {
                // first entry of returned array is the URL
                if ($url === array_shift(wp_get_attachment_image_src($id, 'full')))
                    return $id;
            }
        }
        $query['meta_query'][0]['key'] = '_wp_attachment_metadata';
        // query attachments again
        $ids = get_posts($query);
        if (empty($ids))
            return false;
        foreach ($ids as $id) {
            $meta = wp_get_attachment_metadata($id);
            foreach ($meta['sizes'] as $size => $values) {
                if ($values['file'] === $file && $url === array_shift(wp_get_attachment_image_src($id, $size)))
                    return $id;
            }
        }
        return false;
    }

}


?>