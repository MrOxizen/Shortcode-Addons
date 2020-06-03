<?php

namespace SHORTCODE_ADDONS\Support\Settings;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Fonts
 * Content of Shortcode Addons Plugins
 *
 * @author $biplob018
 */
class Fonts {

    /**
     * Shortcode Addons Extension Constructor.
     *
     * @since 2.0.0
     */
    public function __construct() {
        $this->hooks();
    }
    /**
     * Shortcode Addons Hooks.
     *
     * @since 2.1.0
     */
    public function hooks() {
        $this->Render();
        $this->font_manager_load();
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function font_manager_load() {
        wp_enqueue_script('shortcode-addons-font-manager', SA_ADDONS_URL . '/assets/backend/js/font_manager.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_localize_script('shortcode-addons-font-manager', 'shortcode_addons_font_manager', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('shortcode-addons-font-manager')));
    }
    /**
     * Shortcode Addons Render.
     *
     * @since 2.1.0
     */
    public function Render() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-row">
                <h1><?php _e('Google Fonts Manager'); ?> </h1>
            </div>

            <div class="oxi-addons-row">
                <div class="s-a-font-manager-wrapper">
                    <div class="s-a-font-manager-row">
                        <div class="s-a-font-manager-search">
                            <input type="text" id="shortcode-addons-search-font" name="shortcode-addons-search-font" placeholder="Search font..">
                            <input type="button" class="add-new-h2" id="shortcode-addons-custom-fonts" value="Add Custom Font">
                        </div>
                    </div>
                    <div class="s-a-font-manager-row">
                        <div class="oxi-addons-style-left">
                            <div class="s-a-font-manager-fonts" id="s-a-font-manager-fonts" data-font-load="1">
                            </div>
                        </div>
                        <div class="oxi-addons-style-right shortcode-addons-fonts-selected">
                            <div class="oxi-addons-shortcode  shortcode-addons-templates-right-panel ">
                                <div class="oxi-addons-shortcode-heading  shortcode-addons-templates-right-panel-heading">
                                    Your Font Collection
                                    <div class="oxi-head-toggle"></div>
                                </div>
                                <div class="oxi-addons-shortcode-body  shortcode-addons-templates-right-panel-body" id="shortcode-addons-stored-font" style="padding-bottom: 0px; overflow-y: auto; max-height: 428px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="shortcode-addons-custom-fonts-modal" >
                <form method="post" id="shortcode-addons-custom-fonts-modal-form">
                    <div class="modal-dialog modal-sm modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">                    
                                <h5 class="modal-title">Add Custom Font</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class=" form-group row">
                                    <label for="addons-font-name" class="col-sm-6 col-form-label" oxi-addons-tooltip="Write Your Font here like Open Sans">Font</label>
                                    <div class="col-sm-6 addons-dtm-laptop-lock">
                                        <input class="form-control" type="text" value="" id="addons-font-name"  name="addons-font-name">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-success" name="addons-font-name-submit" id="addons-font-name-submit" value="Save">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

}
