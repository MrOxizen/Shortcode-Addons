jQuery.noConflict();
(function ($) {


    var urlParams = new URLSearchParams(window.location.search);
    var styleid = urlParams.get("styleid");
    var childid = "";
    var rawdata = "";
    var WRAPPER = $('#oxi-addons-preview-data').attr('template-wrapper');

    function NEWRegExp(par = '') {
        return new RegExp(par, "g");
    }

    function replaceStr(str, find, replace) {
        for (var i = 0; i < find.length; i++) {
            str = str.replace(new RegExp(find[i], 'gi'), replace[i]);
        }
        return str;
    }


    async function ShortcodeAddonsTemplateSettings(functionname, rawdata, styleid, childid, callback) {
        if (functionname === "") {
            alert('Confirm Function Name');
            return false;
        }
        let result;
        try {
            result = await $.ajax({
                url: shortcode_addons_ultimate.ajaxurl,
                method: 'POST',
                data: {
                    action: 'shortcode_addons_ultimate',
                    _wpnonce: shortcode_addons_ultimate.nonce,
                    functionname: functionname,
                    styleid: styleid,
                    childid: childid,
                    rawdata: rawdata
                }
            });
            if (result) {
                try {
                    console.log(JSON.parse(result));
                    return callback(JSON.parse(result));
                } catch (e) {
                    console.log(result);
                    return callback(result)
                }
            }
        } catch (error) {
            console.error(error);
        }
    }


    var WRAPPER = $('#oxi-addons-preview-data').attr('template-wrapper');
    $(".oxi-addons-tabs-ul li:first").addClass("active");
    $(".oxi-addons-tabs-content-tabs:first").addClass("active");
    $(".oxi-addons-tabs-ul li").click(function () {
        if ($(this).hasClass('active')) {
            $(".oxi-addons-tabs-ul li").removeClass("active");
            var activeTab = $(this).attr("ref");
            $(activeTab).removeClass("active");
        } else {
            $(".oxi-addons-tabs-ul li").removeClass("active");
            $(this).toggleClass("active");
            $(".oxi-addons-tabs-content-tabs").removeClass("active");
            var activeTab = $(this).attr("ref");
            $(activeTab).addClass("active");
        }
    });
    $("#oxi-addons-setting-reload").click(function () {
        location.reload();
    });
    $(".oxi-head").click(function () {
        var self = $(this).parent();
        self.toggleClass("oxi-admin-head-d-none");
    });
    $(".shortcode-addons-templates-right-panel-heading").click(function () {
        var self = $(this).parent();
        self.toggleClass("oxi-admin-head-d-none");
    });
    $("#oxi-addons-form-submit").submit(function (e) {
        e.preventDefault();
        return false;
    });
    $("#shortcode-addons-name-change-submit").submit(function (e) {
        e.preventDefault();
        return false;
    });
    $("#shortcode-addons-name-change-submit").submit(function (e) {
        e.preventDefault();
        return false;
    });
    $("#shortcode-addons-template-modal-form").submit(function (e) {
        e.preventDefault();
        return false;
    });
    $('.shortcode-control-type-select .shortcode-addons-select-input').each(function (e) {
        if (!$(this).parents('.shortcode-addons-form-repeater-store').length) {
            $(this).select2({width: '100%'});
        }
    });
    $('.shortcode-form-control').each(function (e) {
        if ($(this).hasClass('shortcode-addons-form-responsive-tab')) {
            $(this).addClass('shortcode-addons-responsive-display-none');
        } else if ($(this).hasClass('shortcode-addons-form-responsive-mobile')) {
            $(this).addClass('shortcode-addons-responsive-display-none');
        }
    });
    $(document.body).on("click", ".shortcode-form-responsive-switcher-desktop", function () {
        $("#oxi-addons-form-submit").toggleClass('shortcode-responsive-switchers-open');
        $(".shortcode-form-responsive-switcher-tablet").removeClass('active');
        $(".shortcode-form-responsive-switcher-mobile").removeClass('active');
        $(".shortcode-addons-form-responsive-laptop").removeClass('shortcode-addons-responsive-display-none');
        $(".shortcode-addons-form-responsive-tab").addClass('shortcode-addons-responsive-display-none');
        $(".shortcode-addons-form-responsive-mobile").addClass('shortcode-addons-responsive-display-none');
    });
    $(document.body).on("click", ".shortcode-form-responsive-switcher-tablet", function () {
        $(".shortcode-form-responsive-switcher-tablet").addClass('active');
        $(".shortcode-form-responsive-switcher-mobile").removeClass('active');
        $(".shortcode-addons-form-responsive-laptop").addClass('shortcode-addons-responsive-display-none');
        $(".shortcode-addons-form-responsive-tab").removeClass('shortcode-addons-responsive-display-none');
        $(".shortcode-addons-form-responsive-mobile").addClass('shortcode-addons-responsive-display-none');
    });
    $(document.body).on("click", ".shortcode-form-responsive-switcher-mobile", function () {
        $(".shortcode-form-responsive-switcher-tablet").removeClass('active');
        $(".shortcode-form-responsive-switcher-mobile").addClass('active');
        $(".shortcode-addons-form-responsive-laptop").addClass('shortcode-addons-responsive-display-none');
        $(".shortcode-addons-form-responsive-tab").addClass('shortcode-addons-responsive-display-none');
        $(".shortcode-addons-form-responsive-mobile").removeClass('shortcode-addons-responsive-display-none');
    });
    $.fn.uncheckableRadio = function () {
        var $root = this;
        $root.each(function () {
            var $radio = $(this);
            if ($radio.prop('checked')) {
                $radio.data('checked', true);
            } else {
                $radio.data('checked', false);
            }

            $radio.click(function () {
                var $this = $(this);
                if ($this.data('checked')) {
                    $this.prop('checked', false);
                    $this.data('checked', false);
                    $this.trigger('change');
                } else {
                    $this.data('checked', true);
                    $this.closest('form').find('[name="' + $this.prop('name') + '"]').not($this).data('checked', false);
                }
            });
        });
        return $root;
    };
    $('.shortcode-addons-form-toggle [type=radio]').uncheckableRadio();

    function PopoverActiveDeactive($_This) {
        $(".shortcode-form-control").not($_This.parents()).removeClass('popover-active');
        $_This.closest(".shortcode-form-control").toggleClass('popover-active');
        event.stopPropagation();
    }

    $(document.body).on("click", ".shortcode-form-control-content-popover .shortcode-form-control-input-wrapper", function (event) {
        PopoverActiveDeactive($(this));
    });
    $(document.body).on("click", ".shortcode-form-control-input-link", function (event) {
        PopoverActiveDeactive($(this));
        event.stopPropagation();
    });


    $('.shortcode-control-type-control-tabs .shortcode-control-type-control-tab-child:first-child').addClass('shortcode-control-tab-active');
    $('.shortcode-control-type-control-tabs .shortcode-form-control-tabs-content:first-child').removeClass('shortcode-control-tab-close');
    $(document.body).on("click", ".shortcode-control-type-control-tab-child", function () {
        $(this).siblings().removeClass("shortcode-control-tab-active");
        $(this).addClass('shortcode-control-tab-active');
        var index = $(this).index();
        $(this).parent().parent('.shortcode-form-control-content-tabs').next().children('.shortcode-form-control-tabs-content').addClass('shortcode-control-tab-close');
        $(this).parent().parent('.shortcode-form-control-content-tabs').next().children('.shortcode-form-control-tabs-content:eq(' + index + ')').removeClass('shortcode-control-tab-close');
        console.log(index);
    });

    (function ($) {
        setTimeout(function () {
            var data = 'body#tinymce.wp-editor{font-family:Arial,Helvetica,sans-serif!important}body#tinymce.wp-editor p{font-size:14px!important}body#tinymce.wp-editor h1{font-size:1.475em}body#tinymce.wp-editor h2{font-size:1.065em}body#tinymce.wp-editor h3{font-size:1.065em}body#tinymce.wp-editor h4{font-size:.9}body#tinymce.wp-editor h5{font-size:.75}body#tinymce.wp-editor h6{font-size:.65em}body#tinymce.wp-editor .gallery-caption,body#tinymce.wp-editor figcaption{font-size:.65em}body#tinymce.wp-editor .editor-post-title__block .editor-post-title__input{font-size:1.77em}body#tinymce.wp-editor .editor-default-block-appender .editor-default-block-appender__content{font-size:14px}body#tinymce.wp-editor .wp-block-paragraph.has-drop-cap:not(:focus)::first-letter{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",sans-serif;font-size:2.12em;margin:0 .15em 0 0}.wp-block-cover .wp-block-cover-text,.wp-block-cover h2{font-size:1.07em;padding-left:.7rem;padding-right:.7rem}.wp-block-gallery .blocks-gallery-image figcaption,.wp-block-gallery .blocks-gallery-item figcaption,.wp-block-gallery .gallery-item .gallery-caption{font-size:.65em}.wp-block-button .wp-block-button__link{line-height:1.8;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",sans-serif;font-size:.7em;font-weight:700}.wp-block-quote.is-large p,.wp-block-quote.is-style-large p{font-size:1.07em;margin-bottom:.4em;margin-top:.4em}.wp-block-quote .wp-block-quote__citation,.wp-block-quote cite,.wp-block-quote footer{font-size:.65em}.wp-block[data-type="core/pullquote"] blockquote>.block-library-pullquote__content .editor-rich-text__tinymce[data-is-empty=true]::before,.wp-block[data-type="core/pullquote"] blockquote>.editor-rich-text p,.wp-block[data-type="core/pullquote"] p,.wp-block[data-type="core/pullquote"][data-align=left] blockquote>.block-library-pullquote__content .editor-rich-text__tinymce[data-is-empty=true]::before,.wp-block[data-type="core/pullquote"][data-align=left] blockquote>.editor-rich-text p,.wp-block[data-type="core/pullquote"][data-align=left] p,.wp-block[data-type="core/pullquote"][data-align=right] blockquote>.block-library-pullquote__content .editor-rich-text__tinymce[data-is-empty=true]::before,.wp-block[data-type="core/pullquote"][data-align=right] blockquote>.editor-rich-text p,.wp-block[data-type="core/pullquote"][data-align=right] p{font-size:1.067em;margin-bottom:.4em;margin-top:.4em}.wp-block[data-type="core/pullquote"] .wp-block-pullquote__citation,.wp-block[data-type="core/pullquote"][data-align=left] .wp-block-pullquote__citation,.wp-block[data-type="core/pullquote"][data-align=right] .wp-block-pullquote__citation{font-size:.65em}.wp-block-file .wp-block-file__button{font-size:.75em}.wp-block-separator.is-style-dots:before{font-size:1.067em;letter-spacing:calc(2 * .63rem);padding-left:calc(2 * .63rem)}.wp-block-categories li,.wp-block-latest-posts li,ul.wp-block-archives li{font-size:calc(14px * 1.125);padding-bottom:.6rem}';
            var head = $(".mce-container-body iframe").contents().find("head");
            var css = '<style type="text/css">' + data + '</style>';
            $(head).append(css);
        }, 2000);
    })($);

    $("#oxi-addons-list-data-modal-open").on("click", function () {
        $("#oxi-addons-list-data-modal").modal("show");
        $('#shortcode-addons-template-modal-form').trigger("reset");
        $('.modal-body .shortcode-form-repeater-fields-wrapper').html('');
        $('.modal-body .shortcode-control-type-repeater .shortcode-control-type-hidden input').val(0);
        $('#shortcode-addons-template-modal-form *:checkbox').each(function (i, e) {
            if ($(this).attr('ckdflt') === 'true') {
                $(this).attr('checked', true);
            } else {
                $(this).attr('checked', false);
            }
        });
        $('#shortcode-addons-template-modal-form *:radio').each(function (i, e) {
            if ($(this).attr('ckdflt') === 'true') {
                $(this).attr('checked', true);
            } else {
                $(this).attr('checked', false);
            }
        });
        $('#shortcode-addons-template-modal-form .shortcode-addons-media-control-image-load').each(function (i, e) {
            $(this).attr('style', $(this).attr('ckdflt'));
        });
        $('#shortcodeitemid').val("");
        $("[data-condition]").each(function (index, value) {
            $(this).addClass('shortcode-addons-form-conditionize');
        });

        $('.shortcode-addons-form-conditionize').conditionize();
    });
    $("[data-condition]").each(function (index, value) {
        $(this).addClass('shortcode-addons-form-conditionize');
    });

    jQuery(".OXIAddonsElementsDeleteSubmit").submit(function () {
        var status = confirm("Do you Want to Deactive this Elements?");
        if (status == false) {
            return false;
        } else {
            return true;
        }
    });
    jQuery(".oxi-addons-style-delete .btn.btn-danger").on("click", function () {
        var status = confirm("Do you want to Delete this Shortcode? Before delete kindly confirm that you don't use or already replaced this Shortcode. If deleted will never Restored.");
        if (status == false) {
            return false;
        } else {
            return true;
        }
    });
    jQuery(".btn btn-warning.oxi-addons-addons-style-btn-warning").on("click", function () {
        var status = confirm("Do you Want to Deactive This Layouts?");
        if (status == false) {
            return false;
        } else {
            return true;
        }
    });

    function oxiequalHeight(group) {
        tallest = 0;
        group.each(function () {
            thisHeight = jQuery(this).height();
            if (thisHeight > tallest) {
                tallest = thisHeight;
            }
        });
        group.height(tallest);
    }

    setTimeout(function () {
        oxiequalHeight(jQuery(".oxiequalHeight"));
    }, 500);


    setTimeout(function () {
        jQuery("<style type='text/css'>.oxi-addons-style-left-preview{background: " + jQuery("#shortcode-addons-2-0-preview").val() + "; } </style>").appendTo(".oxi-addons-style-left-preview");
    }, 500);

    oxiequalHeight(jQuery(".oxiaddonsoxiequalHeight"));

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

    jQuery("#shortcode-addons-2-0-color").on("change", function (e) {
        $input = jQuery(this);
        jQuery("<style type='text/css'>.oxi-addons-style-left-preview{background: " + $input.val() + "; } </style>").appendTo(".oxi-addons-style-left-preview");
        jQuery('#shortcode-addons-2-0-preview').val($input.val());
    });


    function ShortcodeAddonsPreviewDataLoader() {
        ShortcodeAddonsTemplateSettings('elements_template_render_data',
            JSON.stringify($("#oxi-addons-form-submit").serializeJSON({checkboxUncheckedValue: "0"})),
            styleid, childid, function (callback) {
                $("#oxi-addons-preview-data").html(callback);
            });
    }

    function ShortcodeAddonsModalConfirm(id, data) {
        if (($("#OXIAADDONSCHANGEDPOPUP").data("bs.modal") || {})._isShown) {
            setTimeout(function () {
                $("#OXIAADDONSCHANGEDPOPUP").modal("hide");
            }, 3000);
            $(id).html(data);
        }
        ShortcodeAddonsPreviewDataLoader();
    }

    $("#addonsstylenamechange").on("click", function (e) {
        e.preventDefault();
        var rawdata = JSON.stringify($("#shortcode-addons-name-change-submit").serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_change_name";
        $(this).html('<span class="dashicons dashicons-admin-generic"></span>');
        ShortcodeAddonsTemplateSettings(functionname, rawdata, styleid, childid, function (callback) {
            if (callback === "success") {
                $("#OXIAADDONSCHANGEDPOPUP .icon-box").html('<span class="dashicons dashicons-yes"></span>');
                $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center h4").html("Superb!");
                $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center p").html("Shortcode name has been saved successfully.");
                $("#OXIAADDONSCHANGEDPOPUP").modal("show");
                ShortcodeAddonsModalConfirm("#addonsstylenamechange", "Save")
            }
        });
    });
    $("#oxi-addons-setting-old-version").on("click", function (e) {
        e.preventDefault();
        var status = confirm("Do you Want to use Old Version?  As using Old version You can Only View it not any editings or customization also New version data will be deleted during update old version.");
        if (status == false) {
            return false;
        }
        var rawdata = 'old_version';
        var functionname = "elements_template_old_version";
        var styleid = $('#shortcode-addons-elements-id').val();

        $(this).html('<span class="dashicons dashicons-admin-generic"></span>');
        ShortcodeAddonsTemplateSettings(functionname, rawdata, styleid, childid, function (callback) {
            console.log(callback);
            if (callback === "success") {
                location.reload();
            }
        });
    });
    $("#shortcode-addons-templates-submit").on("click", function (e) {
        e.preventDefault();
        var rawdata = JSON.stringify($("#oxi-addons-form-submit").serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_style_data";
        $(this).html('<span class="dashicons dashicons-admin-generic"></span>');
        ShortcodeAddonsTemplateSettings(functionname, rawdata, styleid, childid, function (callback) {
            $("#shortcode-addons-templates-submit").html(callback);
            $("#OXIAADDONSCHANGEDPOPUP .icon-box").html('<span class="dashicons dashicons-yes"></span>');
            $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center h4").html("Great!");
            $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center p").html("Layouts data has been saved successfully.");
            $("#OXIAADDONSCHANGEDPOPUP").modal("show");
            ShortcodeAddonsModalConfirm("#shortcode-addons-templates-submit", "Save");

        });
    });
    $("#shortcode-template-modal-submit").on("click", function (e) {
        e.preventDefault();
        var rawdata = JSON.stringify($("#shortcode-addons-template-modal-form").serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_modal_data";
        var childid = $("#shortcodeitemid").val();
        $(this).html('<span class="dashicons dashicons-admin-generic"></span>');
        ShortcodeAddonsTemplateSettings(functionname, rawdata, styleid, childid, function (callback) {
            $("#oxi-addons-list-data-modal").modal("hide");
            $("#OXIAADDONSCHANGEDPOPUP .icon-box").html('<span class="dashicons dashicons-yes"></span>');
            $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center h4").html("Great!");
            $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center p").html("Data has been saved successfully.");
            $("#OXIAADDONSCHANGEDPOPUP").modal("show");
            ShortcodeAddonsModalConfirm("#shortcode-template-modal-submit", "Submit");
            ShortcodeAddonsPreviewDataLoader();
        });
    });

    function RepeaterTitle() {
        $(".shortcode-form-repeater-controls-title").each(function (index, value) {
            $patent = $(this).parents('.shortcode-form-repeater-fields');
            $t = $patent.attr('tab-title');
            $value = $patent.find("input").filter('[id$=\'' + $t + '\']').val();
            $(this).html($value);
        });
    }

    function ShortCodeMultipleSelector_Handler($value) {
        return $value.replace(/{{[0-9a-zA-Z.?:_-]+}}/g, function (match, contents, offset, input_string) {
            var m = match.replace(/{{/g, "").replace(/}}/g, "");
            var arr = m.split('.');
            if (m.indexOf("SIZE") != -1) {
                m = m.replace(/.SIZE/g, $("#" + arr[0] + '-size').val());
            }
            if (m.indexOf("UNIT") != -1) {
                m = m.replace(/.UNIT/g, $("#" + arr[0] + '-choices').val());
            }
            if (m.indexOf("CHOOSE") != -1) {
                m = m.replace(/.CHOOSE/g, $("input[name=" + arr[0] + "]").val());
            }
            if (m.indexOf("VALUE") != -1) {
                m = m.replace(/.VALUE/g, $("#" + arr[0]).val());
            }
            m = m.replace(NEWRegExp(arr[0]), '');
            return m;
        });
    }

    $("body").on("click", ".shortcode-addons-template-item-edit", function (e) {
        e.preventDefault();
        $('#shortcode-addons-template-modal-form')[0].reset();
        $('.modal-body .shortcode-form-repeater-fields-wrapper').html('');
        $('.modal-body .shortcode-control-type-repeater .shortcode-control-type-hidden input').val(0);
        var rawdata = "edit";
        var functionname = "elements_template_modal_data_edit";
        var childid = $(this).attr("value");
        ShortcodeAddonsTemplateSettings(functionname, rawdata, styleid, childid, function (callback) {
            if (callback === "Go to hell") {
                alert("Data Error");
            } else {
                jQuery("#shortcode-addons-template-modal-form input[type='checkbox']").attr('checked', false);
                $.each($.parseJSON(callback), function (key, value) {
                    if (typeof value === 'object') {
                        $.each(value, function (a, b) {
                            var $data = $('#repeater-' + key + '-initial-data').html();
                            $data = $data.replace(NEWRegExp("repidrep"), a);
                            $($('#' + key).children().find('.shortcode-form-repeater-fields-wrapper')).append($data);
                            $Current = $('#' + key).children().find('.shortcode-form-repeater-fields-wrapper').last();
                            $.each(b, function (c, d) {
                                $Input = $Current.find("input, select, textarea").filter('[name$=\'' + a + 'saarsa' + c + '\']');
                                var tp = $Input.attr("type");
                                if (typeof tp !== 'undefined') {
                                    if (tp === 'radio') {
                                        $Input.val([d]);
                                    } else if (tp === 'checkbox') {
                                        if (d != '0') {
                                            $Input.attr('checked', 'true');
                                        } else {
                                            $Input.prop('checked', false).removeAttr('checked');
                                        }
                                    } else if (tp === 'hidden') {
                                        $Input.val(d);
                                        $Input.siblings('.shortcode-addons-media-control').children('.shortcode-addons-media-control-image-load').css({'background-image': 'url(' + d + ')'});
                                    } else {
                                        $Input.val(d);
                                    }
                                } else {
                                    $Input.val(d);
                                }
                            });
                            RunDefault($Input.parents('.shortcode-form-repeater-fields'));
                            $($('#' + key).children().find('.shortcode-form-repeater-fields-wrapper')).trigger('sortupdate');
                        });
                        RepeaterTitle();
                    } else {
                        var tp = $('input[name="' + key + '"]').attr("type");
                        if (typeof tp !== 'undefined') {
                            if (tp === 'radio') {
                                $('input[name=' + key + ']').val([value]);
                            } else if (tp === 'checkbox') {
                                if (value != '0') {
                                    $('input[name=' + key + ']').attr('checked', 'true');
                                } else {
                                    $('input[name=' + key + ']').prop('checked', false).removeAttr('checked');
                                }
                            } else if (tp === 'hidden') {
                                $('input[name=' + key + ']').val(value);
                                if (!key.includes("image-alt")) {

                                    $('input[name=' + key + ']').siblings('.shortcode-addons-media-control').children('.shortcode-addons-media-control-image-load').css({'background-image': 'url(' + value + ')'});
                                }
                                console.log(key.includes("image-alt"));

                            } else {
                                $("#" + key).val(value);
                            }
                        } else {
                            $("#" + key).val(value);
                        }
                    }

                });
                $("[data-condition]").each(function (index, value) {
                    $(this).addClass('shortcode-addons-form-conditionize');
                });
                $('.shortcode-addons-form-conditionize').conditionize();
                $('.shortcode-control-type-select .shortcode-addons-select-input').each(function (e) {
                    $id = $(this).attr('id');
                    $('#' + $id).select2({width: '100%'});
                });
                $("#oxi-addons-list-data-modal").modal("show");
            }
        });
    });
    $("body").on("click", ".shortcode-addons-template-item-delete", function (e) {
        e.preventDefault();
        var rawdata = "delete";
        var functionname = "elements_template_modal_data_delete";
        var childid = $(this).attr("value");
        var status = confirm("Do you Want to Delete this?");
        if (status === false) {
            return false;
        } else {
            ShortcodeAddonsTemplateSettings(functionname, rawdata, styleid, childid, function (callback) {
                if (callback === "done") {
                    $("#OXIAADDONSCHANGEDPOPUP .icon-box").html('<span class="dashicons dashicons-trash"></span>');
                    $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center h4").html("Deleted :(");
                    $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center p").html("Your data has been delete successfully.");
                    $("#OXIAADDONSCHANGEDPOPUP").modal("show");
                    ShortcodeAddonsModalConfirm(".shortcode-addons-template-item-delete", "Delete")
                    ShortcodeAddonsPreviewDataLoader();
                } else {
                    alert("Data Error")
                }
            });
        }
    });
    $(document.body).on("keyup", ".shortcode-control-type-text input", function (e) {
        $input = $(this);
        if ($input.attr("retundata") !== '') {
            e.preventDefault();
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (el, obj) {
                if (el.indexOf('{{KEY}}') != -1) {
                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                $(cls).html($input.val());
            });
        }
    });
    $(document.body).on("keyup", ".shortcode-control-type-textarea textarea", function (e) {
        $input = $(this);
        if ($input.attr("retundata") !== '') {
            e.preventDefault();
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (el, obj) {
                if (el.indexOf('{{KEY}}') != -1) {
                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                $(cls).html($input.val());
            });
        }
    });
    setTimeout(function () {
        $parent = $(".shortcode-control-type-wysiwyg textarea");
        if ($(".shortcode-form-control").hasClass("shortcode-control-type-wysiwyg")) {
            if (!$($parent).is(':visible') && !$($parent).parents('.shortcode-addons-form-repeater-store')) {
                tinyMCE.activeEditor.on('keyup', function (e) {
                    // Get the editor content (html)
                    get_ed_content = tinyMCE.activeEditor.getContent();
                    // Do stuff here... (run do_stuff_here() function)
                    SA_wysiwyg_Content(get_ed_content);
                });
            } else {
                $($parent).on('keyup', function (e) {
                    // Get the editor content (html)
                    get_ed_content = $(this).val();
                    // Do stuff here... (run do_stuff_here() function)
                    SA_wysiwyg_Content(get_ed_content);
                });
            }
        }

        function SA_wysiwyg_Content(content) {
            $input = $(".shortcode-control-type-wysiwyg .shortcode-form-control-input-wrapper");
            if ($input.attr("retundata") !== '') {
                var $data = JSON.parse($input.attr("retundata"));
                $.each($data, function (el, obj) {
                    if (el.indexOf('{{KEY}}') != -1) {
                        el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                    }
                    var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    $(cls).html(content);
                });
            }
        }

    }, 1000);
    $(".shortcode-control-type-number input").on("keyup", function () {
        $input = $(this);
        if ($input.attr("retundata") !== '') {
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (el, obj) {
                if (el.indexOf('{{KEY}}') != -1) {
                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                var Cval = obj.replace(NEWRegExp("{{VALUE}}"), $input.val());
                if (Cval.indexOf("{{") != -1) {
                    Cval = ShortCodeMultipleSelector_Handler(Cval);
                }
                if ($input.attr('responsive') === 'tab') {
                    $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                } else if ($input.attr('responsive') === 'mobile') {
                    $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                } else {
                    $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                }
            });
            if ($input.val() === '') {
                ShortcodeAddonsPreviewDataLoader();
            }
        }
    });
    $(".shortcode-control-type-select select").on("change", function (e) {
        $input = $(this);
        if ($input.attr("retundata") !== '') {
            id = $(this).attr('id');
            var arr = [];
            jQuery("#" + id + " option").each(function () {
                arr.push($(this).val());
            });
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (key, obj) {
                if (key.indexOf('{{KEY}}') != -1) {
                    key = key.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                $.each(obj, function (k, o) {
                    var cls = key.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    if (o.type === 'CSS') {
                        var Cval = o.value.replace(NEWRegExp("{{VALUE}}"), $input.val());
                        if (Cval.indexOf("{{") != -1) {
                            Cval = ShortCodeMultipleSelector_Handler(Cval);
                        }
                        if ($input.attr('responsive') === 'tab') {
                            $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                        } else if ($input.attr('responsive') === 'mobile') {
                            $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                        } else {
                            $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                        }
                    } else {
                        $.each(arr, function (i, v) {
                            $(cls).removeClass(v);
                        });
                        $(cls).addClass($input.val());
                    }

                });
            });
            if ($input.val() === '') {
                ShortcodeAddonsPreviewDataLoader();
            }
        }

    });
    $(document.body).on("click", ".shortcode-control-type-choose input", function (e) {
        name = $(this).attr('name');
        $value = $(this).val();
        if ($(this).parent('.shortcode-form-choices').attr("retundata") !== '') {
            var arr = [];
            $("input[name=" + name + "]").each(function () {
                arr.push($(this).val());
            });
            $input = $(this).parent('.shortcode-form-choices');
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (key, obj) {
                if (key.indexOf('{{KEY}}') != -1) {
                    key = key.replace(NEWRegExp("{{KEY}}"), name.split('saarsa')[1]);
                }
                $.each(obj, function (k, o) {
                    var cls = key.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    if (o.type === 'CSS') {
                        var Cval = o.value.replace(NEWRegExp("{{VALUE}}"), $value);
                        if (Cval.indexOf("{{") != -1) {
                            Cval = ShortCodeMultipleSelector_Handler(Cval);
                        }
                        if ($input.attr('responsive') === 'tab') {
                            $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                        } else if ($input.attr('responsive') === 'mobile') {
                            $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                        } else {
                            $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                        }
                    } else {
                        $.each(arr, function (i, v) {
                            $(cls).removeClass(v);
                        });
                        $(cls).addClass($value);
                    }
                });
            });
            if ($(this).val() === '') {
                ShortcodeAddonsPreviewDataLoader();
            }
        }

    });
    jQuery('.oxi-addons-minicolor').each(function () {
        jQuery(this).minicolors({
            control: jQuery(this).attr('data-control') || 'hue',
            defaultValue: jQuery(this).attr('data-defaultValue') || '',
            format: jQuery(this).attr('data-format') || 'hex',
            keywords: jQuery(this).attr('data-keywords') || 'transparent' || 'initial' || 'inherit',
            inline: jQuery(this).attr('data-inline') === 'true',
            letterCase: jQuery(this).attr('data-letterCase') || 'lowercase',
            opacity: jQuery(this).attr('data-opacity'),
            position: jQuery(this).attr('data-position') || 'bottom left',
            swatches: jQuery(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
            change: function (value, opacity) {
                if (!value)
                    return;
                if (opacity)
                    value += ', ' + opacity;
                if (typeof console === 'object') {
                    // console.log(value);
                }
            },
            theme: 'bootstrap'
        });
    });
    $(".shortcode-control-type-color input").on("keyup, change", function () {
        $input = $(this);
        $custom = $input.attr("custom");
        if ($input.attr("retundata") !== '') {
            if ($custom === '') {
                var $data = JSON.parse($input.attr("retundata"));
                $.each($data, function (el, obj) {
                    if (el.indexOf('{{KEY}}') != -1) {
                        el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                    }
                    var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    var Cval = obj.replace(NEWRegExp("{{VALUE}}"), $input.val());
                    if (Cval.indexOf("{{") != -1) {
                        Cval = ShortCodeMultipleSelector_Handler(Cval);
                    }
                    if ($input.attr('responsive') === 'tab') {
                        $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                    } else if ($input.attr('responsive') === 'mobile') {
                        $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                    } else {
                        $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                    }
                });
                if ($input.val() === '') {
                    $input.siblings('.minicolors-swatch').children('.minicolors-swatch-color').css('background-color', 'transparent');
                    ShortcodeAddonsPreviewDataLoader();
                }
            } else {
                var $data = JSON.parse($input.attr("retundata"));
                $custom = $custom.split("|||||");
                $id = $custom[0];
                $VALUE = '';
                if ($custom[1] === 'text-shadow') {
                    $color = $('#' + $id + "-color").val();
                    $blur = $('#' + $id + "-blur-size").val();
                    $horizontal = $('#' + $id + "-horizontal-size").val();
                    $vertical = $('#' + $id + "-vertical-size").val();
                    $true = (($blur === '0' && $horizontal === '0' && $vertical === '0') || !$blur || !$horizontal || !$vertical) ? true : false;
                    if ($true === false) {
                        $VALUE = 'text-shadow: ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $color + ';';
                    } else {
                        ShortcodeAddonsPreviewDataLoader();
                    }
                } else if ($custom[1] === 'box-shadow') {
                    $type = $('input[name="' + $id + '-type"]:checked').val();
                    $color = $('#' + $id + "-color").val();
                    $blur = $('#' + $id + "-blur-size").val();
                    $spread = $('#' + $id + "-spread-size").val();
                    $horizontal = $('#' + $id + "-horizontal-size").val();
                    $vertical = $('#' + $id + "-vertical-size").val();
                    $true = (($blur === '0' && $spread === '0' && $horizontal === '0' && $vertical === '0') || !$blur || !$spread || !$horizontal || !$vertical) ? true : false;
                    if ($true === false) {
                        $VALUE = 'box-shadow: ' + $type + ' ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $spread + 'px ' + $color + ';';
                    } else {
                        ShortcodeAddonsPreviewDataLoader();
                    }
                }

                $.each($data, function (el, obj) {
                    if (el.indexOf('{{KEY}}') != -1) {
                        el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                    }
                    var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    var Cval = obj.replace(NEWRegExp("{{VALUE}}"), $VALUE);
                    if (Cval.indexOf("{{") != -1) {
                        Cval = ShortCodeMultipleSelector_Handler(Cval);
                    }
                    if ($input.attr('responsive') === 'tab') {
                        $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                    } else if ($input.attr('responsive') === 'mobile') {
                        $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                    } else {
                        $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                    }
                });
            }
        }
    });
    $(".shortcode-control-type-font input").on("change", function () {
        $input = $(this);
        if ($(this).attr("retundata") !== '') {
            var font = $input.val().replace(/\+/g, ' ');
            font = font.split(':');
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (el, obj) {
                if (el.indexOf('{{KEY}}') != -1) {
                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                var Cval = obj.replace(NEWRegExp("{{VALUE}}"), font[0]);
                if (Cval.indexOf("{{") != -1) {
                    Cval = ShortCodeMultipleSelector_Handler(Cval);
                }
                if ($input.attr('responsive') === 'tab') {
                    $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                } else if ($input.attr('responsive') === 'mobile') {
                    $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                } else {
                    $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                }
            });
        }
    });

//console.log(offset);

    function ShortCodeFormSliderINT(ID = '') {
        $this = $('.shortcode-form-slider');
        if (ID !== '') {
            $this = ID.find('.shortcode-form-slider');
        }
        $this.each(function (key, value) {
            var $This = $(this);
            if (!$This.parents('.shortcode-addons-form-repeater-store').length) {

                var $input = $This.siblings('.shortcode-form-slider-input').children('input');
                var step = parseFloat($(this).siblings('.shortcode-form-slider-input').children('input').attr('step'));
                var min = parseFloat($(this).siblings('.shortcode-form-slider-input').children('input').attr('min'));
                var max = parseFloat($(this).siblings('.shortcode-form-slider-input').children('input').attr('max'));
                if (step % 1 == 0) {
                    decimals = 0;
                } else if (step % 0.1 == 0) {
                    decimals = 1;
                } else if (step % 0.01 == 0) {
                    decimals = 2;
                } else {
                    decimals = 3;
                }
                noUiSlider.create(value, {
                    animate: true,
                    start: $input.val(),
                    step: step,
                    connect: 'lower',
                    range: {
                        'min': min,
                        'max': max
                    },
                    format: wNumb({
                        decimals: decimals
                    })
                });
                value.noUiSlider.on('slide', function (v) {
                    $input.val(v);
                    $custom = $input.attr("custom");
                    if ($input.attr("retundata") !== '') {
                        if ($custom === '') {
                            var $data = JSON.parse($input.attr("retundata"));
                            $.each($data, function (el, obj) {
                                if (el.indexOf('{{KEY}}') != -1) {
                                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                                }
                                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                                var Cval = obj.replace(NEWRegExp("{{SIZE}}"), v);
                                Cval = Cval.replace(NEWRegExp("{{UNIT}}"), $input.attr('unit'));
                                if (Cval.indexOf("{{") != -1) {
                                    Cval = ShortCodeMultipleSelector_Handler(Cval);
                                }
                                if ($input.attr('responsive') === 'tab') {
                                    $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                                } else if ($input.attr('responsive') === 'mobile') {
                                    $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                                } else {
                                    $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                                }
                            });
                        } else {
                            var $data = JSON.parse($input.attr("retundata"));
                            $custom = $custom.split("|||||");
                            $id = $custom[0];
                            $VALUE = '';
                            if ($custom[1] === 'text-shadow') {
                                $color = $('#' + $id + "-color").val();
                                $blur = $('#' + $id + "-blur-size").val();
                                $horizontal = $('#' + $id + "-horizontal-size").val();
                                $vertical = $('#' + $id + "-vertical-size").val();
                                $true = (($blur === '0' && $horizontal === '0' && $vertical === '0') || !$blur || !$horizontal || !$vertical) ? true : false;
                                if ($true === false) {
                                    $VALUE = 'text-shadow: ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $color + ';';
                                } else {
                                    ShortcodeAddonsPreviewDataLoader();
                                }
                            } else if ($custom[1] === 'box-shadow') {
                                $type = $('input[name="' + $id + '-type"]:checked').val();
                                $color = $('#' + $id + "-color").val();
                                $blur = $('#' + $id + "-blur-size").val();
                                $spread = $('#' + $id + "-spread-size").val();
                                $horizontal = $('#' + $id + "-horizontal-size").val();
                                $vertical = $('#' + $id + "-vertical-size").val();
                                $true = (($blur === '0' && $spread === '0' && $horizontal === '0' && $vertical === '0') || !$blur || !$spread || !$horizontal || !$vertical) ? true : false;
                                if ($true === false) {
                                    $VALUE = 'box-shadow: ' + $type + ' ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $spread + 'px ' + $color + ';';
                                } else {
                                    ShortcodeAddonsPreviewDataLoader();
                                }
                            }
                            $.each($data, function (el, obj) {
                                if (el.indexOf('{{KEY}}') != -1) {
                                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                                }
                                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                                var Cval = obj.replace(NEWRegExp("{{VALUE}}"), $VALUE);
                                if (Cval.indexOf("{{") != -1) {
                                    Cval = ShortCodeMultipleSelector_Handler(Cval);
                                }
                                if ($input.attr('responsive') === 'tab') {
                                    $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                                } else if ($input.attr('responsive') === 'mobile') {
                                    $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                                } else {
                                    $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                                }
                            });
                        }
                    }
                });
            }
        });
    }

    ShortCodeFormSliderINT();
    $(".shortcode-form-slider-input input").on("keyup", function () {
        $input = $(this);
        $custom = $input.attr("custom");
        var html5Slider = $(this).parent().siblings('.shortcode-form-slider');
        html5Slider = html5Slider[0];
        var val = $(this).val();
        html5Slider.noUiSlider.set(val);
        if ($input.attr("retundata") !== '') {

            if ($custom === '') {
                var $data = JSON.parse($input.attr("retundata"));
                $.each($data, function (el, obj) {
                    if (el.indexOf('{{KEY}}') != -1) {
                        el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                    }
                    var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    var Cval = obj.replace(NEWRegExp("{{SIZE}}"), $input.val());
                    Cval = Cval.replace(NEWRegExp("{{UNIT}}"), $input.attr('unit'));
                    if (Cval.indexOf("{{") != -1) {
                        Cval = ShortCodeMultipleSelector_Handler(Cval);
                    }
                    if ($input.attr('responsive') === 'tab') {
                        $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                    } else if ($input.attr('responsive') === 'mobile') {
                        $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                    } else {
                        $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                    }
                });
            } else {
                var $data = JSON.parse($input.attr("retundata"));
                $custom = $custom.split("|||||");
                $id = $custom[0];
                $VALUE = '';
                if ($custom[1] === 'text-shadow') {
                    $color = $('#' + $id + "-color").val();
                    $blur = $('#' + $id + "-blur-size").val();
                    $horizontal = $('#' + $id + "-horizontal-size").val();
                    $vertical = $('#' + $id + "-vertical-size").val();
                    $true = (($blur === '0' && $horizontal === '0' && $vertical === '0') || !$blur || !$horizontal || !$vertical) ? true : false;
                    if ($true === false) {
                        $VALUE = 'text-shadow: ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $color + ';';
                    } else {
                        ShortcodeAddonsPreviewDataLoader();
                    }
                } else if ($custom[1] === 'box-shadow') {
                    $type = $('input[name="' + $id + '-type"]:checked').val();
                    $color = $('#' + $id + "-color").val();
                    $blur = $('#' + $id + "-blur-size").val();
                    $spread = $('#' + $id + "-spread-size").val();
                    $horizontal = $('#' + $id + "-horizontal-size").val();
                    $vertical = $('#' + $id + "-vertical-size").val();
                    $true = (($blur === '0' && $spread === '0' && $horizontal === '0' && $vertical === '0') || !$blur || !$spread || !$horizontal || !$vertical) ? true : false;
                    if ($true === false) {
                        $VALUE = 'box-shadow: ' + $type + ' ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $spread + 'px ' + $color + ';';
                    } else {
                        ShortcodeAddonsPreviewDataLoader();
                    }
                }
                $.each($data, function (el, obj) {
                    if (el.indexOf('{{KEY}}') != -1) {
                        el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                    }
                    var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    var Cval = obj.replace(NEWRegExp("{{VALUE}}"), $VALUE);
                    if (Cval.indexOf("{{") != -1) {
                        Cval = ShortCodeMultipleSelector_Handler(Cval);
                    }
                    if ($input.attr('responsive') === 'tab') {
                        $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                    } else if ($input.attr('responsive') === 'mobile') {
                        $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                    } else {
                        $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                    }
                });
            }
            if ($input.val() === '') {
                ShortcodeAddonsPreviewDataLoader();
            }
        }


    });
    $(".shortcode-control-type-slider .shortcode-form-units-choices-label").click(function () {
        var id = "#" + $(this).attr('for');
        var input = $(this).parent().siblings('.shortcode-form-control-input-wrapper').children('.shortcode-form-slider-input').children('input');
        input.attr('min', $(id).attr('min'));
        input.attr('max', $(id).attr('max'));
        input.attr('step', $(id).attr('step'));
        input.attr('unit', $(id).val());
        var step = parseFloat($(id).attr('step'));
        var min = parseFloat($(id).attr('min'));
        var max = parseFloat($(id).attr('max'));
        var start = input.attr('default-value');
        if (step % 1 == 0) {
            decimals = 0;
        } else if (step % 0.1 == 0) {
            decimals = 1;
        } else if (step % 0.01 == 0) {
            decimals = 2;
        } else {
            decimals = 3;
        }

        var html5Slider = $(this).parent().siblings('.shortcode-form-control-input-wrapper').children('.shortcode-form-slider');
        html5Slider = html5Slider[0];
        html5Slider.noUiSlider.updateOptions({
            start: start,
            step: step,
            range: {
                'min': min,
                'max': max
            },
            format: wNumb({
                decimals: decimals
            })
        });
    });
    $(".shortcode-form-link-dimensions").click(function () {
        $(this).toggleClass("link-dimensions-unlink");
    });
    $(".shortcode-control-type-dimensions .shortcode-form-units-choices-label").click(function () {
        var id = "#" + $(this).attr('for');
        var input = $(this).parent().siblings('.shortcode-form-control-input-wrapper').children('.shortcode-form-control-dimensions').children('li').children('input');
        input.attr('min', $(id).attr('min'));
        input.attr('max', $(id).attr('max'));
        input.attr('step', $(id).attr('step'));
        // console.log($(id).attr('step'));

    });
    $(".shortcode-control-type-dimensions input").on("input", function () {
        $this = $(this);
        if ($this.parent().siblings('.shortcode-form-control-dimension').children('.shortcode-form-link-dimensions').hasClass('link-dimensions-unlink')) {
            if ($this.val() === '') {
                $this.val(0);
            }
            $siblings = $this.parent().siblings('.shortcode-form-control-dimension').children('input');
            $.each($siblings, function (e, o) {
                if ($(this).val() === '') {
                    $(this).val(0);
                }
            });
        } else {
            var value = $this.val();
            $this.parent().siblings('.shortcode-form-control-dimension').children('input').val(value);
        }
        $input = $this;
        $InputID = $input.attr('input-id');
        UNIT = $InputID + '-choices';
        TOP = $InputID + '-top';
        RIGHT = $InputID + '-right';
        BOTTOM = $InputID + '-bottom';
        LEFT = $InputID + '-left';
        if ($input.attr("retundata") !== '' && $input.attr('type') !== 'radio') {
            //  console.log($this.val());
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (el, obj) {
                if (el.indexOf('{{KEY}}') != -1) {
                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                var Cval = obj.replace(NEWRegExp("{{UNIT}}"), jQuery("input[name=\"" + UNIT + "\"]:checked").val());
                Cval = Cval.replace(NEWRegExp("{{TOP}}"), $('#' + TOP).val());
                Cval = Cval.replace(NEWRegExp("{{RIGHT}}"), $('#' + RIGHT).val());
                Cval = Cval.replace(NEWRegExp("{{BOTTOM}}"), $('#' + BOTTOM).val());
                Cval = Cval.replace(NEWRegExp("{{LEFT}}"), $('#' + LEFT).val());
                if (Cval.indexOf("{{") != -1) {
                    Cval = ShortCodeMultipleSelector_Handler(Cval);
                }
                if ($input.attr('responsive') === 'tab') {
                    $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                } else if ($input.attr('responsive') === 'mobile') {
                    $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                } else {
                    $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                }
            });
            if ($input.val() === '') {
                ShortcodeAddonsPreviewDataLoader();
            }
        }

    });
    jQuery(".shortcode-addons-gradient-color").each(function (i, v) {
        $(this).coloringPick({
            "show_input": true,
            "theme": "dark",
            'destroy': false,
            change: function (val) {
                $data = [];
                var $This = $(this).children('input');
                // console.log($(this).children('input'));
                var _VALUE = $This.val();
                if ($This.attr('background') === '') {
                    var $data = ($This.attr("retundata") !== '' ? JSON.parse($This.attr("retundata")) : []);
                    $.each($data, function (el, obj) {
                        if (el.indexOf('{{KEY}}') != -1) {
                            el = el.replace(NEWRegExp("{{KEY}}"), $This.attr('name').split('saarsa')[1]);
                        }
                        var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                        var Cval = obj.replace(NEWRegExp("{{VALUE}}"), _VALUE);
                        if (Cval.indexOf("{{") != -1) {
                            Cval = ShortCodeMultipleSelector_Handler(Cval);
                        }
                        if ($This.attr('responsive') === 'tab') {
                            $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                        } else if ($This.attr('responsive') === 'mobile') {
                            $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                        } else {
                            $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                        }
                    });
                } else {
                    $id = $This.attr('background');
                    $imagecheck = $("#" + $id + "-img").is(":checked");
                    $imagesource = $('input[name="' + $id + '-select"]:checked').val();
                    $Image = ($imagecheck === true ? ($imagesource === 'media-library' ? $("#" + $id + "-image").val() : $("#" + $id + "-url").val()) : '');
                    var wordcount = val.split(/\b[\s,\.-:;]*/).length;
                    var limitWord = 23;
                    if ($Image === '') {
                        if (wordcount < limitWord) {
                            $BACKGROUND = 'background: ' + _VALUE + ';';
                        } else {
                            $BACKGROUND = ' background:-ms-' + _VALUE + '; background:-webkit-' + _VALUE + '; background:-moz-' + _VALUE + '; background:-o-' + _VALUE + '; background:' + _VALUE + ';';
                        }
                    } else {
                        if (wordcount < limitWord) {
                            $BACKGROUND = 'background:linear-gradient(0deg, ' + _VALUE + ' 0%, ' + _VALUE + ' 100%), url(\'' + $Image + '\') ' + $("#" + $id + "-repeat").val() + ' ' + $("#" + $id + "-position").val() + ';';
                        } else {
                            $BACKGROUND = 'background:' + _VALUE + ', url(\'' + $Image + '\' ) ' + $("#" + $id + "-repeat").val() + ' ' + $("#" + $id + "-position-lap").val() + ';';
                        }
                    }
                    var $data = ($This.attr("retundata") !== '' ? JSON.parse($This.attr("retundata")) : []);
                    $.each($data, function (el, obj) {
                        if (el.indexOf('{{KEY}}') != -1) {
                            el = el.replace(NEWRegExp("{{KEY}}"), $This.attr('name').split('saarsa')[1]);
                        }
                        var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                        if (cls.indexOf("{{") != -1) {
                            cls = ShortCodeMultipleSelector_Handler(cls);
                        }
                        Cval = $BACKGROUND;

                        if ($This.attr('responsive') === 'tab') {
                            $("#oxi-addons-preview-data").append('<style>@media only screen and (min-width : 669px) and (max-width : 993px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                        } else if ($This.attr('responsive') === 'mobile') {
                            $("#oxi-addons-preview-data").append('<style>@media only screen and (max-width : 668px){#oxi-addons-preview-data ' + cls + '{' + Cval + '}} < /style>');
                        } else {
                            $("#oxi-addons-preview-data").append('<style>#oxi-addons-preview-data ' + cls + '{' + Cval + '} < /style>');
                        }
                    });
                }
            },
            on_select: function (color) {
                ShortcodeAddonsPreviewDataLoader();
            }
        });
    });
    $('.oxi-admin-icon-selector').iconpicker();
    $('.shortcode-addons-form-conditionize').conditionize();


    $(document.body).on("change", ".shortcode-addons-control-loader  input", function () {
        ShortcodeAddonsPreviewDataLoader();
    });
    $(document.body).on("change", ".shortcode-addons-control-loader  select", function () {
        ShortcodeAddonsPreviewDataLoader();
    });
    $(document.body).on("keyup", ".shortcode-addons-control-loader input", function () {
        ShortcodeAddonsPreviewDataLoader();
    });
    $(document.body).on("keyup", ".shortcode-addons-control-loader textarea", function () {
        ShortcodeAddonsPreviewDataLoader();
    });
    $(document.body).on("keyup", ".shortcode-addons-control-loader wysiwyg", function () {
        ShortcodeAddonsPreviewDataLoader();
    });
    $(document.body).on("change", ".shortcode-control-type-icon input", function () {
        ShortcodeAddonsPreviewDataLoader();
    });

    function ShortcodeFormSlider(ID, type) {
        if (type === 'create') {
            ShortCodeFormSliderINT(ID);
        } else {
            ID.find('.shortcode-form-slider').each(function (key, value) {
                var $This = $(this);
                if ($This[0].noUiSlider) {
                    $This[0].noUiSlider.destroy();
                }
            });
        }
    }

    function RunDefault(ID) {
        ID.find('.oxi-admin-icon-selector').iconpicker();
        ID.find('.shortcode-addons-form-conditionize').conditionize();
        ID.find('.oxi-addons-minicolor').each(function () {
            $(this).minicolors('destroy').minicolors({
                control: jQuery(this).attr('data-control') || 'hue',
                defaultValue: jQuery(this).attr('data-defaultValue') || '',
                format: jQuery(this).attr('data-format') || 'hex',
                keywords: jQuery(this).attr('data-keywords') || 'transparent' || 'initial' || 'inherit',
                inline: jQuery(this).attr('data-inline') === 'true',
                letterCase: jQuery(this).attr('data-letterCase') || 'lowercase',
                opacity: jQuery(this).attr('data-opacity'),
                position: jQuery(this).attr('data-position') || 'bottom left',
                swatches: jQuery(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
                change: function (value, opacity) {
                    if (!value)
                        return;
                    if (opacity)
                        value += ', ' + opacity;
                    if (typeof console === 'object') {
                        // console.log(value);
                    }
                },
                theme: 'bootstrap'
            });
        });
        ID.find('textarea').each(function () {
            var $This = $(this);
            if ($This.hasClass('wp-editor-area')) {
                $id = $This.attr('id');
                wp.editor.remove($id);
                wp.editor.initialize($id, {
                    tinymce: {
                        wpautop: false,
                        force_br_newlines: true,
                        force_p_newlines: false,
                        theme: 'modern',
                        skin: 'lightgray',
                        language: 'en',
                        formats: {
                            alignleft: [
                                {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign: 'left'}},
                                {selector: 'img,table,dl.wp-caption', classes: 'alignleft'}
                            ],
                            aligncenter: [
                                {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign: 'center'}},
                                {selector: 'img,table,dl.wp-caption', classes: 'aligncenter'}
                            ],
                            alignright: [
                                {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign: 'right'}},
                                {selector: 'img,table,dl.wp-caption', classes: 'alignright'}
                            ],
                            strikethrough: {inline: 'del'}
                        },
                        relative_urls: false,
                        remove_script_host: false,
                        convert_urls: false,
                        browser_spellcheck: true,
                        fix_list_elements: true,
                        entities: '38,amp,60,lt,62,gt',
                        entity_encoding: 'raw',
                        keep_styles: false,
                        paste_webkit_styles: 'font-weight font-style color',
                        preview_styles: 'font-family font-size font-weight font-style text-decoration text-transform',
                        tabfocus_elements: ':prev,:next',
                        plugins: 'charmap,hr,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpview',
                        resize: 'vertical',
                        menubar: false,
                        indent: false,
                        toolbar1: 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv',
                        toolbar2: 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                        toolbar3: '',
                        toolbar4: '',
                        body_class: 'id post-type-post post-status-publish post-format-standard',
                        wpeditimage_disable_captions: false,
                        wpeditimage_html5_captions: true

                    },
                    quicktags: true,
                    mediaButtons: true

                })
                setTimeout(function () {
                    $('#' + $id).css({'display': ''});
                }, 1500);

            }
        });

//       shortcode-form-control-tag-area
    }

    if ($('div').hasClass('shortcode-form-repeater-fields-wrapper')) {
        $(".shortcode-form-repeater-fields-wrapper").sortable({
            axis: 'y',
            handle: ".shortcode-form-repeater-controls-title"
        });
    }
    if ($('div').hasClass('shortcode-form-rearrange-fields-wrapper')) {
        $(".shortcode-form-rearrange-fields-wrapper").sortable({
            axis: 'y',
            handle: ".shortcode-form-repeater-controls-title",
            update: function (event, ui) {
                var list_sortable = jQuery(this).sortable('toArray').toString();
                jQuery(jQuery(this).attr('vlid')).val(list_sortable);
            }
        });
    }
    $(document.body).on("click", ".shortcode-form-repeater-fields-wrapper .shortcode-form-repeater-controls-remove", function () {
        event.preventDefault();
        $(this).parents('.shortcode-form-repeater-fields').remove();
        $(this).parents('.shortcode-form-repeater-fields-wrapper').trigger('sortupdate');
        ShortcodeAddonsPreviewDataLoader();
    });
    $(document.body).on("click", ".shortcode-form-repeater-controls-title", function () {
        event.preventDefault();
        RepeaterTitle();
        $(this).parents('.shortcode-form-repeater-fields').toggleClass('shortcode-form-repeater-controls-open');
    });

    function childRecursive(element, func) {
        func(element);
        var children = element.children();
        if (children.length > 0) {
            children.each(function () {
                childRecursive($(this), func);
            });
        }
    }

    function getNewAttr(str, newNum, REP) {
        return str.replace(REP, 'saarsarep' + newNum);
    }

    function setCloneAttr(element, value, REP) {

        if (element.attr('id') !== undefined) {
            element.attr('id', getNewAttr(element.attr('id'), value, REP));
        }
        if (element.attr('name') !== undefined) {
            element.attr('name', getNewAttr(element.attr('name'), value, REP));
        }
        if (element.attr('for') !== undefined) {
            element.attr('for', getNewAttr(element.attr('for'), value, REP));
        }
        if (element.attr('data-condition') !== undefined) {
            element.attr('data-condition', getNewAttr(element.attr('data-condition'), value, REP));
        }
    }

    $(document.body).on("click", ".shortcode-form-repeater-button", function () {
        event.preventDefault();
        $This = $(this);
        $inputVAL = $This.parent().siblings('.shortcode-control-type-hidden').children().find('input').val(parseInt($This.parent().siblings('.shortcode-control-type-hidden').children().find('input').val()) + 1).val();
        var REP = 'saarsarepidrep';
        var newItem = $('#repeater-' + $This.attr('parent-id') + '-initial-data').children().clone();
        childRecursive(newItem, function (e) {
            setCloneAttr(e, $inputVAL, REP);
        });
        newItem.appendTo($This.parent().siblings('.shortcode-form-repeater-fields-wrapper'));
        $Current = $This.parent().siblings('.shortcode-form-repeater-fields-wrapper').children(':last');
        $Current.find('select').each(function () {
            if (this.type === 'select-one') {
                $(this).select2({width: '100%'})
            }
        });
        ShortcodeFormSlider($Current, 'create');
        ShortcodeAddonsPreviewDataLoader();
        RunDefault($Current);
    });

    $(document.body).on("click", ".shortcode-form-repeater-fields-wrapper .shortcode-form-repeater-controls-duplicate", function () {
        event.preventDefault();
        $patent = $(this).parents('.shortcode-form-repeater-fields');
        var REP = 'saarsa' + $patent.find('*').filter(':input').attr('name').split('saarsa')[1];
        $inputVAL = $patent.parent().siblings('.shortcode-control-type-hidden').children().find('input').val(parseInt($patent.parent().siblings('.shortcode-control-type-hidden').children().find('input').val()) + 1).val();
        $patent.find('select').each(function () {
            $('#' + this.id).select2('destroy');
        });
        ShortcodeFormSlider($patent, 'destroy');
        var newItem = $patent.clone();
        childRecursive(newItem, function (e) {
            setCloneAttr(e, $inputVAL, REP);
        });
        newItem.insertAfter($patent);
        $patent.find('select').each(function () {
            $('#' + this.id).select2({width: '100%'});
        });
        ShortcodeFormSlider($patent, 'create');
        $patent.next().find('select').each(function () {
            if (this.type === 'select-one') {
                $(this).select2({width: '100%'});
            }
        });
        ShortcodeFormSlider($patent.next(), 'create');
        $(this).parents('.shortcode-form-repeater-fields-wrapper').trigger('sortupdate');
        ShortcodeAddonsPreviewDataLoader();
        RunDefault($patent.next());
    });

    $c = $.cookie('shortcode-addons-admin-preview-mode');
    if ($c === '' || typeof $c === 'undefined' || $c === 'undefined') {
        $.cookie('shortcode-addons-admin-preview-mode', 'oxi-shortcode-admin-layouts-design-float');
        $c = 'shortcode-addons-preview-align';
    }
    if ($c === 'oxi-shortcode-admin-layouts-design-grid') {
        $('.wp-admin.toplevel_page_shortcode-addons').addClass('toplevel_page_shortcode-addons-design-grid');
    }

    $('.shortcode-addons-tabs-mode').addClass($c);

    $(document.body).on("click", ".oxi-addons-style-left-preview-heading .shortcode-control-type-choose input", function (e) {
        $value = $(this).val();
        $.cookie('shortcode-addons-admin-preview-mode', $value);
        $('.shortcode-addons-tabs-mode').removeClass('oxi-shortcode-admin-layouts-design-float').removeClass('oxi-shortcode-admin-layouts-design-grid').removeClass('oxi-shortcode-admin-layouts-design-flex');
        $('.shortcode-addons-tabs-mode').addClass($value);
        if ($value === 'oxi-shortcode-admin-layouts-design-grid') {
            $('.wp-admin.toplevel_page_shortcode-addons').addClass('toplevel_page_shortcode-addons-design-grid');
        } else {
            $('.wp-admin.toplevel_page_shortcode-addons').removeClass('toplevel_page_shortcode-addons-design-grid');
        }
    });
})(jQuery);