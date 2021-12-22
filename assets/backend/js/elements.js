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

    jQuery(".shortcode-addons-template-deactive").submit(function (e) {
        e.preventDefault();
        var $This = $(this);
        var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_deactive";
        $(this).prepend('<span class="spinner sa-spinner-open-left"></span>');
        ShortCodeAddonsRestApi(functionname, rawdata, styleid, childid, function (callback) {
            setTimeout(function () {
                if (callback === "Confirm") {
                    $This.parents('.oxi-addons-style-preview').remove();
                    $This.parents('.oxi-addons-style-preview').remove();
                }
                jQuery(".oxi-addons-parent-loader-wrap").hide();
            }, 1000);
        });

    });


    jQuery(".shortcode-addons-template-active").submit(function (e) {
        e.preventDefault();
        var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_active";
        $(this).prepend('<span class="spinner sa-spinner-open-left"></span>');
        ShortCodeAddonsRestApi(functionname, rawdata, styleid, childid, function (callback) {
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


    $(".oxi-addons-addons-template-create").on("click", function () {
        $("#oxi-addons-style-modal-form")[0].reset();
        var data = $(this).attr("addons-data");
        $("#oxi-addons-data").val(jQuery("#" + data).val());
        $("#oxi-addons-style-create-modal").modal("show");
    });
    jQuery(".shortcode-addons-addons-data-clone").on("click", function () {
        $("#oxi-addons-style-modal-form")[0].reset();
        var dataid = jQuery(this).val();
        jQuery('#oxistyleid').val(dataid);
        jQuery("#oxi-addons-style-create-modal").modal("show");
    });

    jQuery("#oxi-addons-style-modal-form").submit(function (e) {
        e.preventDefault();
        var rawdata = JSON.stringify($(this).serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_create";
        $('.modal-footer').prepend('<span class="spinner sa-spinner-open-left"></span>');
        ShortCodeAddonsRestApi(functionname, rawdata, styleid, childid, function (callback) {
            setTimeout(function () {
                document.location.href = callback;
            }, 1000);
        });
    });


    $(".shortcode-addons-addons-data-delete").on("click", function (e) {
        e.preventDefault();
        var $This = $(this);
        var rawdata = 'deleting';
        var styleid = $This.val();


        var functionname = 'shortcode_delete';
        $(this).append('<span class="spinner sa-spinner-open"></span>');
        ShortCodeAddonsRestApi(functionname, rawdata, styleid, childid, function (callback) {
            console.log(callback);
            setTimeout(function () {
                if (callback === 'done') {
                    $This.parents('tr').remove();
                }
            }, 1000);
        }
        );
    });



    setTimeout(function () {
        if (jQuery(".table").hasClass("oxi_addons_table_data")) {
            jQuery(".oxi_addons_table_data").DataTable({
                "aLengthMenu": [[7, 25, 50, -1], [7, 25, 50, "All"]],
                "initComplete": function (settings, json) {
                    jQuery(".oxi-addons-row.table-responsive").css("opacity", "1").animate({height: jQuery(".oxi-addons-row.table-responsive").get(0).scrollHeight}, 1000);
                    ;
                }
            });
        }
    }, 500);


})(jQuery)