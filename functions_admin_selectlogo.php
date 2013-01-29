<?php

function check_upload_image_context($context) {
    if (isset($_REQUEST['context']) && $_REQUEST['context'] == $context) {
        return TRUE;
    } elseif (isset($_POST['attachments']) && is_array($_POST['attachments'])) {
        // check for context in attachment objects 
        $image_data = current($_POST['attachments']);
        if (isset($image_data['context']) && $image_data['context'] == $context) {
            return TRUE;
        }
    }
    return FALSE;
}

function mxpress_image_tabs($_default_tabs) {
    unset($_default_tabs['type_url']);
    return($_default_tabs);
}

function mxpress_action_button($form_fields, $post) {
    $send = "<input type='submit' class='button' name='send[$post->ID]' value='" . esc_attr__('Use as MXit Logo') . "' />";
    $fields_to_unset = array('post_title', 'image_alt', 'post_excerpt', 'post_content', 'url', 'menu_order', 'align', 'image-size',);
    foreach ($fields_to_unset as $field) {
        unset($form_fields[$field]);
    }
    $form_fields['buttons'] = array('tr' => "\t\t<tr class='submit'><td></td><td class='savesend'>$send</td></tr>\n");
    $form_fields['context'] = array('input' => 'hidden', 'value' => 'mxpress-logo-image');
    return $form_fields;
}

function mxpress_image_selected($html, $send_id, $attachment) {
    $image_attributes = wp_get_attachment_image_src($send_id, 'mxptmb108'); 
    ?>
    <script type="text/javascript">
        /* <![CDATA[ */
        var win = window.dialogArguments || opener || parent || top;
        win.jQuery( '#new_logo_id' ).val('<?php echo $send_id; ?>');
        win.jQuery( '#newly_selected' ).attr("src","<?php echo $image_attributes[0]; ?>"); 
        win.jQuery( '#newly_selected' ).attr("width","<?php echo $image_attributes[1]; ?>");
        win.jQuery( '#newly_selected' ).attr("height","<?php echo $image_attributes[2]; ?>");
        win.jQuery( '#logo_option_new' ).removeAttr('disabled');
        win.jQuery( '#MasterLogo' ).val('new');
        win.tb_remove();           
        /* ]]> */
    </script><?php
    //exit();
}
?>