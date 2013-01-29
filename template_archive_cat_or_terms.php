<?php
global $mxpress_options;
if ($mxpress_options['useLogoOnArchivePage']) {
    mxpress_logo();
}
if ($mxpress_options['doShowTitles']) {
    $current_category = single_cat_title("", false);
    echo mxpress_ifoption('<h1><span style="color:', $mxpress_options['titleCol'], ';">', false);
    //echo $current_category;
    wp_title("", true);
    echo mxpress_ifoption('</span></h1>', $mxpress_options['titleCol'], '', false, false);
}
echo term_description(); //todo: configurale option

while (have_posts()) : the_post();
// this is a listing of links to items, we don't show content 
    echo mxpress_ifoption('* <span style="color:', $mxpress_options['h2Col'], ';">', false);
    mxpress_render_link(get_permalink(), get_the_title());
//echo ' ('.get_comment_count().')';
    comments_number('', ' (1)', ' (%)');
    echo mxpress_ifoption('</span>', $mxpress_options['h2Col'], '', false, false); // . '<br/>' 
    echo '<br>';
endwhile;

if ($wp_query->max_num_pages > 1) :
    ?>
    <div id="nav-above" class="navigation">
        <div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&lt;&lt;</span> Prev', 'twentyten')); ?></div>
        <div class="nav-next"><?php previous_posts_link(__('Next <span class="meta-nav">&gt;&gt;</span>', 'twentyten')); ?></div>
    </div><!-- #nav-above -->
    <?php
endif;
?>