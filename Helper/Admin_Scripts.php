<?php

namespace SHORTCODE_ADDONS\Helper;

if (!defined('ABSPATH')) {
    exit;
}

/**
 *
 * @author biplo
 */
trait Admin_Scripts {
    
    
    public function allowed_html($rawdata) {
        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href' => array(),
                'rel' => array(),
                'title' => array(),
            ),
            'abbr' => array(
                'title' => array(),
            ),
            'b' => array(),
            'br' => array(),
            'blockquote' => array(
                'cite' => array(),
            ),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'del' => array(
                'datetime' => array(),
                'title' => array(),
            ),
            'dd' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
                'id' => array(),
            ),
            'table' => array(
                'class' => array(),
                'id' => array(),
                'style' => array(),
            ),
            'button' => array(
                'class' => array(),
                'type' => array(),
                'value' => array(),
            ),
            'thead' => array(),
            'tbody' => array(),
            'tr' => array(),
            'td' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'i' => array(
                'class' => array(),
            ),
            'img' => array(
                'alt' => array(),
                'class' => array(),
                'height' => array(),
                'src' => array(),
                'width' => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'q' => array(
                'cite' => array(),
                'title' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'strike' => array(),
            'strong' => array(),
            'ul' => array(
                'class' => array(),
            ),
        );
        if (is_array($rawdata)):
            return $rawdata = array_map(array($this, 'allowed_html'), $rawdata);
        else:
            return wp_kses($rawdata, $allowed_tags);
        endif;
    }

    public function validate_post($files = '') {
        
        $rawdata = [];
        if (!empty($files) && !is_array($files)):
            $data = json_decode(stripslashes($files), true);
        endif;
        if (is_array($data)):
            $rawdata = array_map(array($this, 'allowed_html'), $data);
        else:
            $rawdata = $this->allowed_html($files);
        endif;
       
        return $rawdata;
    }

    /**
     * font family loader validation
     *
     * @since v2.1.0
     */
    public function loader_font_familly_validation($data = []) {
        foreach ($data as $value) {
            wp_enqueue_style('' . $value . '', 'https://fonts.googleapis.com/css?family=' . $value . '');
        }
    }

    /**
     * Load Admin CSS and jQuery
     *
     * @since v2.1.0
     */
    public function admin_scripts() {
        $this->loader_font_familly_validation(['Bree+Serif', 'Source+Sans+Pro']);
        wp_enqueue_style('shortcode-addons-bootstrap', SA_ADDONS_URL . 'assets/backend/css/bootstrap.min.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('font-awsome.min', SA_ADDONS_URL . 'assets/front/css/font-awsome.min.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('shortcode-addons-admin-css', SA_ADDONS_URL . '/assets/backend/css/admin.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script("jquery");
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-autocomplete');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('shortcode-addons-popper', SA_ADDONS_URL . '/assets/backend/js/popper.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('shortcode-addons-bootstrap', SA_ADDONS_URL . '/assets/backend/js/bootstrap.min.js', false, SA_ADDONS_PLUGIN_VERSION);

        wp_localize_script('shortcode-addons-bootstrap', 'ShortCodeAddonsUltimate', array(
            'root' => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest')
        ));
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function admin_settings() {
        $this->admin_scripts();
        wp_enqueue_script('shortcode-addons-settings-page', SA_ADDONS_URL . '/assets/backend/js/settings.js', false, SA_ADDONS_PLUGIN_VERSION);
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function admin_font_manager() {
        $this->admin_scripts();
        wp_enqueue_script('shortcode-addons-font-manager', SA_ADDONS_URL . '/assets/backend/js/font_manager.js', false, SA_ADDONS_PLUGIN_VERSION);
    }

    /**
     * Load Admin vendor Css and js
     *
     * @since v2.1.0
     */
    public function admin_elements_scripts() {
        $this->admin_scripts();
        wp_enqueue_script('jquery.serializejson.min', SA_ADDONS_URL . '/assets/backend/js/jquery.serializejson.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('jquery.dataTables.min', SA_ADDONS_URL . '/assets/backend/js/jquery.dataTables.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('dataTables.bootstrap.min', SA_ADDONS_URL . '/assets/backend/js/dataTables.bootstrap.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('shortcode-addons-elements', SA_ADDONS_URL . '/assets/backend/js/elements.js', false, SA_ADDONS_PLUGIN_VERSION);
    }

    /**
     * Load Frontend Loader
     *
     * @since v2.1.0
     */
    public function template_scripts() {
        $this->admin_scripts();

        wp_enqueue_style('jquery.coloring-pick.min.js', SA_ADDONS_URL . '/assets/backend/css/jquery.coloring-pick.min.js.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('jquery.coloring-pick.min', SA_ADDONS_URL . '/assets/backend/js/jquery.coloring-pick.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('jquery.minicolors', SA_ADDONS_URL . '/assets/backend/css/minicolors.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('jquery.minicolors', SA_ADDONS_URL . '/assets/backend/js/minicolors.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('nouislider', SA_ADDONS_URL . '/assets/backend/css/nouislider.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('nouislider', SA_ADDONS_URL . '/assets/backend/js/nouislider.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('fontawesome-iconpicker', SA_ADDONS_URL . '/assets/backend/css/fontawesome-iconpicker.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('fontawesome-iconpicker', SA_ADDONS_URL . '/assets/backend/js/fontawesome-iconpicker.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('jquery.coloring-pick.min.js', SA_ADDONS_URL . '/assets/backend/css/jquery.coloring-pick.min.js.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('jquery.coloring-pick.min', SA_ADDONS_URL . '/assets/backend/js/jquery.coloring-pick.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('jquery.conditionize2.min', SA_ADDONS_URL . '/assets/backend/js/jquery.conditionize2.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('select2.min', SA_ADDONS_URL . '/assets/backend/css/select2.min.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('select2.min', SA_ADDONS_URL . '/assets/backend/js/select2.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('js.cookie', SA_ADDONS_URL . '/assets/backend/js/js.cookie.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('jquery.serializejson.min', SA_ADDONS_URL . '/assets/backend/js/jquery.serializejson.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('jquery.fontselect', SA_ADDONS_URL . '/assets/backend/css/jquery.fontselect.css', false, SA_ADDONS_PLUGIN_VERSION);
        $this->admin_media_scripts();
        wp_enqueue_script('shortcode-addons-editor', SA_ADDONS_URL . '/assets/backend/js/editor.js', false, SA_ADDONS_PLUGIN_VERSION);
    }

    /**
     * Admin Media Scripts.
     * Most of time using into Style Editing Page
     *
     * @since 2.0.0
     */
    public function admin_media_scripts() {
        wp_enqueue_media();
        wp_register_script('shortcode_addons_media_scripts', SA_ADDONS_URL . '/assets/backend/js/media-uploader.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('shortcode_addons_media_scripts');
    }

}
