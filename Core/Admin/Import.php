<?php

namespace SHORTCODE_ADDONS\Core\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Import
 * @author biplob018
 */
class Import {

    use \SHORTCODE_ADDONS\Support\Validation;
    use \SHORTCODE_ADDONS\Support\JSS_CSS_LOADER;

    /**
     * Shortcode Addons Extension Constructor.
     *
     * @since 2.0.0
     */
    public function __construct() {
        do_action('shortcode-addons/before_init');
        $this->hooks();
    }

    /**
     * Shortcode Addons Extension hooks.
     *
     * @since 2.0.0
     */
    public function hooks() {
        $this->admin_elements_frontend_loader();
        $this->loader();
        $this->render();
    }
    /**
     * Shortcode Addons Extension js loader.
     *
     * @since 2.1.0
     */
    public function loader() {
        $js = 'jQuery.noConflict();
                (function ($) {
                    $("#oxi-addons-import-data-form").on("submit", function (e) {
                        e.preventDefault();
                        var rawdata = $("#shortcode-addons-content").val();
                        $(this).children().find(".oxi-buttom").prepend(\'<span class="spinner sa-spinner-open-left"></span>\');
                        $(this)[0].reset();
                        $.ajax({
                            url: "' . admin_url('admin-ajax.php') . '",
                            type: "post",
                            data: {
                                action: "shortcode_home_data",
                                _wpnonce: "' . wp_create_nonce('shortcode-addons-editor') . '",
                                functionname: "elements_template_import",
                                rawdata: rawdata,
                                styleid: "",
                                childid: "",
                            },
                            success: function (response) {
                                console.log(response);
                                setTimeout(function () {
                                    document.location.href = response;
                                }, 1000);
                            }
                        });
                    });
                })(jQuery)';
        wp_add_inline_script('shortcode-addons-vendor', $js);
    }
    /**
     * Shortcode Addons Extension render.
     *
     * @since 2.1.0
     */
    public function render() {
        ?>
        <div class="wrap">  
            <?php
            apply_filters('shortcode-addons/admin_nav_menu', false);
            ?>
            <div class="oxi-addons-wrapper">   
                <div class="oxi-addons-import-layouts">
                    <h1>Import Data</h1>
                    <p> The Import tool allows you to easily manage your Shortcode content. Its too easy as copy templete files from our online style list or local files and paste it into our import box. Once Imported your data will shown automatically with new shortcode.</p>

                    <?php
                    if (apply_filters('shortcode-addons/pro_enabled', false) == false) {
                        echo '<div class="oxi-addons-updated">
                                    <p>Hey, Thank you very much, to using <strong>Shortcode Addons- with Visual Composer, Divi, Beaver Builder and Elementor Extension </strong>! Import style or layouts will works only at Pro or Premium version. Our Premium version comes with lots of features and 16/6 Dedicated Support.</p>
                              </div>';
                    }
                    ?>
                    <!----- Import Form ---->
                    <form method="post" id="oxi-addons-import-data-form">
                        <div class="oxi-addons-import-data">
                            <div class="oxi-headig">
                                Import Data Form
                            </div>
                            <div class="oxi-content">
                                <textarea placeholder="Paste your style files..." name="shortcode-addons-content" id="shortcode-addons-content"></textarea>
                            </div>
                            <div class="oxi-buttom">
                                <a href="" class="btn btn-danger"> Reset </a>
                                <input type="submit" class="btn btn-success" name="submit" value="Save">
                            </div>
                        </div>
                    </form>
                    <div class="feature-section">
                        <h3>Get Trouble to Import Style?</h3>
                        <p>Your suggestions will make this plugin even better, Even if you get any bugs on Shortcode Addons so let us to know, We will try to solved within few hours</p>
                        <p class="oxi-feature-button">
                            <a href="https://www.shortcode-addons.com/docs/shortcode-addons/import-layouts/" target="_blank" rel="noopener" class="ihewc-image-features-button button button-primary">Documentation</a>
                            <a href="https://wordpress.org/plugins/shortcode-addons/" target="_blank" rel="noopener" class="ihewc-image-features-button button button-primary">Support Forum</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
