<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback.
 *
 */
?>
<br><div id="comments">
    <?php if (post_password_required()) : ?>
        <p class="nopassword"><?php _e('This post is password protected. Enter the password to view any comments.', 'twentyten'); ?></p>
    </div><!-- #comments -->
    <?php
    /* Stop the rest of comments.php from being processed,
     * but don't kill the script entirely -- we still have
     * to fully load the template. */
    return;
endif;

// You can start editing here -- including this comment!

global $doMxpressComment, $mxpress_options;

if ((have_comments())) :
    if (!$doMxpressComment) {
        ?><br><br><?php
        mxpress_comment_link();
        footer_nav();
        ?><br><br><?php
        //mxpress_debug('have comments, not leaving one');
        $limit_comments = ($mxpress_options['limitCommentsTo']) ? $mxpress_options['limitCommentsTo'] : get_option('comments_per_page');
        $reverse_order = $mxpress_options['orderComments']; //oldest first = 1
        $order = ($reverse_order) ? 'ASC' : 'DESC';
        $post_id = $post->ID;
        $comments = get_comments(array(
            'post_id' => $post_id,
            'status' => 'approve', //Change this to the type of comments to be displayed
            'order' => $order,
                /* 'number'=>null, */
                ));
        /* if(!$reverse_order ){
          $comments = array_reverse($comments);
          } */
        /* var_dump($comments); */
        ?>

        <br>

        <div class="commentlist">
            <?php
            /* Loop through and list the comments. */
            //wp_list_comments('avatar_size=0&style=div&type=comment&callback=mxpress_comment' . $reverse_order . '&per_page=' . $limit_comments . $reverse_order);

            wp_list_comments(array(
                'callback' => 'mxpress_comment',
                'avatar_size' => 0,
                'style' => 'div',
                'type' => 'comment',
                'per_page' => $limit_comments, //Allow comment pagination
                'reverse_top_level' => false /* !($mxpress_options['orderComments'])  */  //Show the latest comments at the top of the list*/
                    ), $comments);
            ?>
        </div>

        <div class="commentlist">
            <?php
            //Display the list of comments
            ?> </div>
        <?php
        // Are there comments to navigate through? 
        //$cpage_count = get_comment_pages_count();
        $cpage_count = ceil(count($comments) / $limit_comments);
        /* var_dump('count($comments)',count($comments));
          var_dump('$cpage_count',$cpage_count);
          var_dump('$limit_comments)',$limit_comments); */
        if ($cpage_count > 1 && $limit_comments) :
            $cpage = get_query_var('cpage');

            // figure out nomenclature
            if (!$reverse_order) {
                //newest first
                $comments_pagelink_titles = array(
                    'first' => 'Newest',
                    'prev' => 'Newer',
                    'next' => 'Older',
                    'last' => 'Oldest'
                );
            } else {
                //oldest first
                $comments_pagelink_titles = array(
                    'first' => 'Oldest',
                    'prev' => 'Older',
                    'next' => 'Newer',
                    'last' => 'Newest'
                );
            }
            ?>
            <div class="navigation">
                <?php
                $comment_nav_links = array();
                if ($cpage > 1) { //
                    $comment_nav_links[] = '<a href="' . esc_url(get_comments_pagenum_link(1)) . '">&lt;&lt; ' . $comments_pagelink_titles['first'] . '</a>';
                } else {
                    $comment_nav_links[] = '&lt;&lt; ' . $comments_pagelink_titles['first'];
                }
                $prev = get_previous_comments_link('&lt; ' . $comments_pagelink_titles['prev']);
                if ($cpage > 1) {
                    $comment_nav_links[] = '<a href="' . esc_url(get_comments_pagenum_link($cpage - 1)) . '">&lt;&lt; ' . $comments_pagelink_titles['prev'] . '</a>';
                } else {
                    $comment_nav_links[] = '&lt; ' . $comments_pagelink_titles['prev'];
                }
                $next = get_next_comments_link(__($comments_pagelink_titles['next'] . ' &gt;'));
                if ($cpage < $cpage_count) {
                    $comment_nav_links[] = '<a href="' . esc_url(get_comments_pagenum_link($cpage + 1)) . '">' . $comments_pagelink_titles['next'] . ' &gt;&gt;</a>';
                } else {
                    $comment_nav_links[] = $comments_pagelink_titles['next'] . ' &gt;';
                }
                ?>
                <?php
                if ($cpage < $cpage_count) { //  
                    $comment_nav_links[] = '<a href="' . esc_url(get_comments_pagenum_link($cpage_count)) . '">' . $comments_pagelink_titles['last'] . ' &gt;&gt;</a>';
                } else {
                    $comment_nav_links[] = $comments_pagelink_titles['last'] . ' &gt;&gt;';
                }
                echo implode(' | ', $comment_nav_links);
                echo ' (of ' . get_comments_number() . ')';
                ?> 
            </div> <!-- .navigation -->
        <?php endif; // check for comment navigation      ?><br><br>
        <?php
    } else {
        // have comments but leaving one
        mxpress_comment_form();
    }
else : // or, if we don't have comments:
    if (!comments_open()) :
    // we do nothing
    else:
        if ($doMxpressComment) {
            mxpress_comment_form();
        }
    endif; // end ! comments_open() 
endif; // end have_comments() 
?>
</div><!-- #comments -->