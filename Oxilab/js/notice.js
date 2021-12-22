 jQuery.noConflict();
(function ($) {
    "use strict";
    $(document).on("click", ".shortcode-addons-support-reviews", function (e) {
        e.preventDefault();
       var _This = $(this);
        $.ajax({
            url: shortcode_addons_reviews.ajaxurl,
            type: 'post',
            data: {
                action: 'shortcode_addons_reviews',
                _wpnonce: shortcode_addons_reviews.nonce,
                notice: _This.attr('sup-data')
            },
            success: function (response) {
                console.log(response);
                $('.shortcode-addons-review-notice').remove();
            },
            error: function (error) {
                console.log('Something went wrong!');
            },
        });
    });
})(jQuery);
