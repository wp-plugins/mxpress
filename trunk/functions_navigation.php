<?php

function footer_nav() {

    global $wp_query, $doMxpressComment, $mxpress_options;
    $footer_nav_ar = array();
    $queried_object = $wp_query->queried_object;
    $parentid = $queried_object->post_parent;

    if (!$doMxpressComment) {
        $footer_nav_ar[] = mxpress_comment_link();
    }

    //figure out if we want to allow going 'back' and where that might be
    $back_url = false;

    if (is_front_page()) {
        $back_url = get_bloginfo('url');
    } else {
        if ($parentid) {
            //normal content
            $back_url = get_permalink($parentid);
        } else {
            $parentid = $queried_object->parent;
            $taxonomy = $queried_object->taxonomy;
            //issue: this goes 'back to parent, 'complicated' situations possible in combination with menus possible
            $back_url = ($parentid && $taxonomy) ? get_term_link(intval($parentid), $taxonomy) : false;
        }
    }

    /* // fall-back to referer, if appropriate
      if ((!$back_url) && ($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] != get_bloginfo('url')) && (stristr($_SERVER['HTTP_REFERER'], get_bloginfo(url)))) {
      $back_url = $_SERVER['HTTP_REFERER'];
      }
      // if referer was a page, override parent
      //TMP:
      if(stristr($_SERVER['HTTP_REFERER'],'page_id')){

      $back_url = $_SERVER['HTTP_REFERER'];
      } */

    //TODO: configurable mapping a switch from e.g. category to a pages etc.
    /*
      if ($back_url == 'http://misc.kazazoom.com/wp_portals/subxwp/wordpress/?cat=3') {
      $back_url = 'http://misc.kazazoom.com/wp_portals/subxwp/wordpress/?page_id=64';
      } */

    // Back link? (goes 'up'
    if (!is_front_page()) {
        if (!$doMxpressComment) {
            $footer_nav_ar[] = '<a href="' . $back_url . '">Back</a>';
        }
    } else {
        if ($mxpress_options['doShowBackFrontPage']) {
            $footer_nav_ar[] = '<a href="' . $back_url . '">Back</a>';
        }
    }
    if (!is_front_page()) {
        if (!$doMxpressComment) {
            $footer_nav_ar[] = '<a href="' . get_bloginfo('url') . '">Menu</a>';
        }
    } else {
        if ($mxpress_options['doShowMenuFrontPage']) {
            $footer_nav_ar[] = '<a href="' . get_bloginfo('url') . '">Menu</a>';
        }
    }
    $footer_nav_ar = array_filter($footer_nav_ar);
    $footer_nav_str = implode(' | ', $footer_nav_ar);
    echo $footer_nav_str;
    return;
}

function mxpress_strip_fwdslashes($str) {
    return str_ireplace('/', '', $str);
}

function fix_navlist($content) {
    //todo: convert html ul to space nested list
}

function mxpress_comment_link() {
    global $doMxpressComment;
    $return = '';
    if ((comments_open()) && (!$doMxpressComment) && (!is_archive())) { //comments_open()
        global $_query_string;
        $return = '<a href="?' . $_query_string . '&doMxpressComment=1">Comment</a>';
    }
    return $return;
}

?>