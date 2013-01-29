<?php
global $mxpress_options;
if ($mxpress_options['useLogoOnPage']) {
    mxpress_logo();
}
mxpress_put_menu('placeMenuOnPage', 'useMenuOnPage', 'Before');

if (have_posts()):
    the_post();
    if ($mxpress_options['doShowTitles']) {
        echo mxpress_ifoption('<h1><span style="color:', $mxpress_options['titleCol'], ';">', false);
        the_title();
        echo mxpress_ifoption('</span></h1>', $mxpress_options['titleCol'], '', false, false);
        ?><br/><?php
    }
    $content = get_the_content();
    mxpress_the_content($content, $post->ID);

    if (!$doMxpressComment) {
        // don't show meta data when commenting 
        $theyear = get_the_date('Y');
        $thisyear = date_i18n('Y', time());
        $yeartoshow = ($theyear == $thisyear) ? '' : ' ' . $theyear;
        $showdate = get_the_date('j M') . $yeartoshow;
        echo '<span style="color:#ccc">By ' . get_the_author() . ', ' . $showdate . '</span>';
    }

    mxpress_comments_template('/mxpress_comments.php', true);
endif;
mxpress_link_pages();

mxpress_put_menu('placeMenuOnPage', 'useMenuOnPage', 'After');
?>