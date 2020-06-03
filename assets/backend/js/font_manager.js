
jQuery.noConflict();
(function ($) {
    $wrapper = $("#s-a-font-manager-fonts");
    function FONTNEWRegExp(par = '') {
        return new RegExp(par, "g");
    }

    function ShortcodeAddonsFontManager(functionname, rawdata, type, callback) {
        if (functionname !== "") {
            $.ajax({
                url: shortcode_addons_font_manager.ajaxurl,
                type: "post",
                data: {
                    action: "shortcode_addons_font_manager",
                    _wpnonce: shortcode_addons_font_manager.nonce,
                    functionname: functionname,
                    type: type,
                    rawdata: rawdata
                },
                success: function (response) {
                    callback(response);
                }
            });
        }
    }
    function convert_json_to_html(object) {
        object = $.parseJSON(object);
        $HTML = '';
        if (object.length == 0) {
            $HTML += '<div class="empty-g-font">';
            $HTML += 'Sorry, there are no font families that match. Try with other search keyword';
            $HTML += '</div>';
        } else {
            $.each(object, function (i, f) {
                $('head').append('<link href="//fonts.googleapis.com/css?family=' + f.font + '" type="text/css" media="all" rel="stylesheet"/>');
                var font = f.font.replace(/\+/g, ' ');
                font = font.split(':');
                $HTML += '<div class="s-a-font-load">';
                $HTML += '<div class="s-a-font-load-header" style="font-family:\'' + font[0] + '\'">';
                $HTML += f.font;
                $HTML += '<span class="spinner"></span>';
                $HTML += '</div>';
                if (f.stored === 'yes') {
                    $HTML += '<input type="button" class="btn btn-warning s-a-google-font-selected alignright" data-font_family="' + f.font + '" value="Selected">';
                } else {
                    $HTML += '<input type="button" class="btn btn-success s-a-google-font-collection alignright" data-font_family="' + f.font + '" value="Add to Collection">';
                }
                $HTML += '</div>';
            });
        }

        return $HTML;
    }

    function Selected_Google_Fonts() {
        var functionname = "selected_google_font";
        var rawdata = 'selected';
        var id = '';
        var type = '';
        ShortcodeAddonsFontManager(functionname, rawdata, type, function (callback) {
            object = $.parseJSON(callback);
            $HTML = '';
            $.each(object, function (i, f) {
                var font = f.font.replace(/\+/g, ' ');
                font = font.split(':');
                $HTML += '<div class="s-a-font-list"><div class="s-a-font-load">';
                if (f.name === '') {
                    $('head').append('<link href="//fonts.googleapis.com/css?family=' + f.font + '" type="text/css" media="all" rel="stylesheet"/>');
                    $HTML += '<div class="s-a-font-load-header" style="font-family:\'' + font[0] + '\'">';
                } else {
                    $HTML += '<div class="s-a-font-load-header">';
                }
                $HTML += f.font;
                $HTML += '<span class="spinner"></span>';
                $HTML += '</div>';
                $HTML += '<a href="#" class="s-a-google-font-selected selected-type" data-font_family="' + f.font + '"><span class="dashicons dashicons-no-alt"></span></a>';
                $HTML += '</div></div>';
            });
            $('#shortcode-addons-stored-font').html($HTML);
        });
    }
    function Get_Google_Fonts() {
        var rawdata = parseInt($('#s-a-font-manager-fonts').attr('data-font-load'));
        if (rawdata < 3) {
            R = 0;
        } else {
            R = rawdata;
        }
        var functionname = "get_google_font";
        var type = $("#shortcode-addons-search-font").val();
        ShortcodeAddonsFontManager(functionname, rawdata, type, function (callback) {
            var $HTML = convert_json_to_html(callback);
            $wrapper.append($HTML);
            $wrapper.attr('data-font-load', (R + 10));
            $wrapper.removeClass('font-loading');
        });
    }
    $(window).load(function () {
        $wrapper.addClass('font-loading');
        Get_Google_Fonts();
        Selected_Google_Fonts();
    });
    $(window).scroll(function () {
        if ($(window).scrollTop() > ($(document).height() - $(window).height() - 150)) {
            var $SEARCH = $('#shortcode-addons-search-font').val();
            if ($SEARCH === '' && !($wrapper.hasClass('font-loading'))) {
                $wrapper.addClass('font-loading');
                Get_Google_Fonts();
            }
        }
    });
    $('body').on('click', '.s-a-google-font-collection', function () {
        var rawdata = $(this).attr('data-font_family');
        $(this).siblings('.s-a-font-load-header').children('.spinner').css('visibility', 'visible');
        var functionname = "add_google_font";
        var id = '';
        var type = '';
        $This = $(this);
        ShortcodeAddonsFontManager(functionname, rawdata, type, function (callback) {
            $This.siblings('.s-a-font-load-header').children('.spinner').css("visibility", "hidden");
            $This.val('Selected').removeClass('btn-success').removeClass('s-a-google-font-collection').addClass('btn-warning').addClass('s-a-google-font-selected');
            Selected_Google_Fonts();
        });
    });
    $('body').on('click', '.s-a-google-font-selected', function (e) {
        e.preventDefault();
        var rawdata = $(this).attr('data-font_family');
        $(this).siblings('.s-a-font-load-header').children('.spinner').css('visibility', 'visible');
        var functionname = "remove_google_font";
        var id = '';
        var type = '';
        $This = $(this);
        ShortcodeAddonsFontManager(functionname, rawdata, type, function (callback) {
            $This.siblings('.s-a-font-load-header').children('.spinner').css("visibility", "hidden");
            $This.val('Add to Collection').removeClass('btn-warning').removeClass('s-a-google-font-selected').addClass('btn-success').addClass('s-a-google-font-collection');
            if ($This.hasClass('selected-type')) {
                $('.s-a-google-font-selected').each(function (i, o) {
                    if ($(this).attr('data-font_family') === rawdata) {
                        $(this).val('Add to Collection').removeClass('btn-warning').removeClass('s-a-google-font-selected').addClass('btn-success').addClass('s-a-google-font-collection');
                    }
                });
            }
            Selected_Google_Fonts();
        });
    });

    $('body').on('click', '#shortcode-addons-custom-fonts', function () {
        $("#addons-font-name").val('');
        $("#shortcode-addons-custom-fonts-modal").modal("show");
    });
    $("#shortcode-addons-custom-fonts-modal-form").submit(function (e) {
        e.preventDefault();
        var rawdata = $('#addons-font-name').val();
        var functionname = "add_custom_font";
        var id = '';
        var type = '';
        $This = $(this);
        ShortcodeAddonsFontManager(functionname, rawdata, type, function (callback) {
            Selected_Google_Fonts();
            $("#addons-font-name").val('');
            $("#shortcode-addons-custom-fonts-modal").modal("hide");
        });
        return false;
    });

    $(document).ready(function () {
        var typingTimer;
        var doneTypingInterval = 500;
        $('#shortcode-addons-search-font').keyup(function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(Search_Font, doneTypingInterval);
        });
        $('#shortcode-addons-search-font').keydown(function () {
            clearTimeout(typingTimer);
        });
    });

    function Search_Font() {
        var Search = $('#shortcode-addons-search-font').val();
        $wrapper.html('');
        if (Search == '') {
            $wrapper.attr('data-font-load', parseInt(1));
        }
        Get_Google_Fonts();
    }

    $(document).ready(function () {
        var $menu = $(".shortcode-addons-fonts-selected"),
                offset = $menu.offset(),
                topPadding = 15;
        $(window).scroll(function () {
            if ($(window).scrollTop() + 35 + 15 > offset.top) {
                $menu.addClass('font_selection_fixed');
            } else {
                $menu.removeClass('font_selection_fixed');
            }
        });
    });
})(jQuery)