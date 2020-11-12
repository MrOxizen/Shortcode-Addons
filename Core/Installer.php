<?php

namespace SHORTCODE_ADDONS\Core;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Installer
 *
 * @author biplobadhikari
 */
class Installer {

    use \SHORTCODE_ADDONS\Support\Admin;

    protected static $lfe_instance = NULL;

    const SHORTCODE_TRANSIENT_ELEMENTS = 'shortcode_addons_elements';
    const SHORTCODE_TRANSIENT_MENU = 'get_oxilab_addons_menu';
    const SHORTCODE_TRANSIENT_GOOGLE_FONT = 'shortcode_addons_google_font';
    const SHORTCODE_TRANSIENT_EXTENSION = 'shortcode_addons_extension';

    /**
     * Access plugin instance. You can create further instances by calling
     */
    public static function get_instance() {
        if (NULL === self::$lfe_instance)
            self::$lfe_instance = new self;

        return self::$lfe_instance;
    }

    /**
     * Plugin activation hook
     *
     * @since 2.0.0
     */
    public function activation_hook() {
        delete_transient(self::SHORTCODE_TRANSIENT_ELEMENTS);
        \SHORTCODE_ADDONS\Core\Admin\Shortcode_Remote::get_instance()->categories_list();
        delete_transient(self::SHORTCODE_TRANSIENT_MENU);
        delete_transient(self::SHORTCODE_TRANSIENT_GOOGLE_FONT);
        delete_transient(self::SHORTCODE_TRANSIENT_EXTENSION);
        // save sql data
        $this->sql_default_data();
        // create upload folder
        $this->create_upload_folder();
        // Redirect to options page
        set_transient('shortcode_adddons_activation_redirect', true, 60);
    }

    /**
     * Plugin Upgrade hook
     *
     * @since 2.0.0
     */
    public function plugin_upgrade_hook() {
        delete_transient(self::SHORTCODE_TRANSIENT_ELEMENTS);
        \SHORTCODE_ADDONS\Core\Admin\Shortcode_Remote::get_instance()->categories_list();
        delete_transient(self::SHORTCODE_TRANSIENT_MENU);
        delete_transient(self::SHORTCODE_TRANSIENT_GOOGLE_FONT);
        delete_transient(self::SHORTCODE_TRANSIENT_EXTENSION);
        // save sql data
        $this->sql_default_data();
        // create upload folder
        $this->create_upload_folder();
    }

    /**
     * Plugin Transient hook
     *
     * @since 2.0.0
     */
    public function plugin_deactivation_hook() {
        delete_transient(self::SHORTCODE_TRANSIENT_ELEMENTS);
        delete_transient(self::SHORTCODE_TRANSIENT_MENU);
        delete_transient(self::SHORTCODE_TRANSIENT_GOOGLE_FONT);
        delete_transient(self::SHORTCODE_TRANSIENT_EXTENSION);
    }

}
