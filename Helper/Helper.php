<?php

namespace SHORTCODE_ADDONS\Helper;

if (!defined('ABSPATH')) {
    exit;
}

/**
 *
 * @author biplo
 */
trait Helper {

    public function allowed_html($rawdata) {
        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href' => array(),
                'rel' => array(),
                'title' => array(),
            ),
            'abbr' => array(
                'title' => array(),
            ),
            'b' => array(),
            'br' => array(),
            'blockquote' => array(
                'cite' => array(),
            ),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'del' => array(
                'datetime' => array(),
                'title' => array(),
            ),
            'dd' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
                'id' => array(),
            ),
            'table' => array(
                'class' => array(),
                'id' => array(),
                'style' => array(),
            ),
            'button' => array(
                'class' => array(),
                'type' => array(),
                'value' => array(),
            ),
            'thead' => array(),
            'tbody' => array(),
            'tr' => array(),
            'td' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'i' => array(
                'class' => array(),
            ),
            'img' => array(
                'alt' => array(),
                'class' => array(),
                'height' => array(),
                'src' => array(),
                'width' => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'q' => array(
                'cite' => array(),
                'title' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'strike' => array(),
            'strong' => array(),
            'ul' => array(
                'class' => array(),
            ),
        );
        if (is_array($rawdata)):
            return $rawdata = array_map(array($this, 'allowed_html'), $rawdata);
        else:
            return wp_kses($rawdata, $allowed_tags);
        endif;
    }

    public function validate_post($files = '') {

        $rawdata = [];
        if (!empty($files) && !is_array($files)):
            $data = json_decode(stripslashes($files), true);
        endif;
        if (is_array($data)):
            $rawdata = array_map(array($this, 'allowed_html'), $data);
        else:
            $rawdata = $this->allowed_html($files);
        endif;

        return $rawdata;
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

    public function shortcode_render($atts) {
        extract(shortcode_atts(array('id' => ' ',), $atts));
        $styleid = $atts['id'];
        ob_start();
        $CLASS = '\SHORTCODE_ADDONS\Includes\Shortcode';
        if (class_exists($CLASS)) :
            new $CLASS($styleid, 'user');
        endif;
        return ob_get_clean();
    }

    /**
     * Plugin menu Permission
     *
     * @since 2.0.0
     */
    public function menu_permission() {
        $user_role = get_option('oxi_addons_user_permission');
        $role_object = get_role($user_role);
        if (isset($role_object->capabilities) && is_array($role_object->capabilities)) :
            reset($role_object->capabilities);
            return key($role_object->capabilities);
        else :
            return 'manage_options';
        endif;
    }

    /**
     * Plugin admin menu
     *
     * @since 2.0.0
     */
    public function oxilab_admin_menu($agr) {

        $bgimage = SA_ADDONS_URL . 'image/sa-logo.png';
        $GETPage = $this->validate_post($_GET['page']);
        $oxitype = (!empty($_GET['oxitype']) ? $this->validate_post($_GET['oxitype']) : '');

        $response = [
            'Elements' => [
                'name' => 'Elements',
                'homepage' => 'shortcode-addons'
            ],
            'Import' => [
                'name' => 'Import',
                'homepage' => 'shortcode-addons-import'
            ]
        ];
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxilab-new-admin-menu">
                <div class="oxi-site-logo">
                    <a href="<?php echo esc_url(admin_url('admin.php?page=shortcode-addons')); ?>" class="header-logo" style=" background-image: url(<?php echo esc_url($bgimage); ?>);">
                    </a>
                </div>
                <nav class="oxilab-sa-admin-nav">
                    <ul class="oxilab-sa-admin-menu">
                        <?php
                        if ($oxitype != '' && $GETPage = 'shortcode-addons') :
                            echo '<li class="active"><a href="' . esc_url(admin_url('admin.php?page=shortcode-addons&oxitype=' . $oxitype)) . '">' . esc_html($this->name_converter($oxitype)) . '</a></li>';
                        endif;

                        foreach ($response as $key => $value) {
                            $active = ($GETPage == $value['homepage'] ? ' class="active" ' : '');

                            echo'<li ' . esc_attr($active) . '><a href="' . esc_url(admin_url('admin.php?page=' . $value['homepage'] . '')) . '">' . esc_html($this->name_converter($value['name'])) . '</a></li>';
                        }
                        ?>
                    </ul>
                    <ul class="oxilab-sa-admin-menu2">
                        <?php
                        if (apply_filters(SA_ADDONS_PLUGIN_ADMIN, false) == FALSE):
                            ?>
                            <li class="fazil-class" ><a target="_blank" href="https://www.oxilabdemos.com/accordions/pricing">Upgrade</a></li>
                            <?php
                        endif;
                        ?>
                        <li class="saadmin-doc"><a target="_black" href="https://www.oxilabdemos.com/shortcode-addons/docs/">Docs</a></li>
                        <li class="saadmin-doc"><a target="_black" href="https://wordpress.org/support/plugin/shortcode-addons/">Support</a></li>
                        <li class="saadmin-set"><a href="<?php echo esc_url(admin_url('admin.php?page=shortcode-addons-settings')); ?>"><span class="dashicons dashicons-admin-generic"></span></a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <?php
    }

    /**
     * Plugin admin menu
     *
     * @since 2.0.0
     */
    public function admin_menu() {
        $permission = $this->menu_permission();
        add_menu_page('Shortcode Addons', 'Shortcode Addons', $permission, 'shortcode-addons', [$this, 'addons_elements']);
        add_submenu_page('shortcode-addons', 'Elements', 'Elements', $permission, 'shortcode-addons', [$this, 'addons_elements']);
        add_submenu_page('shortcode-addons', 'Import', 'Import', $permission, 'shortcode-addons-import', [$this, 'addons_import']);
        add_submenu_page('shortcode-addons', 'Font Manager', 'Font Manager', $permission, 'shortcode-addons-font', [$this, 'addons_font']);
        add_submenu_page('shortcode-addons', 'Settings', 'Settings', $permission, 'shortcode-addons-settings', [$this, 'addons_settings']);
        add_submenu_page('shortcode-addons', 'Oxilab Plugins', 'Oxilab Plugins', $permission, 'shortcode-addons-plugins', [$this, 'oxilab_plugins']);
        add_submenu_page('shortcode-addons', 'Addons Support', 'Addons Support', $permission, 'shortcode-addons-support', [$this, 'addons_support']);
    }

    /**
     * Plugin admin Menu Home
     *
     * @since 2.0.0
     */
    public function addons_elements() {
        $oxitype = ucfirst(strtolower(!empty($_GET['oxitype']) ? $this->validate_post($_GET['oxitype']) : ''));
        $style = (!empty($_GET['styleid']) ? (int) $_GET['styleid'] : '');
        if (!empty($oxitype) && empty($style)):
            $clsss = '\SHORTCODE_ADDONS_UPLOAD\\' . $oxitype . '\\' . $oxitype . '';

            if (class_exists($clsss)):
                $elements = new $clsss();
                $elements->elements();
            else:
                $url = admin_url('admin.php?page=shortcode-addons');
                echo '<script type="text/javascript"> document.location.href = "' . $url . '"; </script>';
                exit;
            endif;
        elseif (!empty($oxitype) && !empty($style)):
            $database = new \SHORTCODE_ADDONS\Helper\Database();
            $query = $database->wpdb->get_row($database->wpdb->prepare("SELECT style_name, type, id FROM $database->parent_table WHERE id = %d ", $style), ARRAY_A);
            if (array_key_exists('style_name', $query) && strtolower($oxitype) != strtolower($query['type'])):
                $url = admin_url('admin.php?page=shortcode-addons&oxitype=' . $query['type'] . '&styleid=' . $style);
                echo '<script type="text/javascript"> document.location.href = "' . $url . '"; </script>';
                exit;
            endif;
            if (array_key_exists('style_name', $query)):
                $StyleName = ucfirst(str_replace('-', "_", $query['style_name']));
                $clsss = '\SHORTCODE_ADDONS_UPLOAD\\' . $oxitype . '\Admin\\' . $StyleName . '';
                if (class_exists($clsss)):
                    new $clsss();
                else:
                    $this->file_check($oxitype);
                    $url = admin_url('admin.php?page=shortcode-addons');
                    echo '<script type="text/javascript"> document.location.href = "' . $url . '"; </script>';
                    exit;
                endif;
            else:
                $url = admin_url('admin.php?page=shortcode-addons');
                echo '<script type="text/javascript"> document.location.href = "' . $url . '"; </script>';
                exit;
            endif;
        else:
            $elements = new \SHORTCODE_ADDONS\Layouts\Collection();
            $elements->element_page();
        endif;
    }

    /**
     * Plugin Import Addons
     *
     * @since 2.1.0
     */
    public function addons_import() {


        $elements = new \SHORTCODE_ADDONS\Layouts\Import();
        $elements->element_page();
    }

    /**
     * Plugin admin Menu Extension
     *
     * @since 2.0.0
     */
    public function addons_settings() {
        new \SHORTCODE_ADDONS\Layouts\Settings();
    }

    /**
     * Plugin admin Menu Extension
     *
     * @since 2.0.0
     */
    public function addons_font() {
        $elements = new \SHORTCODE_ADDONS\Layouts\GoogleFont();
        $elements->font_manager();
    }

    public function addons_support() {
        new \SHORTCODE_ADDONS\Layouts\Support();
    }

    public function oxilab_plugins() {
        if (current_user_can('activate_plugins')):
            new \SHORTCODE_ADDONS\Layouts\Plugins();
        endif;
    }

    /**
     * shortcode addons menu Icon
     * @since 1.0.0
     */
    public function admin_icon() {
        ?>
        <style type='text/css' media='screen'>
            #adminmenu #toplevel_page_shortcode-addons div.wp-menu-image:before {
                content: "\f486";
            }
        </style>
        <?php
    }

    public function supportandcomments($agr) {
        ?>
        <div class="oxi-addons-admin-notifications">
            <h3>
                <span class="dashicons dashicons-flag"></span>
                Notifications
            </h3>
            <p></p>
            <div class="oxi-addons-admin-notifications-holder">
                <div class="oxi-addons-admin-notifications-alert">
                    <p>Unable to create your desire design or need any help? I Just wanted to see if you have any questions or concerns about my plugins. If you do, Please do not hesitate to <a href="https://wordpress.org/support/plugin/shortcode-addons#new-post">file a bug report or help</a>. Our dedicated team will helps you to solve your issues. </p>
                    <?php
                    if (apply_filters(SA_ADDONS_PLUGIN_ADMIN, false) != true):
                        echo '<p>By the way, did you know we also have a <a href="https://www.oxilabdemos.com/shortcode-addons/pricing/">Premium Version</a>? It offers lots of options with automatic update. It also comes with 16/5 personal support.</p>';
                    endif;
                    ?>
                    <p>Thanks Again!</p>
                    <p></p>
                </div>
            </div>
            <p></p>
        </div>
        <?php
    }

    /**
     * Plugin check Current Accordions
     *
     * @since 2.0.1
     */
    public function check_current_version($agr) {
        $vs = get_option('oxi_addons_license_status');
        if ($vs == $this->fixed_data('76616c6964')) {
            return true;
        } else {
            return false;
        }
    }

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
    public function oxi_addons_shortcode($atts) {
        extract(shortcode_atts(array('id' => ' ',), $atts));
        $styleid = (int) $atts['id'];
        $shortcode = new \SHORTCODE_ADDONS\Layouts\Shortcode();
        return $shortcode->oxi_addons($styleid);
    }

    public function User_Reviews() {
        if (!current_user_can('activate_plugins')):
            return;
        endif;

        $this->admin_recommended();
        $this->admin_notice();
    }

    public function admin_recommended() {
        if (!empty($this->admin_recommended_status())):
            return;
        endif;

        if (strtotime('-1 days') < $this->installation_date()):
            return;
        endif;
        new \SHORTCODE_ADDONS\Oxilab\Recommended();
    }

    /**
     * Admin Notice Check
     *
     * @since 2.0.0
     */
    public function admin_recommended_status() {
        $data = get_option('shortcode_addons_recommended');
        return $data;
    }

    /**
     * Admin Notice Check
     *
     * @since 2.0.0
     */
    public function admin_notice_status() {
        $data = get_option('shortcode_addons_no_bug');
        return $data;
    }

    /**
     * Admin Install date Check
     *
     * @since 2.0.0
     */
    public function installation_date() {
        $data = get_option('shortcode_addons_activation_date');
        if (empty($data)):
            $data = strtotime("now");
            update_option('shortcode_addons_activation_date', $data);
        endif;
        return $data;
    }

    public function admin_notice() {
        if (!empty($this->admin_notice_status())):
            return;
        endif;
        if (strtotime('-7 days') < $this->installation_date()):
            return;
        endif;
        new \SHORTCODE_ADDONS\Oxilab\Reviews();
    }

    public function redirect_on_activation() {
        if (get_transient('shortcode_adddons_activation_redirect')) :
            delete_transient('shortcode_adddons_activation_redirect');
            if (is_network_admin() || isset($_GET['activate-multi'])) :
                return;
            endif;
            wp_safe_redirect(admin_url("admin.php?page=shortcode-addons-support"));
        endif;
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

            if (strpos($classname, 'SHORTCODE_ADDONS_UPLOAD') === false):
                return new \WP_REST_Request('Invalid URL', 422);
            endif;

            if (!(strpos($classname, 'Ajax') !== false || strpos($classname, 'Files') !== false)):
                return new \WP_REST_Request('Invalid URL', 422);
            endif;

            if (!empty($classname) && !empty($functionname)):
                $classname::$functionname($rawdata, $optional, $optional2);
            endif;
        else:
            return;
        endif;
        die();
    }

}
