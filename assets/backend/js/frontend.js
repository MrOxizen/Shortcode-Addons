jQuery.noConflict();
(function ($) {
    $(window).load(function () {
        $css = 'html { margin-top: 0px !important; }* html body { margin-top: 0px !important; }@media screen and ( max-width: 782px ) {html { margin-top: 0px !important; }* html body { margin-top: 0px !important; }}';
        jQuery("<style type='text/css'>" + $css + "</style>").appendTo(".shortcode-addons-template-body");
    });
})(jQuery);


