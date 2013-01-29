<?php

global $mxpress_options;
if ($mxpress_options['useLogoOnFrontPage']) {
    mxpress_logo();
}

//if we are on the front page and should show the front page 'home' title
echo mxpress_ifoption('<h1><span style="color:', $mxpress_options['titleCol'], ';">', false);
echo $mxpress_options['HomeTitleText'];
echo mxpress_ifoption('</span></h1>', $mxpress_options['titleCol'], '', false, false);
echo $mxpress_options['HomeTitleSentence'];

mxpress_put_menu('placeMenuOnFrontPage', 'useMenuOnFrontPage', 'Before');
//var_dump($mxpress_options);//['doHideHomeContent']
//should we show content?
if ($mxpress_options['doHideHomeContent'] != 'on') {
    // don't show content when commenting
    if (!$doMxpressComment) {
        if (have_posts()):
            the_post();
            $content = get_the_content();
            mxpress_the_content(''); //($content);

        endif;
    } else {
        if ($mxpress_options['doShowContentAboveCommentform']) {
            if (have_posts()):
                the_post();
                $content = get_the_content();
                mxpress_the_content($content);
            endif;
        }
    }
    mxpress_link_pages();
    mxpress_comments_template('/mxpress_comments.php', true);
} else {
    //echo 'hide frontpage content';
}

mxpress_put_menu('placeMenuOnFrontPage', 'useMenuOnFrontPage', 'After');

// show posts?
if (($mxpress_options['doListPostsOnFrontPage'])
        && (!$doMxpressComment)) {
    global $mxpres_wpsettings, $post;
    $args = array();
    if ($mxpress_options['titlePostsOnFrontPage'] != '') {
        echo mxpress_ifoption('<h1><span style="color:', $mxpress_options['h1Col'], ';">', false);
        echo $mxpress_options['titlePostsOnFrontPage'];
        echo mxpress_ifoption('</span></h1>', $mxpress_options['titleCol'], '', false, false);
    }

    if ($mxpress_options['limitListPostsOnFrontPage'] != NULL) {
        $args['numberposts'] = intval($mxpress_options['limitListPostsOnFrontPage']);
    } else {
        $args['numberposts'] = $mxpres_wpsettings['posts_per_page'];
    }
    if ($mxpress_options['catListPostsOnFrontPage']) {
        $args['category'] = $mxpress_options['catListPostsOnFrontPage'];
    }


    $postslist = get_posts($args);
    echo '<br><br>';
    foreach ($postslist as $post) : setup_postdata($post);
        echo '* <a href="';
        the_permalink();
        echo'">';
        the_title();
        echo '</a><br>';
    endforeach;
    /* echo '<br><br>';
      $ppp = get_option('posts_per_page');
      // first page 14 posts
      if (!is_paged()) {
      $posts = get_posts('numberposts=' . $args['numberposts']);
      // second page with offset
      } elseif ($paged == 2) {
      $posts = get_posts('offset=' . $args['numberposts']);
      // all other pages with settings from backend
      } else {
      $offset = $ppp * ($paged - 2) + $args['numberposts'];
      $posts = get_posts('offset=' . $offset);
      }
      if ($posts) :
      foreach ($posts as $post) :
      echo '* <a href="';
      the_permalink();
      echo'">';
      the_title();
      echo '</a><br>';
      endforeach;
      endif;
     * 
     */
    wp_reset_query();
}
?>