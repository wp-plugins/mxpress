var uploadID = 'upload_image'; /*setup the var*/

jQuery('.upload-button').click(function() {
    uploadID = jQuery(this).prev('input'); /*grab the specific input*/
    formfield = jQuery('.upload').attr('name');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    return false;
});

window.send_to_editor = function(html) {
    imgurl = jQuery('img',html).attr('src');
    uploadID.val(imgurl); /*assign the value to the input*/
    tb_remove();
};
