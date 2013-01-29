<?php

//see also functions_admin.php
// add the admin settings and such
// initiate and configure settings
function mxpress_admin_init() {
    wp_register_style('MXPressStylesheet', plugins_url('style.css', __FILE__));
    wp_register_script('mxpressscript', plugins_url('/script.js', __FILE__));
    add_thickbox();

    global $mxpress_sections, $mxpres_wpsettings, $mxpress_options;

    $mxpress_options = get_option('mxpress_options');
    $mxpres_wpsettings = mxpress_get_relevant_wp_settings();
    // all setting fields are array elements of a single _options table row
    register_setting('mxpress_options', 'mxpress_options', 'mxpress_process_options');
    // <editor-fold defaultstate="collapsed" desc=" define admin sections ">
    // values ordered as per add_settings_section() requirements, except element[4] maps to reset buttons
    $mxpress_sections = array(
        array('branding_admin', 'Branding', 'mxpress_branding_section_text', 'mxpress_admin', 'Reset Branding Defaults'),
        array('contentfilter_admin', 'Content filtering', 'mxpress_contentfilter_section_text', 'mxpress_admin', 'Reset Filtering Defaults'),
        array('contentformat_admin', 'Content formatting', 'mxpress_contentformat_section_text', 'mxpress_admin', 'Reset Formatting Defaults'),
        array('navigation_admin', 'Navigation', 'mxpress_navigation_section_text', 'mxpress_admin', 'Reset Navigation Defaults'),
        array('template_front_page', 'Static Front Page', 'mxpress_template_front_page_text', 'mxpress_admin', 'Reset Static Front Page Defaults'),
        array('template_home_page', 'Static Home/Blog Index', 'mxpress_template_home_page_text', 'mxpress_admin', 'Reset Static Home/Blog Page Defaults'),
        array('template_page', 'Page', 'mxpress_template_page_text', 'mxpress_admin', 'Reset Page Defaults'),
        array('template_single_post', 'Single Post', 'mxpress_template_single_post_text', 'mxpress_admin', 'Reset Single Post Defaults'),
        array('template_archive', 'Archive (Category / Taxonomy Term Listing)', 'mxpress_template_archive_cat_or_terms_text', 'mxpress_admin', 'Reset Archive Defaults'),
        array('comments_admin', 'Comments', 'mxpress_comments_text', 'mxpress_admin', 'Reset Comments Defaults'),
        array('advertising_admin', 'Advertising', 'mxpress_advertising_admin_text', 'mxpress_admin', 'Reset Advertising Defaults'),
        array('log_admin', 'Logging &amp; tracking', 'mxpress_admin_section_text', 'mxpress_admin', 'Reset Tracking Defaults'),
    );

    foreach ($mxpress_sections as $section) {
        add_settings_section($section[0], $section[1], $section[2], $section[3]);
    }
    // </editor-fold>
    global $admin_options, $wp;
    // <editor-fold defaultstate="collapsed" desc=" Prepare a few labels that depend on existing wordpress settings ">
    // static front page ?                                                                                      
    $description_part_show_on_front = 'The <a href="' . get_bloginfo('url') . '/wp-admin/options-reading.php">Reading Settings</a> of this site are currently configured to show';
    if ($mxpres_wpsettings['show_on_front'] == 'posts') {
        $description_part_show_on_front .= ' <strong>recent posts</strong>. If it was set to show a static page this setting would';
    } elseif ($mxpres_wpsettings['show_on_front'] == 'page') {
        $description_part_show_on_front .= ' the page';
        $wpfront_page_id = get_option('page_on_front');
        $wpfront_page_item = get_post($wpfront_page_id);
        $wpfront_page_title = $wpfront_page_item->post_title;
        $description_part_show_on_front .= ' <strong>' . $wpfront_page_title . '</strong> at the root url (the &apos;front page&apos;),
      and to use the page';
        $wpposts_page_id = get_option('page_for_posts');
        $wpposts_page_item = get_post($wpposts_page_id);
        $wpposts_page_title = $wpposts_page_item->post_title;
        $description_part_show_on_front .= ' <strong>' . $wpposts_page_title . '</strong> to show the latest posts (the &apos;blog&apos; view).';
    }
    $mxpres_wpsettings['navmenus'] = wp_get_nav_menus();
    $catListPostsOnFrontPage_id_tmp = ($mxpress_options['catListPostsOnFrontPage']) ? '&selected=' . $mxpress_options['catListPostsOnFrontPage'] : '';
    $mxpres_wpsettings['catListPostsOnFrontPage'] = wp_dropdown_categories('show_count=1&hierarchical=1&echo=0&class=left&show_option_none=None' . $catListPostsOnFrontPage_id_tmp);
    $mxpres_wpsettings['posts_per_page'] = get_option('posts_per_page');
// </editor-fold>
    
    include 'admin_options.php';
    foreach ($admin_options as $option) {
        add_settings_field($option['id'], $option['label'], 'mxpress_render_setting_field', 'mxpress_admin', $option['section'], array(
            'ID' => $option['id'], 'field_type' => $option['field_type'], 'helptxt' => $option['helptxt'], 'disabled' => $option['disabled']
        ));
    }
}

