<?php

namespace SHORTCODE_ADDONS\Layouts\Elements;

if (!defined('ABSPATH')) {
    exit;
}

/**
 *
 * @author biplo
 */
trait Database {

    public function database_data() {

        $oxitype = ucfirst($this->oxitype);

        return $this->wpdb->get_results("SELECT id, name, style_name  FROM $this->parent_table WHERE type = '$oxitype' ORDER BY id DESC", ARRAY_A);
    }

    public function pre_active_check($pre = false) {
        $template = $this->wpdb->get_results("SELECT * FROM  $this->import_table WHERE type = '$this->oxitype' ORDER BY id DESC", ARRAY_A);
        if (count($template) < 1 || $pre):
            $recheck = $this->pre_active();
            foreach ($recheck as $value) {
                $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} (type, name) VALUES (%s, %s )", array($this->oxitype, $value)));
            }
            $template = $this->wpdb->get_results("SELECT * FROM  $this->import_table WHERE type = '$this->oxitype' ORDER BY id DESC", ARRAY_A);
        endif;
        $return = array();
        foreach ($template as $value) {
            $return[ucfirst(str_replace('-', '_', $value['name']))] = ucfirst(str_replace('-', '_', $value['name']));
        }
        return $return;
    }

}
