jQuery.noConflict();
(function ($) {
    "use strict";
    $(document).on("click", ".oxi-plugins-admin-recommended-dismiss", function (e) {

        e.preventDefault();
        var _This = $(this);
        console.log(_This.attr('sup-data'));
        $.ajax({
            url: shortcode_addons_recommended.ajaxurl,
            type: 'post',
            data: {
                action: 'shortcode_addons_recommended',
                _wpnonce: shortcode_addons_recommended.nonce,
                notice: _This.attr('sup-data')
            },
            success: function (response) {
                $('.oxi-addons-admin-notifications').remove();
            },
            error: function (error) {
                console.log('Something went wrong!');
            },
        });
        return false;
    });
})(jQuery);
