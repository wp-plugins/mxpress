<?php

// add the admin options page
function mxpress_add_admin_page() {
    //add_filter('template_include','start_buffer_capture',1);
    $page = add_submenu_page('themes.php', 'Mxit Options', 'Mxit Options', 'manage_options', 'mxpress_plugin', 'mxpress_options_page');
    add_action('admin_print_styles-' . $page, 'mxpress_admin_styles');
    add_action("admin_print_scripts-{$page}", 'mxpress_page_scripts');

    if (check_upload_image_context('mxpress-logo-image')) {
        // mxpress_debug('check_upload_image_context');
        add_filter('media_upload_tabs', 'mxpress_image_tabs', 10, 1);
        add_filter('attachment_fields_to_edit', 'mxpress_action_button', 20, 2);
        add_filter('media_send_to_editor', 'mxpress_image_selected', 10, 3);
    } else {
        // mxpress_debug('NOT check_upload_image_context');
    }
}

function mxpress_admin_styles() {
    wp_enqueue_style('MXPressStylesheet');
    wp_enqueue_style('thickbox');
}

function mxpress_page_scripts() {
    wp_enqueue_script('thickbox');
}

// display the admin options page
function mxpress_options_page() {
    global $mxpress_sections;
    ?>
    <div>
        <h2><?php _e('Settings for translating WordPress output for Mxit Mobi-Portal'); ?></h2>
        <p><?php
    _e('This is the public BETA release: ');
    echo mxpress_get_version();
    ?></p>
        <script type="text/javascript">
            function unhide(classIdentifier) {
                var div_ar = document.getElementsByTagName("div")
                                                                                                                                            
                for (var i=0; i<div_ar.length;i++ )
                {     
                    var item = div_ar[i];
                    //item.className +=" "+i;
                    if(item.className.indexOf(classIdentifier)!==-1){
                        if(item.className.indexOf("doHide")<0) {
                            item.className +=" doHide";
                            item.className.replace(" doShow","");
                        } else {
                            item.className = item.className.replace("doHide","doShow"); 
                            item.parentNode.scrollIntoView(true);
                        }
                    }
                }
            }
        </script>
        <?php
        // render shortcuts to sections
        ?><a name="mxpress_sections_menu" id="mxpress_sections_menu"> </a><div id="mxpress-tabs" >
        <?php
        foreach ($mxpress_sections as $section) {
            ?> <div class="mxptab" ><a href="javascript:unhide('<?php echo $section[0]; ?>')"> <?php echo $section[1]; ?></a>  </div>

                <?php
            }
            ?><div style="clear:all" class="mxspacer">&nbsp;</div></div>
        <form action="options.php" method="post">
            <?php settings_fields('mxpress_options'); ?>            
            <?php mxpress_do_settings_sections('mxpress_admin'); ?>
            <p>
                <?php mxpress_save_input(); ?>
                <input name="Submit" type="submit" value="<?php esc_attr_e('Restore All Option Defaults'); ?>" class="button-primary "/>
            </p>
        </form>
    </div>
    <?php
}

function mxpress_do_settings_sections($page) {
    global $wp_settings_sections, $wp_settings_fields;

    if (!isset($wp_settings_sections) || !isset($wp_settings_sections[$page])) {
        return;
    }
    foreach ((array) $wp_settings_sections[$page] as $section) {
        if ($section['title']) {
            ?>
            <div class='mxblock'>
                <div class='mxpsection' title='Click to toggle' style='background:#CCCCCC'>
                    <h3><a href="javascript:unhide('<?php echo $section['id']; ?>')"><?php echo $section['title']; ?></a></h3>
                </div>
                <?php
                global $_SERVER;
                if (($section['id'] == 'branding_admin') && (stristr($_SERVER['HTTP_REFERER'], 'upload.php'))) {
                    $doHide = '';
                } else {
                    $doHide = 'doHide';
                }
                ?>
                <div class="mxpsection-detail <?php echo $section['id'] . ' ' . $doHide; ?>">
                    <?php
                    if ($section['callback']) {
                        call_user_func($section['callback'], $section);
                    }
                    ?>
                    <a href="#mxpress_sections_menu">Back to top</a>
                    <?php
                    if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']])) {
                        ////  continue;
                    } else {
                        ?>
                        <table class="form-table">
                <?php do_settings_fields($page, $section['id']); ?>
                        </table>
                    </div>
                </div>
                <?php
            }
        }
    }
}

