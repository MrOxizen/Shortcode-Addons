<?php

namespace SHORTCODE_ADDONS\Layouts;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Support
 *
 * @author biplo
 */
class Support {

    use \SHORTCODE_ADDONS\Helper\Admin_Scripts;

    public function __construct() {
        $this->header();
        $this->Public_Render();
    }

    public function header() {
        $this->admin_scripts();
        apply_filters('shortcode-addons/admin_menu', false);
    }

    public function Public_Render() {
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-import-layouts">
                <div class="about-wrap text-center">
                    <h1>Welcome to Shortcode Addons</h1>
                    <div class="about-text">
                        Thank you for Installing Shortcode Addons- with Visual Composer, Divi, Beaver Builder and Elementor Extension, The most friendly Wordpress extension or all in one Package for any Wordpress Sites. Here's how to get started.
                    </div>
                </div>
                <div class="feature-section">
                    <div class="about-container">
                        <div class="about-addons-videos"><iframe src="https://www.youtube.com/embed/Ovvqi7iZJ-s" frameborder="0" allowfullscreen="" class="about-video"></iframe></div>
                    </div>
                </div>
            </div>
            <div class="oxi-addons-docs-column-wrapper">
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="oxi-docs-admin-wrapper">

                            <div class="oxi-docs-admin-block">
                                <div class="oxi-docs-admin-header">
                                    <div class="oxi-docs-admin-header-icon">
                                        <span class="dashicons dashicons-format-aside"></span>
                                    </div>
                                    <h4 class="oxi-docs-admin-header-title">Documentation</h4>
                                </div>
                                <div class="oxi-docs-admin-block-content">
                                    <p>Get started by spending some time with the documentation to get familiar with Shortcode Addons. Build multiple Shortcode for you or your clients with ease.</p>
                                    <a href="https://www.oxilabdemos.com/shortcode-addons/docs/" class="oxi-docs-button" target="_blank">Documentation</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="oxi-docs-admin-wrapper">
                            <div class="oxi-docs-admin-block">
                                <div class="oxi-docs-admin-header">
                                    <div class="oxi-docs-admin-header-icon">
                                        <span class="dashicons dashicons-format-aside"></span>
                                    </div>
                                    <h4 class="oxi-docs-admin-header-title">Contribute to Responsive Accordions</h4>
                                </div>
                                <div class="oxi-docs-admin-block-content">
                                    <p>You can contribute to make Shortcode Addons better reporting bugs &amp; creating issues. Our Development team always try to make more powerfull Plugins day by day with solved Issues</p>
                                    <a href="https://wordpress.org/support/plugin/shortcode-addons/" class="oxi-docs-button" target="_blank">Report a bug</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="oxi-docs-admin-wrapper">
                            <div class="oxi-docs-admin-block">
                                <div class="oxi-docs-admin-header">
                                    <div class="oxi-docs-admin-header-icon">
                                        <span class="dashicons dashicons-format-aside"></span>
                                    </div>
                                    <h4 class="oxi-docs-admin-header-title">Video Tutorials </h4>
                                </div>
                                <div class="oxi-docs-admin-block-content">
                                    <p>Unable to useShortcode Addons? Don't worry you can check your web tutorials to make easier to use :) </p>
                                    <a href="https://www.youtube.com/watch?v=BhgngA_cF1c&list=PLUIlGSU2bl8i_6t-ZIymCiAqILaT4jDMj" class="oxi-docs-button" target="_blank">Video Tutorials</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php
    }

}
