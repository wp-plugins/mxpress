<?php

// add the admin options page

function kzmx_add_admin_page() {
    add_submenu_page('themes.php', 'Mxit Options', 'Mxit Options', 'manage_options', 'kzmx_plugin', 'kzmx_options_page');
}

// display the admin options page
function kzmx_options_page() {
    ?>
    <div>
        <h2><?php _e('Settings for translating WordPress output for Mxit Mobi-Portal'); ?></h2>
        <p><?php _e('This is a pre-release Alpha version.'); ?></p>
        <form action="options.php" method="post">
            <?php settings_fields('kzmx_options'); ?>
            <?php do_settings_sections('kzmx_admin'); ?>
            <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>
    </div>
    <?php
}

// add the admin settings and such
add_action('admin_init', 'kzmx_admin_init');

// initiate and configure settings
function kzmx_admin_init() {
    // all setting fields are array elements of a single _options table row
    register_setting('kzmx_options', 'kzmx_options', 'kzmx_options_validate');
    add_settings_section('branding_admin', 'Branding', 'kzmx_branding_section_text', 'kzmx_admin');
    add_settings_section('contentfilter_admin', 'Content filtering', 'kzmx_contentfilter_section_text', 'kzmx_admin');
    add_settings_section('contentformat_admin', 'Content formatting', 'kzmx_contentformat_section_text', 'kzmx_admin');
    add_settings_section('navigation_admin', 'Navigation', 'kzmx_navigation_section_text', 'kzmx_admin');
    add_settings_section('log_admin', 'Logging &amp; tracking', 'kzmx_admin_section_text', 'kzmx_admin');

    $settings_fields = array(
        // <editor-fold defaultstate="collapsed" desc="settings config array">
// for branding
        array('id' => 'MasterLogo',
            'label' => 'High res logo',
            'field_type' => 'img_upload',
            'section' => 'branding_admin',
            'helptxt' => '',
            'disabled' => true,
        ),
        array('id' => 'HomeTitleText',
            'label' => 'Front Page Heading',
            'field_type' => 'text',
            'section' => 'branding_admin',
            'helptxt' => '',
            
        ),
        array('id' => 'HomeTitleSentence',
            'label' => 'Front Page Sentence (200 chars)',
            'field_type' => 'text',
            'section' => 'branding_admin',
            'helptxt' => '',
            
        ),
        array('id' => '',
            'label' => '',
            'field_type' => '',
            'section' => '',
            'helptxt' => ''
        ),
//for content filtering        
        array('id' => 'doShowTitles',
            'label' => 'Show titles',
            'field_type' => 'checkbox',
            'section' => 'contentfilter_admin',
            'helptxt' => 'Turn this off to manually create headings other than the link text generated from page and post titles, in the actual content editor.'
        ),
        array('id' => 'doConvertBullets',
            'label' => 'Fix bullet lists',
            'field_type' => 'checkbox',
            'section' => 'contentfilter_admin',
            'helptxt' => 'Recomended. Changes ul to span, li to *...br'
        ),
        array('id' => 'doWrapListBRs',
            'label' => 'Wrap lists with line-breaks',
            'field_type' => 'checkbox',
            'section' => 'contentfilter_admin',
            'helptxt' => 'Recomended. Wraps ul and ol with br'
        ),
        array('id' => 'doExtraLiBRs',
            'label' => 'Extra linebreak adfter list items',
            'field_type' => 'checkbox',
            'section' => 'contentfilter_admin',
            'helptxt' => 'Recomended.'
        ),
        array('id' => 'doDynamicSmartLinks',
            'label' => 'Create Smartlinks',
            'field_type' => 'checkbox',
            'section' => 'contentfilter_admin',
            'helptxt' => 'For devices with small screens (width < 300px) create numbered links, with the number as link separated from the link text with parenthesis &apos;)&apos;',
        ),
        array('id' => 'doStripTags',
            'label' => 'Remove tags, but keep',
            'field_type' => 'text',
            'section' => 'contentfilter_admin',
            'helptxt' => 'Remove html tags except for those specified here. Leave blank to not strip any tags. Html comments are always stripped (&lt;!--nextmixitpage--&gt; is retained). If &lt;p&gt; is not retained &lt;br&gt; (line breaks) are inserted after paragraph blocks. Default &apos;&lt;a&gt;&lt;b&gt;&lt;br&gt;&lt;i&gt;&lt;em&gt;&lt;li&gt;&lt;h1&gt;&lt;h2&gt;&lt;h3&gt;&lt;h4&gt;&lt;h5&gt;&lt;h6&gt;&apos;',
        ),
        array('id' => 'doAutoSplitContent',
            'label' => 'Auto-split content',
            'field_type' => 'checkbox',
            'section' => 'contentfilter_admin',
            'helptxt' => 'If set will automatically split any content longer than <b>Length to split after</b> into sections/pages of that length in characters. Does not currently discern markup. Also see <b>Use manual splits</b> View/page navigation links will be inserted below the content.',
        ),
        array('id' => 'splitLength',
            'label' => 'Max. length of auto-split',
            'field_type' => 'text', //todo: make numeric
            'section' => 'contentfilter_admin',
            'helptxt' => 'If <b>Auto-split content</b> is set, content longer than this value will be split into sections/pages of that length in characters. Does not currently discern markup. Also see <b>Use manual splits</b>',
        ),
        array('id' => 'doKeepManualSplits',
            'label' => 'Use manual splits',
            'field_type' => 'checkbox', //todo: make numeric
            'section' => 'contentfilter_admin',
            'helptxt' => 'If this is set content will be split where-ever <b>&lt;!--nextmixitpage--&gt;</b> has been inserted in the item&apos;s html. May be used with or without Auto-split</b>',
        ),
        array('id' => 'doFixExternalLinks',
            'label' => 'Fix external links',
            'field_type' => 'checkbox',
            'section' => 'contentfilter_admin',
            'helptxt' => 'Insert the required &apos; onclick=&quot;window.open(this.href); return false;&quot; &apos; to open links to outside of Mxit in the device&apos;s browser',
        ),
        array('id' => 'isRedirExternalLinks',
            'label' => 'Redirect external links',
            'field_type' => 'checkbox',
            'section' => 'contentfilter_admin',
            'helptxt' => 'Show an itermediary page that redirects to the exteral url - to track clicks to external links'
        ),
// for content formatting        
        array('id' => 'setBackgroundColor',
            'label' => 'Background colour',
            'field_type' => 'text',
            'section' => 'contentformat_admin',
            'helptxt' => 'eg. #000000 or black'
        ),
        array('id' => 'setBodyColor',
            'label' => 'Default text colour',
            'field_type' => 'text',
            'section' => 'contentformat_admin',
            'helptxt' => 'eg. #000000 or black'
        ),
        array('id' => 'titleCol', 'label' => 'Title colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black'
        ),
        array('id' => 'h1Col', 'label' => 'h1 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black'
        ),
        array('id' => 'h2Col', 'label' => 'h2 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black'
        ),
        array('id' => 'h3Col', 'label' => 'h3 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black'
        ),
        array('id' => 'h4Col', 'label' => 'h4 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black'
        ),
        array('id' => 'h5Col', 'label' => 'h5 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black'
        ),
        array('id' => 'h6Col', 'label' => 'h6 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black'
        ),
// for navigation
        array('id' => 'doHideHomeContent',
            'label' => 'Hide site front page content',
            'field_type' => 'checkbox',
            'section' => 'navigation_admin',
            'helptxt' => 'Mxit users typically expect to find links rather than text on the first page of a Mxit service.'
        ),
         
        array('id' => 'doListAllPagesHome',
            'label' => 'List all pages on front page',
            'field_type' => 'checkbox',
            'section' => 'navigation_admin',
            'helptxt' => 'Recomended. Sub pages are nested. If set, this disallows other page lists from rendering on the front page.'
        ),
        array('id' => 'setListPagesHomeTitle',
            'label' => 'Title for list all pages on front page',
            'field_type' => 'text',
            'section' => 'navigation_admin',
            'helptxt' => 'Default &apos;Main Menu&apos;'
        ),
         array('id' => 'doListCategoriesHome',
            'label' => 'List blog categories on front page',
            'field_type' => 'checkbox',
            'section' => 'navigation_admin',
            'helptxt' => 'Sub categories are nested.'
        ),
        array('id' => 'doListallPagesOnPages',
            'label' => 'List all pages below Pages',
            'field_type' => 'checkbox',
            'section' => 'navigation_admin',
            'helptxt' => 'List all pages on Page post types.'
        ),
        array('id' => 'doListChildPagesOnPages',
            'label' => 'List child pages below Pages',
            'field_type' => 'checkbox',
            'section' => 'navigation_admin',
            'helptxt' => 'List pages that have the current page set as their parent.'
        ),
        array('id' => 'doListCategoriesOnSingle',
            'label' => 'List categories below single posts',
            'field_type' => 'checkbox',
            'section' => 'navigation_admin',
            'helptxt' => 'List pages that have the current page set as their parent.'
        ),
        array('id' => 'doFooterNav',
            'label' => 'Standard footer navigation',
            'field_type' => 'checkbox',
            'section' => 'navigation_admin',
            'helptxt' => 'Place a Back | Home links at the bottom of all views. Home goes to site home, Back tries to always go one level up whatever hyrarchy is in effect, regardless of what item was previously visited. If it fails it falls back to referring url as long as that is in the local site.'
        ),
// for tracking 

        array('id' => 'doGA',
            'label' => 'Use GoogleAnalytics',
            'field_type' => 'checkbox',
            'section' => 'log_admin',
            'helptxt' => 'Render a GA pixel',
        ),
        array('id' => 'setGA_ACCOUNT',
            'label' => 'GA_ACCOUNT',
            'field_type' => 'text',
            'section' => 'log_admin',
            'helptxt' => 'Google account, eg. MO-123123-1',
        ),
        array('id' => 'isPersistMessages',
            'label' => 'Log all pages served to Mxit in DB',
            'field_type' => 'checkbox',
            'section' => 'log_admin',
            'helptxt' => 'Page address and Mxit user id is saved',
            'disabled' => true,
        ),
        array('id' => 'isPersistUser',
            'label' => 'Log mixit users in DB',
            'field_type' => 'checkbox',
            'section' => 'log_admin',
            'helptxt' => '',
            'disabled' => true,
        ),
        array('id' => 'isUpdateUser',
            'label' => 'Update user details in DB',
            'field_type' => 'checkbox',
            'section' => 'log_admin',
            'helptxt' => 'Eg. user record will show lates screensize used',
            'disabled' => true,
        ),
            // </editor-fold>
    );

    foreach ($settings_fields as $settings_field) {
        add_settings_field($settings_field['id'], $settings_field['label'], 'kzmx_render_setting_field', 'kzmx_admin', $settings_field['section'], array(
            'ID' => $settings_field['id'], 'field_type' => $settings_field['field_type'], 'helptxt' => $settings_field['helptxt'], 'disabled' => $settings_field['disabled']
        ));
    }
}

