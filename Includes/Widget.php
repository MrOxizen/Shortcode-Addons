<?php

namespace SHORTCODE_ADDONS\Includes;

/**
 * Description of Widget
 *
 * @author biplo
 */
class Widget extends \WP_Widget {

    function __construct() {
        parent::__construct(
                'shortcode_addons_widget', __('Shortcode Addons', 'shortcode_addons_widget_widget'), array('description' => __('Shortcode Addons- with Visual Composer, Divi, Beaver Builder and Elementor Extension', 'shortcode_addons_widget_widget'),)
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
            $title = __('1', 'shortcode_addons_widget_widget');
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Style ID:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

}
