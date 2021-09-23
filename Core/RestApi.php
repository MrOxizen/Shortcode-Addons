<?php

namespace SHORTCODE_ADDONS\Core;

use \SHORTCODE_ADDONS\Core\Console as Console;

class RestApi extends Console {

    public $request;
    public $rawdata;
    public $styleid;
    public $childid;

    public function build_api() {
        add_action('rest_api_init', function () {
            register_rest_route(untrailingslashit('ShortCodeAddonsUltimate/v2/'), '/(?P<action>\w+)/', array(
                'methods' => array('GET', 'POST'),
                'callback' => [$this, 'api_action'],
                'permission_callback' => '__return_true'
            ));
        });
    }

    public function api_action($request) {
        $this->request = $request;
        $this->rawdata = isset($request['rawdata']) ? addslashes($request['rawdata']) : '';
        $this->styleid = isset($request['styleid']) ? $request['styleid'] : '';
        $this->childid = isset($request['childid']) ? $request['childid'] : '';
        $class = isset($request['class']) ? $request['class'] : '';
        $action_class = strtolower($request->get_method()) . '_' . sanitize_key($request['action']);

        if ($class != ''):
            $args = $request['args'];
            $optional = $request['optional'];
            ob_start();
            $CLASS = new $class;
            $CLASS->__construct($request['action'], $this->rawdata, $args, $optional);
            return ob_get_clean();
        else:
            if (method_exists($this, $action_class)) {
                return $this->{$action_class}();
            } else {
                return 'Go to Hell';
            }

        endif;
    }

