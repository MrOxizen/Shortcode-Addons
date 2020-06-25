<?php

namespace SHORTCODE_ADDONS\Support;

/**
 *
 * @author biplo
 */
trait Shortcode {

    /**
     * Shortcode Call
     */
    public function oxi_addons_shortcode($atts) {
        extract(shortcode_atts(array('id' => ' ',), $atts));
        $styleid = (int) $atts['id'];
        ob_start();
       
        $styledata = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->parent_table WHERE id = %d ", $styleid), ARRAY_A);
        $listdata = $this->wpdb->get_results("SELECT * FROM $this->child_table WHERE styleid= '$styleid'  ORDER by id ASC", ARRAY_A);
        $shortcode = '';
        if (is_array($styledata)) {
            $element = ucfirst(strtolower(str_replace('-', '_', $styledata['type'])));
            $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $element . '\Templates\\' . ucfirst(str_replace('-', '_', $styledata['style_name'])) . '';
            if (!class_exists($cls)):
                $this->file_check($element);
            else:
                $CLASS = new $cls;
                $CLASS->__construct($styledata, $listdata, 'user');
            endif;
        } else {
            $shortcode .= $styleid;
            $shortcode .= '<div class="oxi-addons-container">
                                <div class="oxi-addons-error">
                                    **<strong>Empty</strong> data found. Kindly check shortcode and put right shortcode with id from Shortcode Addons Elements** 
                                </div>
                            </div>';
        }
        echo $shortcode;
        return ob_get_clean();
    }
    /*
     * Shortcode Addons file Check.
     * 
     * @since 2.1.0
     */
    public function file_check($elements) {
        ob_start();
        $sd = new \SHORTCODE_ADDONS\Core\Admin\Admin_Ajax('elements', $elements);
        ob_get_clean();
    }

}
