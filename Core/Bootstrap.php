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

    public function User_Reviews() {
        if (!current_user_can('activate_plugins')):
            return;
        endif;

        $this->admin_recommended();
        $this->admin_notice();
    }

    public function admin_recommended() {
        if (!empty($this->admin_recommended_status())):
            return;
        endif;

        if (strtotime('-1 days') < $this->installation_date()):
            return;
        endif;
        new \SHORTCODE_ADDONS\Oxilab\Recommended();
    }

    /**
     * Admin Notice Check
     *
     * @since 2.0.0
     */
    public function admin_recommended_status() {
        $data = get_option('shortcode_addons_recommended');
        return $data;
    }

    /**
     * Admin Notice Check
     *
     * @since 2.0.0
     */
    public function admin_notice_status() {
        $data = get_option('shortcode_addons_no_bug');
        return $data;
    }

    /**
     * Admin Install date Check
     *
     * @since 2.0.0
     */
    public function installation_date() {
        $data = get_option('shortcode_addons_activation_date');
        if (empty($data)):
            $data = strtotime("now");
            update_option('shortcode_addons_activation_date', $data);
        endif;
        return $data;
    }

    public function admin_notice() {
        if (!empty($this->admin_notice_status())):
            return;
        endif;
        if (strtotime('-7 days') < $this->installation_date()):
            return;
        endif;
        new \SHORTCODE_ADDONS\Oxilab\Reviews();
    }

    public function redirect_on_activation() {
        if (get_transient('shortcode_adddons_activation_redirect')) :
            delete_transient('shortcode_adddons_activation_redirect');
            if (is_network_admin() || isset($_GET['activate-multi'])) :
                return;
            endif;
            wp_safe_redirect(admin_url("admin.php?page=shortcode-addons-support"));
        endif;
    }

}
