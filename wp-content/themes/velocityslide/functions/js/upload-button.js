jQuery(document).ready(function () {
    jQuery('#vs_portfolio_upload_images').click(function () {
        var tbURL = jQuery('#add_image').attr('href');
        if (typeof tbURL === 'undefined') {
            tbURL = jQuery('#content-add_media').attr('href');
        }
        tb_show('', tbURL);
        return false;
    });
    jQuery('#vs_post_upload_images').click(function () {
        var tbURL = jQuery('#add_image').attr('href');
        if (typeof tbURL === 'undefined') {
            tbURL = jQuery('#content-add_media').attr('href');
        }
        tb_show('', tbURL);
        return false;
    });
});