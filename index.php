<?php

/**
 * Plugin Name: Shortcode Addons- with Visual Composer, Divi, Beaver Builder and Elementor Extension
 * Description: Shortcode Addons is an amazing set of beautiful and useful elements with Visual Composer, Divi, Beaver Builder and Elementor Extension.
 * Plugin URI: https://www.shortcode-addons.com
 * Version: 2.1.4
 * Author: biplob018
 * Author URI: https://www.oxilab.org/
 * Text Domain: shortcode-addons
 */
if (!defined('ABSPATH'))
    exit;

/**
 * Defining plugin constants.
 *
 * @since 2.0.0
 */
define('SA_ADDONS_FILE', __FILE__);
define('SA_ADDONS_BASENAME', plugin_basename(__FILE__));
define('SA_ADDONS_PATH', plugin_dir_path(__FILE__));
define('SA_ADDONS_URL', plugins_url('/', __FILE__));
define('SA_ADDONS_PLUGIN_VERSION', '2.1.4');
define('SHORTCODE_ADDOONS', 'SHORTCODE_ADDOONS');
$upload = wp_upload_dir();
define('SA_ADDONS_UPLOAD_PATH', $upload['basedir'] . '/shortcode-addons/');
define('SA_ADDONS_UPLOAD_URL', $upload['baseurl'] . '/shortcode-addons/');

/**
 * Including composer autoloader globally.
 *
 * @since 2.0.0
 */
require_once SA_ADDONS_PATH . 'autoloader.php';


/**
 * Run shortcode addons with wordpress
 *
 * @since 2.0.0
 */
add_action('plugins_loaded', function () {
    \SHORTCODE_ADDONS\Core\Bootstrap::instance();
});

/**
 * Activation hook
 *
 * @since v2.0.0
 */
register_activation_hook(__FILE__, function () {
    $installer = \SHORTCODE_ADDONS\Core\Installer::get_instance();
    $installer->activation_hook();
});



/**
 * Upgrade hook
 *
 * @since v2.0.0
 */
add_action('upgrader_process_complete', function () {
    $migration = \SHORTCODE_ADDONS\Core\Installer::get_instance();
    $migration->plugin_upgrade_hook();
}, 10, 2);

/**
 * Deactivation hook
 *
 * @since 2.0.0
 */
register_deactivation_hook(__FILE__, function () {
    $Installation = \SHORTCODE_ADDONS\Core\Installer::get_instance();
    $Installation->plugin_deactivation_hook();
});
if (!function_exists('array_key_first')) {

    function array_key_first(array $array) {
        if (count($array)) {
            reset($array);
            return key($array);
        }
        return null;
    }

}
