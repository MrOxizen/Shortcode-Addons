<?php

namespace SHORTCODE_ADDONS\Support;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
/**
 *  Description of Admin.
 * @author biplobadhikari
 */

trait Admin {

    /**
     * Extending plugin Textdomain
     *
     * @since 2.0.0
     */
    public function i18n() {
        load_plugin_textdomain('shortcode-addons');
    }

    /**
     * Redirect to Shortcode Addons page
     *
     * @since v2.0.0
     */
    public function redirect_on_activation() {
        if (get_transient('shortcode_adddons_activation_redirect')):
            delete_transient('shortcode_adddons_activation_redirect');
            if (is_network_admin() || isset($_GET['activate-multi'])):
                return;
            endif;
            wp_safe_redirect(admin_url("admin.php?page=shortcode-addons"));
        endif;
    }

    /**
     * Add plugin home page at plugins settings page
     *
     * @since 2.0.0
     */
    public function insert_plugin_links($links) {
        // settings
        $links[] = sprintf('<a href="admin.php?page=shortcode-addons">' . __('Settings') . '</a>');

        // go pro
        if (apply_filters('shortcode-addons/pro_enabled', false) == false) {
            $links[] = sprintf('<a href="https://www.oxilab.org/downloads/short-code-addons/" target="_blank" style="color: #39b54a; font-weight: bold;">' . __('Upgrade Now!') . '</a>');
        }

        return $links;
    }

    /**
     * Plugins row meta data
     *
     * @since 2.0.0
     */
    public function insert_plugin_row_meta($links, $file) {
        if (SA_ADDONS_BASENAME == $file) {
            // docs 
            $links[] = sprintf('<a href="https://www.shortcode-addons.com/docs/" target="_blank">' . __('Docs') . '</a>');

            // video tutorials
            $links[] = sprintf('<a href="https://www.youtube.com/watch?v=BhgngA_cF1c" target="_blank">' . __('Video Tutorials') . '</a>');
        }

        return $links;
    }

