<br /><br />
<?php
if ((is_front_page()) && ($mx_options['doListAllPagesHome'])) {

    echo kzmx_ifoption('<h1><span style="color:', $mx_options['titleCol'], ';">', false);
    echo $mx_options['setListPagesHomeTitle'];
    echo kzmx_ifoption('</span></h1><br/>', $mx_options['titleCol'], '', false, false);
    wp_nav_menu(array('echo' => 1, 'container' => false, 'container_class' => ''));

} else {
    if ((is_page()) && ($mx_options['doListallPagesOnPages'])) {

        wp_nav_menu(array('echo' => 1, 'container' => false, 'container_class' => ''));
    }
}
if ((is_page()) && (!is_front_page()) && ($mx_options['doListChildPagesOnPages'])) {

    global $post;
    wp_list_pages(array('child_of' => $post->ID, 'title_li'=>'Read More'));
}
?>
<br />
<?php
if ((is_front_page())) {
    if ($mx_options['doListCategoriesHome']) {
        echo '<br />';
        wp_list_categories();
    }
} else {
    //echo 'not front';
    if((is_single())&&($mx_options['doListCategoriesOnSingle'])){
        //echo 'single';
        echo '<br />';
        wp_list_categories(array('title_li'=>''));
    }
}
?>
<br />
<?php footer_nav() ?>
<?php kzmx_GA_trackbypx(); ?> 
</body>
</html>