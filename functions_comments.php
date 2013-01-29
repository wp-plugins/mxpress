<?php

/**
 * Loads the comment template specified in $file.
 *
 * Will not display the comments template if not on single post or page, or if
 * the post does not have comments.
 *
 * Uses the WordPress database object to query for the comments. The comments
 * are passed through the 'comments_array' filter hook with the list of comments
 * and the post ID respectively.
 *
 * The $file path is passed through a filter hook called, 'comments_template'
 * which includes the TEMPLATEPATH and $file combined. Tries the $filtered path
 * first and if it fails it will require the default comment template from the
 * default theme. If either does not exist, then the WordPress process will be
 * halted. It is advised for that reason, that the default theme is not deleted.
 *
 * @since 1.5.0
 * @global array $comment List of comment objects for the current post
 * @uses $wpdb
 * @uses $post
 * @uses $withcomments Will not try to get the comments if the post has none.
 *
 * @param string $file Optional, default '/comments.php'. The file to load
 * @param bool $separate_comments Optional, whether to separate the comments by comment type. Default is false.
 * @return null Returns null if no comments appear
 */
function mxpress_comments_template($file = '/comments.php', $separate_comments = false) {
    global $wp_query, $withcomments, $post, $wpdb, $id, $comment, $user_login, $user_ID, $user_identity, $overridden_cpage;

    if (!(is_single() || is_page() || $withcomments) || empty($post))
        return;

    if (empty($file))
        $file = '/comments.php';

    $req = false; //get_option('require_name_email');
    //mxpress_debug(array('$req', $req));
    /**
     * Comment author information fetched from the comment cookies.
     *
     * @uses wp_get_current_commenter()
     */
    $commenter = wp_get_current_commenter();
    //mxpress_debug(array('$commenter', $commenter));

    /**
     * The name of the current comment author escaped for use in attributes.
     */
    $comment_author = $commenter['comment_author']; // Escaped by sanitize_comment_cookies()
    //mxpress_debug(array('$comment_author', $comment_author));
    /**
     * The email address of the current comment author escaped for use in attributes.
     */
    $comment_author_email = $commenter['comment_author_email'];  // Escaped by sanitize_comment_cookies()
    //mxpress_debug(array('$comment_author_email', $comment_author_email));
    /**
     * The url of the current comment author escaped for use in attributes.
     */
    $comment_author_url = esc_url($commenter['comment_author_url']);
    //mxpress_debug(array('$comment_author_url', $comment_author_url));
    /** @todo Use API instead of SELECTs. */
    if ($user_ID) {
        $comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND (comment_approved = '1' OR ( user_id = %d AND comment_approved = '0' ) )  ORDER BY comment_date_gmt", $post->ID, $user_ID));
    } else if (empty($comment_author)) {
        $comments = get_comments(array('post_id' => $post->ID, 'status' => 'approve', 'order' => 'ASC'));
    } else {
        $comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND ( comment_approved = '1' OR ( comment_author = %s AND comment_author_email = %s AND comment_approved = '0' ) ) ORDER BY comment_date_gmt", $post->ID, wp_specialchars_decode($comment_author, ENT_QUOTES), $comment_author_email));
    }

    // keep $comments for legacy's sake
    $wp_query->comments = apply_filters('comments_array', $comments, $post->ID);
    $comments = &$wp_query->comments;
    $wp_query->comment_count = count($wp_query->comments);
    update_comment_cache($wp_query->comments);

    if ($separate_comments) {
        $wp_query->comments_by_type = &separate_comments($comments);
        $comments_by_type = &$wp_query->comments_by_type;
    }

    $overridden_cpage = false;
    if ('' == get_query_var('cpage') && get_option('page_comments')) {
        set_query_var('cpage', 'newest' == get_option('default_comments_page') ? get_comment_pages_count() : 1 );
        $overridden_cpage = true;
    }

    if (!defined('COMMENTS_TEMPLATE') || !COMMENTS_TEMPLATE)
        define('COMMENTS_TEMPLATE', true);

    /* $include = apply_filters('comments_template', STYLESHEETPATH . $file );
      if ( file_exists( $include ) )
      require( $include );
      elseif ( file_exists( TEMPLATEPATH . $file ) )
      require( TEMPLATEPATH . $file );
      else // Backward compat code will be removed in a future release
      require( ABSPATH . WPINC . '/theme-compat/comments.php');
     */

    require 'mxpress_comments.php';
}

/**
 * Outputs a complete commenting form for use within a template.
 * Most strings and form fields may be controlled through the $args array passed
 * into the function, while you may also choose to use the comment_form_default_fields
 * filter to modify the array of default fields if you'd just like to add a new
 * one or remove a single field. All fields are also individually passed through
 * a filter of the form comment_form_field_$name where $name is the key used
 * in the array of fields.
 *
 * @since 3.0.0
 * @param array $args Options for strings, fields etc in the form
 * @param mixed $post_id Post ID to generate the form for, uses the current post if null
 * @return void
 */
