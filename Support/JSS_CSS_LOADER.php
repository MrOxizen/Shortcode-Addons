<?php

namespace SHORTCODE_ADDONS\Support;

/**
 * Description of JSS and CSS Loader
 * @author biplo
 */
trait JSS_CSS_LOADER {

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
     * Load Admin Css
     *
     * @since v2.1.0
     */
    public function admin_css() {
        $this->loader_font_familly_validation(['Bree+Serif', 'Source+Sans+Pro']);
        wp_enqueue_style('shortcode-addons-bootstrap', SA_ADDONS_URL . 'assets/backend/css/bootstrap.min.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('font-awsome.min', SA_ADDONS_URL . 'assets/front/css/font-awsome.min.css', false, SA_ADDONS_PLUGIN_VERSION);
    }

    /**
     * Load Admin vendor Css and js
     *
     * @since v2.1.0
     */
    public function admin_vendor() {
        wp_enqueue_style('shortcode-addons-admin-css', SA_ADDONS_URL . '/assets/backend/css/admin.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('shortcode-addons-vendor', SA_ADDONS_URL . '/assets/backend/js/vendor.js', false, SA_ADDONS_PLUGIN_VERSION);
    }

    /**
     * Load Admin JS
     *
     * @since v2.1.0
     */
    public function admin_js() {
        wp_enqueue_script("jquery");
        wp_enqueue_script('shortcode-addons-popper', SA_ADDONS_URL . '/assets/backend/js/popper.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('shortcode-addons-bootstrap', SA_ADDONS_URL . '/assets/backend/js/bootstrap.min.js', false, SA_ADDONS_PLUGIN_VERSION);
    }

    /**
     * Admin Css Loader
     *
     * @since v2.1.0
     */
    public function admin_css_loader() {
        $this->admin_css();
        $this->admin_js();
        $this->admin_vendor();
    }

    /**
     * Load Frontend Loader
     *
     * @since v2.1.0
     */
    public function admin_elements_frontend_loader() {
        $this->admin_css();
        $this->admin_js();
        wp_enqueue_script("jquery");
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-widget');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-autocomplete');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery.dataTables.min', SA_ADDONS_URL . '/assets/backend/js/jquery.dataTables.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('dataTables.bootstrap.min', SA_ADDONS_URL . '/assets/backend/js/dataTables.bootstrap.min.js', false, SA_ADDONS_PLUGIN_VERSION);
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
        $this->admin_vendor();
        $this->admin_media_scripts();
    }

    /**
     * Admin Media Scripts.
     * Most of time using into Style Editing Page
     * 
     * @since 2.0.0
     */
    public function admin_import_media() {
        $this->admin_elements_frontend_loader();
        wp_enqueue_script('shortcode-addons-import', SA_ADDONS_URL . '/assets/backend/js/import.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_localize_script('shortcode-addons-import', 'shortcode_addons_editor', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('shortcode-addons-editor')));
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

    /**
     * Replace data
     *
     * @since v2.1.0
     */
    public function str_replace_first($from, $to, $content) {
        $from = '/' . preg_quote($from, '/') . '/';

        return preg_replace($from, $to, $content, 1);
    }

}
