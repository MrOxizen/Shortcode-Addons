<?php

namespace SHORTCODE_ADDONS\Helper;

if (!defined('ABSPATH')) {
    exit;
}

/**
 *
 * @author biplo
 */
class Database {

    /**
     * Define $wpdb
     *
     * @since 2.0.1
     */
    public $wpdb;

    /**
     * Database Parent Table
     *
     * @since 2.0.1
     */
    public $parent_table;

    /**
     * Database Import Table
     *
     * @since 2.0.1
     */
    public $import_table;

    /**
     * Database Import Table
     *
     * @since 2.0.1
     */
    public $child_table;

    /**
     * Plugins Loader
     *
     * $instance
     *
     * @since 2.0.0
     */
    private static $instance = null;

    /**
     * Singleton instance
     *
     * @since 2.0.0
     */
    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->parent_table = $wpdb->prefix . 'oxi_div_style';
        $this->child_table = $wpdb->prefix . 'oxi_div_list';
        $this->import_table = $wpdb->prefix . 'oxi_div_import';
    }

    public function update_database() {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql1 = "CREATE TABLE $this->parent_table (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                  name varchar(50) NOT NULL,
                type varchar(50) NOT NULL,
                style_name varchar(40),
                rawdata longtext,
                stylesheet longtext,
                font_family text,
		PRIMARY KEY  (id)
	) $charset_collate;";

        $sql2 = "CREATE TABLE $this->child_table (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                styleid mediumint(6) NOT NULL,
                type varchar(50) NOT NULL,
                rawdata text,
                stylesheet text,
		PRIMARY KEY  (id)
	) $charset_collate;";

        $sql3 = "CREATE TABLE $this->import_table (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                type varchar(50) NULL,
                name varchar(100) NULL,
                font varchar(200) NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql1);
        dbDelta($sql2);
        dbDelta($sql3);

        update_option('SA_ADDONS_PLUGIN_VERSION', SA_ADDONS_PLUGIN_VERSION);
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
     * Generate safe path
     * @since v1.0.0
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    public function array_replace($arr = [], $search = '', $replace = '') {
        array_walk($arr, function (&$v) use ($search, $replace) {
            $v = str_replace($search, $replace, $v);
        });
        return $arr;
    }

    public function name_converter($data) {
        $data = str_replace('_', ' ', $data);
        $data = str_replace('-', ' ', $data);
        $data = str_replace('+', ' ', $data);
        return ucwords($data);
    }

    public function font_familly_validation($data = []) {
        foreach ($data as $value) {
            wp_enqueue_style('' . $value . '', 'https://fonts.googleapis.com/css?family=' . $value . '');
        }
    }

    public function admin_name_validation($data) {
        $data = str_replace('_', ' ', $data);
        $data = str_replace('-', ' ', $data);
        $data = str_replace('+', ' ', $data);
        return ucwords($data);
    }

}
