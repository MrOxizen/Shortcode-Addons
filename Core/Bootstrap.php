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
            $this->User_Admin();
            $this->User_Reviews();
            if (isset($_GET['page']) && 'oxi-addons-style-view' === $_GET['page']) {
             
                 $clsss = '\SHORTCODE_ADDONS\Layouts\Preview';
                if (class_exists($clsss)):
                    $elements = new $clsss();
                    $elements->templates();
                endif;
            }
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
        add_shortcode('oxi_addons', [$this, 'shortcode_render']);
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
        if (current_user_can('activate_plugins')):
        //  $this->admin_recommended();
        endif;

        $this->shortcode_addons_update();

        /// $this->admin_notice();
    }

    public function shortcode_addons_update() {
        $version = get_option('SA_ADDONS_PLUGIN_VERSION');
        if ($version != SA_ADDONS_PLUGIN_VERSION) :
            add_action('shortcode_addons_update', [$this, 'plugin_update']);
            wp_schedule_single_event(time() + 10, 'shortcode_addons_update');
        endif;
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
