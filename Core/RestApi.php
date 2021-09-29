<?php

namespace SHORTCODE_ADDONS\Core;

use \SHORTCODE_ADDONS\Core\Console as Console;

class RestApi extends Console {

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
    public function post_elements_template_create() {
        $settings = json_decode(stripslashes($this->rawdata), true);
        $elements = sanitize_text_field($settings['addons-oxi-type']);
        $row = json_decode($settings['oxi-addons-data'], true);

        $style = $row['style'];
        $child = $row['child'];

        $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->parent_table} (name, type, style_name, rawdata) VALUES ( %s, %s, %s, %s)", array($settings['addons-style-name'], $elements, $style['style_name'], $style['rawdata'])));
        $redirect_id = $this->wpdb->insert_id;
        if ($redirect_id > 0):
            $oxitype = ucfirst(strtolower($style['type']));
            $rawdata = json_decode(stripslashes($style['rawdata']), true);
            $stylename = ucfirst(str_replace('-', '_', $style['style_name']));
            $rawdata['shortcode-addons-elements-id'] = $redirect_id;
            $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $oxitype . '\Admin\\' . ucfirst(str_replace('-', '_', $stylename)) . '';
            $CLASS = new $cls('admin');
            $cssgenera = $CLASS->template_css_render($rawdata);

            foreach ($child as $value) {
                $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, type, rawdata) VALUES (%d, %s, %s)", array($redirect_id, 'shortcode-addons', $value['rawdata'])));
            }
            return admin_url("admin.php?page=shortcode-addons&oxitype=" . strtolower($elements) . "&styleid=$redirect_id");
        endif;
    }

    public function import_json_template($folder, $filename, $name = 'truee') {
        
        if (is_file($folder . $filename)) {
            $this->rawdata = file_get_contents($folder . $filename);

            $params = json_decode($this->rawdata, true);
            $style = $params['style'];
            $child = $params['child'];
            if ($name != 'truee'):
                $style['name'] = $name;
            endif;
            $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->parent_table} (name, type, style_name, rawdata) VALUES ( %s, %s, %s, %s)", array($style['name'], $style['type'], $style['style_name'], $style['rawdata'])));
            $redirect_id = $this->wpdb->insert_id;
            if ($redirect_id > 0):
                $oxitype = ucfirst(strtolower($style['type']));
                $rawdata = json_decode(stripslashes($style['rawdata']), true);
                $stylename = ucfirst(str_replace('-', '_', $style['style_name']));
                $rawdata['shortcode-addons-elements-id'] = $redirect_id;
                $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $oxitype . '\Admin\\' . ucfirst(str_replace('-', '_', $stylename)) . '';
                $CLASS = new $cls('admin');
                $cssgenera = $CLASS->template_css_render($rawdata);

                foreach ($child as $value) {
                    $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->child_table} (styleid, type, rawdata) VALUES (%d, %s, %s)", array($redirect_id, $value['type'], $value['rawdata'])));
                }
                return admin_url("admin.php?page=shortcode-addons&oxitype=" . strtolower($style['type']) . "&styleid=$redirect_id");
            endif;
        }
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
            return 'Done';
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
            return json_encode($returnfile);
        else:
            return 'Silence is Golden';
        endif;
    }
    
     public function post_shortcode_delete()
    {
        $styleid = (int) $this->styleid;
        if ($styleid) :
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->parent_table} WHERE id = %d", $styleid));
            $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->child_table} WHERE styleid = %d", $styleid));
            return 'done';
        else :
            return 'Silence is Golden';
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
            return 'done';
        else:
            return 'Silence is Golden';
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

    /**
     * Admin Settings
     * @return void
     */
    public function post_addons_settings() {
        $rawdata = json_decode(stripslashes($this->rawdata), true);
        update_option($rawdata['name'], $rawdata['value']);
        return '<span class="oxi-confirmation-success"></span>';
    }

    /**
     * Admin License
     * @return void
     */
    public function post_oxi_license() {
        $rawdata = json_decode(stripslashes($this->rawdata), true);
        $new = $rawdata['license'];
        $old = get_option('shortcode_addons_license_key');
        $status = get_option('oxi_addons_license_status');
        if ($new == ''):
            if ($old != '' && $status == 'valid'):
                $this->deactivate_license($old);
            endif;
            delete_option('shortcode_addons_license_key');
            $data = ['massage' => '<span class="oxi-confirmation-blank"></span>', 'text' => ''];
        else:
            update_option('shortcode_addons_license_key', $new);
            delete_option('oxi_addons_license_status');
            $r = $this->activate_license($new);
            if ($r == 'success'):
                $data = ['massage' => '<span class="oxi-confirmation-success"></span>', 'text' => 'Active'];
            else:
                $data = ['massage' => '<span class="oxi-confirmation-failed"></span>', 'text' => $r];
            endif;
        endif;
        return $data;
    }

    public function activate_license($key) {
        $api_params = array(
            'edd_action' => 'activate_license',
            'license' => $key,
            'item_name' => urlencode('Short code Addons'),
            'url' => home_url()
        );

        $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = __('An error occurred, please try again.');
            }
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if (false === $license_data->success) {

                switch ($license_data->error) {

                    case 'expired' :

                        $message = sprintf(
                                __('Your license key expired on %s.'), date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                        );
                        break;

                    case 'revoked' :

                        $message = __('Your license key has been disabled.');
                        break;

                    case 'missing' :

                        $message = __('Invalid license.');
                        break;

                    case 'invalid' :
                    case 'site_inactive' :

                        $message = __('Your license is not active for this URL.');
                        break;

                    case 'item_name_mismatch' :

                        $message = sprintf(__('This appears to be an invalid license key for %s.'), Responsive_Tabs_with_Accordions);
                        break;

                    case 'no_activations_left':

                        $message = __('Your license key has reached its activation limit.');
                        break;

                    default :

                        $message = __('An error occurred, please try again.');
                        break;
                }
            }
        }

        if (!empty($message)) {
            return $message;
        }
        update_option('oxi_addons_license_status', $license_data->license);
        return 'success';
    }

    public function deactivate_license($key) {
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license' => $key,
            'item_name' => urlencode('Short code Addons'),
            'url' => home_url()
        );
        $response = wp_remote_post('https://www.oxilab.org', array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = __('An error occurred, please try again.');
            }
            return $message;
        }
        $license_data = json_decode(wp_remote_retrieve_body($response));
        if ($license_data->license == 'deactivated') {
            delete_option('oxi_addons_license_status');
            delete_option('shortcode_addons_license_key');
        }
        return 'success';
    }

}
