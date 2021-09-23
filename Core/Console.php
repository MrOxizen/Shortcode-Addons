<?php

namespace SHORTCODE_ADDONS\Core;

/**
 * Description of Cloud
 *
 * @author biplo
 */
use SHORTCODE_ADDONS\Helper\Database as Database;

class Console extends Database {

    const SHORTCODE_TRANSIENT_AVAILABLE_ELEMENTS = 'shortcode_addons_available_elements';
    const SHORTCODE_TRANSIENT_INSTALLED_ELEMENTS = 'shortcode_addons_installed_elements';
    const SHORTCODE_TRANSIENT_MENU = 'get_oxilab_addons_menu';
    const SHORTCODE_TRANSIENT_GOOGLE_FONT = 'shortcode_addons_google_font';
    const API = 'http://127.0.0.1/shortcode-addons/wp-json/api/';
    const DOWNLOAD_SHORTCODE_ELEMENTS = 'http://127.0.0.1/shortcode-addons/shortcode-elements/elements/';

    /**
     * Plugin fixed debugging data
     *
     * @since 2.0.1
     */
    public function update_plugin() {
        $this->shortcode_elements(true);
        $this->google_fonts(true);
    }

    /**
     * Plugin fixed
     *
     * @since 2.0.1
     */
    public function fixed_data($agr) {
        return hex2bin($agr);
    }

    /**
     * Plugin fixed debugging data
     *
     * @since 2.0.1
     */
    public function fixed_debug_data($str) {
        return bin2hex($str);
    }

    /**
     * Get  template Elements List.
     * @return mixed
     * 
     *  @since 2.0.0
     */
    public function shortcode_elements($force_update = FALSE) {
        $response = get_transient(self::SHORTCODE_TRANSIENT_AVAILABLE_ELEMENTS);
        if (!$response || $force_update) {
            $URL = self::API . 'elements';
            $request = wp_remote_request($URL);
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                set_transient(self::SHORTCODE_TRANSIENT_AVAILABLE_ELEMENTS, $response, 30 * DAY_IN_SECONDS);
            } else {
                $response = $request->get_error_message();
            }
        }
        return $response;
    }

    /**
     * Shortcode Addons Elements.
     *
     * @since 2.0.0
     */
    public function installed_elements($force_update = FALSE) {

        $response = get_transient(self::SHORTCODE_TRANSIENT_INSTALLED_ELEMENTS);
        if (!$response || $force_update) :
            $elements = glob(SA_ADDONS_UPLOAD_PATH . '*', GLOB_ONLYDIR);

            $response = $catarray = $catnewdata = [];
            foreach ($elements as $value) {
                $file = explode('shortcode-addons/', $value);
                if (!empty($value)) {
                    if (!empty($value) && count($file) == 2) {
                        $vs = array('1..0', 'Custom Elements', false);
                        if (file_exists(SA_ADDONS_UPLOAD_PATH . $file[1] . '/Version.php')) {
                            $version = include_once SA_ADDONS_UPLOAD_PATH . $file[1] . '/Version.php';
                            if (is_array($version)) {
                                if ($version[2] == true) {
                                    $vs = $version;
                                }
                            }
                        }
                        $catarray[$vs[1]] = $vs[1];
                        $response[$vs[1]][$file[1]] = array(
                            'type' => 'shortcode-addons',
                            'name' => ucfirst($file[1]),
                            'homepage' => strtolower($file[1]),
                            'slug' => 'shortcode-addons',
                            'version' => $vs[0],
                            'control' => $vs[2]
                        );
                    }
                }
            }
            set_transient(self::SHORTCODE_TRANSIENT_INSTALLED_ELEMENTS, $response, 30 * DAY_IN_SECONDS);
        endif;
        return $response;
    }

    /**
     * Get  template google font.
     * @return mixed
     * 
     *  @since 2.0.0
     */
    public function google_fonts($force_update = FALSE) {
        $response = get_transient(self::SHORTCODE_TRANSIENT_GOOGLE_FONT);
        if (!$response || $force_update) {
            $URL = self::API . 'fonts';
            $request = wp_remote_request($URL);
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                set_transient(self::SHORTCODE_TRANSIENT_GOOGLE_FONT, $response, 30 * DAY_IN_SECONDS);
            } else {
                $response = $request->get_error_message();
            }
        }
        return $response;
    }

    /**
     * Elements in upload folder
     *
     * @since 2.0.0
     */
    public function post_get_elements() {

        if (is_dir(SA_ADDONS_UPLOAD_PATH . $this->rawdata)):
            $this->empty_dir(SA_ADDONS_UPLOAD_PATH . $this->rawdata);
        endif;

        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $tmpfile = download_url(self::DOWNLOAD_SHORTCODE_ELEMENTS . $this->rawdata . '.zip', $timeout = 500);
        if (is_string($tmpfile)):
            $permfile = 'oxilab.zip';
            $zip = new \ZipArchive();
            if ($zip->open($tmpfile) !== TRUE):
                return 'Problem 2';
            endif;
            $zip->extractTo(SA_ADDONS_UPLOAD_PATH);
            $zip->close();
            $this->installed_elements(true);
            return 'Done';
        endif;
    }

    /**
     * Elements in upload folder
     *
     * @since 2.0.0
     */
    public function post_elements_template_deactive() {

        $settings = json_decode(stripslashes($this->rawdata), true);
        $type = sanitize_title($settings['oxitype']);
        $name = sanitize_text_field($settings['oxideletestyle']);
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->import_table WHERE type = %s and name = %s", $type, $name));
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->import_table WHERE type = %s and name = %s", strtolower($type), strtolower(str_replace('_', '-', $name))));
        return 'Confirm';
    }

    /**
     * Check Template Active
     *
     * @since 2.0.0
     */
    public function post_elements_template_active($data = '') {
        $settings = json_decode(stripslashes($this->rawdata), true);
        $type = sanitize_title($settings['oxitype']);
        $name = sanitize_text_field($settings['oxiactivestyle']);
        $d = $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} (type, name) VALUES (%s, %s)", array($type, $name)));
        if ($d == 1):
            return admin_url('admin.php?page=shortcode-addons&oxitype=' . $type . '#' . $name . '');
        else:
            return 'Problem';
        endif;
    }

}
