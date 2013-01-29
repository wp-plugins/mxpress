<?php
$admin_options = array(
// <editor-fold defaultstate="collapsed" desc="settings config array">
// <editor-fold defaultstate="collapsed" desc=" for branding">
    array('id' => 'MasterLogo',
        'label' => 'Logo Options',
        'field_type' => 'singleoption',
        'section' => 'branding_admin',
        'default'=>'none',
        'helptxt' => 'See &apos;Use the Branding logo.&apos; options to include this image on the static frontpage and other templates in their respective sections below',
    ),
    array('id' => 'MasterLogo_id',
        'label' => 'Selected Logo',
        'field_type' => 'imagedisplay',
        'section' => 'branding_admin',
        'default'=>null,
        'helptxt' => '',
    ),
    array('id' => 'HomeTitleText',
        'label' => 'Front Page Heading',
        'field_type' => 'text',
        'section' => 'branding_admin',
        'helptxt' => '',
        'default'=>'',
    ),
    array('id' => 'HomeTitleSentence',
        'label' => 'Front Page Sentence (200 chars)',
        'field_type' => 'text',
        'section' => 'branding_admin',
        'helptxt' => '',
        'default'=>'',
    ),
    
    // </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" for content filtering ">
    array('id' => 'doShowTitles',
        'label' => 'Show titles',
        'field_type' => 'checkbox',
        'section' => 'contentfilter_admin',
        'default'=>'on',
        'helptxt' => 'Turn this off to manually create headings other than the link text generated from page and post titles, in the actual content editor.'
    ),
    array('id' => 'doConvertBullets',
        'label' => 'Fix bullet lists',
        'field_type' => 'checkbox',
        'section' => 'contentfilter_admin',
        'helptxt' => 'Recomended. Changes ul to span, li to *...br',
        'default'=>'on',
    ),
    array('id' => 'doWrapListBRs',
        'label' => 'Wrap lists with line-breaks',
        'field_type' => 'checkbox',
        'section' => 'contentfilter_admin',
        'helptxt' => 'Recomended. Wraps ul and ol with br',
        'default'=>'on',
    ),
    array('id' => 'doExtraLiBRs',
        'label' => 'Extra linebreak adfter list items',
        'field_type' => 'checkbox',
        'section' => 'contentfilter_admin',
        'helptxt' => 'Recomended.',
        'default'=>'on',
    ),
    array('id' => 'doDynamicSmartLinks',
        'label' => 'Create Smartlinks',
        'field_type' => 'checkbox',
        'section' => 'contentfilter_admin',
        'helptxt' => 'Fordevices with small screens (width < 300px) create numbered links, with the number as link separated from the link text with parenthesis &apos;)&apos;',
        'default'=>'on',
    ),
    array('id' => 'doStripTags',
        'label' => 'Remove tags, but keep',
        'field_type' => 'text',
        'section' => 'contentfilter_admin',
        'helptxt' => 'Remove html tags except for those specified here. Leave blank to not strip any tags. Html comments are always stripped (&lt;!--nextmixitpage--&gt; is retained). If &lt;p&gt; is not retained &lt;br&gt; (line breaks) are inserted after paragraph blocks. Default &apos;&lt;a&gt;&lt;b&gt;&lt;br&gt;&lt;i&gt;&lt;em&gt;&lt;li&gt;&lt;h1&gt;&lt;h2&gt;&lt;h3&gt;&lt;h4&gt;&lt;h5&gt;&lt;h6&gt;&apos;',
        'default' => '<img><a><b><br><i><em><li><h1><h2><h3><h4><h5><h6>',
    ),
    array('id' => 'doAutoSplitContent',
        'label' => 'Auto-split content',
        'field_type' => 'checkbox',
        'section' => 'contentfilter_admin',
        'helptxt' => 'Experimental. If set will automatically split any content longer than <b>Length to split after</b> into sections/pages of that length in characters. Does not currently discern markup. Also see <b>Use manual splits</b> View/page navigation links will be inserted below the content.',
        'default'=>'',
    ),
    array('id' => 'splitLength',
        'label' => 'Max. length of auto-split',
        'field_type' => 'text', //todo: make numeric
        'section' => 'contentfilter_admin',
        'helptxt' => 'If <b>Auto-split content</b> is set, content longer than this value will be split into sections/pages of that length in characters. Does not currently discern markup. Also see <b>Use manual splits</b>',
        'default'=>'',
    ),
    array('id' => 'doKeepManualSplits',
        'label' => 'Use manual splits',
        'field_type' => 'checkbox', //todo: make numeric
        'section' => 'contentfilter_admin',
        'helptxt' => 'If this is set content will be split where-ever <b>&lt;!--nextmixitpage--&gt;</b> has been inserted in the item&apos;s html. May be used with or without Auto-split</b>',
        'default'=>'on',
    ),
    array('id' => 'doFixExternalLinks',
        'label' => 'Fix external links',
        'field_type' => 'checkbox',
        'section' => 'contentfilter_admin',
        'helptxt' => 'Insert the required &apos; onclick=&quot;window.open(this.href); return false;&quot; &apos; to open links to outside of Mxit in the device&apos;s browser',
        'default'=>'on',
    ),
    array('id' => 'isRedirExternalLinks',
        'label' => 'Redirect external links',
        'field_type' => 'checkbox',
        'section' => 'contentfilter_admin',
        'helptxt' => 'Show an itermediary page that redirects to the exteral url - to track clicks to external links',
        'default'=>'',
    ),
    // </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" for content formatting ">
    array('id' => 'setBackgroundColor',
        'label' => 'Background colour',
        'field_type' => 'text',
        'section' => 'contentformat_admin',
        'helptxt' => 'eg. #000000 or black',
        'default'=>'#FFFFFF',
    ),
    array('id' => 'setBodyColor',
        'label' => 'Default text colour',
        'field_type' => 'text',
        'section' => 'contentformat_admin',
        'helptxt' => 'eg. #000000 or black',
        'default'=>'#666666',
    ),
    array('id' => 'titleCol', 'label' => 'Title colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black',
        'default'=>'#000000',
    ),
    array('id' => 'h1Col', 'label' => 'h1 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black',
        'default'=>'#222222',
    ),
    array('id' => 'h2Col', 'label' => 'h2 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black',
        'default'=>'#444444',
    ),
    array('id' => 'h3Col', 'label' => 'h3 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black',
        'default'=>'#666666',
    ),
    array('id' => 'h4Col', 'label' => 'h4 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black',
        'default'=>'#888888',
    ),
    array('id' => 'h5Col', 'label' => 'h5 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black',
        'default'=>'#AAAAAA',
    ),
    array('id' => 'h6Col', 'label' => 'h6 Heading colour', 'field_type' => 'text', 'section' => 'contentformat_admin', 'helptxt' => 'eg. #000000 or black',
        'default'=>'#BBBBBB',
    ),
    // </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" for navigation ">

    array('id' => 'doListallPagesOnPages',
        'label' => 'List all pages below Pages',
        'field_type' => 'checkbox',
        'section' => 'navigation_admin',
        'helptxt' => 'List all pages on Page post types.',
        'default'=>'',
    ),
    array('id' => 'doListChildPagesOnPages',
        'label' => 'List child pages below Pages',
        'field_type' => 'checkbox',
        'section' => 'navigation_admin',
        'helptxt' => 'List pages that have the current page set as their parent.',
        'default'=>'on',
    ),
    array('id' => 'doListCategoriesOnSingle',
        'label' => 'List categories below single posts',
        'field_type' => 'checkbox',
        'section' => 'navigation_admin',
        'helptxt' => 'List pages that have the current page set as their parent.',
        'default'=>'',
    ),
    array('id' => 'doFooterNav',
        'label' => 'Standard footer navigation',
        'field_type' => 'checkbox',
        'section' => 'navigation_admin',
        'helptxt' => 'Place a Back | Home links at the bottom of all views. Home goes to site home, Back tries to always go one level up whatever hyrarchy is in effect, regardless of what item was previously visited. If it fails it falls back to referring url as long as that is in the local site.',
        'default'=>'on',
    ),
// </editor-fold>      
// <editor-fold defaultstate="collapsed" desc=" for front page">
    array('id' => 'useLogoOnFrontPage',
        'label' => 'Use the Branding logo.',
        'field_type' => 'checkbox',
        'section' => 'template_front_page',
        'helptxt' => 'See the Branding options section',
        'default'=>'on',
    ),
    array('id' => 'useMenuOnFrontPage',
        'label' => 'Menu to use.',
        'field_type' => 'singleoption',
        'section' => 'template_front_page',
        'helptxt' => $description_part_show_on_front . ' Select an existing menu or create a new menu at Appearance -> <a href="' . get_bloginfo('url') . '/wp-admin/nav-menus.php">Menus</a>.',
        'default'=>'',
    ),
    array('id' => 'placeMenuOnFrontPage',
        'label' => 'Where to place menu.',
        'field_type' => 'singleoption',
        'section' => 'template_front_page',
        'helptxt' => '... relation to where content would be',
        'default'=>'After',
    ),
    array('id' => 'doHideHomeContent',
        'label' => 'Hide content of static front page.',
        'field_type' => 'checkbox',
        'section' => 'template_front_page',
        'helptxt' => 'Mxit users typically expect to find links rather than a lot of text on the first page of a Mxit service or application.'
    ),
    array('id' => 'setListPagesHomeTitle',
        'label' => 'Title for list all pages on static front page',
        'field_type' => 'text',
        'section' => 'template_front_page',
        'helptxt' => 'Default &apos;Main Menu&apos;',
        
    ),
    array('id' => 'doListPostsOnFrontPage',
        'label' => 'List recent posts on static front page',
        'field_type' => 'checkbox',
        'section' => 'template_front_page',
        'helptxt' => 'Also see next three options.'
    ),
    array('id' => 'titlePostsOnFrontPage',
        'label' => 'Title fabove recent posts',
        'field_type' => 'text',
        'section' => 'template_front_page',
        'helptxt' => 'if set to show recent posts on static front page',
        'default'=>'Recent Posts',
    ),
    array('id' => 'limitListPostsOnFrontPage',
        'label' => 'How many posts to show on static front page',
        'field_type' => 'text',
        'section' => 'template_front_page',
        'helptxt' => 'The <a href="' . get_bloginfo('url') . '/wp-admin/options-reading.php">Reading Settings</a> of this site are currently configured to show ' .
        $mxpres_wpsettings['posts_per_page'] . ' posts at a time, leave blank to use the Reading Setting.',
    ),
    array('id' => 'catListPostsOnFrontPage',
        'label' => 'Only show posts from this category',
        'field_type' => 'singleoption',
        'section' => 'template_front_page',
        'helptxt' => '(...and its &apos;child&apos; categories) Leave blank to allow any posts.'
    ),
    array('id' => 'doListCategoriesHome',
        'label' => 'List blog categories on static front page',
        'field_type' => 'checkbox',
        'section' => 'navigation_admin',
        'helptxt' => 'Sub categories are nested.'
    ),
    array('id' => 'doListPostsOnFrontPage',
        'label' => 'List recent posts on static front page',
        'field_type' => 'checkbox',
        'section' => 'template_front_page',
        'helptxt' => 'Also see next two options.'
    ),
    array('id' => 'doShowBackFrontPage',
        'label' => 'Show a &apos;Back&apos; link on static front page',
        'field_type' => 'checkbox',
        'section' => 'template_front_page',
        'helptxt' => 'It will link to the url the viewer is already on but may be desired for consistency.'
    ),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" for static home page">
    array('id' => 'useLogoOnHomePage',
        'label' => 'Use the Branding logo.',
        'field_type' => 'checkbox',
        'section' => 'template_home_page',
        'helptxt' => 'See the Branding options section',
         'default'=>'on',
    ),
    array('id' => 'useMenuOnHomePage',
        'label' => 'Menu to use on static home page.',
        'field_type' => 'singleoption',
        'section' => 'template_home_page',
        'helptxt' => 'Select an existing menu or create a new menu at Appearance -> <a href="' . get_bloginfo('url') . '/wp-admin/nav-menus.php">Menus</a> to use on the page that is set to show recent posts if static front and home pages have been configured.'
    ),
    array('id' => 'placeMenuOnHomePage',
        'label' => 'Where to place menu.',
        'field_type' => 'singleoption',
        'section' => 'template_home_page',
        'helptxt' => '... relation to where content would be',
        'default'=>'After',
    ),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" for page">
    array('id' => 'useLogoOnPage',
        'label' => 'Use the Branding logo.',
        'field_type' => 'checkbox',
        'section' => 'template_page',
        'helptxt' => 'See the Branding options section',
    ),
    array('id' => 'useMenuOnPage',
        'label' => 'Menu to use on normal &apos;pages&apos;.',
        'field_type' => 'singleoption',
        'section' => 'template_page',
        'helptxt' => 'Select an existing menu or create a new menu at Appearance -> <a href="' . get_bloginfo('url') . '/wp-admin/nav-menus.php">Menus</a> to use on the page that is set to show recent posts if static front and home pages have been configured.',
    ),
    array('id' => 'placeMenuOnPage',
        'label' => 'Where to place menu.',
        'field_type' => 'singleoption',
        'section' => 'template_page',
        'helptxt' => '... in relation to the content.',
        'default'=>'After',
    ),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" for single">
    array('id' => 'useLogoOnSingle',
        'label' => 'Use the Branding logo.',
        'field_type' => 'checkbox',
        'section' => 'template_single_post',
        'helptxt' => 'See the Branding options section'
    ),
    array('id' => 'useMenuOnSingle',
        'label' => 'Menu to use on single post template.',
        'field_type' => 'singleoption',
        'section' => 'template_single_post',
        'helptxt' => 'Select an existing menu or create a new menu at Appearance -> <a href="' . get_bloginfo('url') . '/wp-admin/nav-menus.php">Menus</a> to use on the page that is set to show recent posts if static front and home pages have been configured.'
    ),
    array('id' => 'placeMenuOnSingle',
        'label' => 'Where to place menu.',
        'field_type' => 'singleoption',
        'section' => 'template_single_post',
        'helptxt' => '... in relation to the content.'
    ),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" for archive">
    array('id' => 'useLogoOnArchivePage',
        'label' => 'Use the Branding logo.',
        'field_type' => 'checkbox',
        'section' => 'template_archive',
        'helptxt' => 'See the Branding options section'
    ),
    array('id' => 'useMenuOnArchivePage',
        'label' => 'Menu to use on archives.',
        'field_type' => 'singleoption',
        'section' => 'template_archive',
        'helptxt' => 'Select an existing menu or create a new menu at Appearance -> <a href="' . get_bloginfo('url') . '/wp-admin/nav-menus.php">Menus</a> to use on the page that is set to show recent posts if static front and home pages have been configured.'
    ),
    array('id' => 'placeMenuOnArchivePage',
        'label' => 'Where to place menu.',
        'field_type' => 'singleoption',
        'section' => 'template_archive',
        'helptxt' => '... in relation to the content.'
    ),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" for comments">
    array('id' => 'limitCommentsTo',
        'label' => 'Comments Pagination',
        'field_type' => 'text',
        'section' => 'comments_admin',
        'helptxt' => 'How many comments to show per screen. 
                (If blank will default to the discussion comments per screen setting, currently set to ' . get_option('comments_per_page') . ')',
        'disabled' => false,
        'default'=>'5',
    ),
    array('id' => 'orderComments',
        'label' => 'Comments Order',
        'field_type' => 'singleoption',
        'section' => 'comments_admin',
        'helptxt' => '',
        'disabled' => false,
    ),
    array('id' => 'doShowContentAboveCommentform',
        'label' => 'Show content above comment form',
        'field_type' => 'singleoption',
        'section' => 'comments_admin',
        'helptxt' => 'Avoid having extensive content above the comment form.',
        'default'=>'on',
    ),
    array('id' => 'doShowFooterBelowCommentform',
        'label' => 'Show content above comment form',
        'field_type' => 'checkbox',
        'section' => 'comments_admin',
        'helptxt' => 'Recomend this be off as the comment form has a &apos;Cancel Comment&apos; link.',
        'disabled' => false,
    ),
// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" for tracking">
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
        // </editor-fold> - for tracking
// <editor-fold defaultstate="collapsed" desc="Advertising">
array('id' => 'codeShinka_APP_MXIT_ID',
        'label' => 'YOUR_APP_MXIT_ID',
        'field_type' => 'text',
        'section' => 'advertising_admin',
    ),
    array('id' => 'doShinkaMast',
        'label' => 'Render Shinka Mast Banner',
        'field_type' => 'checkbox',
        'section' => 'advertising_admin',
        'helptxt' => '... above all content.',
    ),
    array('id' => 'doShinkaFooter',
        'label' => 'Render Shinka Footer Banner',
        'field_type' => 'checkbox',
        'section' => 'advertising_admin',
        'helptxt' => '... below all content.',
    ),
    array('id' => 'codeShinka_UnitID_320',
        'label' => 'Unit ID 320',
        'field_type' => 'text',
        'section' => 'advertising_admin',
    ),
    array('id' => 'codeShinka_UnitID_216',
        'label' => 'Unit ID 216',
        'field_type' => 'text',
        'section' => 'advertising_admin',
    ),
    array('id' => 'codeShinka_UnitID_168',
        'label' => 'Unit ID 168',
        'field_type' => 'text',
        'section' => 'advertising_admin',
    ),
    array('id' => 'codeShinka_UnitID_120',
        'label' => 'Unit ID 120',
        'field_type' => 'text',
        'section' => 'advertising_admin',
    ),
// </editor-fold>

// </editor-fold> - all sections
);
?>