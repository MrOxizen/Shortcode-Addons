<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SHORTCODE_ADDONS\Layouts;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Plugins
 *
 * @author biplo
 */
class Plugins {

    use \SHORTCODE_ADDONS\Helper\Admin_Scripts;

    const GET_LOCAL_PLUGINS = 'get_all_oxilab_plugins';
    const PLUGINS = 'https://www.oxilab.org/wp-json/oxilabplugins/v2/all_plugins';

    public $get_plugins = [];
    public $current_plugins = 'shortcode-addons/index.php';
    // instance container
    private static $instance = null;

    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Constructor of Oxilab Plugins Page
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->CSSJS_load();
        $this->Header();
        $this->Render();
    }

    public function CSSJS_load() {
        $this->admin_scripts();
        $this->extension();
        if (!current_user_can('activate_plugins')):
            die();
        endif;
    }

    public function Header() {
        echo apply_filters('shortcode-addons/admin_menu', false);
        $this->Admin_header();
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

    public function Admin_header() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Oxilab Addons
                </h1>
                <p> We Develop Couple of plugins which will help you to Create Your Modern and Dynamic Websites. Just click and Install </p>
            </div>
        </div>
        <?php
    }

    public function Render() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-row">
                <div class="row">
                    <?php
                    $installed_plugins = get_plugins();
                    $active_plugins = array_flip(get_option('active_plugins'));

                    foreach ($this->get_plugins as $key => $value) {
                        $modulespath = $value['modules-path'];
                        if ($modulespath != $this->current_plugins):
                            $file_path = $modulespath;
                            $plugin = explode('/', $file_path)[0];
                            ?>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="oxi-addons-modules-elements">
                                    <img class="oxi-addons-modules-banner" src="<?php echo esc_url($value['modules-img']); ?>">
                                    <div class="oxi-addons-modules-action-wrapper">
                                        <span class="oxi-addons-modules-name"><?php echo esc_html($value['modules-name']); ?></span>
                                        <span class="oxi-addons-modules-desc"><?php echo esc_html($value['modules-desc']); ?></span>
                                    </div>
                                    <div class="oxi-addons-modules-action-status">
                                        <span class="oxi-addons-modules-preview"><a href="<?php echo esc_url($value['plugin-url']); ?>" class="btn btn-dark">Preview</a></span>
                                        <span class="oxi-addons-modules-installing"><?php
                                            if (isset($installed_plugins[$file_path])):
                                                if (array_key_exists($file_path, $active_plugins)):
                                                    echo '<a href="#" class="btn btn-light">Installed</a>';
                                                else:
                                                    $activation_url = wp_nonce_url(admin_url('plugins.php?action=activate&plugin=' . $file_path), 'activate-plugin_' . $file_path);
                                                    echo '<a href="' . esc_url($activation_url) . '" class="btn btn-info">' . esc_html__('Activate', 'shortcode-addons') . '</a>';
                                                endif;
                                            else:
                                                if (current_user_can('install_plugins')):
                                                    $install_url = wp_nonce_url(add_query_arg(array('action' => 'install-plugin', 'plugin' => $plugin), admin_url('update.php')), 'install-plugin' . '_' . $plugin);
                                                    echo '<a href="' . esc_url($install_url) . '" class="btn btn-success">' . esc_html__('Install', 'shortcode-addons') . '</a>';
                                                endif;
                                            endif;
                                            ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endif;
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        $data = 'function oxiequalHeight(group) {
                    var tallest = 0;
                    group.each(function () {
                        thisHeight = jQuery(this).height();
                        if (thisHeight > tallest) {
                            tallest = thisHeight;
                        }
                    });
                    group.height(tallest);
                }
                setTimeout(function () {
                    oxiequalHeight(jQuery(".oxi-addons-modules-action-wrapper"));
                }, 1000);';

        wp_add_inline_script('oxilab-bootstrap', $data);
    }

}
