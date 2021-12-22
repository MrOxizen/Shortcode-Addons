<?php

namespace SHORTCODE_ADDONS\Layouts;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Elements
 *
 * @author biplo
 */
use \SHORTCODE_ADDONS\Core\Console as Console;

class Collection extends Console {

    use \SHORTCODE_ADDONS\Helper\Admin_Scripts;

    public $available_elements;
    public $installed_elements;
    public $elements;

    public function element_page() {

        do_action('shortcode-addons/before_init');
        // Load Elements

        $this->admin();

        $this->render();
    }

    public function admin() {
        $this->require_scripts();
        $this->available_elements = $this->shortcode_elements();
        $this->installed_elements = $this->installed_elements();
    }

    public function require_scripts() {
        $this->admin_scripts();
        wp_enqueue_script('shortcode-addons-elements', SA_ADDONS_URL . '/assets/backend/js/collection.js', false, SA_ADDONS_PLUGIN_VERSION);
    }

    /*
     * Shortcode Addons fontawesome Icon Render.
     *
     * @since 2.1.0
     */

    public function font_awesome_render($data) {
        $files = '<i class="' . $data . ' oxi-icons"></i>';
        return $files;
    }

    /*
     * Shortcode Addons name converter.
     *
     * @since 2.1.0
     */

    public function name_converter($data) {
        $data = str_replace('_', ' ', $data);
        $data = str_replace('-', ' ', $data);
        $data = str_replace('+', ' ', $data);
        return ucwords($data);
    }

    public function render() {
        ?>
        <div class="wrap">
            <?php
            apply_filters('shortcode-addons/admin_menu', false);
            ?>
            <div class="oxi-addons-wrapper">
                <div class="oxi-addons-row">
                    <input class="form-control" type="text" id='oxi_addons_search' placeholder="Search..">
                    <?php
                    foreach ($this->available_elements as $key => $elements) {
                        $elementshtml = '';
                        $elementsnumber = 0;
                        asort($elements);
                        foreach ($elements as $value) {
                            $oxilink = 'admin.php?page=shortcode-addons&oxitype=' . strtolower($value['name']);
                            $elementsnumber++;
                            $elementshtml .= ' <div class="oxi-addons-shortcode-import" id="' . $value['name'] . '" oxi-addons-search="' . strtolower($value['name']) . '">
                                                <a class="addons-pre-check ' . ((array_key_exists('premium', $value) && $value['premium'] == true && apply_filters('shortcode-addons/admin_version', false) == FALSE) ? 'addons-pre-check-pro' : '') . '" href="' . admin_url($oxilink) . '" sub-name="' . $value['name'] . '" sub-type="' . (array_key_exists($key, $this->installed_elements) ? array_key_exists($value['name'], $this->installed_elements[$key]) ? (version_compare($this->installed_elements[$key][$value['name']]['version'], $value['version']) >= 0) ? '' : 'update' : 'install' : 'install') . '">
                                                    <div class="oxi-addons-shortcode-import-top">
                                                       ' . $this->font_awesome_render((array_key_exists('icon', $value) ? $value['icon'] : 'fas fa-cloud-download-alt')) . '
                                                    </div>
                                                    <div class="oxi-addons-shortcode-import-bottom">
                                                        <span>' . $this->name_converter($value['name']) . '</span>
                                                    </div>
                                                </a>

                                           </div>';
                        }
                        if ($elementsnumber > 0) {
                            echo '  <div class="oxi-addons-text-blocks-body-wrapper">
                                    <div class="oxi-addons-text-blocks-body">
                                        <div class="oxi-addons-text-blocks">
                                            <div class="oxi-addons-text-blocks-heading">' . $key . '</div>
                                            <div class="oxi-addons-text-blocks-border">
                                                <div class="oxi-addons-text-block-border"></div>
                                            </div>
                                            <div class="oxi-addons-text-blocks-content">Available (' . $elementsnumber . ')</div>
                                        </div>
                                    </div>
                                </div>';
                            echo $elementshtml;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="OXIAADDONSCHANGEDPOPUP" class="modal fade">
            <div class="modal-dialog modal-confirm  bounceIn ">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="icon-box">

                        </div>
                    </div>
                    <div class="modal-body text-center">
                        <h4></h4>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
