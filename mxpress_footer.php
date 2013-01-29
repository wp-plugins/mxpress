<br /><br />
<?php
$menu_output = ''; // will contain navigation menu if required

if ((is_page()) && (!is_front_page()) && ($mxpress_options['doListChildPagesOnPages'])) {
    global $post;
    $menu_output = wp_list_pages(array('child_of' => $post->ID, 'title_li' => 'Read More', 'echo' => 0));
}
// have, must clean for mxit
$menu_output = mxpress_fix_external_links($menu_output);
echo $menu_output;

if ((is_front_page())) {
    if ($mxpress_options['doListCategoriesHome']) {
        echo '<br />';
        wp_list_categories();
    }
} else {
    if ((is_single()) && ($mxpress_options['doListCategoriesOnSingle'])) {
        //echo 'single';
        echo '<br />';
        wp_list_categories(array('title_li' => ''));
    }
}

footer_nav();
mxpress_get_shinka_banner_ad('Footer',true,'<br />','<br/>');
mxpress_GA_trackbypx();
?> 
</body>
</html>