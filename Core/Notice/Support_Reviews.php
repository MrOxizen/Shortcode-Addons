<?php

namespace SHORTCODE_ADDONS\Core\Notice;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Support_Reviews
 *
 * @author biplobadhikari
 */
class Support_Reviews {

    use \SHORTCODE_ADDONS\Support\Admin;

    /**
     * Revoke this function when the object is created.
     * 
     *  @since 2.0.0
     *
     */
    public function __construct() {
        add_action('admin_notices', array($this, 'first_install'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_shortcode_addons_notice_dissmiss', array($this, 'notice_dissmiss'));
        add_action('admin_notices', array($this, 'dismiss_button_scripts'));
    }

    /**
     * First Installation Track
     * @return void
     * 
     *  @since 2.0.0
     */
    public function first_install() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $image = SA_ADDONS_URL . 'image/logo.png';
        echo _(' <div class="notice notice-info put-dismiss-noticenotice-has-thumbnail shortcode-addons-review-notice">
                    <div class="shortcode-addons-notice-thumbnail">
                        <img src="' . $image . '" alt=""></div>
                    <div class="shortcode-addons--notice-message">
                        <p>Hey, You’ve using <strong>Shortcode Addons- with Visual Composer, Divi, Beaver Builder and Elementor Extension</strong> more than 1 week – that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.!</p>
                        <ul class="shortcode-addons--notice-link">
                            <li>
                                <a href="https://wordpress.org/plugins/shortcode-addons/" target="_blank">
                                    <span class="dashicons dashicons-external"></span>Ok, you deserve it!
                                </a>
                            </li>
                            <li>
                                <a class="shortcode-addons-support-reviews" sup-data="success" href="#">
                                    <span class="dashicons dashicons-smiley"></span>I already did
                                </a>
                            </li>
                            <li>
                                <a class="shortcode-addons-support-reviews" sup-data="maybe" href="#">
                                    <span class="dashicons dashicons-calendar-alt"></span>Maybe Later
                                </a>
                            </li>
                            <li>
                                <a href="https://wordpress.org/support/plugins/shortcode-addons/">
                                    <span class="dashicons dashicons-sos"></span>I need help
                                </a>
                            </li>
                            <li>
                                <a class="shortcode-addons-support-reviews" sup-data="never"  href="#">
                                    <span class="dashicons dashicons-dismiss"></span>Never show again
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>');
    }

    /**
     * Admin Notice CSS file loader
     * @return void
     * 
     *  @since 2.0.0
     */
    public function admin_enqueue_scripts() {
        wp_enqueue_script("jquery");
        wp_enqueue_style('shortcode-addons-admin-notice-css', SA_ADDONS_URL . '/assets/backend/css/notice.css', false, SA_ADDONS_PLUGIN_VERSION);
        $this->dismiss_button_scripts();
    }

    /**
     * Admin Notice JS file loader
     * @return void
     * 
     *  @since 2.0.0
     */
    public function dismiss_button_scripts() {
        wp_enqueue_script('shortcode-addons-admin-notice', SA_ADDONS_URL . '/assets/backend/js/admin-notice.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_localize_script('shortcode-addons-admin-notice', 'shortcode_addons_admin_notice', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('shortcode-addons-admin-notice')));
    }

    /**
     * Admin Notice Ajax  loader
     * @return void
     * 
     *  @since 2.0.0
     */
    public function notice_dissmiss() {
        if (isset($_POST['_wpnonce']) || wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), 'shortcode-addons-admin-notice')):
            $notice = isset($_POST['notice']) ? sanitize_text_field($_POST['notice']) : '';
            if ($notice == 'maybe'):
                $data = strtotime("now");
                update_option($this->fixed_data('6f78695f6164646f6e735f73686f7274636f64655f61637469766174696f6e5f64617465'), $data);
            else:
                update_option($this->fixed_data('6f78695f6164646f6e735f73686f7274636f64655f72657669657773'), $notice);
            endif;
            echo 'Its Complete';
        else:
            return;
        endif;

        die();
    }

}