function kzmx_admin_section_text() {
    echo'// unused, could add descriptions etc here';
}

function kzmx_contentfilter_section_text() {
    echo'// unused, could add descriptions etc here';
}
function kzmx_navigation_section_text() {
    echo'// unused, could add descriptions etc here';
}
function kzmx_contentformat_section_text() {
    echo'// unused, could add descriptions etc here';
}

function kzmx_branding_section_text() {
    echo'// unused, could add descriptions etc here';
}

function kzmx_render_setting_field($args) {
    $kzmx_options = get_option('kzmx_options');
    $option_id = $args['ID'];
    $type = $args['field_type'];
    $disabled = ($args['disabled']) ? 'disabled="disabled"' : '';
    $disabled_txt = ($args['disabled']) ? '<br />[Disabled, coming soon]' : '';
    switch ($type) {
        case'checkbox':
            $checked = ($kzmx_options[$option_id]) ? 'checked ="checked"' : '';
            echo "<input $disabled id='$option_id' name='kzmx_options[$option_id]' type='$type' $checked style=\"float:left\" />";
            break;
        case'img_upload':
            //todo: redo from scratch
            break;
        case'text':
        default:
            $output = esc_attr($kzmx_options[$option_id]);
            form_option($kzmx_options[$option_id]);
            echo "<input $disabled id='$option_id' name='kzmx_options[$option_id]' size='40' type='$type' value='$output' style=\"float:left\" />";
            break;
    }
    echo '<span style="float:left; padding-left:1em; margin:0">' . $args['helptxt'] . $disabled_txt . '</span>';
}

// validate our options
function kzmx_options_validate($input) {
    //echo '<br>Old:<br>';
    //var_dump($input);
    $newinput = array();
    foreach ($input as $key => $value) {
        $newinput[$key] = htmlentities($value, ENT_QUOTES);
        if (!true) {
            //todo blank out invalid
            $newinput[$key] = '';
        }
    }


    return $newinput;
}

// uploads?
?>