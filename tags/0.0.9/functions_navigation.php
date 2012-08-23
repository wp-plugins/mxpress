<?php

function footer_nav() {
    global $wp_query;
    $queried_object = $wp_query->queried_object;
    $parentid = $queried_object->post_parent;

//figure out if we want to allow going 'back'
    $back_url = false;
    if ($parentid) {
        //normal content
        $back_url = get_permalink($parentid);
    } else {
        $parentid = $queried_object->parent;
        $taxonomy = $queried_object->taxonomy;
        $back_url = ($parentid && $taxonomy) ? get_term_link(intval($parentid), $taxonomy) : false;
    }
// fall-back to referer, if appropriate
    if ((!$back_url) && ($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] != get_bloginfo('url')) && (stristr($_SERVER['HTTP_REFERER'], get_bloginfo(url)))) {
        $back_url = $_SERVER['HTTP_REFERER'];
    }

    if ($back_url):
        $parent_post = get_post($theparentid);
        $theparentid = ($parent_post->post_parent == $homeid) ? $homeid : $theparentid;
        ?>
        <a href="<?php echo $back_url ?>">Back</a>
        <?php
    endif;

    if(1 == 1):
        ?> | <a href="<?php bloginfo('url'); ?> ">Menu</a>
        <?php  
    elseif(!is_front_page()): // (($theparentid <> $homeid) &&
        ?> | <a href="<?php bloginfo('url'); ?> ">Menu</a>
        <?php
    endif;
}

function fix_navlist($content) {
    //todo: convert html ul to space nested list
}
?>
