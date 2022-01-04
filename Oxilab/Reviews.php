<?php

namespace SHORTCODE_ADDONS\Oxilab;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Reviews
 *
 * @author biplo
 */
class Reviews {

    /**
     * Revoke this function when the object is created.
     *
     */
    public function __construct() {
        if (!current_user_can('manage_options')) {
            return;
        }
        add_action('admin_notices', array($this, 'first_install'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_ajax_shortcode_addons_reviews', array($this, 'notice_dissmiss'));
        add_action('admin_notices', array($this, 'dismiss_button_scripts'));
    }

    /**
     * Admin Notice Ajax  loader
     * @return void
     */
    public function notice_dissmiss() {
        if (isset($_POST['_wpnonce']) || wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), 'shortcode_addons_reviews')):
            $notice = isset($_POST['notice']) ? sanitize_text_field($_POST['notice']) : '';
            if ($notice == 'maybe'):
                $data = strtotime("now");
                update_option('shortcode_addons_activation_date', $data);
            else:
                update_option('shortcode_addons_no_bug', $notice);
            endif;
            echo $notice;
        else:
            return;
        endif;

        die();
    }

    /**
     * First Installation Track
     * @return void
     */
    public function first_install() {

        $image = SA_ADDONS_URL . 'image/logo.png';
        ?>
        <div class="notice notice-info put-dismiss-noticenotice-has-thumbnail shortcode-addons-review-notice  ">
            <div class="shortcode-addons-notice-thumbnail">
                <img src="<?php echo esc_url($image); ?>" alt=""></div>
            <div class="shortcode-addons--notice-message">
                <p>Hey, You’ve using <strong>Shortcode Addons</strong> more than 1 week – that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.!</p>
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
                        <a href="https://wordpress.org/support/plugin/shortcode-addons/">
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
        </div>
        <?php
    }

    /**
     * Admin Notice CSS file loader
     * @return void
     */
    public function admin_enqueue_scripts() {
        wp_enqueue_script("jquery");
        wp_enqueue_style('shortcode-addons-reviews-notice', SA_ADDONS_URL . '/Oxilab/css/notice.css', false, SA_ADDONS_PLUGIN_VERSION);
        $this->dismiss_button_scripts();
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function dismiss_button_scripts() {
        wp_enqueue_script('shortcode_addons_reviews', SA_ADDONS_URL . '/Oxilab/js/notice.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_localize_script('shortcode_addons_reviews', 'shortcode_addons_reviews', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('shortcode_addons_reviews')));
    }

}
