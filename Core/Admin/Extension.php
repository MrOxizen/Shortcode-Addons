<?php

namespace SHORTCODE_ADDONS\Core\Admin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Description of Extensions
 * @author biplob018
 */
class Extension {

    use \SHORTCODE_ADDONS\Support\Validation;
    use \SHORTCODE_ADDONS\Support\JSS_CSS_LOADER;

    const SHORTCODE_TRANSIENT_EXTENSION = 'shortcode_addons_extension';
    const API = 'https://www.oxilab.org/wp-json/api/extension';

    public $extensions;

    /**
     * Shortcode Addons Extension Constructor.
     *
     * @since 2.0.0
     */
    public function __construct() {
        $this->admin_css_loader();
        $this->extension();
        do_action('shortcode-addons/before_init');
        $this->Header();
        $this->Render();
         
    }
    /**
     * Header admin nav menu.
     *
     * @since 2.1.0
     */
    public function Header() {
        apply_filters('shortcode-addons/admin_nav_menu', false);
        $this->Admin_header();
    }
    /**
     * Extension.
     *
     * @since 2.1.0
     */
    public function extension() {
        $response = get_transient(self::SHORTCODE_TRANSIENT_EXTENSION);
        if (!$response || !is_array($response) || count($response) < 3) {
            $URL = self::API;
            $request = wp_remote_request($URL);
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                set_transient(self::SHORTCODE_TRANSIENT_EXTENSION, $response, 10 * DAY_IN_SECONDS);
            } else {
                $response = $request->get_error_message();
            }
        }
        $this->extensions = $response;
    }
    /**
     * Admin Header notice.
     *
     * @since 2.1.0
     */
    public function Admin_header() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <h1>Oxilab Addons
                </h1>
                <p> We Develop Couple of plugins which will help you to Create Your Modern and Dynamic Websites. Just click and Install </p>
                <br>
                <br>
            </div>
        </div>
        <?php
    }
    /**
     * Render data.
     *
     * @since 2.1.0
     */
    public function Render() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-row">
                <div class="row">
                    <?php
                    $installed_plugins = get_plugins();
                    $apl = array_flip(get_option('active_plugins'));
                    foreach ($this->extensions as $key => $value) {
                        if ($key != 'shortcode-addons/index.php'):
                            $file_path = $key;
                            $plugin = explode('/', $file_path)[0];
                            $message = '';
                            if (isset($installed_plugins[$file_path])):
                                if (current_user_can('activate_plugins')):
                                    if (array_key_exists($file_path, $apl)):
                                        $message = '<a href="#" class="btn btn-light">Installed</a>';
                                    else:
                                        $activation_url = wp_nonce_url(admin_url('plugins.php?action=activate&plugin=' . $file_path), 'activate-plugin_' . $file_path);
                                        $message = sprintf('<a href="%s" class="btn btn-info">%s</a>', $activation_url, __('Activate', SHORTCODE_ADDOONS));
                                    endif;
                                endif;
                            else:
                                if (current_user_can('install_plugins')):
                                    $install_url = wp_nonce_url(add_query_arg(array('action' => 'install-plugin', 'plugin' => $plugin), admin_url('update.php')), 'install-plugin' . '_' . $plugin);
                                    $message = sprintf('<a href="%s" class="btn btn-success">%s</a>', $install_url, __('Install', SHORTCODE_ADDOONS));
                                endif;
                            endif;
                            ?>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="oxi-addons-modules-elements">
                                    <img class="oxi-addons-modules-banner" src="<?php echo $value['image']; ?>">
                                    <div class="oxi-addons-modules-action-wrapper">
                                        <span class="oxi-addons-modules-name"><?php echo $value['Name']; ?></span>
                                        <span class="oxi-addons-modules-desc"><?php echo $value['content']; ?></span>	
                                    </div>
                                    <div class="oxi-addons-modules-action-status">
                                        <span class="oxi-addons-modules-preview"><a href="<?php echo $value['PluginURI']; ?>" class="btn btn-dark">Preview</a></span>
                                        <span class="oxi-addons-modules-installing"><?php echo $message; ?></span>	
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


        wp_add_inline_script('shortcode-addons-vendor', $data);
    }

}
