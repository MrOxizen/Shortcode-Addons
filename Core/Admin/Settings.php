<?php

namespace SHORTCODE_ADDONS\Core\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Settings
 * Content of Shortcode Addons Plugins
 *
 * @author $biplob018
 */
class Settings {

    use \SHORTCODE_ADDONS\Support\JSS_CSS_LOADER;

    //Installed Elements List
    public $page;

    /**
     * Shortcode Addons Extension Constructor.
     *
     * @since 2.0.0
     */
    public function __construct() {
        do_action('shortcode-addons/before_init');
        $this->admin_css_loader();
        $this->page = (isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'general');
        $this->hooks();
    }

    /**
     * Shortcode Addons nemu.
     *
     * @since 2.1.0
     */
    public function menu() {
        echo _('<div class="shortcode-addons-main-tab-header">
                    <a href="' . admin_url('admin.php?page=shortcode-addons-settings') . '">
                        <div class="shortcode-addons-header">General</div>
                    </a>
                    <a href="' . admin_url('admin.php?page=shortcode-addons-settings&view=fonts') . '">
                        <div class="shortcode-addons-header">Font Family</div>
                    </a>
                </div>');
    }
    /**
     * Shortcode Addons Hooks.
     *
     * @since 2.1.0
     */
    public function hooks() {
        ?>
        <div class="wrap">  
            <div class="oxi-addons-wrapper">
                <?php
                apply_filters('shortcode-addons/admin_nav_menu', false);
                $this->menu();

                if ($this->page == 'general'):
                    new \SHORTCODE_ADDONS\Support\Settings\General();
                elseif ($this->page == 'fonts'):
                    new \SHORTCODE_ADDONS\Support\Settings\Fonts();
                endif;
                ?>
            </div>
        </div>
        <?php
    }

}
