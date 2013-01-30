<?php

function mxpress_logo() {
    global $mxpress_options;
    switch ($mxpress_options['MasterLogo']) {
        case'none':
            return;
            break;
        case'previous':
            $mxptmb_size = get_mxptmb_size();
            echo wp_get_attachment_image($mxpress_options['MasterLogo_id'], 'mxptmb' . $mxptmb_size);
            break;
        case'featured_image':
            if (has_post_thumbnail()) {
                global $post;
                $mxptmb_size = get_mxptmb_size();
                echo get_the_post_thumbnail($post->ID, 'mxptmb' . $mxptmb_size, $attr);
            }
            break;
    }
    return;
}

// this is a clone of wp_link_pages
// for now, should be used after mxpress_the_content() has been called, inside the loop
function mxpress_link_pages() {
    global $mx_state, $wp_query, $more, $post;
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

    global $post;
    $post_id = $post->ID;
    $content_saved = get_post_meta($post_id, '_mxpress_content', true);
    if ($content_saved != '') {
        $content_ar = unserialize($content_saved);
        $numpages = count($content_ar);
        $multipage = true;
    }

	$output = '';
	
	if ($numpages > 1)
	{		
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
	}

    return $output;
}

//render the content of an item
function mxpress_the_content($content_str = '', $post_id = false, $echo = true) {

    global $post, $mx_state, $mxpress_options, $wp_query;
    $page = ($wp_query->query_vars['page']) ? $wp_query->query_vars['page'] : 1;
    if (!$post_id) {
        $post_id = $post->ID;
    }
    //do we have saved content
    $content_saved = get_post_meta($post_id, '_mxpress_content', true);
    if ($content_saved == '') {
        if ($content_str == '') {
            $tmp_post = get_post($post_id);
            $content_str = $tmp_post->post_content;
        }
        $content_ar = mxpress_paginate($content_str);
        $content_serlz = serialize($content_ar);
        update_post_meta($post_id, '_mxpress_content', $content_serlz);
    } else {
        $content_ar = unserialize($content_saved);
    }
    array_unshift($content_ar, 'Page 0');
    $content_str = force_balance_tags($content_ar[$page]) . "\n";
    if ($echo) {
        echo $content_str;
        return;
    } else {
        return $content_str;
    }
}

function mxpress_paginate($content_str) {
    mxpress_debug('mxpress_paginate');
    global $mxpress_options;

    //var_dump($mxpress_options);
    $content_str_tmp = apply_filters('the_content', $content_str);
    $content_str_tmp2 = str_replace(']]>', ']]&gt;', $content_str_tmp);

    $content_str_tmp3 = mxpress_adjust_images($content_str_tmp2);
    // clean and paginate if neccesary
    $do_process = (($mxpress_options['doKeepManualSplits']) || ($mxpress_options['doAutoSplitContent']) || ($mxpress_options['doStripTags']));
    mxpress_debug('[$do_process:' . $do_process . ']');
    $content_ar = ($do_process ) ? mxpress_clean_and_split($content_str_tmp3) : array($content_str_tmp3);
    return $content_ar;
}

function get_mxptmb_size() {
    global $mx_state;
    $usr_width = $mx_state['usr_width'];
    // switch by user screen size:';
    switch (true) {
        case ($usr_width >= 480):
            $mxptmb_size = 432;
            break;
        case ($usr_width >= 320):
            $mxptmb_size = 288;
            break;
        case ($usr_width >= 240):
            $mxptmb_size = 216;
            break;
        default:
            $mxptmb_size = 108;
            break;
    }
    return $mxptmb_size;
}

function mxpress_adjust_images($content) {
    $mxptmb_size = get_mxptmb_size();

    $html = str_get_html($content);
    $images_found_ar = array();
    // find all images
    if ($html != '') {
        foreach ($html->find('img[src]') as $img) {
            $src = $img->getAttribute('src');
            $width = $img->getAttribute('width');
            $height = $img->getAttribute('height');
            if ((!$width) || (!$height)) {
                $imagesize = getimagesize($src);
                $width = $imagesize[0];
                $height = $imagesize[1];
            }
            if (($width > $mxptmb_size) || ($height > $mxptmb_size)) {
                if (!key_exists($src, $images_found_ar)) {
                    $html_tmp = str_get_html(wp_get_attachment_image(get_attachment_id($src), 'mxptmb' . $mxptmb_size));
                    $new_img_tmp = $html_tmp->find('img[src]');
                    $src_tmp = $new_img_tmp[0]->getAttribute('src');

                    $img->setAttribute('src', $src_tmp);
                    $imagesize_tmp = getimagesize($src_tmp);
                    $img->setAttribute('width', $imagesize_tmp[0]);
                    $img->setAttribute('height', $imagesize_tmp[1]);
                }
            }
        }
    }
    return (string) $html;
}

function mxpress_body_style() {
    global $mxpress_options;
    mxpress_ifoption('style="background-color:', $mxpress_options['setBackgroundColor'], '; ');
    mxpress_ifoption('color:', $mxpress_options['setBodyColor']);
    return;
}

function mxpress_put_menu($place_option, $menu_option, $place) {
    global $mxpress_options, $doMxpressComment;
    if ((($mxpress_options[$menu_option])
            && ($mxpress_options[$place_option]) == $place)
            && (!$doMxpressComment)) {
        wp_nav_menu(array('menu' => $mxpress_options[$menu_option]));
    }
    return;
}

function mxpress_get_shinka_banner_ad($location, $echo = true, $prepend = '', $append='') {
    global $mxpress_options;
    if ((isset($mxpress_options['codeShinka_APP_MXIT_ID'])) && ($mxpress_options['codeShinka_APP_MXIT_ID'] != '')) {
        // we have an ID
        if (($mxpress_options['doShinkaMast']) || ($mxpress_options['doShinkaFooter'])) {
            // have to show a banner, load config & lib
            include_once(dirname(__FILE__) . "/lib/shinka-publisher-lib-php-master/shinka-publisher-lib-php/ShinkaBannerAd.php");
            if ($mxpress_options[doShinka . $location]) {
                echo '<!-- Render Shinka banner '. $location.' -->';
                $ShinkaBannerAd = new ShinkaBannerAd();
                $ShinkaBannerAd->doServerAdRequest();
                $thisShinkaBanner = $ShinkaBannerAd->generateHTMLFromAd();
                if ($echo) {
                    echo $prepend.$thisShinkaBanner.$append; 
                    return;
                } else {
                    return $prepend.$thisShinkaBanner.$append;
                }
            }
        }
    }
}

?>