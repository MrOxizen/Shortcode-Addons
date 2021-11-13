<?php

namespace SHORTCODE_ADDONS\Core;

/**
 * Description of Bootstrap
 *
 * @author biplobadhikari
 */
if (!defined('ABSPATH')) {
    exit;
}

class Bootstrap {

    use \SHORTCODE_ADDONS\Helper\Helper;

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

        do_action('shortcode-addons/before_init');
        // Load translation
        add_action('init', array($this, 'i18n'));

        $RestApi = new \SHORTCODE_ADDONS\Core\RestApi();
        $RestApi->build_api();
        $this->load_shortcode();

        if (is_admin()) {
            $this->User_Reviews();
            $this->User_Admin();
        }
    }

    /**
     * Extending plugin Textdomain
     *
     * @since 2.0.0
     */
    public function i18n() {
        load_plugin_textdomain('shortcode-addons');
    }

    public function load_shortcode() {
        add_action('wp_ajax_shortcode_addons_data', array($this, 'shortcode_addons_data_process'));
        add_action('wp_ajax_nopriv_shortcode_addons_data', [$this, 'shortcode_addons_data_process']);
        add_shortcode('oxi_addons', [$this, 'oxi_addons_shortcode']);
        $Widget = new \SHORTCODE_ADDONS\Includes\Widget();
        add_filter('widget_text', 'do_shortcode');
        add_action('widgets_init', array($Widget, 'register_shortcode_addons_widget'));
    }

    public function User_Admin() {

        add_filter('shortcode-addons/support-and-comments', array($this, 'supportandcomments'));
        add_filter('shortcode-addons/admin_version', array($this, 'check_current_version'));
        add_filter('shortcode-addons/admin_menu', array($this, 'oxilab_admin_menu'));
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_head', [$this, 'admin_icon']);
        add_action('admin_init', array($this, 'redirect_on_activation'));
    }

}
