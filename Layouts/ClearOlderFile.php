<?php

namespace SHORTCODE_ADDONS\Layouts;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of ClearOlderFile
 *
 * @author biplo
 */
use SHORTCODE_ADDONS\Core\Console as Console;

class ClearOlderFile extends Console {

    public function clearolderfiles() {
        $this->clear_elements();
        $this->create_upload_folder();
        $this->delete_transient();
    }

    /**
     * Remove files in dir
     *
     * @since 1.0.0
     */
    public function empty_dir($str) {

        if (is_file($str)) {
            return unlink($str);
        } elseif (is_dir($str)) {
            $scan = glob(rtrim($str, '/') . '/*');
            foreach ($scan as $index => $path) {
                $this->empty_dir($path);
            }
            return @rmdir($str);
        }
    }

    /**
     * Shortcode Addons Elements.
     *
     * @since 2.0.0
     */
    public function clear_elements() {
        $elements = $this->shortcode_elements();

        foreach ($elements as $key => $value) {
            if ($key != 'Custom Elements') {
                foreach ($value as $k => $elements) {
                    if (is_dir(SA_ADDONS_UPLOAD_PATH . $k)):
                        $this->empty_dir(SA_ADDONS_UPLOAD_PATH . $k);
                    endif;
                }
            }
        }
    }

    /**
     * Plugin Create Upload Folder
     *
     * @since 2.0.0
     */
    public function create_upload_folder() {
        $upload = wp_upload_dir();
        $upload_dir = $upload['basedir'];
        $dir = $upload_dir . '/shortcode-addons';
        if (!is_dir($dir)) {
            mkdir($dir, 0777);
        }
    }

//
}
