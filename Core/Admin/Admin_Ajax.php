<?php

namespace SHORTCODE_ADDONS\Core\Admin;

/**
 * Description of Admin Ajax
 * @author biplob018
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Admin_Ajax {

    /**
     * Define $wpdb
     *
     * @since 2.0.0
     */
    private $wpdb;

    /**
     * Database Parent Table
     *
     * @since 2.0.0
     */
    public $parent_table;

    /**
     * Database Import Table
     *
     * @since 2.0.0
     */
    public $import_table;

    /**
     * Database Child Table
     *
     * @since 2.0.0
     */
    public $child_table;

    /**
     * Constructor of plugin class
     *
     * @since 2.0.0
     */
    public function __construct($type = '', $data = '', $styleid = '', $itemid = '') {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->parent_table = $this->wpdb->prefix . 'oxi_div_style';
        $this->child_table = $this->wpdb->prefix . 'oxi_div_list';
        $this->import_table = $this->wpdb->prefix . 'oxi_div_import';
        if (!empty($type) && !empty($data)) {
            $this->$type($data, $styleid, $itemid);
        }
    }
    /**
     * Elements in upload folder
     *
     * @since 2.0.0
     */
    public function elements($data = '') {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $tmpfile = download_url('https://shortcode-addons.com/Shortcode-Addons/Elements/' . $data . '.zip', $timeout = 500);
        if (is_string($tmpfile)):
            $permfile = 'oxilab.zip';
            $zip = new \ZipArchive();
            if ($zip->open($tmpfile) !== TRUE):
                echo 'Problem 2';
            endif;
            $zip->extractTo(SA_ADDONS_UPLOAD_PATH);
            $zip->close();
            echo 'Done';
        endif;
    }

    /**
     * Remove files in dir
     *
     * @since 2.0.0
     */
    public function empty_dir($path) {
        if (!is_dir($path) || !file_exists($path)):
            return;
        endif;
        WP_Filesystem();
        global $wp_filesystem;
        $wp_filesystem->rmdir($path, true);
    }

    /**
     * Check dir
     *
     * @since 2.0.0
     */
    public function check_dir($path) {
        return (is_dir($path) ? TRUE : FALSE);
    }

    /**
     * Check Template Active
     *
     * @since 2.0.0
     */
    public function elements_template_active($data = '') {
        $settings = json_decode(stripslashes($data), true);
        $type = sanitize_title($settings['oxitype']);
        $name = sanitize_text_field($settings['oxiactivestyle']);
        $d = $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} (type, name) VALUES (%s, %s)", array($type, $name)));
        if ($d == 1):
            echo admin_url('admin.php?page=shortcode-addons&oxitype=' . $type . '#' . $name . '');
        else:
            echo 'Problem';
        endif;
    }

    /**
     * Check template Deactive
     *
     * @since 2.0.0
     */
    public function elements_template_deactive($data = '') {
        $settings = json_decode(stripslashes($data), true);
        $type = sanitize_title($settings['oxitype']);
        $name = sanitize_text_field($settings['oxideletestyle']);
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->import_table WHERE type = %s and name = %s", $type, $name));
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->import_table WHERE type = %s and name = %s", strtolower($type), strtolower(str_replace('_', '-', $name))));
        echo 'Confirm';
    }

    /**
     * Create New Template
     *
     * @since 2.0.0
     */
    public function elements_template_create($data = '') {
        $settings = json_decode(stripslashes($data), true);
        $elements = sanitize_text_field($settings['addons-oxi-type']);
        $styleid = (int) $settings['oxistyleid'];
        $row = json_decode($settings['oxi-addons-data'], true);
        if ($elements != ''):
            if ($styleid != ''):
                $newdata = $this->dbdata = $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->parent_table . ' WHERE id = %d ', $styleid), ARRAY_A);
                $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->parent_table} (name, type, style_name, rawdata) VALUES ( %s, %s, %s, %s)", array($settings['addons-style-name'], $newdata['type'], $newdata['style_name'], $newdata['rawdata'])));
                $redirect_id = $this->wpdb->insert_id;
                if ($redirect_id > 0):
                    $rawdata = json_decode(stripslashes($newdata['rawdata']), true);
                    $rawdata['shortcode-addons-elements-id'] = $redirect_id;
                    $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . ucfirst($rawdata['shortcode-addons-elements-name']) . '\Admin\\' . ucfirst(str_replace('-', '_', $rawdata['shortcode-addons-elements-template'])) . '';
                    $CLASS = new $cls('admin');
                    $CLASS->template_css_render($rawdata);
                    $child = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $this->child_table WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
                    foreach ($child as $value) {
                        $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, type, rawdata) VALUES (%d, %s, %s)", array($redirect_id, 'shortcode-addons', $value['rawdata'])));
                    }
                    echo admin_url("admin.php?page=shortcode-addons&oxitype=" . strtolower($elements) . "&styleid=$redirect_id");
                endif;
            else:

                $style = $row['style'];
                $child = $row['child'];

                $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->parent_table} (name, type, style_name, rawdata) VALUES ( %s, %s, %s, %s)", array($settings['addons-style-name'], $elements, $style['style_name'], $style['rawdata'])));
                $redirect_id = $this->wpdb->insert_id;
                if ($redirect_id > 0):
                    if (!empty($style['rawdata'])):
                        $rawdata = json_decode(stripslashes($style['rawdata']), true);
                        $rawdata['shortcode-addons-elements-id'] = $redirect_id;
                        $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . ucfirst($rawdata['shortcode-addons-elements-name']) . '\Admin\\' . ucfirst(str_replace('-', '_', $rawdata['shortcode-addons-elements-template'])) . '';
                        $CLASS = new $cls('admin');
                        $CLASS->template_css_render($rawdata);
                    endif;
                    foreach ($child as $value) {
                        $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, type, rawdata) VALUES (%d, %s, %s)", array($redirect_id, 'shortcode-addons', $value['rawdata'])));
                    }
                    echo admin_url("admin.php?page=shortcode-addons&oxitype=" . strtolower($elements) . "&styleid=$redirect_id");
                endif;
            endif;
        endif;
    }

    /**
     * Delete Element Template
     *
     * @since 2.0.0
     */
    public function elements_template_delete($data = '') {
        $settings = json_decode(stripslashes($data), true);
        $styleid = (int) $settings['oxideleteid'];
        if ($styleid):
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->parent_table} WHERE id = %d ", $styleid));
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->child_table} WHERE styleid = %d ", $styleid));
            echo 'done';
        else:
            echo 'Silence is Golden';
        endif;
    }

    public function array_replace($arr = [], $search = '', $replace = '') {
        array_walk($arr, function (&$v) use ($search, $replace) {
            $v = str_replace($search, $replace, $v);
        });
        return $arr;
    }

    /**
     * Export Element Template
     *
     * @since 2.0.0
     */
    public function elements_template_export($data = '') {
        $settings = json_decode(stripslashes($data), true);
        $styleid = (int) $settings['oxiexportid'];
        if ($styleid):
            $st = $this->dbdata = $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->parent_table . ' WHERE id = %d ', $styleid), ARRAY_A);
            $style = [
                'id' => $st['id'],
                'type' => ucfirst($st['type']),
                'name' => $st['name'],
                'style_name' => ucfirst(str_replace('-', '_', $st['style_name'])),
                'rawdata' => json_encode($this->array_replace(json_decode(stripslashes($st['rawdata']), true), '"', '&quot;')),
                'stylesheet' => htmlentities($st['stylesheet']),
                'font_family' => $st['font_family'],
            ];
            $c = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $this->child_table WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
            $child = [];
            foreach ($c as $value) {
                $child[] = [
                    'id' => $value['id'],
                    'styleid' => $value['styleid'],
                    'rawdata' => json_encode($this->array_replace(json_decode(stripslashes($value['rawdata']), true), '"', '&quot;')),
                    'stylesheet' => htmlentities($value['stylesheet'])
                ];
            }
            $newdata = ['style' => $style, 'child' => $child];
            echo json_encode($newdata);
        endif;
    }

    /**
     * Template Style Data
     *
     * @since 2.0.0
     */
    public function elements_template_style_data($rawdata = '', $styleid = '') {
        $settings = json_decode(stripslashes($rawdata), true);
        $oxitype = sanitize_text_field($settings['shortcode-addons-elements-name']);
        $StyleName = sanitize_text_field($settings['shortcode-addons-elements-template']);
        $stylesheet = '';
        $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $oxitype . '\Admin\\' . $StyleName . '';
        if ((int) $styleid):
            $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->parent_table} SET rawdata = %s, stylesheet = %s WHERE id = %d", $rawdata, $stylesheet, $styleid));
            $CLASS = new $cls('admin');
            echo $CLASS->template_css_render($settings);
        endif;
    }

    /**
     * Template Old Version Data
     *
     * @since 2.0.0
     */
    public function elements_template_old_version($rawdata = '', $styleid = '') {
        $stylesheet = $rawdata = '';
        if ((int) $styleid):
            $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->parent_table} SET rawdata = %s, stylesheet = %s WHERE id = %d", $rawdata, $stylesheet, $styleid));
            echo 'success';
        endif;
    }

    /**
     * Template Modal Data
     *
     * @since 2.0.0
     */
    public function elements_template_modal_data($rawdata = '', $styleid = '', $childid) {
        if ((int) $styleid):
            $type = 'shortcode-addons';
            if ((int) $childid):
                $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->child_table} SET rawdata = %s WHERE id = %d", $rawdata, $childid));
            else:
                $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, type, rawdata) VALUES (%d, %s, %s )", array($styleid, $type, $rawdata)));
            endif;
        endif;
    }

    /**
     * Template Modal Data Edit Form 
     *
     * @since 2.0.0
     */
    public function elements_template_modal_data_edit($rawdata = '', $styleid = '', $childid) {
        if ((int) $childid):
            $listdata = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM {$this->child_table} WHERE id = %d ", $childid), ARRAY_A);
            $returnfile = json_decode(stripslashes($listdata['rawdata']), true);
            $returnfile['shortcodeitemid'] = $childid;
            echo json_encode($returnfile);
        else:
            echo 'Silence is Golden';
        endif;
    }

    /**
     * Template Child Delete Data
     *
     * @since 2.0.0
     */
    public function elements_template_modal_data_delete($rawdata = '', $styleid = '', $childid) {
        if ((int) $childid):
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->child_table} WHERE id = %d ", $childid));
            echo 'done';
        else:
            echo 'Silence is Golden';
        endif;
    }

    /**
     * Template Name Change
     *
     * @since 2.0.0
     */
    public function elements_template_change_name($rawdata = '') {
        $settings = json_decode(stripslashes($rawdata), true);
        $name = sanitize_text_field($settings['addonsstylename']);
        $id = $settings['addonsstylenameid'];
        if ((int) $id):
            $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->parent_table} SET name = %s WHERE id = %d", $name, $id));
            echo 'success';
        endif;
    }

    /**
     * Template Raw Data
     *
     * @since 2.0.0
     */
    public function get_elements_rawdata($rawdata = '', $styleid = '') {
        $id = $styleid;
        if ((int) $id):
            $st = $this->wpdb->get_row($this->wpdb->prepare('SELECT rawdata FROM ' . $this->parent_table . ' WHERE id = %d ', $styleid), ARRAY_A);
            echo json_encode(json_decode(stripcslashes($st['rawdata']), true));
        endif;
    }

    /**
     * Template Template Render
     *
     * @since 2.0.0
     */
    public function elements_template_render_data($rawdata = '', $styleid = '') {
        $settings = json_decode(stripslashes($rawdata), true);
        $child = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $this->child_table WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
        $oxitype = $settings['shortcode-addons-elements-name'];
        $StyleName = $settings['shortcode-addons-elements-template'];
        $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $oxitype . '\Templates\\' . $StyleName . '';
        $CLASS = new $cls;
        $styledata = ['rawdata' => $rawdata, 'id' => $styleid, 'type' => $oxitype, 'style_name' => $StyleName, 'stylesheet' => ''];
        $CLASS->__construct($styledata, $child, 'admin');
    }
    /**
     * Template Import
     *
     * @since 2.0.0
     */
   
    public function elements_template_import($rawdata = '') {
        $settings = json_decode(stripslashes($rawdata), true);
        $redirect_id = '';
        if (array_key_exists('style', $settings)):
            $type = sanitize_text_field(ucfirst($settings['style']['type']));
            $name = sanitize_text_field($settings['style']['name']);
            $style_name = sanitize_text_field(ucfirst(str_replace('-', '_', $settings['style']['style_name'])));
            $rawdata = sanitize_post(json_encode($this->array_replace(json_decode(stripslashes($settings['style']['rawdata']), true), '"', '&quot;')));
            $font_family = sanitize_post($settings['style']['font_family']);
            $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->parent_table} (type, name, style_name, rawdata, font_family) VALUES (%s, %s, %s, %s, %s)", array($type, $name, $style_name, $rawdata, $font_family)));
            $redirect_id = $this->wpdb->insert_id;
            if ($redirect_id > 0 && (int) $redirect_id && $settings['style']['stylesheet'] != ''):
                $stylesheet = sanitize_post(html_entity_decode($settings['style']['stylesheet']));
                $stylesheet = str_replace('shortcode-addons-wrapper-' . $settings['style']['id'], 'shortcode-addons-wrapper-' . $redirect_id, $stylesheet);
                $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->parent_table} SET stylesheet = %s WHERE id = %d", $stylesheet, $redirect_id));
            endif;
            if (array_key_exists('child', $settings) && count($settings['child']) > 0 && $redirect_id > 0):
                foreach ($settings['child'] as $key => $value) {
                    $ty = 'shortcode-addons';
                    $rawdata = sanitize_post(json_encode($this->array_replace(json_decode(stripslashes($value['rawdata']), true), '"', '&quot;')));
                    $stylesheet = array_key_exists('stylesheet', $value) ? sanitize_post($value['stylesheet']) : 'no';
                    $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, type, rawdata, stylesheet) VALUES (%d, %s, %s , %s)", array($redirect_id, $ty, $rawdata, $stylesheet)));
                    $red = $this->wpdb->insert_id;
                    if ($red > 0 && (int) $red && $value['stylesheet'] != ''):
                        $stylesheet = sanitize_post(html_entity_decode($value['stylesheet']));
                        $stylesheet = str_replace('shortcode-addons-wrapper-' . $settings['style']['id'], 'shortcode-addons-wrapper-' . $redirect_id, $stylesheet);
                        $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->child_table} SET stylesheet = %s WHERE id = %d", $stylesheet, $red));
                    endif;
                }
            endif;
            echo admin_url("admin.php?page=shortcode-addons&oxitype=$type&styleid=$redirect_id");
        endif;
    }
     public function elements_elements_import($rawdata = '') {
          $settings = json_decode(stripslashes($rawdata), true);
          echo  $settings;
         
     }
    

}
