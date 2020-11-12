<?php

namespace SHORTCODE_ADDONS\Core\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Font_Manager
 * Content of Shortcode Addons Plugins
 *
 * @author $biplob018
 */
class Font_Manager {

    /**
     * Define $wpdb
     *
     * @since 2.0.0
     */
    private $wpdb;

    /**
     * Database Import Table
     *
     * @since 2.0.0
     */
    public $import_table;

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

    /**
     * Constructor of plugin class
     *
     * @since 2.0.0
     */
    public function __construct($func = '', $rawdata = '', $type) {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->import_table = $this->wpdb->prefix . 'oxi_div_import';
        $this->loader();
        if (!empty($func) && !empty($rawdata)) {
            $this->$func($rawdata, $type);
        }
    }
    /**
     * Font Loader
     *
     * @since 2.1.0
     */
    public function loader() {
        $this->google_font = \SHORTCODE_ADDONS\Core\Admin\Shortcode_Remote::get_instance()->shortcode_addons_google_font();
        $type = 'shortcode-addons';
        $cache = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM  $this->import_table WHERE type = %s ", $type), ARRAY_A);
        foreach ($cache as $value) {
            $this->stored_font[$value['font']] = $value;
        }
    }
    /**
     * Google font selection.
     *
     * @since 2.1.0
     */
    public function selected_google_font($data = '', $type = '') {
        echo json_encode($this->stored_font);
    }
    /**
     * Get Google font.
     *
     * @since 2.1.0
     */
    public function get_google_font($data = '', $type = '') {

        if ($type != ''):
            $response = array();
            foreach ($this->google_font as $val) {
                if (stripos($val['font'], str_replace(' ', '+', $type)) !== false) {
                    $check = (array_key_exists($val['font'], $this->stored_font) ? 'yes' : 'no');
                    $response[$val['font']] = [
                        'font' => $val['font'],
                        'stored' => $check
                    ];
                }
            }
        else:
            $response = array();
            $start_count = ($data != 1 ? $data : 0);
            $fetch_count = 10;
            $font_slice_array = array_slice($this->google_font, $start_count, $fetch_count);
            foreach ($font_slice_array as $val) {
                $check = (array_key_exists($val['font'], $this->stored_font) ? 'yes' : 'no');
                $response[$val['font']] = [
                    'font' => $val['font'],
                    'stored' => $check
                ];
            }
        endif;
        echo json_encode($response);
    }
    /**
     * Add Google font.
     *
     * @since 2.1.0
     */
    public function add_google_font($data = '') {
        if ($data != '' && !empty($data)) {
            $data = sanitize_text_field($data);
            $font = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->import_table WHERE type = %s AND  font = %s ", 'shortcode-addons', $data), ARRAY_A);
            if (is_array($font)):
                echo 'Someone already Saved it';
            else:
                $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} ( type, font) VALUES (%s, %s)", array('shortcode-addons', $data)));
            endif;
        }
    }
    /**
     * Add Custom font.
     *
     * @since 2.1.0
     */
    public function add_custom_font($data = '', $type = '') {
        if ($data != '' && !empty($data)) {
            $data = sanitize_text_field($data);
            $font = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->import_table WHERE ( type, name, font) VALUES (%s, %s, %s) ", 'shortcode-addons', 'custom', $data), ARRAY_A);
            if (is_array($font)):
                echo 'Someone already Saved it';
            else:
                $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} ( type, name, font) VALUES (%s, %s, %s)", array('shortcode-addons', 'custom', $data)));
            endif;
        }
    }
    /**
     * Remove Google font.
     *
     * @since 2.1.0
     */
    public function remove_google_font($data = '', $type = '') {
        $data = sanitize_text_field($data);
        $font = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->import_table WHERE type = %s AND  font = %s ", 'shortcode-addons', $data), ARRAY_A);
        if (is_array($font)):
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->import_table} WHERE id = %d ", $font['id']));
        endif;
    }

}
