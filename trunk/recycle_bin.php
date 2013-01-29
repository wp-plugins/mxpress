<?php

// email mxitID@mxit.im

function mxpress_wrap_old($pageless_content, $chunksize, $insert_str) {
    // try to paginate content nicely, i.e. not break headings, bullets, table cells etc.
    // todo: strip class and similar atributes from tags

    $break_tolerance = 15;
    $remaining_str = $pageless_content;
    $chunks = array();
    $open_tags = array();
    $single_entitietags = array('br', 'hr', 'img', 'input');
    $must_close_tags = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'td', 'li');
    $chunk_borrowflag = false;
    while ($remaining_str) {
        if (strlen($remaining_str) > $chunksize) {
            echo '<br>Have to chunk';
            $this_chunk = substr($remaining_str, 0, $chunksize);
            echo '<br>This chunk:[' . htmlentities($this_chunk) . ']';
            $remaining_str = substr($remaining_str, $chunksize);
            // check for tags
            $remaining_chunk = stristr($this_chunk, '<');
            $processed_piece = substr($this_chunk, 0, strlen($this_chunk) - strlen($remaining_chunk));
            echo '<br>$processed_piece:' . htmlentities($processed_piece) . ' $remaining_chunk :' . htmlentities($remaining_chunk);
            if ($remaining_chunk) {
                while (strlen($remaining_chunk) > $break_tolerance) {
                    // we have a tag to deal with, is it complete, is it closed,
                    // if it is not closed in this chunk, and within the break_tollerance, then break before it as long as there are no open tags
                    $tagendsat = tagname_ends_at($remaining_chunk);
                    echo ' looks like we may have a tag in:' . htmlentities($remaining_chunk) . "($tagendsat)";
                    if (!$tagendsat) {
                        if (!$chunk_borrowflag) {
                            echo '//borrow from next';
                            $chunk_borrowflag = true;
                            $remaining_chunk = $remaining_chunk . substr($remaining_str, 0, $break_tolerance);
                            $remaining_str = substr($remaining_str, $break_tolerance - 1);
                            $tagendsat = tagname_ends_at($remaining_chunk);
                        } else {
                            echo '<br>Could not find the end of tag';
                        }
                    }
                    $the_tag = ($tagendsat) ? substr($remaining_chunk, 1, $tagendsat - 1) : false;
                    if ($the_tag) {
                        echo '<br> have tag:' . $the_tag;
                        $this_piece .= substr($remaining_chunk, 0, strpos($remaining_chunk, '>') + 1);
                        $remaining_chunk = substr($remaining_chunk, strpos($remaining_chunk, '>') + 1);
                        echo'<br>This piece:' . htmlentities($this_piece);
                        echo'<br>This remainder:' . htmlentities($remaining_chunk);

                        if (!strstr($remaining_chunk, '>')) {
                            $remaining_chunk = false;
                        }
                    } else {
                        echo '<br>//problem';
                        $remaining_chunk = false;
                    }
                    if (in_array(strtolower($the_tag), $must_close_tags)) {
                        $open_tags[] = strtolower($the_tag);
                    }
                    if (count($open_tags)) {
                        echo '<br>something needs closing';
                        var_dump('<br>ot:', $open_tags);
                    }

                    if ((strpos($the_tag, '/') === 0) && (count($open_tags))) {
                        $trimed_tag = substr(str_replace(' ', '', $the_tag), 1);
                        $foundat = array_search(strtolower($trimed_tag), $must_close_tags);
                        if ($foundat !== false) {
                            echo 'found the closing tag of [' . html_entity_decode($test[$foundat]) . ']';
                            unset($test[$foundat]);
                        }
                    }
                }
                $chunks[] = $this_piece;
                $remaining_str = $remaining_chunk . $remaining_str;
            } else {
                $chunks[] = $processed_piece;
            }
        } else {
            // we've reached the end of the_content, exit while($remaining_str)
            $chunks[] = $remaining_str;
            break;
        }
    }
    echo '<br><br>After:';
    var_dump($chunks);
    $mxpress_content = implode("<!--nextpage-->
        ", $chunks);
    echo '<br><br><pre>' . htmlentities($mxpress_content) . '</pre>';
}

function tagname_ends_at($str) {
//echo'<br>[fx:tagname_ends_at]';
    $first_space = strpos($str, ' ');
    $first_close = strpos($str, '>');
    $return_pos = false;
//var_dump($first_space,$first_close);
    if (($first_space !== false) && ($first_close !== false)) {
        $return_pos = ($first_space < $first_close) ? $first_space : $first_close; //return smallest
    } else {
        if ($first_space !== false)
            $return_pos = $first_space;
        if ($first_close !== false)
            $return_pos = $first_close;
// echo"[rp:$return_pos]";
    }
    return $return_pos;
}

//experimental: compress all markup tags except '<!--nextmixitpage-->' to zero length , by storing them in separate array
    // so that we don't get shorter pages when eg page contains links with loong href parameters
    $markup_tags = array();
    $content = str_replace('<!--nextmixitpage-->', '[nMp', $content);
    $content_ar = explode('<',' '.$content);
    $count = 0;
    $indexat = 0;
    foreach($content_ar as $content_piece){
        echo '<br><br>.';
        var_dump($content_piece);
        $piecelength = strlen($content_piece);
        echo '<br>'.$piecelength ;
        $markup_tags[$count]['index'] = $piecelength ;
        $indexat += $piecelength ;
        $tag = strstr($content_piece,'>',true);
        
        if($tag){
            $markup_tags[$count-1]['tag'] = $tag;
            echo '<br>Previous tag:'.$tag;
            $markup_tags[$count-1]['newpiece'] = substr($content_piece,  strlen($tag)+1);
            var_dump('<br>np:',$new_piece);
            $new_pieces[]=$new_piece;
        }
        $count++;
    }
    var_dump($markup_tags);
    var_dump($new_pieces);
?>