function mxpress_comment_form($args = array(), $post_id = null) {
    // modified clone of comment_form
    //mxpress_debug('mxpress_comment_form()');
    global $id;

    if (null === $post_id)
        $post_id = $id;
    else
        $id = $post_id;

    $commenter = wp_get_current_commenter();
    $user = wp_get_current_user();
    $user_identity = $user->exists() ? $user->display_name : '';

    $req = get_option('require_name_email');
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $fields = array(
        'author' => '<p class="comment-form-author">' . '<label for="author">' . __('Name') . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>',
        'email' => '<p class="comment-form-email"><label for="email">' . __('Email') . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
        '<input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' /></p>',
        'url' => '<p class="comment-form-url"><label for="url">' . __('Website') . '</label>' .
        '<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" /></p>',
    );

    $required_text = sprintf(' ' . __('Required fields are marked %s'), '<span class="required">*</span>');

    $defaults = array(
        'fields' => apply_filters('comment_form_default_fields', $fields),
        'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x('Comment', 'noun') . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
        'must_log_in' => '<p class="must-log-in">' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink($post_id)))) . '</p>',
        'logged_in_as' => '<p class="logged-in-as">' . sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink($post_id)))) . '</p>',
        'comment_notes_before' => '<p class="comment-notes">' . __('Your email address will not be published.') . ( $req ? $required_text : '' ) . '</p>',
        'comment_notes_after' => '<p class="form-allowed-tags">' . sprintf(__('You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s'), ' <code>' . allowed_tags() . '</code>') . '</p>',
        'id_form' => 'commentform',
        'id_submit' => 'submit',
        'title_reply' => __('Leave a Reply'),
        'title_reply_to' => __('Leave a Reply to %s'),
        'cancel_reply_link' => __('Cancel reply'),
        'label_submit' => __('Post Comment'),
    );

    $args = wp_parse_args($args, apply_filters('comment_form_defaults', $defaults));
    ?>
    <?php if (comments_open($post_id)) : ?>
        <?php do_action('comment_form_before'); ?>
        <div id="respond">
            <!-- h3 id="reply-title"><small><?php cancel_comment_reply_link($args['cancel_reply_link']); ?></small></h3 -->
            <?php $cancelurl = str_replace('&doMxpressComment=1', '', $_SERVER['REQUEST_URI']); ?>
            <a href="<?php echo $cancelurl; ?>">Cancel Comment</a><br><br>
            <?php /* if (get_option('comment_registration') && !is_user_logged_in()) : ?>
              <?php echo $args['must_log_in']; ?>
              <?php do_action('comment_form_must_log_in_after'); ?>
              <?php else : */ ?>
            <form action="<?php echo site_url('/wp-comments-post.php'); ?>" method="post" id="<?php echo esc_attr($args['id_form']); ?>">
                <?php //do_action('comment_form_top'); ?>
                <?php
                //if (is_user_logged_in()) :
                // logged in users don't apply at the moment
                /*          ?>
                  <?php echo apply_filters('comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity); ?>
                  <?php do_action('comment_form_logged_in_after', $commenter, $user_identity);
                 */
                // else :
                ?>
                <?php // echo $args['comment_notes_before']; ?>
                <?php
                // do_action('comment_form_before_fields');
                // mxpress_debug($args);
                global $_SERVER;
                //  mxpress_debug($_SERVER);
                foreach ((array) $args['fields'] as $name => $field) {
                    //  echo '[' . apply_filters("comment_form_field_{$name}", $field) . "]\n";
                }
                global $_SERVER, $post;
                $_mxpress_nic =  urldecode($_SERVER['HTTP_X_MXIT_NICK']);
                $_mxpress_userid_r = $_SERVER['HTTP_X_MXIT_USERID_R'];
                if ($_mxpress_nic == '') {
                    $_mxpress_nic = 'Unspecified Mxit User';
                }
                if ($_mxpress_userid_r == '') {
                    $_mxpress_userid_r = 'Unspecified.Mxit.User.ID';
                }
                echo 'Please write your comment now and click send:';
                echo apply_filters('comment_form_field_comment', '<p class="comment-form-comment"><textarea id="comment" name="comment"  aria-required="true"></textarea></p>');
                ?>
                <input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>" />
                <input type="hidden" name="author" value="<?php echo $_mxpress_nic; ?>" />
                <input type="hidden" name="email" value="<?php echo $_mxpress_userid_r . '@mixit.im'; ?>" />
                <input name="submit" type="submit" id="<?php echo esc_attr($args['id_submit']); ?>" value="<?php echo esc_attr($args['label_submit']); ?>" />

                <?php //endif; ?>

            </form>
            <?php /* endif; */ ?>
        </div><!-- #respond -->
        <?php do_action('comment_form_after'); ?>
    <?php else : ?>
        <?php do_action('comment_form_comments_closed'); ?>
    <?php endif; ?>
    <?php
}

function mxpress_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    //mxpress_debug(get_comment_text($comment_ID));
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
        <?php endif; ?>

        <?php //if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>
        <?php echo '<span style="color:#63B8FF;">'. preg_replace("/[^A-Za-z0-9 ]/", '', urldecode(get_comment_author())).'</span><br/>'; ?>

        <?php if ($comment->comment_approved == '0') : ?>
            <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
            <?php
        endif;
        //comment_text();
        echo $comment->comment_content;

        $now = new DateTime(date_i18n('Y-m-d H:i', time()));
        $ref = new DateTime(get_comment_date('Y-m-d') . ' ' . get_comment_time('H:i'));
        $diff = $now->diff($ref);
        echo ' <span style="color:#CCCCCC;"><i>(';
        if ($diff->d) {
            printf('%dd', $diff->d);
        } else {
            if ($diff->h) {
                printf('%dh', $diff->h);
            } else {
                printf('%dm', $diff->i);
            }
        }
         echo ' ago)</i></span>';
         // edit_comment_link(__('(Edit)'), '  ', '');
        ?>


        <?php //comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>

        <?php if ('div' != $args['style']) : ?>
        </div>
    <?php endif; ?>
    <?php
}

// send comments to oldest if neccesary 
function mxpress_cpage_intercept($query) {
    $mxpress_options = get_option('mxpress_options');
    $reverse_order = $mxpress_options['orderComments'];
    if ((mxpress_isMixit()) && ($reverse_order == 'False') && ($query->query_vars['cpage'] == NULL)) {
        $query->set('cpage', '1');
    }
}
?>