<?php

// paginate, split into eg. $splitLength char long sections, update num of pages in mx_state
// caveat: does not discern markup, yet...
function kzmx_clean_and_split($content, $char_length = false) {
    global $mx_options, $mx_state;
    // if length is set explicitely we use it, otherwise we use the plugin's setting
    //todo: strip unnecesary markup pameters
    //todo: base length on displayed characters only
    $char_length = ($char_length) ? $char_length : $mx_options['splitLength'];


    //remove 'normal' page breaks for other media   
    $content = str_replace('<!--nextpage-->', '', $content);
    $content = str_replace('<!--more-->', '', $content);
    $content = str_replace('<strong>', '<b>', $content);
    $content = str_replace('</strong>', '</b>', $content);

    //fix external link hrefs?
    $content = ($mx_options['doFixExternalLinks']) ? kzmx_fix_external_links($content) : $content;

    //fix bulet items?
    $content = ($mx_options['doWrapListBRs']) ? kzmx_wrap_list_brs($content) : $content;
    $content = ($mx_options['doExtraLiBRs']) ? str_replace('</li>', '</li><br>', $content) : $content;
    $content = ($mx_options['doConvertBullets']) ? kzmx_convert_bullets($content) : $content;

    // strip unnecesary markup
    $allowable_tags = html_entity_decode($mx_options['doStripTags']);
    if ($allowable_tags) {
        $content = str_replace('<!--nextmixitpage-->', '[!--NMP--]', $content);
        if (!stristr($allowable_tags, '<p>')) {
            $content = str_replace('</p>', '</p><br/>', $content);
        }
        $content = strip_tags($content, $allowable_tags);
        $content = str_replace('[!--NMP--]', '<!--nextmixitpage-->', $content);
    }

    // shoud we remove manually inserted Mxit breaks?
    if (!$mx_options['doKeepManualSplits']) {
        $content = str_replace('<!--nextmixitpage-->', '', $pageless_content);
        //echo'// insert auto splits if neccesary';
        if ($mx_options['doAutoSplitContent']) {
            $paged_content = wordwrap($content, $char_length, '<!--nextmixitpage-->');
        }
    } else {
        //echo'//if we are *keeping* manual breaks and inserting auto breaks we prioritise manual breaks';
        if ($mx_options['doAutoSplitContent']) {
            //echo '<br>we doAutoSplitContent';
            $paged_content_ar = explode('<!--nextmixitpage-->', $content);
            //var_dump($content,$paged_content_ar);
            $tmp_ar = array();
            foreach ($paged_content_ar as $paged_content) {
                $paged_content = wordwrap($paged_content, $char_length, '<!--nextmixitpage-->');
                $tmp_ar[] = $paged_content;
            }
            $paged_content = implode('<!--nextmixitpage-->', $tmp_ar);
        } else {
            $paged_content = $content;
        }
    }

    $paged_content_ar = explode('<!--nextmixitpage-->', $paged_content);

    $mx_state['num_pages'] = count($paged_content_ar);
    $mx_state['$multipage'] = (count($paged_content_ar) > 1);
    return $paged_content_ar;
}

// render smart links if appropriate
function kzmx_render_link($url, $text) {
    global $mx_options, $mx_state;
    $count_links = $mx_state['links_count'];

    if ((($mx_options['doDynamicShortlinks']) && ($mx_state['usr_width'] < 300)) ) { // || (1 == 1)
        // mart format
        $output = '<a href="' . $url . '">' . $count_links . '</a>) ' . $text;
    } else {
        // normal format
        $output = '<a href="' . $url . '">' . $text . '</a>';
    }
    echo $output;
    $mx_state['links_count']++;
}

// insert 'onclick="window.open(this.href); return false;"' for all links to external domains in supplied string
function kzmx_fix_external_links($content) {
    $content_ar = explode('<a', $content);

    foreach ($content_ar as $contentpiece01) {
        if (stristr($contentpiece01, '</a>')) {

            $addresstr = stristr($contentpiece01, 'href="');
            $tail = stristr($addresstr, '>');

            $add_endat = strlen(stristr($addresstr, '">'));
            $url = substr($addresstr, 6, strlen($addresstr) - 7 - strlen($tail));


            if ((stristr($url, get_bloginfo('url'))) ||
                    (stristr($contentpiece01, 'mxit://'))) {
                // internal targets
                $parts[] = '<a' . $contentpiece01;
            } else {
                //this is a link to the external

                $remainder = stristr($addresstr, '</a>');

                $addrlen = strlen($url) + 6 + 1;
                $linktext = substr($addresstr, $addrlen + 1, strlen($addresstr) - strlen($remainder) - $addrlen - 1);

                $anchorpart = stristr($contentpiece01, 'href="', 1);
                //echo '<br />cp:><pre>'.$contentpiece01.'</pre>';
                if (!stristr($url, 'onclick="window.open(this.href); return false;"')) {
                    $parts[] = '<a ' . $anchorpart . "  href=\"$redir_url$url\" onclick=\"window.open(this.href); return false;\" >" . trim($linktext) . " $remainder";
                } else {
                    $parts[] = '<a ' . $anchorpart . " href=\"$redir_url$url  >" . trim($linktext) . " $remainder";
                }
            }
        } else {

            $parts[] = $contentpiece01;
        }
    }
    $combined = implode('', $parts);
    return $combined;
}

// convert bullets to asterix or defined text format
function kzmx_convert_bullets($content, $new_bullet = '* ', $append_after = '<br />') {
    $content = str_replace('<li>', $new_bullet, $content);
    $content = str_replace('</li>', $append_after, $content);
    return $content;
}

function kzmx_wrap_list_brs($content) {
    $content = str_replace('<ul', '<br/><ul', $content);
    $content = str_replace('<ol', '<br/><ol', $content);
    return $content;
}

?>
