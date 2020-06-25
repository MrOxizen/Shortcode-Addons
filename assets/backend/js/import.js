jQuery.noConflict();
(function ($) {
    $("#oxi-addons-import-data-form .btn-danger").on("click", function (e) {
        e.preventDefault();
        $("#shortcode-addons-content").val('');
    });
    $(".custom-file-input").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    $("#oxi-addons-import-data-form").on("submit", function (e) {
        e.preventDefault();
        var rawdata = $("#shortcode-addons-content").val();
        $(this).children().find(".oxi-buttom").prepend('<span class="spinner sa-spinner-open-left"></span>');
        $(this)[0].reset();
        $.ajax({
            url: shortcode_addons_editor.ajaxurl,
            type: "post",
            data: {
                action: "shortcode_home_data",
                _wpnonce: shortcode_addons_editor.nonce,
                functionname: "elements_template_import",
                rawdata: rawdata,
                styleid: "",
                childid: ""
            },
            success: function (response) {
                console.log(response);
                setTimeout(function () {
                    document.location.href = response;
                }, 1000);
            }
        });
    });
})(jQuery)