// validate our options
function mxpress_process_options($input) {
    $process_to_perform = $_POST['Submit'];
    $process_to_check = $process_to_perform;

    if (strstr($process_to_perform, 'Reset')) {
        $process_to_check = 'Reset Section';
    }


    switch ($process_to_check) {
        case 'Save All Changes':
            $newinput = array();
            if ($input['MasterLogo'] == "new") {
                $input['MasterLogo_id'] = (string) $input['MasterLogo_id_new'];
                $input['MasterLogo'] = 'previous';
            } elseif ($input['MasterLogo'] == 'previous') {
                $input['MasterLogo_id'] = (string) $input['MasterLogo_id_old'];
            } elseif ($input['MasterLogo_id_old']) {
                $input['MasterLogo_id'] = (string) $input['MasterLogo_id_old'];
            }
            unset($input['MasterLogo_id_new']);
            unset($input['MasterLogo_id_old']);
            foreach ($input as $key => $value) {
                $newinput[$key] = htmlentities($value, ENT_QUOTES);
                if (!true) {
//todo blank out invalid
                    $newinput[$key] = '';
                }
            }
            return $newinput;
            break;

        case 'Restore All Option Defaults':
            return mxpress_get_admin_option_defaults();
            break;

        case 'Reset Section':
            //which section?
            global $mxpress_sections;
            foreach ($mxpress_sections as $section) {
                if ($process_to_perform == $section[4]) {
                    $section_toreset_id = $section[0];
                }
            }
            $saved_admin_options = get_option('mxpress_options');
            $admin_options_tosave = array();
            include'admin_options.php';
            // keep old values except restore relevant section options from $admin_options;
            foreach ($admin_options as $params) {
                if ($params['section'] == $section_toreset_id) {
                    if (!isset($params['default'])) {
                        $params['default'] = '';
                    }
                    $admin_options_tosave[$params['id']] = $params['default'];
                } else {
                    $admin_options_tosave[$params['id']] = $saved_admin_options[$params['id']];
                }
            }
            return $admin_options_tosave;
        default:
            // Not dealing with mxPress options, pass-through
            break;
    }
}

function mxpress_get_admin_option_defaults() {
    include'admin_options.php';
    foreach ($admin_options as $option) {
        if (isset($option['default'])) {
            $admin_option_defaults[$option['id']] = $option['default'];
        } else {
            $admin_option_defaults[$option['id']] = '';
        }
    }
    return $admin_option_defaults;
}

// <editor-fold defaultstate="collapsed" desc="Section description text and partial reset buttons">

function mxpress_admin_section_text($section) {
    mxpress_save_input();
    ?>
    <input name="Submit" type="submit" class="button-primary  " value="<?php esc_attr_e('Reset Tracking Defaults'); ?>" />

    <?php
}

function mxpress_contentfilter_section_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>
    <p>How post & page content html is interpreted for Mxit.</p>
    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Filtering Defaults'); ?>" />
    <?php
}

function mxpress_navigation_section_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>
    <p>Specify what navigation aids will be shown</p>
    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Navigation Defaults'); ?>" />
    <?php
}

function mxpress_contentformat_section_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>

    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Formatting Defaults'); ?>" /><?php
}

function mxpress_branding_section_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>

    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Branding Defaults'); ?>" /><?php
}

function mxpress_template_front_page_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>

    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Front Page Defaults'); ?>" /><?php
}

function mxpress_template_home_page_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>

    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Static Home/Blog Index Defaults'); ?>" /><?php
}

function mxpress_template_page_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>

    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Page Defaults'); ?>" /><?php
}

function mxpress_template_single_post_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>

    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Single Post Defaults'); ?>" /><?php
}

function mxpress_template_archive_cat_or_terms_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>

    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Archive Defaults'); ?>" /><?php
}

/*function mxpress_admin_section_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>
    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Administration Defaults'); ?>" /><?php
}*/

function mxpress_advertising_admin_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>
    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Advertising Defaults'); ?>" /><?php
}

function mxpress_comments_text($section) {
    ?><a name="<?php echo $section['id'] ?>" ></a>

    <?php mxpress_save_input(); ?>
    <input name="Submit" type="submit" class="button-primary " value="<?php esc_attr_e('Reset Comments Defaults'); ?>" /><?php
}

// </editor-fold>
function mxpress_save_input() {
    ?><input name="Submit" type="submit" value="<?php esc_attr_e('Save All Changes'); ?>" class="button-primary "/><?php
}

// get settings affecting mxpress options
// returns array with wp option names as keys and vals as vals
function mxpress_get_relevant_wp_settings() {
    $options_to_get = array('show_on_front');
    $some_settings = array();
    foreach ($options_to_get as $option) {
        $some_settings[$option] = get_option($option);
    }
    return $some_settings;
}
?>
