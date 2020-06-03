jQuery.noConflict();
(function ($) {
    var styleid = '';
    var childid = '';
    function ShortcodeAddonsFrontendTemplate(functionname, rawdata, styleid, childid, callback) {
        if (functionname !== "") {
            $.ajax({
                url: shortcode_addons_editor.ajaxurl,
                type: "post",
                data: {
                    action: "shortcode_home_data",
                    _wpnonce: shortcode_addons_editor.nonce,
                    functionname: functionname,
                    styleid: styleid,
                    childid: childid,
                    rawdata: rawdata
                },
                success: function (response) {
                    callback(response);
                }
            });
        }
    }
    jQuery(".shortcode-addons-template-active").submit(function (e) {
        e.preventDefault();
        var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_active";
        $(this).prepend('<span class="spinner sa-spinner-open-left"></span>');
        ShortcodeAddonsFrontendTemplate(functionname, rawdata, styleid, childid, function (callback) {
            setTimeout(function () {
                jQuery(".oxi-addons-parent-loader-wrap").hide();
                if (callback === "Problem") {
                    alert("Data Error: Kindly contact via WordPress Support Forum or Oxilab.org");
                } else {
                    document.location.href = callback;
                }
                console.log(callback);
            }, 1000);
        });
    });
    jQuery(".shortcode-addons-template-deactive").submit(function (e) {
        e.preventDefault();
        var $This = $(this);
        var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_deactive";
        $(this).prepend('<span class="spinner sa-spinner-open-left"></span>');
        ShortcodeAddonsFrontendTemplate(functionname, rawdata, styleid, childid, function (callback) {
            setTimeout(function () {
                if (callback === "Confirm") {
                    $This.parents('.oxi-addons-style-preview').remove();
                }
                jQuery(".oxi-addons-parent-loader-wrap").hide();
                console.log(callback);
            }, 1000);
        });

    });

    $(".oxi-addons-addons-template-create").on("click", function () {
        $("#oxi-addons-style-modal-form")[0].reset();
        var data = $(this).attr("addons-data");
        $("#oxi-addons-data").val(jQuery("#" + data).val());
        $("#oxi-addons-style-create-modal").modal("show");
    });
    jQuery(".oxi-addons-style-clone").on("click", function () {
        $("#oxi-addons-style-modal-form")[0].reset();
        var dataid = jQuery(this).attr('oxiaddonsdataid');
        jQuery('#oxistyleid').val(dataid);
        jQuery("#oxi-addons-style-create-modal").modal("show");
    });
    jQuery(".oxi-addons-style-export").on("click", function (e) {
        e.preventDefault();
        var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_export";
        $(this).prepend('<span class="spinner sa-spinner-open"></span>');
        ShortcodeAddonsFrontendTemplate(functionname, rawdata, styleid, childid, function (callback) {
            setTimeout(function () {
                $('.sa-spinner-open').remove();
                $("#oxi-addons-style-export-form")[0].reset();
                jQuery("#OxiAddImportDatacontent").val(callback);
                jQuery("#oxi-addons-style-export-modal").modal("show");
            }, 1000);
        });
    });
    jQuery("#oxi-addons-style-modal-form").submit(function (e) {
        e.preventDefault();
        var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_create";
        $('.modal-footer').prepend('<span class="spinner sa-spinner-open-left"></span>');
        ShortcodeAddonsFrontendTemplate(functionname, rawdata, styleid, childid, function (callback) {
            setTimeout(function () {
                document.location.href = callback;
            }, 1000);
        });
    });

    jQuery(".oxi-addons-style-delete").submit(function (e) {
        e.preventDefault();
        var $This = $(this);
        var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_delete";
        $(this).append('<span class="spinner sa-spinner-open"></span>');
        ShortcodeAddonsFrontendTemplate(functionname, rawdata, styleid, childid, function (callback) {
            setTimeout(function () {
               if (callback === "done") {
                $This.parents('tr').remove();
            }
            }, 1000);
        });

    });
    jQuery(".OxiAddImportDatacontent").on("click", function () {
        jQuery("#OxiAddImportDatacontent").select();
        document.execCommand("copy");
        alert("Your Style Data Copied");
    });

})(jQuery)