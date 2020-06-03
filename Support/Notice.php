<?php

namespace SHORTCODE_ADDONS\Support;

if (!defined('ABSPATH')) {
    exit;
}

/**
 *
 * @author biplobadhikari
 */
trait Notice {

    /**
     * Admin Notice Check
     *
     * @since 2.0.0
     */
    public function admin_notice_status() {
        $data = get_option($this->fixed_data('6f78695f6164646f6e735f73686f7274636f64655f72657669657773'));
        return $data;
    }

    /**
     * Admin Install date Check
     *
     * @since 2.0.0
     */
    public function installation_date() {
        $data = get_option($this->fixed_data('6f78695f6164646f6e735f73686f7274636f64655f61637469766174696f6e5f64617465'));
        if (empty($data)):
            $data = strtotime("now");
            update_option($this->fixed_data('6f78695f6164646f6e735f73686f7274636f64655f61637469766174696f6e5f64617465'), $data);
        endif;
        return $data;
    }

    /**
     * Admin Notice
     *
     * @since 2.0.0
     */
    public function admin_notice() {
        if (!empty($this->admin_notice_status())):
            return;
        endif;
        if (strtotime('-7 days') < $this->installation_date()):
            return;
        endif;
        new \SHORTCODE_ADDONS\Core\Notice\Support_Reviews();
    }

}
