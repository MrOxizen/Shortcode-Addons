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
            return callback(result);
        } catch (error) {
            console.error(error);
        }
    }


    $("#oxi_addons_search").on('change keyup paste click', function () {
        Text = $(this).val();
        if (Text.length >= 0) {
            $(".oxi-addons-shortcode-import").each(function () {
                _This = $(this);
                _ThisClass = _This.attr('id');
                $("#" + _ThisClass + ':not(:CaseInsensitive(' + Text + '))').hide();
                $("#" + _ThisClass + ':CaseInsensitive(' + Text + ')').show();
            });

            $(".oxi-addons-text-blocks-body-wrapper:not(:first-of-type)").hide();

        } else {
            $(".oxi-addons-shortcode-import").show();
            $(".oxi-addons-text-blocks-body-wrapper").show();
        }
    });
    $("a.addons-pre-check").on("click", function (e) {
        if ($(this).hasClass("addons-pre-check-pro")) {
            e.preventDefault();
            $("#OXIAADDONSCHANGEDPOPUP .icon-box").html('<span class="dashicons dashicons-yes"></span>');
            $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center h4").html("Premium Only!");
            $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center p").html("This Elements Works only with Premium Version.");
            $("#OXIAADDONSCHANGEDPOPUP").modal("show");
            return true;
        }
        var url = $(this).attr("href");
        var subtype = $(this).attr("sub-type");

        if (subtype !== "") {
            $(this).children(".oxi-addons-shortcode-import-bottom").append('<span class="spinner sa-spinner-open-left"></span>');
            ShortCodeAddonsRestApi('get_elements', $(this).attr("sub-name"), styleid, childid, function (callback) {
                setTimeout(function () {
                    document.location.href = url;
                }, 1000);
            });
            e.preventDefault();
        }
    });



    $.expr[':'].CaseInsensitive = function (n, i, m) {
        return $(n).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };

})(jQuery)