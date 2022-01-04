<?php

namespace SHORTCODE_ADDONS\Includes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Widget
 *
 * @author biplo
 */
class Widget extends \WP_Widget {

    function __construct() {
        parent::__construct(
                'shortcode_addons_widget', esc_html__('Shortcode Addons', 'shortcode-addons'), array('description' => esc_html__('Shortcode Addons- with Visual Composer, Divi, Beaver Builder and Elementor Extension', 'shortcode-addons'),)
        );
    }

    public function register_shortcode_addons_widget() {
        register_widget($this);
    }

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        $CLASS = '\SHORTCODE_ADDONS\Includes\Shortcode';
        if (class_exists($CLASS)):
            new $CLASS($title, 'user');
        endif;
        echo $args['after_widget'];
    }

    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('1', 'shortcode-addons');
        }
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html__('Style ID:', 'shortcode-addons'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }

}