    /**
     * Plugin Pre Installation
     *
     * @since 2.0.0
     */
    public function sql_default_data() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'oxi_div_style';
        $table_list = $wpdb->prefix . 'oxi_div_list';
        $table_import = $wpdb->prefix . 'oxi_div_import';
        $charset_collate = $wpdb->get_charset_collate();
        $sql1 = "CREATE TABLE $table_name (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                name varchar(50) NOT NULL,
                type varchar(50) NOT NULL,
                style_name varchar(40),
                rawdata longtext,
                stylesheet longtext,
                font_family text,
		PRIMARY KEY  (id)
	) $charset_collate;";

        $sql2 = "CREATE TABLE $table_list (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                styleid mediumint(6) NOT NULL,
                type varchar(50) NOT NULL,
                rawdata text,
                stylesheet text,
		PRIMARY KEY  (id)
	) $charset_collate;";
        $sql3 = "CREATE TABLE $table_import (
		id mediumint(5) NOT NULL AUTO_INCREMENT,
                type varchar(50) NULL,
                name varchar(100) NULL,
                font varchar(200) NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql1);
        dbDelta($sql2);
        dbDelta($sql3);
        update_option("oxi_div_database", SA_ADDONS_PLUGIN_VERSION);
        $yes = 'yes';
        add_option('oxi_addons_admin_version', $yes);
        add_option('oxi_addons_bootstrap', $yes);
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
     * Plugin Pre Row Data Debug
     *
     * @since 2.0.0
     */
    public function shortcode_addons_row_data($agr) {
        $vs = get_option($this->fixed_data('6f78695f6164646f6e735f6c6963656e73655f737461747573'));
        if ($vs == $this->fixed_data('76616c6964')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Plugin Elements Name Convert to View
     *
     * @since 2.0.0
     */
    public function name_converter($data) {
        $data = str_replace('_', ' ', $data);
        $data = str_replace('-', ' ', $data);
        $data = str_replace('+', ' ', $data);
        return ucwords($data);
    }

    /**
     * Plugin admin menu
     *
     * @since 2.0.0
     */
    public function admin_nav_menu($agr) {
        $elements = \SHORTCODE_ADDONS\Core\Admin\Shortcode_Remote::get_instance()->Menu();
        if (!array_key_exists('Shortcode', $elements)):
            $elements = \SHORTCODE_ADDONS\Core\Admin\Shortcode_Remote::get_instance()->Menu(TRUE);
        endif;
        $bgimage = SA_ADDONS_URL . 'image/sa-logo.png';
        $sub = '';

        $menu = '<div class="oxi-addons-wrapper">
                    <div class="oxilab-new-admin-menu">
                        <div class="oxi-site-logo">
                            <a href="' . admin_url('admin.php?page=shortcode-addons') . '" class="header-logo" style=" background-image: url(' . $bgimage . ');">
                            </a>
                        </div>
                        <nav class="oxilab-sa-admin-nav">
                            <ul class="oxilab-sa-admin-menu">';


        $GETPage = sanitize_text_field($_GET['page']);
        $oxitype = (!empty($_GET['oxitype']) ? sanitize_text_field($_GET['oxitype']) : '');

        if (count($elements) == 1):
            if ($oxitype != ''):
                $menu .= '<li class="active"><a href="' . admin_url('admin.php?page=shortcode-addons&oxitype=' . $oxitype) . '">' . $this->name_converter($oxitype) . '</a></li>';
            endif;
            foreach ($elements['Shortcode'] as $key => $value) {
                $active = ($GETPage == $value['homepage'] ? (empty($oxitype) ? ' class="active" ' : '') : '');
                $menu .= '<li ' . $active . '><a href="' . admin_url('admin.php?page=' . $value['homepage'] . '') . '">' . $this->name_converter($value['name']) . '</a></li>';
            }
        else:
            foreach ($elements as $key => $value) {
                $active = ($key == 'Shortcode' ? 'active' : '');
                $menu .= '<li class="' . $active . '"><a class="oxi-nev-drop-menu" href="#">' . $this->name_converter($key) . '</a>';
                $menu .= '     <div class="oxi-nev-d-menu">
                                                    <div class="oxi-nev-drop-menu-li">';
                foreach ($value as $key2 => $submenu) {
                    $menu .= '<a href="' . admin_url('admin.php?page=' . $submenu['homepage'] . '') . '">' . $this->name_converter($submenu['name']) . '</a>';
                }
                $menu .= '                                                                                                  </div>';
                $menu .= '</li>';
            }
            if ($GETPage == 'shortcode-addons' || $GETPage == 'shortcode-addons-import' || $GETPage == 'shortcode-addons-extension'):
                $sub .= '<div class="shortcode-addons-main-tab-header">';
                if ($oxitype != ''):
                    $sub .= '<a href="' . admin_url('admin.php?page=shortcode-addons&oxitype=' . $oxitype) . '">
                                <div class="shortcode-addons-header oxi-active">' . $this->name_converter($oxitype) . '</div>
                              </a>';
                endif;
                foreach ($elements['Shortcode'] as $key => $value) {
                    $active = ($GETPage == $value['homepage'] ? (empty($oxitype) ? 'oxi-active' : '') : '');
                    $sub .= '<a href="' . admin_url('admin.php?page=' . $value['homepage'] . '') . '">
                                <div class="shortcode-addons-header ' . $active . '">' . $this->name_converter($value['name']) . '</div>
                              </a>';
                }
                $sub .= '</div>';
            endif;
        endif;
        $menu .= '              </ul>
                            <ul class="oxilab-sa-admin-menu2">
                               ' . (apply_filters('shortcode-addons/pro_enabled', false) == FALSE ? ' <li class="fazil-class" ><a target="_blank" href="https://www.oxilab.org/downloads/short-code-addons/">Upgrade</a></li>' : '') . '
                               <li class="saadmin-doc"><a target="_black" href="https://www.shortcode-addons.com/docs/">Docs</a></li>
                               <li class="saadmin-doc"><a target="_black" href="https://wordpress.org/support/plugin/shortcode-addons/">Support</a></li>
                               <li class="saadmin-set"><a href="' . admin_url('admin.php?page=shortcode-addons-settings') . '"><span class="dashicons dashicons-admin-generic"></span></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                ' . $sub;
        echo __($menu, SHORTCODE_ADDOONS);
    }

    /**
     * Plugin fixed
     *
     * @since 2.0.0
     */
    public function fixed_data($agr) {
        return hex2bin($agr);
    }

    /**
     * Plugin fixed debugging data
     *
     * @since 2.0.0
     */
    public function fixed_debug_data($str) {
        return bin2hex($str);
    }

    /**
     * Plugin menu Permission
     *
     * @since 2.0.0
     */
    public function menu_permission() {
        $user_role = get_option('oxi_addons_user_permission');
        $role_object = get_role($user_role);
        if (isset($role_object->capabilities) && is_array($role_object->capabilities)):
            reset($role_object->capabilities);
            return key($role_object->capabilities);
        else:
            return 'manage_options';
        endif;
    }

    /**
     * Plugin admin menu
     *
     * @since 2.0.0
     */
    public function admin_menu() {
        $permission = $this->menu_permission();
        add_menu_page('Shortcode Addons', 'Shortcode Addons', $permission, 'shortcode-addons', [$this, 'addons_home']);
        add_submenu_page('shortcode-addons', 'Elements', 'Elements', $permission, 'shortcode-addons', [$this, 'addons_home']);
        add_submenu_page('shortcode-addons', 'Import', 'Import', $permission, 'shortcode-addons-import', [$this, 'addons_import']);
        add_submenu_page('shortcode-addons', 'Extension', 'Extension', $permission, 'shortcode-addons-extension', [$this, 'addons_extension']);
        add_submenu_page('shortcode-addons', 'Settings', 'Settings', $permission, 'shortcode-addons-settings', [$this, 'addons_settings']);
    }

    /**
     * Plugin admin Menu Home
     *
     * @since 2.0.0
     */
    public function addons_home() {
        $oxitype = ucfirst(strtolower(!empty($_GET['oxitype']) ? sanitize_text_field($_GET['oxitype']) : ''));
        $style = (!empty($_GET['styleid']) ? (int) $_GET['styleid'] : '');
        if (!empty($oxitype) && empty($style)):
            $clsss = '\SHORTCODE_ADDONS_UPLOAD\\' . $oxitype . '\\' . $oxitype . '';
            if (class_exists($clsss)):
                new $clsss();
            else:
                $url = admin_url('admin.php?page=shortcode-addons');
                echo '<script type="text/javascript"> document.location.href = "' . $url . '"; </script>';
                exit;
            endif;
        elseif (!empty($oxitype) && !empty($style)):
            $query = $this->wpdb->get_row($this->wpdb->prepare("SELECT style_name, type FROM $this->parent_table WHERE id = %d ", $style), ARRAY_A);
            if (array_key_exists('style_name', $query) && strtolower($oxitype) != strtolower($query['type'])):
                $url = admin_url('admin.php?page=shortcode-addons&oxitype=' . $query['type'] . '&styleid=' . $style);
                echo '<script type="text/javascript"> document.location.href = "' . $url . '"; </script>';
                exit;
            endif;
            if (array_key_exists('style_name', $query)):
                $StyleName = ucfirst(str_replace('-', "_", $query['style_name']));
                $clsss = '\SHORTCODE_ADDONS_UPLOAD\\' . $oxitype . '\Admin\\' . $StyleName . '';
                new $clsss();
            else:
                $url = admin_url('admin.php?page=shortcode-addons');
                echo '<script type="text/javascript"> document.location.href = "' . $url . '"; </script>';
                exit;
            endif;
        else:
            new \SHORTCODE_ADDONS\Core\Admin\Home();
        endif;
    }
    /**
     * Plugin Import Addons
     *
     * @since 2.1.0
     */
    public function addons_import() {
        new \SHORTCODE_ADDONS\Core\Admin\Import();
    }
    /**
     * Plugin extensions
     *
     * @since 2.1.0
     */
    public function addons_extension() {
        new \SHORTCODE_ADDONS\Core\Admin\Extension();
    }

    /**
     * Plugin admin Menu Extension
     *
     * @since 2.0.0
     */
    public function addons_settings() {
        new \SHORTCODE_ADDONS\Core\Admin\Settings();
    }

    /**
     * shortcode addons menu Icon
     * @since 1.0.0
     */
    public function menu_icon() {
        ?>
        <style type='text/css' media='screen'>
            #adminmenu #toplevel_page_shortcode-addons  div.wp-menu-image:before {
                content: "\f486";
            }
        </style>
        <?php

    }

    /**
     * shortcode addons Data Process
     * 
     * @since 2.0.0
     */
    public function data_process() {
        if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), 'shortcode-addons-editor')):
            $functionname = isset($_POST['functionname']) ? sanitize_text_field($_POST['functionname']) : '';
            $rawdata = isset($_POST['rawdata']) ? sanitize_post($_POST['rawdata']) : '';
            $styleid = isset($_POST['styleid']) ? (int) $_POST['styleid'] : '';
            $childid = isset($_POST['childid']) ? (int) $_POST['childid'] : '';
            if (!empty($functionname) && !empty($rawdata)):
                new \SHORTCODE_ADDONS\Core\Admin\Admin_Ajax($functionname, $rawdata, $styleid, $childid);
            endif;
        else:
            return;
        endif;
        die();
    }

    /**
     * shortcode addons font Manager
     * 
     * @since 2.0.0
     */
    public function font_manager() {
        if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), 'shortcode-addons-font-manager')):
            $functionname = isset($_POST['functionname']) ? sanitize_text_field($_POST['functionname']) : '';
            $rawdata = isset($_POST['rawdata']) ? sanitize_text_field($_POST['rawdata']) : '';
            $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
            if (!empty($functionname) && !empty($rawdata)):
                new \SHORTCODE_ADDONS\Core\Admin\Font_Manager($functionname, $rawdata, $type);
            endif;
        else:
            return;
        endif;
        die();
    }

    /**
     * shortcode addons Data Process
     * 
     * @since 2.0.0
     */
    public function shortcode_addons_data_process() {

        if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), 'shortcode-addons-data')):
            $classname = isset($_POST['classname']) ? '\\' . str_replace('\\\\', '\\', sanitize_text_field($_POST['classname'])) : '';
            $functionname = isset($_POST['functionname']) ? sanitize_text_field($_POST['functionname']) : '';
            $rawdata = isset($_POST['rawdata']) ? sanitize_post($_POST['rawdata']) : '';
            $optional = isset($_POST['optional']) ? sanitize_post($_POST['optional']) : '';
            $optional2 = isset($_POST['optional2']) ? sanitize_post($_POST['optional2']) : '';
            if (!empty($classname) && !empty($functionname)):
                $classname::$functionname($rawdata, $optional, $optional2);
            endif;
        else:
            return;
        endif;
        die();
    }

}
