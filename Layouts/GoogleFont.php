<?php

namespace SHORTCODE_ADDONS\Layouts;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Settings
 *
 * @author biplo
 */
use \SHORTCODE_ADDONS\Core\Console as Console;

class GoogleFont extends Console {

    use \SHORTCODE_ADDONS\Helper\Admin_Scripts;

    /**
     * Google Font List
     *
     * @since 2.0.0
     */
    public $google_font;

    /**
     * Database Google Font
     *
     * @since 2.0.0
     */
    public $stored_font;

    public function font_manager() {

        do_action('shortcode-addons/before_init');
        // Load Elements

        $this->admin();

        $this->render();
    }

    public function admin() {
        $this->admin_font_manager();
    }

    public function render() {
        ?>
        <div class="wrap">
            <?php
            apply_filters('shortcode-addons/admin_menu', false);
            ?>
            <div class="oxi-addons-wrapper">
                <div class="oxi-addons-row">
                    <h1>Google Fonts Manager</h1>
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
                                        <label for="addons-font-name" class="col-sm-6 col-form-label" oxi-addons-tooltip="Write Your Font here like Open Sans">Font Name</label>
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
        </div>
        <?php
    }

}
