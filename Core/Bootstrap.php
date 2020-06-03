<?php

namespace SHORTCODE_ADDONS\Core;

/**
 * Description of Bootstrap
 *
 * @author biplobadhikari
 */
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class Bootstrap {

    use \SHORTCODE_ADDONS\Support\Admin;
    use \SHORTCODE_ADDONS\Support\Notice;
    use \SHORTCODE_ADDONS\Support\Shortcode;

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

    /**
     * Database Parent Table
     *
     * @since 2.0.0
     */
    public $parent_table;

    /**
     * Database Import Table
     *
     * @since 2.0.0
     */
    public $import_table;

    /**
     * Database Child Table
     *
     * @since 2.0.0
     */
    public $child_table;

    /**
     * Define $wpdb
     *
     * @since 2.0.0
     */
    public $wpdb;

    /**
     * Constructor of Shortcode Addons
     *
     * @since 2.0.0
     */
    private function __construct() {
        // before init hook
        do_action('shortcode-addons/before_init');
        add_filter($this->fixed_data('73686f7274636f64652d6164646f6e732f70726f5f656e61626c6564'), array($this, $this->fixed_data('73686f7274636f64655f6164646f6e735f726f775f64617461')));
        add_action('init', [$this, 'i18n']);
        // register hooks
        $this->Shortcode();
        if (is_admin()) {
            $this->register();
        }
    }
    /**
     * Define Shortcode
     *
     * @since 2.1.0
     */
    protected function Shortcode() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->parent_table = $this->wpdb->prefix . 'oxi_div_style';
        $this->child_table = $this->wpdb->prefix . 'oxi_div_list';
        $this->import_table = $this->wpdb->prefix . 'oxi_div_import';
        add_action('wp_ajax_shortcode_addons_data', array($this, 'shortcode_addons_data_process'));
        add_action('wp_ajax_nopriv_shortcode_addons_data', [$this, 'shortcode_addons_data_process']);
        add_shortcode('oxi_addons', [$this, 'oxi_addons_shortcode']);
    }

    /**
     * Register  of Shortcode Addons
     *
     * @since 2.0.0
     */
    protected function register() {
        //Plugins Core
        add_action('admin_init', array($this, 'redirect_on_activation'));
        add_filter('plugin_action_links_' . SA_ADDONS_BASENAME, array($this, 'insert_plugin_links'));
        add_filter('plugin_row_meta', array($this, 'insert_plugin_row_meta'), 10, 2);

        // Admin
        $this->admin_notice();
        add_action('wp_ajax_shortcode_home_data', array($this, 'data_process'));
        add_action('wp_ajax_shortcode_addons_font_manager', array($this, 'font_manager'));
        add_filter($this->fixed_data('73686f7274636f64652d6164646f6e732f61646d696e5f6e61765f6d656e75'), array($this, $this->fixed_data('61646d696e5f6e61765f6d656e75')));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_head', array($this, 'menu_icon'));
        add_action('admin_init', [$this, 'plugin_settings']);
        add_action('admin_init', [$this, 'register_license']);
        add_action('admin_init', [$this, 'activate_license']);
        add_action('admin_init', [$this, 'deactivate_license']);
    }
    /**
     * Create Plugin Settings
     *
     * @since 2.1.0
     */
    public function plugin_settings() {
        //register our settings
        register_setting('shortcode-addons-settings-group', 'oxi_addons_user_permission');
        register_setting('shortcode-addons-settings-group', 'oxi_addons_google_font');
        register_setting('shortcode-addons-settings-group', 'oxi_addons_font_awesome');
        register_setting('shortcode-addons-settings-group', 'oxi_addons_bootstrap');
        register_setting('shortcode-addons-settings-group', 'oxi_addons_linear_gradient');
        register_setting('shortcode-addons-settings-group', 'oxi_addons_conflict_class');
        register_setting('shortcode-addons-settings-group', 'oxi_addons_waypoints');
    }
    /**
     * Enqueue jQuery
     *
     * @since 2.1.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script("jquery");
    }
    /**
     * Register License.
     *
     * @since 2.1.0
     */
     public function register_license() {
        register_setting('shortcode_addons_license', 'shortcode_addons_license_key', [$this, 'sanitize_license']);
    }
    /**
     * Sanitize License.
     *
     * @since 2.1.0
     */
    public function sanitize_license($new) {
        $old = get_option('shortcode_addons_license_key');
        if ($old && $old != $new) {
            delete_option('oxi_addons_license_status');
        }
        return $new;
    }
    /**
     * Activation License.
     *
     * @since 2.1.0
     */
    public function activate_license() {

        if (isset($_POST['shortcode_addons_license_activate'])) {


            if (!check_admin_referer('shortcode_addons_license_key_nonce', 'shortcode_addons_license_key_nonce'))
                return;
            $license = trim(get_option('shortcode_addons_license_key'));

            $api_params = array(
                'edd_action' => 'activate_license',
                'license' => $license,
                'item_name' => urlencode('Short code Addons'),
                'url' => home_url()
            );

            $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

            if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
                if (is_wp_error($response)) {
                    $message = $response->get_error_message();
                } else {
                    $message = __('An error occurred, please try again.');
                }
            } else {

                $license_data = json_decode(wp_remote_retrieve_body($response));

                if (false === $license_data->success) {

                    switch ($license_data->error) {

                        case 'expired' :

                            $message = sprintf(
                                    __('Your license key expired on %s.'), date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                            );
                            break;

                        case 'revoked' :

                            $message = __('Your license key has been disabled.');
                            break;

                        case 'missing' :

                            $message = __('Invalid license.');
                            break;

                        case 'invalid' :
                        case 'site_inactive' :

                            $message = __('Your license is not active for this URL.');
                            break;

                        case 'item_name_mismatch' :

                            $message = sprintf(__('This appears to be an invalid license key for %s.'), SHORTCODE_ADDOONS);
                            break;

                        case 'no_activations_left':

                            $message = __('Your license key has reached its activation limit.');
                            break;

                        default :

                            $message = __('An error occurred, please try again.');
                            break;
                    }
                }
            }

            if (!empty($message)) {
                $base_url = admin_url('admin.php?page=shortcode-addons-settings');
                $redirect = add_query_arg(array('sl_activation' => 'false', 'message' => urlencode($message)), $base_url);

                wp_redirect($redirect);
                exit();
            }
            update_option('oxi_addons_license_status', $license_data->license);
            wp_redirect(admin_url('admin.php?page=shortcode-addons-settings'));
            exit();
        }
    }
    /**
     * Deactivation License.
     *
     * @since 2.1.0
     */
    public function deactivate_license() {
        if (isset($_POST['shortcode_addons_license_deactivate'])) {
            if (!check_admin_referer('shortcode_addons_license_key_nonce', 'shortcode_addons_license_key_nonce'))
                return;
            $license = trim(get_option('shortcode_addons_license_key'));
            $api_params = array(
                'edd_action' => 'deactivate_license',
                'license' => $license,
                'item_name' => urlencode('Short code Addons'),
                'url' => home_url()
            );

            $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));


            if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

                if (is_wp_error($response)) {
                    $message = $response->get_error_message();
                } else {
                    $message = __('An error occurred, please try again.');
                }

                $base_url = admin_url('admin.php?page=shortcode-addons-settings');
                $redirect = add_query_arg(array('sl_activation' => 'false', 'message' => urlencode($message)), $base_url);

                wp_redirect($redirect);
                exit();
            }
            $license_data = json_decode(wp_remote_retrieve_body($response));
            if ($license_data->license == 'deactivated') {
                delete_option('oxi_addons_license_status');
            }

            wp_redirect(admin_url('admin.php?page=shortcode-addons-settings'));
            exit();
        }
    }
}
