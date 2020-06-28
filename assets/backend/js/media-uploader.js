
jQuery.noConflict();
(function ($) {

    var custom_uploader;
    var $Storetype;
    var $image;
    $(document.body).on("click", ".shortcode-addons-media-control", function (e) {
        $This = $(this);
        var link = $This.siblings("input").attr('id');
        var datatype = $This.siblings("input").data('type');


        $('#oxi-addons-preview-data').prepend('<input type="hidden" id="shortcode-addons-body-image-upload-hidden" value="#' + link + '" />');
        e.preventDefault();
        if (custom_uploader && $Storetype === datatype) {
            custom_uploader.open();
            return;
        }
        $Storetype = datatype;

        if (datatype === 'file') {
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose File',
                button: {
                    text: 'Choose File'
                },
                multiple: false,
                library: {
                    type: ['application']
                }
            });
        } else if (datatype === 'video') {
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Video',
                button: {
                    text: 'Choose Video'
                },
                multiple: false,
                library: {
                    type: ['video']
                }
            });
        } else if (datatype === 'audio') {
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Audio',
                button: {
                    text: 'Choose Audio'
                },
                multiple: false,
                library: {
                    type: ['audio']
                }
            });
        } else {
            $image = 'image';
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false,
                library: {
                    type: ['image']
                }
            });
        }


        custom_uploader.on('select', function () {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            var url = attachment.url;

            if (($("#oxi-addons-list-data-modal").data("bs.modal") || {})._isShown) {
                jQuery("#oxi-addons-list-data-modal").css({
                    "overflow-x": "hidden",
                    "overflow-y": "auto"

                });
                jQuery("body").css({
                    "overflow": "hidden"
                });
            }
            var lnkdata = $("#shortcode-addons-body-image-upload-hidden").val();
            $(lnkdata).val(url).change();
            if ($image === 'image') {
                var alt = attachment.alt;
                $(lnkdata + '-alt').val(alt).change();
                console.log(alt);
            }
            $(lnkdata).siblings('.shortcode-addons-media-control').removeClass('shortcode-addons-media-control-hidden-button');
            if (datatype === '') {
                $(lnkdata).siblings('.shortcode-addons-media-control').children('.shortcode-addons-media-control-image-load').css('background-image', 'url(' + url + ')');
            }
        });
        custom_uploader.open();

    }).on('click', '.shortcode-addons-media-control-image-load-delete-button', function (e) {
        $(this).parent().parent().addClass('shortcode-addons-media-control-hidden-button');
        $(this).parent('.shortcode-addons-media-control-image-load').css('background-image', 'url()');
        $(this).parent().parent().siblings('input').val("").change();
        e.stopPropagation();
    });

})(jQuery)