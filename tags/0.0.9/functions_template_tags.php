<?php

// this is a clone of wp_link_pages
// for now, should be used after kzmx_the_content() has been called, inside the loop
function kzmx_link_pages() {
    global $mx_state, $wp_query,$more;
    $defaults = array(
        'before' => '<p>' . __('Pages:'), 'after' => '</p>',
        'link_before' => '', 'link_after' => '',
        'next_or_number' => 'number', 'nextpagelink' => __('Next page'),
        'previouspagelink' => __('Previous page'), 'pagelink' => '%',
        'echo' => 1
    );

    $r = wp_parse_args($args, $defaults);
    $r = apply_filters('wp_link_pages_args', $r);
    extract($r, EXTR_SKIP);
//var_dump($wp_query);
    $page = ($wp_query->query_vars['page']) ? $wp_query->query_vars['page'] : 1;
    $numpages = $mx_state['num_pages'];
    $multipage = $mx_state['$multipage'];

    ;

    $output = '';
    if ($multipage) {
        if ('number' == $next_or_number) {
            $output .= $before;
            for ($i = 1; $i < ($numpages + 1); $i = $i + 1) {
                $j = str_replace('%', $i, $pagelink);
                $output .= ' ';
                if (($i != $page) || ((!$more) && ($page == 1))) {
                    $output .= _wp_link_page($i);
                }
                $output .= $link_before . $j . $link_after;
                if (($i != $page) || ((!$more) && ($page == 1)))
                    $output .= '</a>';
            }
            $output .= $after;
        } else {
            if ($more) {
                $output .= $before;
                $i = $page - 1;
                if ($i && $more) {
                    $output .= _wp_link_page($i);
                    $output .= $link_before . $previouspagelink . $link_after . '</a>';
                }
                $i = $page + 1;
                if ($i <= $numpages && $more) {
                    $output .= _wp_link_page($i);
                    $output .= $link_before . $nextpagelink . $link_after . '</a>';
                }
                $output .= $after;
            }
        }
    }

    if ($echo)
        echo $output;

    return $output;
}

//render the content of an item
function kzmx_the_content($content_str) {
    global $post, $mx_state, $mx_options,$wp_query;
    $page = ($wp_query->query_vars['page']) ? $wp_query->query_vars['page'] : 1;

    // clean and paginate if neccesary
    $do_process = (($mx_options['doKeepManualSplits']) || ($mx_options['doAutoSplitContent'])||($mx_options['doStripTags']));
    $content_ar = ($do_process ) ? kzmx_clean_and_split($content_str) : array($content_str);
    array_unshift($content_ar, 'Page 0');
    //var_dump($content_ar);
    echo force_balance_tags($content_ar[$page]) . "\n<hr>";
    return;
}

function kzmx_body_style() {
    global $mx_options;
    kzmx_ifoption('style="background-color:', $mx_options['setBackgroundColor'], '; ');
    kzmx_ifoption('color:', $mx_options['setBodyColor']);
    return;
}

?>