    /**
     * Check Template Active
     *
     * @since 2.0.0
     */
    public function post_elements_template_create($data = '') {
        $settings = json_decode(stripslashes($this->rawdata), true);
        $elements = sanitize_text_field($settings['addons-oxi-type']);
        $row = json_decode($settings['oxi-addons-data'], true);

        $style = $row['style'];
        $child = $row['child'];

        $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->parent_table} (name, type, style_name, rawdata) VALUES ( %s, %s, %s, %s)", array($settings['addons-style-name'], $elements, $style['style_name'], $style['rawdata'])));
        $redirect_id = $this->wpdb->insert_id;
        if ($redirect_id > 0):
            foreach ($child as $value) {
                $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, type, rawdata) VALUES (%d, %s, %s)", array($redirect_id, 'shortcode-addons', $value['rawdata'])));
            }
            return admin_url("admin.php?page=shortcode-addons&oxitype=" . strtolower($elements) . "&styleid=$redirect_id");
        endif;
    }

    public function post_load_admin_template() {
        $query = json_decode(stripslashes($this->rawdata), true);
        $StyleName = ucfirst(str_replace('-', "_", $query['style_name']));
        $clsss = '\SHORTCODE_ADDONS_UPLOAD\\' . $query['type'] . '\Admin\\' . $StyleName . '';
        if (class_exists($clsss)):
            $elements = new $clsss();
            return $elements->reactjs_templates($query);
        else:
            return $clsss;
        endif;
    }

    public function get_get_template_data() {
        $response = get_transient(self::SHORTCODE_TRANSIENT_AVAILABLE_ELEMENTS);
        return $response;
    }

    /**
     * Template Name Change
     *
     * @since 2.0.0
     */
    public function post_elements_template_change_name() {
        $settings = json_decode(stripslashes($this->rawdata), true);
        $name = sanitize_text_field($settings['addonsstylename']);
        $id = $settings['addonsstylenameid'];
        if ((int) $id):
            $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->parent_table} SET name = %s WHERE id = %d", $name, $id));
            return 'success';
        endif;
        return;
    }

    /**
     * Template Style Data
     *
     * @since 2.0.0
     */
    public function post_elements_template_style_data() {


        $rawdata = $this->rawdata;

        $styleid = $this->styleid;

        $settings = json_decode(stripslashes($rawdata), true);

        $oxitype = sanitize_text_field($settings['shortcode-addons-elements-name']);
        $StyleName = sanitize_text_field($settings['shortcode-addons-elements-template']);
        $stylesheet = '';
        $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $oxitype . '\Admin\\' . $StyleName . '';

        if ((int) $styleid):
            $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->parent_table} SET rawdata = %s, stylesheet = %s WHERE id = %d", $rawdata, $stylesheet, $styleid));
            $CLASS = new $cls('admin');
            $data = $CLASS->template_css_render($settings);
            
            ob_start();
            $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $oxitype . '\Templates\\' . $StyleName . '';
            $CLASS = new $cls;
            $child = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $this->child_table WHERE styleid = %d ORDER by id ASC", $this->styleid), ARRAY_A);
            $styledata = ['rawdata' => $this->rawdata, 'id' => $this->styleid, 'type' => $oxitype, 'style_name' => $StyleName, 'stylesheet' => ''];
            $CLASS->__construct($styledata, $child, 'admin');
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        endif;
    }

    /**
     * Template Template Render
     *
     * @since 2.0.0
     */
    public function post_elements_template_render_data() {
        $settings = json_decode(stripslashes($this->rawdata), true);
        $child = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $this->child_table WHERE styleid = %d ORDER by id ASC", $this->styleid), ARRAY_A);
        $oxitype = $settings['shortcode-addons-elements-name'];
        $StyleName = $settings['shortcode-addons-elements-template'];

        ob_start();

        $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $oxitype . '\Templates\\' . $StyleName . '';
        $CLASS = new $cls;
        $styledata = ['rawdata' => $this->rawdata, 'id' => $this->styleid, 'type' => $oxitype, 'style_name' => $StyleName, 'stylesheet' => ''];
        $CLASS->__construct($styledata, $child, 'admin');

        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
     /**
     * Template Modal Data
     *
     * @since 2.0.0
     */
    public function post_elements_template_modal_data() {
        if ((int) $this->styleid):
            $type = 'shortcode-addons';
            if ((int) $this->childid):
                $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->child_table} SET rawdata = %s WHERE id = %d", $this->rawdata, $this->childid));
            else:
                $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, type, rawdata) VALUES (%d, %s, %s )", array($this->styleid, $type, $this->rawdata)));
            endif;
        endif;
    }
    
    /**
     * Template Modal Data Edit Form 
     *
     * @since 2.0.0
     */
    public function post_elements_template_modal_data_edit() {
        if ((int) $this->childid):
            $listdata = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM {$this->child_table} WHERE id = %d ", $this->childid), ARRAY_A);
            $returnfile = json_decode(stripslashes($listdata['rawdata']), true);
            $returnfile['shortcodeitemid'] = $this->childid;
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
    public function post_elements_template_modal_data_delete() {
        if ((int) $this->childid):
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->child_table} WHERE id = %d ", $this->childid));
            echo 'done';
        else:
            echo 'Silence is Golden';
        endif;
    }
     /**
     * Template Old Version Data
     *
     * @since 2.0.0
     */
    public function post_elements_template_old_version() {
        $stylesheet = $rawdata = '';
        if ((int) $this->styleid):
            $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->parent_table} SET rawdata = %s, stylesheet = %s WHERE id = %d", $rawdata, $stylesheet, $this->styleid));
            echo 'success';
        endif;
    }
    
    
    public function get_shortcode_export() {
        $styleid = (int) $this->styleid;
        
        if ($styleid):
            $style = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM {$this->parent_table} WHERE id = %d", $styleid), ARRAY_A);
            $child = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM {$this->child_table} WHERE styleid = %d ORDER by id ASC", $styleid), ARRAY_A);
            $filename = 'shortcode-addons-template-' . $styleid . '.json';
            $files = [
                'style' => $style,
                'child' => $child,
            ];
            $finalfiles = json_encode($files);
            $this->send_file_headers($filename, strlen($finalfiles));
            @ob_end_clean();
            flush();
            echo $finalfiles;
            die;
        else:
            return 'Silence is Golden';
        endif;
    }

    /**
     * Send file headers.
     *
     *
     * @param string $file_name File name.
     * @param int    $file_size File size.
     */
    private function send_file_headers($file_name, $file_size) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $file_name);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $file_size);
    }

}
