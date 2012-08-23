<?php
// if we are in Mxit, load, process and render, then exit

$input = $_SERVER['QUERY_STRING'];
$pos = strpos($input,'mxitversion');

if (($_SERVER['HTTP_X_MXIT_USERID_R'] || ($pos !== false)) && (!is_admin())) {// todo: remove 1==1
    global $wp_query, $mx_options, $mx_state;
    $mx_options = get_option('kzmx_options');
    /* Options include eg. : { 
     * ["doShowTitles"]=> string(2) "on" ["doConvertBullets"]=> string(2) "on" ["doDynamicShortlinks"]=> string(2) "on" ["fixExternalLinks"]=> string(2) "on" 
     * ["isRedirExternalLinks"]=> string(2) "on" ["setBackgroundColor"]=> string(7) "#FFFFFF" ["setBodyColor"]=> string(7) "#888888" ["titleCol"]=> string(7) "#000000" 
     * ["h1Col"]=> string(7) "#222222" ["h2Col"]=> string(7) "#444444" ["h3Col"]=> string(7) "#555555" ["h4Col"]=> string(7) "#666666" ["h5Col"]=> string(7) "#777777" 
     * ["h6Col"]=> string(7) "#888888" ["DeclineNotice"]=> string(53) "We are away for a short while. Check back again soon." } 
     */
    $mx_state = array('_SERVER' => $_SERVER, 'usr_width' => kzmx_get_screenwidth($_SERVER), 'links_count' => 0);

    include dirname(__FILE__) . '/kzmx_header.php';

    while (have_posts()) : the_post();
        if ((is_archive()) || (is_home())) {

            // this is a listing of links to items, we don't show content 
            $current_category = single_cat_title("", false);
            echo "<h1>".$current_category."</h1>";
            echo kzmx_ifoption('<h2><span style="color:', $mx_options['h2Col'], ';">', false);
            kzmx_render_link(get_permalink(), get_the_title());
            echo kzmx_ifoption('</span></h2>', $mx_options['h2Col'], '', false, false);
        } else {
            // here we show actual content, except if we hide the front page content
            if (is_front_page()) {
                echo kzmx_ifoption('<h1><span style="color:', $mx_options['titleCol'], ';">', false);
                echo $mx_options['HomeTitleText'];
                echo kzmx_ifoption('</span></h1>', $mx_options['titleCol'], '', false, false);
                echo $mx_options['HomeTitleSentence'];
            }
            if ($mx_options['doShowTitles'] && !(is_front_page()) ) {
                echo kzmx_ifoption('<h1><span style="color:', $mx_options['titleCol'], ';">', false);
                the_title();
                echo kzmx_ifoption('</span></h1>', $mx_options['titleCol'], '', false, false);
                ?><br/><?php
            }
            if ((!$mx_options['doHideHomeContent']) || (($mx_options['doHideHomeContent']) && (!is_front_page()))) {
                $content = get_the_content();
                $content = apply_filters('the_content', $content);
                $content = str_replace(']]>', ']]&gt;', $content);
                kzmx_the_content($content);
                kzmx_link_pages();
            }
        }
        ?>&nbsp;<?php
    endwhile;
    include dirname(__FILE__) . '/kzmx_footer.php';

    exit;
} else {
    // we're not in mxit, and probably not in Kansas either, WordPress clicks its heels and proceeds normally...
}
?>