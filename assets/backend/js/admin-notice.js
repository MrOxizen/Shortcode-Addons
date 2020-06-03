
jQuery.noConflict();
(function ($) {
    "use strict";
    $(document).on("click", ".shortcode-addons-support-reviews", function (e) {
        e.preventDefault();
        alert("ok");
        $.ajax({
            url: shortcode_addons_admin_notice.ajaxurl,
            type: 'post',
            data: {
                action: 'shortcode_addons_notice_dissmiss',
                _wpnonce: shortcode_addons_admin_notice.nonce,
                notice: $(this).attr('sup-data'),
            },
            success: function (response) {
                $('.shortcode-addons-review-notice').hide();
            },
            error: function (error) {
                console.log('Something went wrong!');
            },
        });
    });
})(jQuery);
