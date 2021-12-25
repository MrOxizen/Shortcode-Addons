<?php

namespace SHORTCODE_ADDONS\Layouts;

if (!defined('ABSPATH')) {
    exit;
}

use \SHORTCODE_ADDONS\Core\Console as Console;

class Import extends Console {

    use \SHORTCODE_ADDONS\Helper\Admin_Scripts;

    public $elements;

    /**
     * Shortcode Addons Extension Constructor.
     *
     * @since 2.0.0
     */
    public function element_page() {
        do_action('shortcode-addons/before_init');
        // Load Elements

        $this->admin();

        $this->render();
    }

    public function admin() {

        $this->admin_scripts();
    }

    /**
     * Generate safe path
     * @since v1.0.0
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function render() {
        ?>
        <div class="wrap">
        <?php
        apply_filters('shortcode-addons/admin_menu', false);
        ?>
            <div class="oxi-addons-wrapper">
                <div class="oxi-addons-import-layouts">
                    <h1>Import Elements or Template</h1>
                    <p> The Import Elements allows you to easily Import your Elements or Templates. You can import local Or manually elements if your automatic tools not works properly. Once Imported your Elements will works properly into shortcode home page.</p>

                    <!----- Import Form ---->
                    <form method="post" id="oxi-addons-import-elements-form" enctype="multipart/form-data">
                        <div class="oxi-addons-import-data">
                            <div class="oxi-headig">
                                Elemensts or Template
                            </div>
                            <div class="oxi-content-box">
                                <div class="oxi-content">
                                    <div class="form-group">
                                        <input type="file" class="form-control-file" id="ShortcodeAddonsUploa"  name="validuploaddata">
                                    </div>
                                </div>
                                <div class="oxi-buttom">
        <?php wp_nonce_field("oxi-addons-upload-nonce") ?>
                                    <input type="submit" class="btn btn-success" name="data-upload" value="Save">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

}
