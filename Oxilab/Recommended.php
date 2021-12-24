<?php

namespace SHORTCODE_ADDONS\Oxilab;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Support
 *
 * @author biplo
 */
class Recommended {

    const GET_LOCAL_PLUGINS = 'get_all_oxilab_plugins';
    const PLUGINS = 'https://www.oxilab.org/wp-json/oxilabplugins/v2/all_plugins';

    public $get_plugins = [];
    public $current_plugins = 'shortcode-addons/index.php';

    /**
     * Revoke this function when the object is created.
     *
     */
    public function __construct() {
        require_once(ABSPATH . 'wp-admin/includes/screen.php');
        $screen = get_current_screen();
        if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
            return;
        }

        $this->extension();
        add_action('admin_notices', array($this, 'install_plugins'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_shortcode_addons_recommended', array($this, 'notice_dissmiss'));
        add_action('admin_notices', array($this, 'dismiss_button_scripts'));
    }

    public function extension() {
        $response = get_transient(self::GET_LOCAL_PLUGINS);
        if (!$response || !is_array($response)) {
            $URL = self::PLUGINS;
            $request = wp_remote_request($URL);
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                set_transient(self::GET_LOCAL_PLUGINS, $response, 10 * DAY_IN_SECONDS);
            } else {
                $response = $request->get_error_message();
            }
        }
        $this->get_plugins = $response;
    }

    /**
     * First Installation Track
     * @return void
     */
    public function install_plugins() {

        $installed_plugins = get_plugins();

        $plugin = [];
        $i = 1;

        foreach ($this->get_plugins as $key => $value) {
            if (!isset($installed_plugins[$value['modules-path']])):
                $plugin[$i] = $value;
                $i++;
            endif;
        }

        $recommend = [];

        for ($p = 1; $p < 100; $p++):
            if (isset($plugin[$p])):
                if (isset($plugin[$p]['dependency']) && $plugin[$p]['dependency'] != ''):
                    if (isset($installed_plugins[$plugin[$p]['dependency']])):
                        $recommend = $plugin[$p];
                        $p = 100;
                    endif;
                elseif ($plugin[$p]['modules-path'] != $this->current_plugins):
                    $recommend = $plugin[$p];
                    $p = 100;
                endif;
            else:
                $p = 100;
            endif;
        endfor;

        if (count($recommend) > 2 && $recommend['modules-path'] != ''):
            $plugin = explode('/', $recommend['modules-path'])[0];

            $install_url = wp_nonce_url(add_query_arg(array('action' => 'install-plugin', 'plugin' => $plugin), admin_url('update.php')), 'install-plugin' . '_' . $plugin);
            echo '<div class="wrap oxi-addons-admin-notifications" style="width: auto;">
                        <h3>
                            <span class="dashicons dashicons-flag"></span>
                            Recommendation
                        </h3>
                        <p></p>
                        <div class="oxi-addons-admin-notifications-holder">
                            <div class="oxi-addons-admin-notifications-alert">
                                <p>Thank you for using my Shortcode Addons. ' . esc_html($recommend['modules-massage']) . '</p>
                                <p><a href="' . esc_url($install_url) . '" class="button button-large button-primary">' . esc_html__('Install Now', 'shortcode-addons') . '</a>&nbsp;&nbsp;<a href="#" class="button button-large button-secondary oxi-plugins-admin-recommended-dismiss" sup-data="done">No, Thanks</a></p>
                            </div>
                        </div>
                        <p></p>
                    </div>';
        endif;
    }

    /**
     * Admin Notice CSS file loader
     * @return void
     */
    public function admin_enqueue_scripts() {
        wp_enqueue_script("jquery");
        wp_enqueue_style('shortcode-addons-notice-css', SA_ADDONS_URL . '/Oxilab/css/notice.css', false, SA_ADDONS_PLUGIN_VERSION);
        $this->dismiss_button_scripts();
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function dismiss_button_scripts() {
        wp_enqueue_script('shortcode-addons-recommend', SA_ADDONS_URL . '/Oxilab/js/recommend.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_localize_script('shortcode-addons-recommend', 'shortcode_addons_recommended', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('shortcode_addons_recommended')));
    }

    /**
     * Admin Notice Ajax  loader
     * @return void
     */
    public function notice_dissmiss() {
        if (isset($_POST['_wpnonce']) || wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), 'shortcode_addons_recommended')):
            $data = 'done';
            update_option('shortcode_addons_recommended', $data);
            echo 'done';
        else:
            return;
        endif;
        die();
    }

}
