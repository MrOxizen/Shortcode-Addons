<?php

namespace SHORTCODE_ADDONS\Helper;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Installation
 *
 * @author biplo
 */
class Installation {

    public $database;

    /**
     * Plugins Loader
     *
     * $instance
     *
     * @since 2.0.0
     */
    private static $instance = null;

    /**
     * Singleton instance
     *
     * @since 2.0.0
     */
    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct() {
        $this->database = new \SHORTCODE_ADDONS\Layouts\ClearOlderFile();
    }

    /**
     * Plugin upgrade hook
     *
     * @since 2.0.1
     */
    public function plugin_upgrade_hook() {
        // save sql data
        $this->database->update_database();
        // create upload folder
        $this->database->create_upload_folder();
        $this->database->clearolderfiles();
    }

    /**
     * Plugin activation hook
     *
     * @since 2.0.1
     */
    public function plugin_activation_hook() {
        // save sql data
        $this->database->update_database();
        // create upload folder
        $this->database->create_upload_folder();

        $this->database->clearolderfiles();
        set_transient('shortcode_adddons_activation_redirect', true, 60);
    }

    /**
     * Plugin deactivation hook
     *
     * @since 2.0.1
     */
    public function plugin_deactivation_hook() {

        $this->database->clearolderfiles();
    }

}