function mxpress_render_setting_field($args) {
    global $mxpres_wpsettings;
    $mxpress_options = get_option('mxpress_options');
    $option_id = $args['ID'];
    $type = $args['field_type'];
    $disabled = ($args['disabled']) ? 'disabled="disabled"' : '';
    $disabled_txt = ($args['disabled']) ? '<br />[Disabled, coming soon]' : '';

    switch ($type) {
        case'singleoption':
            switch ($option_id) {
                case'MasterLogo':
                    $logo_options = array(
                        'none' => 'None',
                        'featured_image' => 'Use featured image (if available)',
                        'new' => 'Use newly selected',
                    );
                    if (($_GET['logo_selected_id']) && (stristr($_SERVER['HTTP_REFERER'], 'upload.php'))) {
                        $new_logo_id = $_GET['logo_selected_id'];
                    }
                    $saved_logo_id = (isset($mxpress_options['MasterLogo_id'])) ? $mxpress_options['MasterLogo_id'] : false;
                    $amended_options = $mxpress_options;
                    if (($new_logo_id)) {
                        $new_disabled = '';
                        $amended_options['MasterLogo'] = 'new';
                    } else {
                        $new_disabled = ' disabled="disabled" ';
                    }
                    if ($saved_logo_id) {
                        if ($mxpress_options['MasterLogo'] != 'previous') {
                            $logo_options['previous'] = 'Use previously selected';
                        } else {
                            $logo_options['previous'] = 'Keep using previously selected';
                        }
                    }
                    echo "<select $disabled id='$option_id' name='mxpress_options[$option_id]'  style=\"float:left\" >";
                    foreach ($logo_options as $option => $description) {
                        $selected = ($amended_options[$option_id] == $option) ? 'selected ="selected"' : '';
                        $option_disbled = '';
                        if ($option == 'new') {
                            $option_disbled = $new_disabled;
                        }
                        echo '<option id = "logo_option_' . $option . '" value ="' . $option . '" ' . $selected . $option_disbled . ' >' . $description . '</option>';
                    }
                    echo "</select>";
                    break;
                case 'useMenuOnFrontPage':
                case 'useMenuOnHomePage':
                case 'useMenuOnPage':
                case 'useMenuOnSingle':
                case 'useMenuOnArchivePage':
                    echo "<select $disabled id='$option_id' name='mxpress_options[$option_id]'  style=\"float:left\" >";
                    $navmenuar = $mxpres_wpsettings['navmenus'];
                    echo "<option value =''> </option>";
                    $selected = '';
                    foreach ($navmenuar as $navmenu) {
                        $selected = ($mxpress_options[$option_id] == $navmenu->name) ? 'selected ="selected"' : '';
                        echo '<option value ="' . $navmenu->name . '" ' . $selected . ' >' . $navmenu->name . '</option>';
                    }
                    echo "</select>";
                    break;
                case 'placeMenuOnFrontPage':
                case 'placeMenuOnHomePage':
                case 'placeMenuOnPage':
                case 'placeMenuOnSingle':
                case 'placeMenuOnArchivePage':
                    if ($mxpress_options[$option_id] == 'Before') {
                        $before_selected = 'selected = "selected"';
                        $after_selected = '';
                    } elseif ($mxpress_options[$option_id] == 'After') {
                        $before_selected = '';
                        $after_selected = 'selected = "selected"';
                    }
                    echo "<select $disabled id='$option_id' name='mxpress_options[$option_id]'  style=\"float:left\" >";
                    echo "<option value ='Before' $before_selected >Before</option>";
                    echo "<option value ='After' $after_selected >After</option>";
                    echo "</select>";
                    break;
                case 'catListPostsOnFrontPage':
                    echo 'huh' . $mxpres_wpsettings['catListPostsOnFrontPage'];
                    break;
                case 'orderComments':
                    if ($mxpress_options[$option_id] == true) {
                        $oldest_selected = 'selected = "selected"';
                        $newest_selected = '';
                    } elseif ($mxpress_options[$option_id] == false) {
                        $oldest_selected = '';
                        $newest_selected = 'selected = "selected"';
                    }
                    echo "<select $disabled id='$option_id' name='mxpress_options[$option_id]'  style=\"float:left\" >";
                    echo "<option value ='0' $newest_selected >Newest First</option>";
                    echo "<option value ='1' $oldest_selected >Oldest First</option>";
                    echo "</select>";
                    break;
                case 'doShowContentAboveCommentform':
                    if ($mxpress_options[$option_id] == 'None') {
                        $none_selected = 'selected = "selected"';
                        $extract_selected = '';
                        $full_selected = '';
                    } elseif ($mxpress_options[$option_id] == 'Extract') {
                        $none_selected = '';
                        $extract_selected = 'selected = "selected"';
                        $full_selected = '';
                    } elseif ($mxpress_options[$option_id] == 'Full') {
                        $none_selected = '';
                        $extract_selected = '';
                        $full_selected = 'selected = "selected"';
                    }

                    echo "<select $disabled id='$option_id' name='mxpress_options[$option_id]'  style=\"float:left\" >";
                    echo "<option value ='None' $none_selected >None</option>";
                    echo "<option value ='Extract' $extract_selected >Extract</option>";
                    echo "<option value ='Full' $full_selected >Full</option>";
                    echo "</select>";
                    break;
                    break;
            }
            break;
        case'imagedisplay':
            switch ($option_id) {
                case 'MasterLogo_id':
                    $new_logo_id = $_GET['logo_selected_id'];
                    $saved_logo_id = $mxpress_options['MasterLogo_id'];
                    if ($new_logo_id) {
                        echo "have new";
                        // var_dump($_SERVER);
                        // exit;
                    }

                    if (($saved_logo_id)) {
                        ?><div id="old_logo" style="float:left;padding:0 1em 0 0"><span>Previous Logo</span ><br><?php
                        echo wp_get_attachment_image($saved_logo_id, 'mxptmb108');
                        ?></div>
                            <?php
                        }
                        $image_library_url_old = get_upload_iframe_src('image', null, 'library');
                        $image_library_url_sans_TB = remove_query_arg(array('TB_iframe'), $image_library_url_old);
                        $image_library_url = add_query_arg(array('context' => 'mxpress-logo-image', 'TB_iframe' => 1), $image_library_url_sans_TB); //
                        ?>
                    <div id="new_logo" style="float:left;padding:0 1em 0 0"><span>New Logo</span ><br><?php
                    if (($new_logo_id) && ($new_logo_id != $saved_logo_id)) {
                        echo wp_get_attachment_image($_GET['logo_selected_id'], 'mxptmb108', false, 'id=newly_selected');
                    } else {
                            ?><img src="" id="newly_selected" /><?php
                    }
                        ?>
                        <p>
                            <a title="Set logo image" href="<?php echo esc_url($image_library_url); ?>" id="set-logo-image" class=" thickbox insert-media add_media" >Set Logo Image</a>
                        </p>
                    </div>
                    <?php ?>
                    <input type='hidden' name="mxpress_options[MasterLogo_id_old]" value="<?php echo $saved_logo_id; ?>" />
                    <input type='hidden' name="mxpress_options[MasterLogo_id_new]" value="<?php echo $new_logo_id; ?>" id="new_logo_id" />
                    <?php
                    //echo $saved_logo_id . ',' . $new_logo_id;
                    break;
            }
            break;
        case'checkbox':
            $checked = ($mxpress_options[$option_id]) ? 'checked ="checked"' : '';
            echo "<input $disabled id='$option_id' name='mxpress_options[$option_id]' type='$type' $checked style=\"float:left\" />";
            break;
        case'text':
        default:
            $output = esc_attr($mxpress_options[$option_id]);
            form_option($mxpress_options[$option_id]);
            echo "<input $disabled id='$option_id' name='mxpress_options[$option_id]' size='40' type='$type' value='$output' style=\"float:left\" />";
            break;
    }
    echo '<span style="float:left; padding-left:1em; margin:0">' . $args['helptxt'] . $disabled_txt . '</span>';
}
?>