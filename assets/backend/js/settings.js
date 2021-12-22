jQuery.noConflict();
(function ($) {
    var styleid = '';
    var childid = '';
    var clsname = '';
    var childid = '';
    async function ShortCodeAddonsRestApi(functionname, rawdata, styleid, childid, callback) {
        if (functionname === "") {
            alert('Confirm Function Name');
            return false;
        }
        let result;
        try {
            result = await $.ajax({
                url: ShortCodeAddonsUltimate.root + 'ShortCodeAddonsUltimate/v2/' + functionname,
                method: 'POST',
                dataType: "json",

                data: {
                    _wpnonce: ShortCodeAddonsUltimate.nonce,
                    styleid: styleid,
                    childid: childid,
                    rawdata: rawdata
                }
            });
            console.log(result);
            return callback(result);
        } catch (error) {
            console.error(error);
        }
    }

    function delay(callback, ms) {
        var timer = 0;
        return function () {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }


    $(document.body).on("click keyup", "input", function (e) {
        var $This = $(this), name = $This.attr('name'), $value = $This.val();


        if (name === 'shortcode_addons_license_key') {
            return;
        }
        var rawdata = JSON.stringify({name: name, value: $value});
        var functionname = "addons_settings";
        $('.' + name).html('<span class="spinner sa-spinner-open"></span>');
        ShortCodeAddonsRestApi(functionname, rawdata, styleid, childid, function (callback) {
            $('.' + name).html(callback);
            setTimeout(function () {
                $('.' + name).html('');
            }, 8000);
        });
    });
    $(document.body).on("change", "select", function (e) {
        var $This = $(this), name = $This.attr('name'), $value = $This.val();
        var rawdata = JSON.stringify({name: name, value: $value});
        var functionname = "addons_settings";
        $('.' + name).html('<span class="spinner sa-spinner-open"></span>');
        ShortCodeAddonsRestApi(functionname, rawdata, styleid, childid, function (callback) {
            $('.' + name).html(callback);
            setTimeout(function () {
                $('.' + name).html('');
            }, 8000);
        });
    });

    $("input[name=shortcode_addons_license_key] ").on("keyup", delay(function (e) {
        var $This = $(this), $value = $This.val();
        if ($value !== $.trim($value)) {
            $value = $.trim($value);
            $This.val($.trim($value));
        }
        var rawdata = JSON.stringify({license: $value});
        var functionname = "oxi_license";
        $('.shortcode_addons_license_massage').html('<span class="spinner sa-spinner-open"></span>');
        ShortCodeAddonsRestApi(functionname, rawdata, styleid, childid, function (callback) {
            $('.shortcode_addons_license_massage').html(callback.massage);
            $('.shortcode_addons_license_text .oxi-addons-settings-massage').html(callback.text);
        });
    }, 1000));

})(jQuery)