jQuery(document).ready(function () {
    jQuery(".oxi-addons-export-button").OximagnificPopup({
        type: "inline",
        preloader: true,
        mainClass: "oxi-addons-export-data-body",
    });
});

jQuery(".oxi-addons-export-button-copy").on("click", function () {
    var data = jQuery(this).attr('id');
    jQuery("#" + data).select();
    document.execCommand("copy");
    alert("Your Style Data Copied");
    jQuery.OximagnificPopup.close();
});