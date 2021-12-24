<?php

namespace SHORTCODE_ADDONS\Layouts;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Shortcode
 *
 * @author biplo
 */
use SHORTCODE_ADDONS\Helper\Database as Database;

class Shortcode extends Database {
    /*
     * Shortcode Addons file Check.
     *
     * @since 2.1.0
     */

    public function file_check($elements) {
        ob_start();
        $upload = new \SHORTCODE_ADDONS\Core\Console();
        $upload->post_get_elements($elements);
        ob_get_clean();
    }

    /**
     * Shortcode Call
     */
    public function oxi_addons($styleid = 0, $user = 'user') {
        if ($styleid == 0):
            return;
        endif;
        ob_start();
        $database = new \SHORTCODE_ADDONS\Helper\Database();
        $styledata = $database->wpdb->get_row($database->wpdb->prepare("SELECT * FROM $database->parent_table WHERE id = %d ", $styleid), ARRAY_A);
        $listdata = $database->wpdb->get_results("SELECT * FROM $database->child_table WHERE styleid= '$styleid'  ORDER by id ASC", ARRAY_A);

        if (is_array($styledata)) {
            $element = ucfirst(strtolower(str_replace('-', '_', $styledata['type'])));
            $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $element . '\Templates\\' . ucfirst(str_replace('-', '_', $styledata['style_name'])) . '';
            if (!class_exists($cls)):
                $this->file_check($element);
            endif;
            $CLASS = new $cls;
            $CLASS->__construct($styledata, $listdata, $user);
        } else {
            ?>

            <div class="oxi-addons-container">
                <div class="oxi-addons-error">
                    **<strong>Empty</strong> data found. Kindly check shortcode and put right shortcode with id from Shortcode Addons Elements**
                </div>
            </div>
            <?php
        }

        return ob_get_clean();
    }

}
