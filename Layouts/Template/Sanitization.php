<?php

namespace SHORTCODE_ADDONS\Layouts\Template;

if (!defined('ABSPATH')) {
    exit;
}

use SHORTCODE_ADDONS\Core\Admin\Controls as Controls;

trait Sanitization {

    /**
     * font settings sanitize
     * works at layouts page to adding font Settings sanitize
     */
    public function AdminTextSenitize($data) {
        $data = str_replace('\\\\"', '&quot;', $data);
        $data = str_replace('\\\"', '&quot;', $data);
        $data = str_replace('\\"', '&quot;', $data);
        $data = str_replace('\"', '&quot;', $data);
        $data = str_replace('"', '&quot;', $data);
        $data = str_replace('\\\\&quot;', '&quot;', $data);
        $data = str_replace('\\\&quot;', '&quot;', $data);
        $data = str_replace('\\&quot;', '&quot;', $data);
        $data = str_replace('\&quot;', '&quot;', $data);
        $data = str_replace("\\\\'", '&apos;', $data);
        $data = str_replace("\\\'", '&apos;', $data);
        $data = str_replace("\\'", '&apos;', $data);
        $data = str_replace("\'", '&apos;', $data);
        $data = str_replace("\\\\&apos;", '&apos;', $data);
        $data = str_replace("\\\&apos;", '&apos;', $data);
        $data = str_replace("\\&apos;", '&apos;', $data);
        $data = str_replace("\&apos;", '&apos;', $data);
        $data = str_replace("'", '&apos;', $data);
        $data = str_replace('<', '&lt;', $data);
        $data = str_replace('>', '&gt;', $data);
        $data = sanitize_text_field($data);
        return $data;
    }

    /*
     * Shortcode Addons Style Admin Panel header
     *
     * @since 2.0.0
     */

    public function start_section_header($id, array $arg = []) {
        echo '<ul class="oxi-addons-tabs-ul">   ';
        foreach ($arg['options'] as $key => $value) {
            echo '<li ref="#shortcode-addons-section-' . esc_attr($key) . '">' . esc_attr($value) . '</li>';
        }
        echo '</ul>';
    }

    /*
     * Shortcode Addons Style Admin Panel Body
     *
     * @since 2.0.0
     */

    public function start_section_tabs($id, array $arg = []) {
        echo '<div class="oxi-addons-tabs-content-tabs" id="shortcode-addons-section-';
        if (array_key_exists('condition', $arg)) :
            foreach ($arg['condition'] as $value) {
                echo esc_html($value);
            }
        endif;
        echo '">';
    }

    /*
     * Shortcode Addons Style Admin Panel end tabs
     *
     * @since 2.0.0
     */

    public function end_section_tabs() {
        echo '</div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Col 6 or Entry devider
     *
     * @since 2.0.0
     */

    public function start_section_devider() {
        echo '<div class="oxi-addons-col-6">';
    }

    /*
     * Shortcode Addons Style Admin Panel end Entry Divider
     *
     * @since 2.0.0
     */

    public function end_section_devider() {
        echo '</div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Form Dependency
     *
     * @since 2.0.0
     */

    public function forms_condition(array $arg = []) {

        if (array_key_exists('condition', $arg)) :
            $i = $arg['condition'] != '' ? count($arg['condition']) : 0;

            $data = '';
            $s = 1;
            $form_condition = array_key_exists('form_condition', $arg) ? $arg['form_condition'] : '';
            foreach ($arg['condition'] != '' ? $arg['condition'] : [] as $key => $value) {
                if (is_array($value)):
                    $c = count($value);
                    $crow = 1;
                    if ($c > 1 && $i > 1):
                        $data .= '(';
                    endif;
                    foreach ($value as $item) {
                        $data .= $form_condition . $key . ' === \'' . $item . '\'';
                        if ($crow < $c) :
                            $data .= ' || ';
                            $crow++;
                        endif;
                    }
                    if ($c > 1 && $i > 1):
                        $data .= ')';
                    endif;
                elseif ($value == 'COMPILED') :
                    $data .= $form_condition . $key;
                elseif ($value == 'EMPTY') :
                    $data .= $form_condition . $key . ' !== \'\'';
                elseif (empty($value)) :
                    $data .= $form_condition . $key;
                else :
                    $data .= $form_condition . $key . ' === \'' . $value . '\'';
                endif;

                if ($s < $i) :
                    $data .= ' && ';
                    $s++;
                endif;
            }
            if (!empty($data)) :
                return 'data-condition="' . $data . '"';
            endif;
        endif;
    }

    /*
     * Shortcode Addons Style Admin Panel Each Tabs
     *
     * @since 2.0.0
     */

    public function start_controls_section($id, array $arg = []) {
        $defualt = ['showing' => FALSE];
        $arg = array_merge($defualt, $arg);
        $condition = $this->forms_condition($arg);
        echo '<div class="oxi-addons-content-div ' . (($arg['showing']) ? '' : 'oxi-admin-head-d-none') . '" ' . esc_attr($condition) . '>
                    <div class="oxi-head">
                    ' . esc_html($arg['label']) . '
                    <div class="oxi-head-toggle"></div>
                    </div>
                    <div class="oxi-addons-content-div-body">';
    }

    /*
     * Shortcode Addons Style Admin Panel end Each Tabs
     *
     * @since 2.0.0
     */

    public function end_controls_section() {
        echo '</div></div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Section Inner Tabs
     * This Tabs like inner tabs as Normal view and Hover View
     *
     * @since 2.0.0
     */

    public function start_controls_tabs($id, array $arg = []) {
        $defualt = ['options' => ['normal' => 'Normal', 'hover' => 'Hover']];
        $arg = array_merge($defualt, $arg);
        echo '<div class="shortcode-form-control shortcode-control-type-control-tabs ' . (array_key_exists('separator', $arg) ? ($arg['separator'] === TRUE ? 'shortcode-form-control-separator-before' : '') : '') . '">
                <div class="shortcode-form-control-content shortcode-form-control-content-tabs">
                    <div class="shortcode-form-control-field">';
        foreach ($arg['options'] as $key => $value) {
            echo '  <div class="shortcode-control-type-control-tab-child">
			<div class="shortcode-control-content">
				' . esc_html($value) . '
                        </div>
                    </div>';
        }
        echo '</div>
              </div>
              <div class="shortcode-form-control-content">';
    }

    /*
     * Shortcode Addons Style Admin Panel end Section Inner Tabs
     *
     * @since 2.0.0
     */

    public function end_controls_tabs() {
        echo '</div> </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Section Inner Tabs Child
     *
     * @since 2.0.0
     */

    public function start_controls_tab() {
        echo '<div class="shortcode-form-control-content shortcode-form-control-tabs-content shortcode-control-tab-close">';
    }

    /*
     * Shortcode Addons Style Admin Panel End Section Inner Tabs Child
     *
     * @since 2.0.0
     */

    public function end_controls_tab() {
        echo '</div>';
    }

    /*
     * Shortcode Addons Style Admin Panel  Section Popover
     *
     * @since 2.0.0
     */

    public function start_popover_control($id, array $arg = []) {
        $condition = $this->forms_condition($arg);
        $separator = (array_key_exists('separator', $arg) ? ($arg['separator'] === TRUE ? 'shortcode-form-control-separator-before' : '') : '');
        echo '  <div class="shortcode-form-control shortcode-control-type-popover ' . esc_attr($separator) . '" ' . esc_attr($condition) . '>
                    <div class="shortcode-form-control-content shortcode-form-control-content-popover">
                        <div class="shortcode-form-control-field">
                            <label for="" class="shortcode-form-control-title">' . esc_html($arg['label']) . '</label>
                            <div class="shortcode-form-control-input-wrapper">
                                <span class="dashicons popover-set"></span>
                            </div>
                        </div>
                        ' . (array_key_exists('description', $arg) ? '<div class="shortcode-form-control-description">' . esc_html($arg['description']) . '</div>' : '') . '

                    </div>
                    <div class="shortcode-form-control-content shortcode-form-control-content-popover-body">

               ';
    }

    /*
     * Shortcode Addons Style Admin Panel end Popover
     *
     * @since 2.0.0
     */

    public function end_popover_control() {
        echo '</div></div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Form Add Control.
     * Call All Input Control from here Based on Control Name.
     *
     * @since 2.0.0
     */

    public function add_control($id, array $data = [], array $arg = []) {
        /*
         * Responsive Control Start
         * @since 2.0.0
         */
        $responsive = $responsiveclass = '';
        if (array_key_exists('responsive', $arg)) :
            if ($arg['responsive'] == 'laptop') :
                $responsiveclass = 'shortcode-addons-form-responsive-laptop';
            elseif ($arg['responsive'] == 'tab') :
                $responsiveclass = 'shortcode-addons-form-responsive-tab';
            elseif ($arg['responsive'] == 'mobile') :
                $responsiveclass = 'shortcode-addons-form-responsive-mobile';
            endif;

        endif;
        $defualt = [
            'type' => 'text',
            'label' => 'Input Text',
            'default' => '',
            'label_on' => esc_html__('Yes', 'shortcode-addons'),
            'label_off' => esc_html__('No', 'shortcode-addons'),
            'placeholder' => esc_html__('', 'shortcode-addons'),
            'selector-data' => TRUE,
            'render' => TRUE,
            'responsive' => 'laptop'
        ];

        /*
         * Data Currection while Its comes from group Control
         */
        if (array_key_exists('selector-value', $arg)) :
            foreach ($arg['selector'] as $key => $value) {
                $arg['selector'][$key] = $arg['selector-value'];
            }
        endif;

        $arg = array_merge($defualt, $arg);
        if ($arg['type'] == 'animation'):
            $arg['type'] = 'select';
            $arg['options'] = [
                '' => esc_html__('None', 'shortcode-addons'),
                'bounce' => esc_html__('Bounce', 'shortcode-addons'),
                'flash' => esc_html__('Flash', 'shortcode-addons'),
                'pulse' => esc_html__('Pulse', 'shortcode-addons'),
                'rubberBand' => esc_html__('RubberBand', 'shortcode-addons'),
                'shake' => esc_html__('Shake', 'shortcode-addons'),
                'swing' => esc_html__('Swing', 'shortcode-addons'),
                'tada' => esc_html__('Tada', 'shortcode-addons'),
                'wobble' => esc_html__('Wobble', 'shortcode-addons'),
                'jello' => esc_html__('Jello', 'shortcode-addons'),
            ];
        endif;
        $fun = $arg['type'] . '_admin_control';
        $condition = $this->forms_condition($arg);
        $toggle = (array_key_exists('toggle', $arg) ? 'shortcode-addons-form-toggle' : '');
        $separator = (array_key_exists('separator', $arg) ? ($arg['separator'] === TRUE ? 'shortcode-form-control-separator-before' : '') : '');

        $loader = (array_key_exists('loader', $arg) ? $arg['loader'] == TRUE ? ' shortcode-addons-control-loader ' : '' : '');
        echo '<div class="shortcode-form-control shortcode-control-type-' . esc_attr($arg['type']) . ' ' . esc_attr($separator) . ' ' . esc_attr($toggle) . ' ' . esc_attr($responsiveclass) . ' ' . esc_attr($loader) . '" ' . esc_attr($condition) . '>
                <div class="shortcode-form-control-content">
                    <div class="shortcode-form-control-field">
                    <label for="" class="shortcode-form-control-title">' . esc_html($arg['label']) . '</label>';
        if (array_key_exists('responsive', $arg)) :

            echo '<div class="shortcode-form-control-responsive-switchers">
                                <a class="shortcode-form-responsive-switcher shortcode-form-responsive-switcher-desktop" data-device="desktop">
                                    <span class="dashicons dashicons-desktop"></span>
                                </a>
                                <a class="shortcode-form-responsive-switcher shortcode-form-responsive-switcher-tablet" data-device="tablet">
                                    <span class="dashicons dashicons-tablet"></span>
                                </a>
                                <a class="shortcode-form-responsive-switcher shortcode-form-responsive-switcher-mobile" data-device="mobile">
                                    <span class="dashicons dashicons-smartphone"></span>
                                </a>
                            </div>';

        endif;
        $this->$fun($id, $data, $arg);
        echo '      </div>
                ' . (array_key_exists('description', $arg) ? '<div class="shortcode-form-control-description">' . esc_html($arg['description']) . '</div>' : '') . '
                </div>
        </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Responsive Control.
     * Can Possible to modify any Add control to Responsive Control
     *
     * @since 2.0.0
     */

    public function add_responsive_control($id, array $data = [], array $arg = []) {
        $lap = $id . '-lap';
        $tab = $id . '-tab';
        $mob = $id . '-mob';
        $laparg = ['responsive' => 'laptop'];
        $tabarg = ['responsive' => 'tab'];
        $mobarg = ['responsive' => 'mobile'];

        $this->add_control($lap, $data, array_merge($arg, $laparg));
        $this->add_control($tab, $data, array_merge($arg, $tabarg));
        $this->add_control($mob, $data, array_merge($arg, $mobarg));
    }

    /*
     * Shortcode Addons Style Admin Panel Group Control.
     *
     * @since 2.0.0
     */

    public function add_group_control($id, array $data = [], array $arg = []) {
        $defualt = ['type' => 'text', 'label' => 'Input Text'];
        $arg = array_merge($defualt, $arg);
        $fun = $arg['type'] . '_admin_group_control';
        $this->$fun($id, $data, $arg);
    }

    /*
     * Shortcode Addons Style Admin Panel Repeater Control.
     *
     * @since 2.0.0
     */

    public function add_repeater_control($id, array $data = [], array $arg = []) {
        $condition = $this->forms_condition($arg);
        $separator = (array_key_exists('separator', $arg) ? ($arg['separator'] === TRUE ? 'shortcode-form-control-separator-before' : '') : '');
        $buttontext = (array_key_exists('button', $arg) ? $arg['button'] : 'Add Item');
        echo '<div class="shortcode-form-control shortcode-control-type-' . esc_attr($arg['type']) . ' ' . esc_attr($separator) . '" ' . esc_attr($condition) . ' id="' . esc_attr($id) . '">
                <div class="shortcode-form-control-content">
                    <div class="shortcode-form-control-field">
                        <label for="" class="shortcode-form-control-title">' . esc_html($arg['label']) . '</label>
                    </div>
                    <div class="shortcode-form-repeater-fields-wrapper">';
        if (array_key_exists($id, $data)) :
            foreach ($data[$id] as $k => $vl) {
                $style = [];
                foreach ($vl as $c => $v) {
                    $style[$id . 'saarsa' . $k . 'saarsa' . $c] = $v;
                }
                echo '  <div class="shortcode-form-repeater-fields" tab-title="' . esc_html($arg['title_field']) . '">
                            <div class="shortcode-form-repeater-controls">
                                <div class="shortcode-form-repeater-controls-title">
                                    ' . esc_html($vl[$arg['title_field']]) . '
                                </div>
                                <div class="shortcode-form-repeater-controls-duplicate">
                                    <span class="dashicons dashicons-admin-page"></span>
                                </div>
                                <div class="shortcode-form-repeater-controls-remove">
                                    <span class="dashicons dashicons-trash"></span>
                                </div>
                            </div>
                            <div class="shortcode-form-repeater-content">';
                foreach ($arg['fields'] as $key => $value) {
                    $controller = (array_key_exists('controller', $value) ? $value['controller'] : 'add_control');
                    $child = esc_attr($id) . 'saarsa' . $k . 'saarsa' . $key;
                    $value['conditional'] = (array_key_exists('conditional', $value) ? ($value['conditional'] == 'outside') ? 'outside' : 'inside' : '');
                    $value['form_condition'] = (array_key_exists('conditional', $value) ? ($value['conditional'] == 'inside') ? esc_attr($id) . 'saarsa' . $k . 'saarsa' : '' : '');

                    if ($controller == 'add_control' || $controller == 'add_group_control' || $controller == 'add_responsive_control') :
                        $this->$controller($child, $style, $value);
                    else :
                        $this->$controller($child, $value);
                    endif;
                }
                echo '      </div>
                        </div>';
            }
        endif;

        echo '      </div>';

        $this->add_control(
                $id . 'nm', $data, ['type' => Controls::HIDDEN, 'default' => '0',]
        );
        echo '      <div class="shortcode-form-repeater-button-wrapper"><a href="#" parent-id="' . esc_attr($id) . '" class="shortcode-form-repeater-button"><span class="dashicons dashicons-plus"></span> ' . esc_html($buttontext) . '</a></div>';

        echo '  </div>
             </div>';

        $this->repeater .= '<div id="repeater-' . esc_attr($id) . '-initial-data">
                                <div class="shortcode-form-repeater-fields" tab-title="' . esc_html($arg['title_field']) . '">
                                    <div class="shortcode-form-repeater-controls">
                                        <div class="shortcode-form-repeater-controls-title">
                                            Title Goes Here
                                        </div>
                                        <div class="shortcode-form-repeater-controls-duplicate">
                                            <span class="dashicons dashicons-admin-page"></span>
                                        </div>
                                        <div class="shortcode-form-repeater-controls-remove">
                                            <span class="dashicons dashicons-trash"></span>
                                        </div>
                                    </div>
                                    <div class="shortcode-form-repeater-content">';
        foreach ($arg['fields'] as $key => $value) {
            $controller = (array_key_exists('controller', $value) ? $value['controller'] : 'add_control');
            $child = $id . 'saarsarepidrepsaarsa' . $key;
            $value['conditional'] = (array_key_exists('conditional', $value) ? ($value['conditional'] == 'outside') ? 'outside' : 'inside' : '');
            $value['form_condition'] = (array_key_exists('conditional', $value) ? ($value['conditional'] == 'inside') ? $id . 'saarsarepidrepsaarsa' : '' : '');
            ob_start();
            if ($controller == 'add_control' || $controller == 'add_group_control' || $controller == 'add_responsive_control') :
                $this->$controller($child, [], $value);
            else :
                $this->$controller($child, $value);
            endif;

            $this->repeater .= ob_get_clean();
        }
        $this->repeater .= '         </div>
                                </div>
                            </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Rearrange Control.
     *
     * @since 2.1.0
     */

    public function add_rearrange_control($id, array $data = [], array $arg = []) {
        $condition = $this->forms_condition($arg);
        $separator = (array_key_exists('separator', $arg) ? ($arg['separator'] === TRUE ? 'shortcode-form-control-separator-before' : '') : '');
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        echo '<div class="shortcode-form-control shortcode-control-type-' . esc_attr($arg['type']) . ' ' . esc_attr($separator) . '" ' . esc_attr($condition) . '>
                <div class="shortcode-form-control-content">
                    <div class="shortcode-form-control-field">
                        <label for="" class="shortcode-form-control-title">' . esc_html($arg['label']) . '</label>
                    </div>
                    <div class="shortcode-form-rearrange-fields-wrapper" vlid="#' . esc_attr($id) . '">';
        $rearrange = explode(',', $value);
        foreach ($rearrange as $k => $vl) {
            if ($vl != ''):
                echo '  <div class="shortcode-form-repeater-fields" id="' . esc_attr($vl) . '">
                            <div class="shortcode-form-repeater-controls">
                                <div class="shortcode-form-repeater-controls-title">
                                    ' . esc_html($arg['fields'][$vl]['label']) . '
                                </div>
                            </div>
                        </div>';
            endif;
        }
        echo '          <div class="shortcode-form-control-input-wrapper">
                            <input type="hidden" value="' . esc_attr($value) . '" name="' . esc_attr($id) . '" id="' . esc_attr($id) . '">
                        </div>
                    </div>
                </div>
            </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Heading Input.
     *
     * @since 2.0.0
     */

    public function heading_admin_control($id, array $data = [], array $arg = []) {
        echo ' ';
    }

    /*
     * Shortcode Addons Style Admin Panel separator control.
     *
     * @since 2.0.0
     */

    public function separator_admin_control($id, array $data = [], array $arg = []) {
        echo '';
    }

    /*
     * Shortcode Addons Style Admin Panel multiple selector.
     *
     * @since 2.1.0
     */

    public function multiple_selector_handler($data, $val) {

        $val = preg_replace_callback('/\{\{\K(.*?)(?=}})/', function ($match)use ($data) {
            $ER = explode('.', $match[0]);
            if (strpos($match[0], 'SIZE') !== FALSE):
                $size = array_key_exists($ER[0] . '-size', $data) ? $data[$ER[0] . '-size'] : '';
                $match[0] = str_replace('.SIZE', $size, $match[0]);
            endif;
            if (strpos($match[0], 'UNIT') !== FALSE):
                $size = array_key_exists($ER[0] . '-choices', $data) ? $data[$ER[0] . '-choices'] : '';
                $match[0] = str_replace('.UNIT', $size, $match[0]);
            endif;
            if (strpos($match[0], 'VALUE') !== FALSE):
                $size = array_key_exists($ER[0], $data) ? $data[$ER[0]] : '';
                $match[0] = str_replace('.VALUE', $size, $match[0]);
            endif;
            return str_replace($ER[0], '', $match[0]);
        }, $val);
        return str_replace("{{", '', str_replace("}}", '', $val));
    }

    /*
     * Shortcode Addons Style Admin Panel Switcher Input.
     *
     * @since 2.0.0
     */

    public function switcher_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <label class="shortcode-switcher">
                        <input type="checkbox" ' . ($value == $arg['return_value'] ? 'checked ckdflt="true"' : '') . ' value="' . esc_attr($arg['return_value']) . '"  name="' . esc_attr($id) . '" id="' . esc_attr($id) . '"/>
                        <span data-on="' . esc_attr($arg['label_on']) . '" data-off="' . esc_attr($arg['label_off']) . '"></span>
                    </label>
                </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Text Input.
     *
     * @since 2.0.0
     */

    public function text_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        if (array_key_exists('link', $arg)) :
            echo '<div class="shortcode-form-control-input-wrapper shortcode-form-control-input-link">
                     <input type="text"  name="' . esc_attr($id) . '" id="' . esc_attr($id) . '" value="' . esc_attr($value) . '" placeholder="' . esc_html($arg['placeholder']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
                     <span class="dashicons dashicons-admin-generic"></span>';
        else :
            echo '<div class="shortcode-form-control-input-wrapper">
                <input type="text"  name="' . esc_attr($id) . '" id="' . esc_attr($id) . '" value="' . esc_attr(esc_attr($retunvalue)) . '" placeholder="' . esc_html($arg['placeholder']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
            </div>';
        endif;
    }

    /*
     * Shortcode Addons Style Admin Panel Password Input.
     *
     * @since 2.0.0
     */

    public function password_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        if (array_key_exists('link', $arg)) :
            echo '<div class="shortcode-form-control-input-wrapper shortcode-form-control-input-link">
                     <input type="password"  name="' . esc_attr($id) . '" id="' . esc_attr($id) . '" value="' . esc_attr($value) . '" placeholder="' . esc_html($arg['placeholder']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
                     <span class="dashicons dashicons-admin-generic"></span>';
        else :
            echo '<div class="shortcode-form-control-input-wrapper">
                <input type="password"  name="' . esc_attr($id) . '" id="' . esc_attr($id) . '" value="' . esc_attr($value) . '" placeholder="' . esc_html($arg['placeholder']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
            </div>';
        endif;
    }

    /*
     * Shortcode Addons Style Admin Panel Hidden Input.
     *
     * @since 2.0.0
     */

    public function hidden_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        echo ' <div class="shortcode-form-control-input-wrapper">
                   <input type="hidden" value="' . esc_attr($value) . '" name="' . esc_attr($id) . '" id="' . esc_attr($id) . '">
               </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Textarea Input.
     *
     * @since 2.0.0
     */

    public function textarea_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        echo '<div class="shortcode-form-control-input-wrapper">
                 <textarea  name="' . esc_attr($id) . '" id="' . esc_attr($id) . '" retundata=\'' . esc_attr($retunvalue) . '\' class="shortcode-form-control-tag-area" rows="' . (int) ((strlen($value) / 50) + 2) . '" placeholder="' . esc_html($arg['placeholder']) . '">' . str_replace('&nbsp;', '  ', str_replace('<br>', '&#13;&#10;', esc_attr($value))) . '</textarea>
              </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel WYSIWYG Input.
     *
     * @since 2.0.0
     */

    public function wysiwyg_admin_control($id, array $data = [], array $arg = []) {

        if ($this->template_css_render != 'css_render'):


            $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
            $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
            echo ' <div class="shortcode-form-control-input-wrapper"  retundata=\'' . esc_attr($retunvalue) . '\'>';
            wp_editor(
                    $value, $id, $settings = array(
                'textarea_name' => $id,
                'wpautop' => false,
                'textarea_rows' => 7,
                'force_br_newlines' => true,
                'force_p_newlines' => false
                    )
            );
            echo ' </div>';
        endif;
    }

    /*
     * Shortcode Addons Style Admin Panel Image Input.
     *
     * @since 2.0.0
     */

    public function image_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $alt = array_key_exists($id . '-alt', $data) ? $data[$id . '-alt'] : '';
        if (isset($arg['select'])):
            $img = '';
            $type = ($arg['select'] != 'file') ? $arg['select'] : 'file';
            $altfile = '';
        else:
            $img = 'style="background-image: url(' . esc_url($value) . ');" ckdflt="background-image: url(' . esc_url($value) . ');"';
            $type = '';
            $altfile = '<input type="hidden" class="shortcode-addons-media-control-link-alt" id="' . esc_attr($id) . '-alt" name="' . esc_attr($id) . '-alt" value="' . esc_attr($alt) . '" >';
        endif;
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <div class="shortcode-addons-media-control ' . (empty($value) ? 'shortcode-addons-media-control-hidden-button' : '') . ' shortcode-addons-media-control-type-' . esc_attr($type) . '">
                        <div class="shortcode-addons-media-control-pre-load">
                        </div>
                        <div class="shortcode-addons-media-control-image-load" ' . esc_attr($img) . '>
                            <div class="shortcode-addons-media-control-image-load-delete-button">
                            </div>
                        </div>
                        <div class="shortcode-addons-media-control-choose-image">
                            Choose ' . (isset($arg['select']) ? esc_html(ucfirst($arg['select'])) : 'Image') . '
                        </div>
                    </div>
                    <input type="hidden" data-type="' . esc_attr($type) . '" class="shortcode-addons-media-control-link" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" value="' . esc_attr($value) . '" >
                    ' . esc_html($altfile) . '
                </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Number Input.
     *
     * @since 2.0.0
     */

    public function number_admin_control($id, array $data = [], array $arg = []) {

        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $this->render_condition_control($id, $data, $arg)) :
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                    $class = str_replace('{{WRAPPER}}', $this->WRAPPER, $key);
                    $file = str_replace('{{VALUE}}', $value, $val);
                    if (strpos($file, '{{') !== FALSE):
                        $file = $this->multiple_selector_handler($data, $file);
                    endif;
                    $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                }
            endif;
        endif;
        $defualt = ['min' => 0, 'max' => 1000, 'step' => 1,];
        $arg = array_merge($defualt, $arg);
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <input id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" type="number" min="' . esc_attr($arg['min']) . '" max="' . esc_attr($arg['max']) . '" step="' . esc_attr($arg['step']) . '" value="' . esc_attr($value) . '"  responsive="' . esc_attr($arg['responsive']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
                </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Slider Input.
     *
     * @since 2.0.0
     */

    public function slider_admin_control($id, array $data = [], array $arg = []) {
        $unit = array_key_exists($id . '-choices', $data) ? $data[$id . '-choices'] : $arg['default']['unit'];
        $size = array_key_exists($id . '-size', $data) ? $data[$id . '-size'] : $arg['default']['size'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';

        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $arg['render'] == TRUE && $this->render_condition_control($id, $data, $arg)) :
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    if ($size != '' && $val != '') :
                        $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                        $class = str_replace('{{WRAPPER}}', $this->WRAPPER, $key);
                        $file = str_replace('{{SIZE}}', $size, $val);
                        $file = str_replace('{{UNIT}}', $unit, $file);
                        if (strpos($file, '{{') !== FALSE):
                            $file = $this->multiple_selector_handler($data, $file);
                        endif;
                        if (!empty($size)):
                            $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                        endif;
                    endif;
                }
            endif;
        endif;
        if (array_key_exists('range', $arg)) :
            if (count($arg['range']) > 1) :
                echo ' <div class="shortcode-form-units-choices">';
                foreach ($arg['range'] as $key => $val) {
                    $rand = rand(10000, 233333333);
                    echo '<input id="' . esc_attr($id) . '-choices-' . esc_attr($rand) . '" type="radio" name="' . esc_attr($id) . '-choices' . '"  value="' . esc_attr($key) . '" ' . ($key == $unit ? 'checked' : '') . '  min="' . esc_attr($val['min']) . '" max="' . esc_attr($val['max']) . '" step="' . esc_attr($val['step']) . '">
                      <label class="shortcode-form-units-choices-label" for="' . esc_attr($id) . '-choices-' . esc_attr($rand) . '">' . esc_html($key) . '</label>';
                }
                echo '</div>';
            endif;
        endif;
        $unitvalue = array_key_exists($id . '-choices', $data) ? 'unit="' . $data[$id . '-choices'] . '"' : '';
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <div class="shortcode-form-slider" id="' . esc_attr($id) . '-slider' . '"></div>
                    <div class="shortcode-form-slider-input">
                        <input name="' . esc_attr($id) . '-size' . '" custom="' . (array_key_exists('custom', $arg) ? esc_attr($arg['custom']) : '') . '" id="' . esc_attr($id) . '-size' . '" type="number" min="' . esc_attr($arg['range'][$unit]['min']) . '" max="' . esc_attr($arg['range'][$unit]['max']) . '" step="' . esc_attr($arg['range'][$unit]['step']) . '" value="' . esc_attr($size) . '" default-value="' . esc_attr($size) . '" ' . esc_attr($unitvalue) . ' responsive="' . esc_attr($arg['responsive']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
                    </div>
                </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Select Input.
     *
     * @since 2.0.0
     */

    public function select_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retun = [];

        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $this->render_condition_control($id, $data, $arg)) {
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                    if (!empty($value) && !empty($val) && $arg['render'] == TRUE) {
                        $class = str_replace('{{WRAPPER}}', $this->WRAPPER, $key);
                        $file = str_replace('{{VALUE}}', $value, $val);
                        if (strpos($file, '{{') !== FALSE):
                            $file = $this->multiple_selector_handler($data, $file);
                        endif;
                        $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                    }
                    $retun[$key][$key]['type'] = ($val != '' ? 'CSS' : 'HTML');
                    $retun[$key][$key]['value'] = $val;
                }
            endif;
        }
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($retun)) : '';
        $multiple = (array_key_exists('multiple', $arg) && $arg['multiple']) == true ? TRUE : FALSE;

        echo '<div class="shortcode-form-control-input-wrapper">
                <div class="shortcode-form-control-input-select-wrapper">
                <select id="' . esc_attr($id) . '" class="shortcode-addons-select-input ' . ($multiple ? 'js-example-basic-multiple' : '' ) . '" ' . ($multiple ? 'multiple' : '' ) . ' name="' . esc_attr($id) . '' . ($multiple ? '[]' : '' ) . '"  responsive="' . esc_attr($arg['responsive']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>';
        foreach ($arg['options'] as $key => $val) {
            if (is_array($val)):
                if (isset($val[0]) && $val[0] == true):
                    echo '<optgroup label="' . esc_attr($val[1]) . '">';
                else:
                    echo '</optgroup>';
                endif;
            else:
                if (is_array($value)):
                    $new = array_flip($value);
                    echo ' <option value="' . esc_attr($key) . '" ' . (array_key_exists($key, $new) ? 'selected' : '') . '>' . esc_html($val) . '</option>';
                else:
                    echo ' <option value="' . esc_attr($key) . '" ' . ($value == $key ? 'selected' : '') . '>' . esc_html($val) . '</option>';
                endif;
            endif;
        }
        echo '</select>
                </div>
            </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Choose Input.
     *
     * @since 2.0.0
     */

    public function choose_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retun = [];

        $operator = array_key_exists('operator', $arg) ? $arg['operator'] : 'text';
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $this->render_condition_control($id, $data, $arg)) {
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                    if (!empty($val)) {
                        $class = str_replace('{{WRAPPER}}', $this->WRAPPER, $key);
                        $file = str_replace('{{VALUE}}', $value, $val);
                        if (strpos($file, '{{') !== FALSE):
                            $file = $this->multiple_selector_handler($data, $file);
                        endif;
                        if (!empty($value)):
                            $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                        endif;
                    }
                    $retun[$key][$key]['type'] = ($val != '' ? 'CSS' : 'HTML');
                    $retun[$key][$key]['value'] = $val;
                }
            endif;
        }
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($retun)) : '';
        echo '<div class="shortcode-form-control-input-wrapper">
                <div class="shortcode-form-choices" responsive="' . $arg['responsive'] . '" retundata=\'' . esc_attr($retunvalue) . '\'>';
        foreach ($arg['options'] as $key => $val) {
            echo '  <input id="' . esc_attr($id) . '-' . esc_attr($key) . '" type="radio" name="' . esc_attr($id) . '" value="' . esc_attr($key) . '" ' . ($value == $key ? 'checked  ckdflt="true"' : '') . '>
                                    <label class="shortcode-form-choices-label" for="' . esc_attr($id) . '-' . esc_attr($key) . '" tooltip="' . esc_html($val['title']) . '">
                                        ' . (($operator == 'text') ? esc_html($val['title']) : '<i class="' . esc_attr($val['icon']) . '" aria-hidden="true"></i>') . '
                                    </label>';
        }
        echo '</div>
        </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel render conditional control.
     *
     * @since 2.0.0
     */

    public function render_condition_control($id, array $data = [], array $arg = []) {

        if (array_key_exists('condition', $arg)):
            foreach ($arg['condition'] as $key => $value) {
                if (array_key_exists('conditional', $arg) && $arg['conditional'] == 'outside'):
                    $data = $this->style;
                elseif (array_key_exists('conditional', $arg) && $arg['conditional'] == 'inside' && isset($arg['form_condition'])):
                    $key = $arg['form_condition'] . $key;
                endif;
                if (strpos($key, '&') !== FALSE):
                    return true;
                endif;
                if (!array_key_exists($key, $data)):
                    return false;
                endif;
                if ($data[$key] != $value):
                    if (is_array($value)):
                        $t = false;
                        foreach ($value as $val) {
                            if ($data[$key] == $val):
                                $t = true;
                            endif;
                        }
                        return $t;
                    endif;
                    if ($value == 'EMPTY' && $data[$key] != '0'):
                        return true;
                    endif;
                    if (strpos($data[$key], '&') !== FALSE):
                        return true;
                    endif;
                    return false;
                endif;
            }
        endif;
        return true;
    }

    /*
     * Shortcode Addons Style Admin Panel Color control.
     *
     * @since 2.1.0
     */

    public function color_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $arg['render'] == TRUE && $this->render_condition_control($id, $data, $arg)) {
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                    $class = str_replace('{{WRAPPER}}', $this->WRAPPER, $key);
                    $file = str_replace('{{VALUE}}', $value, $val);
                    if (!empty($value)):
                        $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                    endif;
                }
            endif;
        }
        $type = array_key_exists('oparetor', $arg) ? 'data-format="rgb" data-opacity="TRUE"' : '';
        echo '<div class="shortcode-form-control-input-wrapper">
                <input ' . esc_attr($type) . ' type="text"  class="oxi-addons-minicolor" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" value="' . esc_attr($value) . '" responsive="' . esc_attr($arg['responsive']) . '" retundata=\'' . esc_attr($retunvalue) . '\' custom="' . (array_key_exists('custom', $arg) ? '' . esc_attr($arg['custom']) . '' : '') . '">
             </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Icon Selector.
     *
     * @since 2.0.0
     */

    public function icon_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <input type="text"  class="oxi-admin-icon-selector" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" value="' . esc_attr($value) . '">
                    <span class="input-group-addon"></span>
                </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Font Selector.
     *
     * @since 2.0.0
     */

    public function font_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $retunvalue = '';
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        if ($value != '' && array_key_exists($value, $this->google_font)) :
            $this->font[$value] = $value;
        endif;

        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE) {
            if (array_key_exists('selector', $arg) && $value != '') :
                foreach ($arg['selector'] as $key => $val) {
                    if ($arg['render'] == TRUE) :
                        $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                        $class = str_replace('{{WRAPPER}}', $this->WRAPPER, $key);
                        $file = str_replace('{{VALUE}}', str_replace("+", ' ', $value), $val);
                        if (!empty($value)):
                            $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                        endif;
                    endif;
                }
            endif;
        }
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';

        echo '  <div class="shortcode-form-control-input-wrapper">
                    <input type="text"  class="shortcode-addons-family" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" value="' . esc_attr($value) . '" responsive="' . esc_attr($arg['responsive']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
                </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Date and Time Selector.
     *
     * @since 2.0.0
     */

    public function date_time_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $format = 'date';
        if (array_key_exists('time', $arg)) :
            if ($arg['time'] == TRUE) :
                $format = 'datetime-local';
            endif;
        endif;
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <input type="' . esc_attr($format) . '"  id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" value="' . esc_attr($value) . '">
                </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Gradient Selector.
     *
     * @since 2.0.0
     */

    public function gradient_admin_control($id, array $data = [], array $arg = []) {
        $id = (array_key_exists('repeater', $arg) ? $id . ']' : $id);
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE) {
            if (array_key_exists('selector', $arg)) :
                foreach ($arg['selector'] as $key => $val) {
                    if ($arg['render'] == TRUE) :
                        $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                        $class = str_replace('{{WRAPPER}}', $this->WRAPPER, $key);
                        $file = str_replace('{{VALUE}}', $value, $val);
                        if (!empty($value)):
                            $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                        endif;
                    endif;
                }
            endif;
        }
        $background = (array_key_exists('gradient', $arg) ? $arg['gradient'] : '');
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <input type="text" background="' . esc_attr($background) . '"  class="shortcode-addons-gradient-color" id="' . esc_attr($id) . '" name="' . esc_attr($id) . '" value="' . esc_attr($value) . '" responsive="' . esc_attr($arg['responsive']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
                </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Dimensions Selector.
     *
     * @since 2.0.0
     */

    public function dimensions_admin_control($id, array $data = [], array $arg = []) {
        $unit = array_key_exists($id . '-choices', $data) ? $data[$id . '-choices'] : $arg['default']['unit'];
        $top = array_key_exists($id . '-top', $data) ? $data[$id . '-top'] : $arg['default']['size'];
        $bottom = array_key_exists($id . '-bottom', $data) ? $data[$id . '-bottom'] : $top;
        $left = array_key_exists($id . '-left', $data) ? $data[$id . '-left'] : $top;
        $right = array_key_exists($id . '-right', $data) ? $data[$id . '-right'] : $top;
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        $ar = [$top, $bottom, $left, $right];
        $unlink = (count(array_unique($ar)) === 1 ? '' : 'link-dimensions-unlink');
        if (array_key_exists('selector-data', $arg) && $arg['selector-data'] == TRUE && $arg['render'] == TRUE) {
            if (array_key_exists('selector', $arg)) :
                if (isset($top) && isset($right) && isset($bottom) && isset($left)) :
                    foreach ($arg['selector'] as $key => $val) {
                        $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                        $class = str_replace('{{WRAPPER}}', $this->WRAPPER, $key);
                        $file = str_replace('{{UNIT}}', $unit, $val);
                        $file = str_replace('{{TOP}}', $top, $file);
                        $file = str_replace('{{RIGHT}}', $right, $file);
                        $file = str_replace('{{BOTTOM}}', $bottom, $file);
                        $file = str_replace('{{LEFT}}', $left, $file);
                        $this->CSSDATA[$arg['responsive']][$class][$file] = $file;
                    }
                endif;
            endif;
        }

        if (array_key_exists('range', $arg)) :
            if (count($arg['range']) > 1) :
                echo ' <div class="shortcode-form-units-choices">';
                foreach ($arg['range'] as $key => $val) {
                    $rand = rand(10000, 233333333);
                    echo '<input id="' . esc_attr($id) . '-choices-' . esc_attr($rand) . '" type="radio" name="' . esc_attr($id) . '-choices"  value="' . esc_attr($key) . '" ' . ($key == $unit ? 'checked' : '') . '  min="' . esc_attr($val['min']) . '" max="' . esc_attr($val['max']) . '" step="' . esc_attr($val['step']) . '">
                      <label class="shortcode-form-units-choices-label" for="' . esc_attr($id) . '-choices-' . esc_attr($rand) . '">' . esc_html($key) . '</label>';
                }
                echo '</div>';
            endif;
        endif;
        $unitvalue = array_key_exists($id . '-choices', $data) ? 'unit="' . $data[$id . '-choices'] . '"' : $arg['default']['unit'];
        echo '<div class="shortcode-form-control-input-wrapper">
                <ul class="shortcode-form-control-dimensions">
                    <li class="shortcode-form-control-dimension">
                        <input id="' . esc_attr($id) . '-top" input-id="' . esc_attr($id) . '" name="' . esc_attr($id) . '-top" type="number"  min="' . esc_attr($arg['range'][$unit]['min']) . '" max="' . esc_attr($arg['range'][$unit]['min']) . '" step="' . esc_attr($arg['range'][$unit]['step']) . '" value="' . esc_attr($top) . '" default-value="' . esc_attr($top) . '" ' . esc_attr($unitvalue) . ' responsive="' . esc_attr($arg['responsive']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
                        <label for="' . esc_attr($id) . '-top" class="shortcode-form-control-dimension-label">Top</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                       <input id="' . esc_attr($id) . '-right" input-id="' . esc_attr($id) . '" name="' . esc_attr($id) . '-right" type="number"  min="' . esc_attr($arg['range'][$unit]['min']) . '" max="' . esc_attr($arg['range'][$unit]['min']) . '" step="' . esc_attr($arg['range'][$unit]['step']) . '" value="' . esc_attr($right) . '" default-value="' . esc_attr($right) . '" ' . esc_attr($unitvalue) . ' responsive="' . esc_attr($arg['responsive']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
                         <label for="' . esc_attr($id) . '-right" class="shortcode-form-control-dimension-label">Right</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                       <input id="' . esc_attr($id) . '-bottom" input-id="' . esc_attr($id) . '" name="' . esc_attr($id) . '-bottom" type="number"  min="' . esc_attr($arg['range'][$unit]['min']) . '" max="' . esc_attr($arg['range'][$unit]['min']) . '" step="' . esc_attr($arg['range'][$unit]['step']) . '" value="' . esc_attr($bottom) . '" default-value="' . esc_attr($bottom) . '" ' . esc_attr($unitvalue) . ' responsive="' . esc_attr($arg['responsive']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
                       <label for="' . esc_attr($id) . '-bottom" class="shortcode-form-control-dimension-label">Bottom</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                        <input id="' . esc_attr($id) . '-left" input-id="' . esc_attr($id) . '" name="' . esc_attr($id) . '-left" type="number"  min="' . esc_attr($arg['range'][$unit]['min']) . '" max="' . esc_attr($arg['range'][$unit]['max']) . '" step="' . esc_attr($arg['range'][$unit]['step']) . '" value="' . esc_attr($left) . '" default-value="' . esc_attr($left) . '" ' . esc_attr($unitvalue) . ' responsive="' . esc_attr($arg['responsive']) . '" retundata=\'' . esc_attr($retunvalue) . '\'>
                         <label for="' . esc_attr($id) . '-left" class="shortcode-form-control-dimension-label">Left</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                        <button type="button" class="shortcode-form-link-dimensions ' . esc_attr($unlink) . '"  data-tooltip="Link values together"></button>
                    </li>
                </ul>
            </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Typography.
     *
     * @since 2.0.0
     */

    public function typography_admin_group_control($id, array $data = [], array $arg = []) {
        $cond = $condition = '';
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $this->start_popover_control(
                $id, [
            'label' => esc_html__('Typography', 'shortcode-addons'),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => (array_key_exists('description', $arg) ? esc_html($arg['description']) : ''),
                ]
        );

        $selector_key = $selector = $selectorvalue = $loader = $loadervalue = '';
        if (array_key_exists('selector', $arg)) :
            $selectorvalue = 'selector-value';
            $selector_key = 'selector';
            $selector = $arg['selector'];
        endif;
        if (array_key_exists('loader', $arg)) :
            $loader = 'loader';
            $loadervalue = $arg['loader'];
        endif;
        $this->add_control(
                $id . '-font', $data, [
            'label' => esc_html__('Font Family', 'shortcode-addons'),
            'type' => Controls::FONT,
            $selectorvalue => 'font-family:"{{VALUE}}";',
            $selector_key => $selector,
            $loader => $loadervalue
                ]
        );
        $this->add_responsive_control(
                $id . '-size', $data, [
            'label' => esc_html__('Size', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            $loader => $loadervalue,
            $selectorvalue => 'font-size: {{SIZE}}{{UNIT}};',
            $selector_key => $selector,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
                'rem' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
                'vm' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
                ]
        );
        $this->add_control(
                $id . '-weight', $data, [
            'label' => esc_html__('Weight', 'shortcode-addons'),
            'type' => Controls::SELECT,
            $selectorvalue => 'font-weight: {{VALUE}};',
            $loader => $loadervalue,
            $selector_key => $selector,
            'options' => [
                '100' => esc_html__('100', 'shortcode-addons'),
                '200' => esc_html__('200', 'shortcode-addons'),
                '300' => esc_html__('300', 'shortcode-addons'),
                '400' => esc_html__('400', 'shortcode-addons'),
                '500' => esc_html__('500', 'shortcode-addons'),
                '600' => esc_html__('600', 'shortcode-addons'),
                '700' => esc_html__('700', 'shortcode-addons'),
                '800' => esc_html__('800', 'shortcode-addons'),
                '900' => esc_html__('900', 'shortcode-addons'),
                '' => esc_html__('Default', 'shortcode-addons'),
                'normal' => esc_html__('Normal', 'shortcode-addons'),
                'bold' => esc_html__('Bold', 'shortcode-addons')
            ],
                ]
        );
        $this->add_control(
                $id . '-transform', $data, [
            'label' => esc_html__('Transform', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => esc_html__('Default', 'shortcode-addons'),
                'uppercase' => esc_html__('Uppercase', 'shortcode-addons'),
                'lowercase' => esc_html__('Lowercase', 'shortcode-addons'),
                'capitalize' => esc_html__('Capitalize', 'shortcode-addons'),
                'none' => esc_html__('Normal', 'shortcode-addons'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'text-transform: {{VALUE}};',
            $selector_key => $selector,
                ]
        );
        $this->add_control(
                $id . '-style', $data, [
            'label' => esc_html__('Style', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => esc_html__('Default', 'shortcode-addons'),
                'normal' => esc_html__('normal', 'shortcode-addons'),
                'italic' => esc_html__('Italic', 'shortcode-addons'),
                'oblique' => esc_html__('Oblique', 'shortcode-addons'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'font-style: {{VALUE}};',
            $selector_key => $selector,
                ]
        );
        $this->add_control(
                $id . '-decoration', $data, [
            'label' => esc_html__('Decoration', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => esc_html__('Default', 'shortcode-addons'),
                'underline' => esc_html__('Underline', 'shortcode-addons'),
                'overline' => esc_html__('Overline', 'shortcode-addons'),
                'line-through' => esc_html__('Line Through', 'shortcode-addons'),
                'none' => esc_html__('None', 'shortcode-addons'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'text-decoration: {{VALUE}};',
            $selector_key => $selector,
                ]
        );

        if (array_key_exists('include', $arg)) :
            if ($arg['include'] == 'align_normal') :
                $this->add_responsive_control(
                        $id . '-align', $data, [
                    'label' => esc_html__('Text Align', 'shortcode-addons'),
                    'type' => Controls::SELECT,
                    'default' => '',
                    'options' => [
                        '' => esc_html__('Default', 'shortcode-addons'),
                        'left' => esc_html__('Left', 'shortcode-addons'),
                        'center' => esc_html__('Center', 'shortcode-addons'),
                        'right' => esc_html__('Right', 'shortcode-addons'),
                    ],
                    $loader => $loadervalue,
                    $selectorvalue => 'text-align: {{VALUE}};',
                    $selector_key => $selector,
                        ]
                );
            else :
                $this->add_responsive_control(
                        $id . '-justify', $data, [
                    'label' => esc_html__('Justify Content', 'shortcode-addons'),
                    'type' => Controls::SELECT,
                    'default' => '',
                    'options' => [
                        '' => esc_html__('Default', 'shortcode-addons'),
                        'flex-start' => esc_html__('Flex Start', 'shortcode-addons'),
                        'flex-end' => esc_html__('Flex End', 'shortcode-addons'),
                        'center' => esc_html__('Center', 'shortcode-addons'),
                        'space-around' => esc_html__('Space Around', 'shortcode-addons'),
                        'space-between' => esc_html__('Space Between', 'shortcode-addons'),
                    ],
                    $loader => $loadervalue,
                    $selectorvalue => 'justify-content: {{VALUE}};',
                    $selector_key => $selector,
                        ]
                );
                $this->add_responsive_control(
                        $id . '-align', $data, [
                    'label' => esc_html__('Align Items', 'shortcode-addons'),
                    'type' => Controls::SELECT,
                    'default' => '',
                    'options' => [
                        '' => esc_html__('Default', 'shortcode-addons'),
                        'stretch' => esc_html__('Stretch', 'shortcode-addons'),
                        'baseline' => esc_html__('Baseline', 'shortcode-addons'),
                        'center' => esc_html__('Center', 'shortcode-addons'),
                        'flex-start' => esc_html__('Flex Start', 'shortcode-addons'),
                        'flex-end' => esc_html__('Flex End', 'shortcode-addons'),
                    ],
                    $loader => $loadervalue,
                    $selectorvalue => 'align-items: {{VALUE}};',
                    $selector_key => $selector,
                        ]
                );
            endif;
        endif;

        $this->add_responsive_control(
                $id . '-l-height', $data, [
            'label' => esc_html__('Line Height', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            $loader => $loadervalue,
            $selectorvalue => 'line-height: {{SIZE}}{{UNIT}};',
            $selector_key => $selector,
                ]
        );
        $this->add_responsive_control(
                $id . '-l-spacing', $data, [
            'label' => esc_html__('Letter Spacing', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            $loader => $loadervalue,
            $selectorvalue => 'letter-spacing: {{SIZE}}{{UNIT}};',
            $selector_key => $selector,
                ]
        );
        $this->end_popover_control();
    }

    /*
     * Shortcode Addons Style Admin Panel Media Group Control.
     *
     * @since 2.0.0
     */

    public function media_admin_group_control($id, array $data = [], array $arg = []) {
//        'default' => [
//                'type' => 'media-library',
//                'link' => '#asdas',
//            ],
// 'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),

        $type = array_key_exists('default', $arg) ? $arg['default']['type'] : 'media-library';
        $value = array_key_exists('default', $arg) ? $arg['default']['link'] : '';
        $level = array_key_exists('label', $arg) ? esc_html($arg['label']) : 'Photo Source';
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;

        echo '<div class="shortcode-form-control" style="padding: 0;" ' . esc_attr($this->forms_condition($arg)) . '>';
        $this->add_control(
                $id . '-select', $data, [
            'label' => esc_html__($level, 'shortcode-addons'),
            'type' => Controls::CHOOSE,
            'loader' => TRUE,
            'default' => $type,
            'separator' => $separator,
            'options' => [
                'media-library' => [
                    'title' => esc_html__('Media Library', 'shortcode-addons'),
                    'icon' => 'fa fa-align-left',
                ],
                'custom-url' => [
                    'title' => esc_html__('Custom URL', 'shortcode-addons'),
                    'icon' => 'fa fa-align-center',
                ]
            ],
                ]
        );
        $this->add_control(
                $id . '-image', $data, [
            'label' => esc_html__('Image', 'shortcode-addons'),
            'type' => Controls::IMAGE,
            'loader' => TRUE,
            'default' => $value,
            'condition' => [
                $id . '-select' => 'media-library',
            ],
                ]
        );
        $this->add_control(
                $id . '-url', $data, [
            'label' => esc_html__('Image URL', 'shortcode-addons'),
            'type' => Controls::TEXT,
            'default' => $value,
            'loader' => TRUE,
            'placeholder' => 'www.example.com/image.jpg',
            'condition' => [
                $id . '-select' => 'custom-url',
            ],
                ]
        );
        echo '</div>';
    }

    /*
     * Shortcode Addons Style Admin Panel File  Control.
     *
     * @since 2.0.0
     */

    public function fileupload_admin_group_control($id, array $data = [], array $arg = []) {

        $type = array_key_exists('default', $arg) ? $arg['default']['type'] : 'media-library';
        $value = array_key_exists('default', $arg) ? $arg['default']['link'] : '';
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $filetype = array_key_exists('select', $arg) ? $arg['select'] : 'file';
        $placeholder = array_key_exists('placeholder', $arg) ? $arg['placeholder'] : '';

        echo '<div class="shortcode-form-control" style="padding: 0;" ' . esc_attr($this->forms_condition($arg)) . '>';
        $this->add_control(
                $id . '-select', $data, [
            'label' => esc_html__(ucfirst($filetype) . ' Source', 'shortcode-addons'),
            'type' => Controls::CHOOSE,
            'loader' => TRUE,
            'default' => $type,
            'separator' => $separator,
            'options' => [
                'media-library' => [
                    'title' => esc_html__('Media', 'shortcode-addons'),
                    'icon' => 'fa fa-align-left',
                ],
                'custom-url' => [
                    'title' => esc_html__('Custom', 'shortcode-addons'),
                    'icon' => 'fa fa-align-center',
                ]
            ],
                ]
        );
        $this->add_control(
                $id . '-media', $data, [
            'label' => esc_html__(ucfirst($filetype), 'shortcode-addons'),
            'type' => Controls::IMAGE,
            'loader' => TRUE,
            'select' => $filetype,
            'default' => $value,
            'condition' => [
                $id . '-select' => 'media-library',
            ],
                ]
        );
        $this->add_control(
                $id . '-url', $data, [
            'label' => esc_html__(ucfirst($filetype) . ' URL', 'shortcode-addons'),
            'type' => Controls::TEXT,
            'default' => $value,
            'loader' => TRUE,
            'placeholder' => '' . $placeholder . '',
            'condition' => [
                $id . '-select' => 'custom-url',
            ],
                ]
        );
        echo '</div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Box Shadow Control.
     *
     * @since 2.0.0
     */

    public function boxshadow_admin_group_control($id, array $data = [], array $arg = []) {


        $cond = $condition = $boxshadow = '';
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;
        $true = TRUE;
        $selector_key = $selector = $selectorvalue = $loader = $loadervalue = '';
        if (!array_key_exists($id . '-shadow', $data)):
            $data[$id . '-shadow'] = 'yes';
        endif;
        if (!array_key_exists($id . '-blur-size', $data)):
            $data[$id . '-blur-size'] = 0;
        endif;
        if (!array_key_exists($id . '-horizontal-size', $data)):
            $data[$id . '-horizontal-size'] = 0;
        endif;
        if (!array_key_exists($id . '-vertical-size', $data)):
            $data[$id . '-vertical-size'] = 0;
        endif;

        if (array_key_exists($id . '-shadow', $data) && $data[$id . '-shadow'] == 'yes' && array_key_exists($id . '-color', $data) && array_key_exists($id . '-blur-size', $data) && array_key_exists($id . '-spread-size', $data) && array_key_exists($id . '-horizontal-size', $data) && array_key_exists($id . '-vertical-size', $data)) :
            $true = ($data[$id . '-blur-size'] == 0 || empty($data[$id . '-blur-size'])) && ($data[$id . '-spread-size'] == 0 || empty($data[$id . '-spread-size'])) && ($data[$id . '-horizontal-size'] == 0 || empty($data[$id . '-horizontal-size'])) && ($data[$id . '-vertical-size'] == 0 || empty($data[$id . '-vertical-size'])) ? TRUE : FALSE;
            $boxshadow = ($true == FALSE ? '-webkit-box-shadow:' . (array_key_exists($id . '-type', $data) ? $data[$id . '-type'] : '') . ' ' . $data[$id . '-horizontal-size'] . 'px ' . $data[$id . '-vertical-size'] . 'px ' . $data[$id . '-blur-size'] . 'px ' . $data[$id . '-spread-size'] . 'px ' . $data[$id . '-color'] . ';' : '');
            $boxshadow .= ($true == FALSE ? '-moz-box-shadow:' . (array_key_exists($id . '-type', $data) ? $data[$id . '-type'] : '') . ' ' . $data[$id . '-horizontal-size'] . 'px ' . $data[$id . '-vertical-size'] . 'px ' . $data[$id . '-blur-size'] . 'px ' . $data[$id . '-spread-size'] . 'px ' . $data[$id . '-color'] . ';' : '');
            $boxshadow .= ($true == FALSE ? 'box-shadow:' . (array_key_exists($id . '-type', $data) ? $data[$id . '-type'] : '') . ' ' . $data[$id . '-horizontal-size'] . 'px ' . $data[$id . '-vertical-size'] . 'px ' . $data[$id . '-blur-size'] . 'px ' . $data[$id . '-spread-size'] . 'px ' . $data[$id . '-color'] . ';' : '');
        endif;

        if (array_key_exists('selector', $arg)) :
            $selectorvalue = 'selector-value';
            $selector_key = 'selector';
            $selector = $arg['selector'];
            $boxshadow = array_key_exists($id . '-shadow', $data) && $data[$id . '-shadow'] == 'yes' ? $boxshadow : '';
            foreach ($arg['selector'] as $key => $val) {
                $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                $class = str_replace('{{WRAPPER}}', $this->WRAPPER, $key);
                $this->CSSDATA['laptop'][$class][$boxshadow] = $boxshadow;
            }
        endif;
        $this->start_popover_control(
                $id, [
            'label' => esc_html__('Box Shadow', 'shortcode-addons'),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => (array_key_exists('description', $arg) ? esc_html($arg['description']) : ''),
                ]
        );
        $this->add_control(
                $id . '-shadow', $data, [
            'label' => esc_html__('Shadow', 'shortcode-addons'),
            'type' => Controls::SWITCHER,
            'loader' => TRUE,
            'default' => 'yes',
            'label_on' => esc_html__('Yes', 'shortcode-addons'),
            'label_off' => esc_html__('None', 'shortcode-addons'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                $id . '-type', $data, [
            'label' => esc_html__('Type', 'shortcode-addons'),
            'type' => Controls::CHOOSE,
            'loader' => TRUE,
            'default' => '',
            'options' => [
                '' => [
                    'title' => esc_html__('Outline', 'shortcode-addons'),
                    'icon' => 'fa fa-align-left',
                ],
                'inset' => [
                    'title' => esc_html__('Inset', 'shortcode-addons'),
                    'icon' => 'fa fa-align-center',
                ],
            ],
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );

        $this->add_control(
                $id . '-horizontal', $data, [
            'label' => esc_html__('Horizontal', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'custom' => $id . '|||||box-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );
        $this->add_control(
                $id . '-vertical', $data, [
            'label' => esc_html__('Vertical', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'custom' => $id . '|||||box-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );
        $this->add_control(
                $id . '-blur', $data, [
            'label' => esc_html__('Blur', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'custom' => $id . '|||||box-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );
        $this->add_control(
                $id . '-spread', $data, [
            'label' => esc_html__('Spread', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'custom' => $id . '|||||box-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );
        $this->add_control(
                $id . '-color', $data, [
            'label' => esc_html__('Color', 'shortcode-addons'),
            'separator' => TRUE,
            'type' => Controls::COLOR,
            'oparetor' => 'RGB',
            'default' => '#CCC',
            'custom' => $id . '|||||box-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );
        $this->end_popover_control();
    }

    /*
     * Shortcode Addons Style Admin Panel Text Shadow .
     *
     * @since 2.0.0
     */

    public function textshadow_admin_group_control($id, array $data = [], array $arg = []) {

        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $cond = $condition = $textshadow = '';
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;
        $true = TRUE;
        $selector_key = $selector = $selectorvalue = $loader = $loadervalue = '';
        if (array_key_exists($id . '-color', $data) && array_key_exists($id . '-blur-size', $data) && array_key_exists($id . '-horizontal-size', $data) && array_key_exists($id . '-vertical-size', $data)) :
            $true = ($data[$id . '-blur-size'] == 0 || empty($data[$id . '-blur-size'])) && ($data[$id . '-horizontal-size'] == 0 || empty($data[$id . '-horizontal-size'])) && ($data[$id . '-vertical-size'] == 0 || empty($data[$id . '-vertical-size'])) ? TRUE : FALSE;
            $textshadow = ($true == FALSE ? 'text-shadow: ' . $data[$id . '-horizontal-size'] . 'px ' . $data[$id . '-vertical-size'] . 'px ' . $data[$id . '-blur-size'] . 'px ' . $data[$id . '-color'] . ';' : '');
        endif;
        if (array_key_exists('selector', $arg)) :
            $selectorvalue = 'selector-value';
            $selector_key = 'selector';
            $selector = $arg['selector'];
            foreach ($arg['selector'] as $key => $val) {
                $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                $class = str_replace('{{WRAPPER}}', $this->WRAPPER, $key);
                $this->CSSDATA['laptop'][$class][$textshadow] = $textshadow;
            }
        endif;
        $this->start_popover_control(
                $id, [
            'label' => esc_html__('Text Shadow', 'shortcode-addons'),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => (array_key_exists('description', $arg) ? esc_html($separator) : ''),
                ]
        );
        $this->add_control(
                $id . '-color', $data, [
            'label' => esc_html__('Color', 'shortcode-addons'),
            'type' => Controls::COLOR,
            'oparetor' => 'RGB',
            'default' => '#FFF',
            'custom' => $id . '|||||text-shadow',
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector,
            'render' => FALSE,
                ]
        );
        $this->add_control(
                $id . '-blur', $data, [
            'label' => esc_html__('Blur', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'separator' => TRUE,
            'custom' => $id . '|||||text-shadow',
            'render' => FALSE,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector
                ]
        );
        $this->add_control(
                $id . '-horizontal', $data, [
            'label' => esc_html__('Horizontal', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'custom' => $id . '|||||text-shadow',
            'render' => FALSE,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector
                ]
        );
        $this->add_control(
                $id . '-vertical', $data, [
            'label' => esc_html__('Vertical', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'custom' => $id . '|||||text-shadow',
            'render' => FALSE,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -50,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            $selectorvalue => '{{VALUE}}',
            $selector_key => $selector
                ]
        );

        $this->end_popover_control();
    }

    /*
     * Shortcode Addons Style Admin Panel Animation .
     *
     * @since 2.0.0
     */

    public function animation_admin_group_control($id, array $data = [], array $arg = []) {
        $cond = $condition = '';
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $this->start_popover_control(
                $id, [
            'label' => esc_html__('Animation', 'shortcode-addons'),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => 'Customize animation with animation type, Animation Duration with Delay and Looping Options',
                ]
        );
        $this->add_control(
                $id . '-type', $data, [
            'label' => esc_html__('Type', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                'optgroup0' => [true, 'Attention Seekers'],
                '' => esc_html__('None', 'shortcode-addons'),
                'optgroup1' => [false],
                'optgroup2' => [true, 'Attention Seekers'],
                'bounce' => esc_html__('Bounce', 'shortcode-addons'),
                'flash' => esc_html__('Flash', 'shortcode-addons'),
                'pulse' => esc_html__('Pulse', 'shortcode-addons'),
                'rubberBand' => esc_html__('RubberBand', 'shortcode-addons'),
                'shake' => esc_html__('Shake', 'shortcode-addons'),
                'swing' => esc_html__('Swing', 'shortcode-addons'),
                'tada' => esc_html__('Tada', 'shortcode-addons'),
                'wobble' => esc_html__('Wobble', 'shortcode-addons'),
                'jello' => esc_html__('Jello', 'shortcode-addons'),
                'optgroup3' => [false],
                'optgroup4' => [true, 'Bouncing Entrances'],
                'bounceIn' => esc_html__('BounceIn', 'shortcode-addons'),
                'bounceInDown' => esc_html__('BounceInDown', 'shortcode-addons'),
                'bounceInLeft' => esc_html__('BounceInLeft', 'shortcode-addons'),
                'bounceInRight' => esc_html__('BounceInRight', 'shortcode-addons'),
                'bounceInUp' => esc_html__('BounceInUp', 'shortcode-addons'),
                'optgroup5' => [false],
                'optgroup6' => [true, 'Fading Entrances'],
                'fadeIn' => esc_html__('FadeIn', 'shortcode-addons'),
                'fadeInDown' => esc_html__('FadeInDown', 'shortcode-addons'),
                'fadeInDownBig' => esc_html__('FadeInDownBig', 'shortcode-addons'),
                'fadeInLeft' => esc_html__('FadeInLeft', 'shortcode-addons'),
                'fadeInLeftBig' => esc_html__('FadeInLeftBig', 'shortcode-addons'),
                'fadeInRight' => esc_html__('FadeInRight', 'shortcode-addons'),
                'fadeInRightBig' => esc_html__('FadeInRightBig', 'shortcode-addons'),
                'fadeInUp' => esc_html__('FadeInUp', 'shortcode-addons'),
                'fadeInUpBig' => esc_html__('FadeInUpBig', 'shortcode-addons'),
                'optgroup7' => [false],
                'optgroup8' => [true, 'Flippers'],
                'flip' => esc_html__('Flip', 'shortcode-addons'),
                'flipInX' => esc_html__('FlipInX', 'shortcode-addons'),
                'flipInY' => esc_html__('FlipInY', 'shortcode-addons'),
                'optgroup9' => [false],
                'optgroup10' => [true, 'Lightspeed'],
                'lightSpeedIn' => esc_html__('LightSpeedIn', 'shortcode-addons'),
                'optgroup11' => [false],
                'optgroup12' => [true, 'Rotating Entrances'],
                'rotateIn' => esc_html__('RotateIn', 'shortcode-addons'),
                'rotateInDownLeft' => esc_html__('RotateInDownLeft', 'shortcode-addons'),
                'rotateInDownRight' => esc_html__('RotateInDownRight', 'shortcode-addons'),
                'rotateInUpLeft' => esc_html__('RotateInUpLeft', 'shortcode-addons'),
                'rotateInUpRight' => esc_html__('RotateInUpRight', 'shortcode-addons'),
                'optgroup13' => [false],
                'optgroup14' => [true, 'Sliding Entrances'],
                'slideInUp' => esc_html__('SlideInUp', 'shortcode-addons'),
                'slideInDown' => esc_html__('SlideInDown', 'shortcode-addons'),
                'slideInLeft' => esc_html__('SlideInLeft', 'shortcode-addons'),
                'slideInRight' => esc_html__('SlideInRight', 'shortcode-addons'),
                'optgroup15' => [false],
                'optgroup16' => [true, 'Zoom Entrances'],
                'zoomIn' => esc_html__('ZoomIn', 'shortcode-addons'),
                'zoomInDown' => esc_html__('ZoomInDown', 'shortcode-addons'),
                'zoomInLeft' => esc_html__('ZoomInLeft', 'shortcode-addons'),
                'zoomInRight' => esc_html__('ZoomInRight', 'shortcode-addons'),
                'zoomInUp' => esc_html__('ZoomInUp', 'shortcode-addons'),
                'optgroup17' => [false],
                'optgroup18' => [true, 'Specials'],
                'hinge' => esc_html__('Hinge', 'shortcode-addons'),
                'rollIn' => esc_html__('RollIn', 'shortcode-addons'),
                'optgroup19' => [false],
            ],
                ]
        );
        $this->add_control(
                $id . '-duration', $data, [
            'label' => esc_html__('Duration (ms)', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 1000,
            ],
            'range' => [
                'px' => [
                    'min' => 00,
                    'max' => 10000,
                    'step' => 100,
                ],
            ],
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
                ]
        );
        $this->add_control(
                $id . '-delay', $data, [
            'label' => esc_html__('Delay (ms)', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => 00,
                    'max' => 10000,
                    'step' => 100,
                ],
            ],
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
                ]
        );
        $this->add_control(
                $id . '-offset', $data, [
            'label' => esc_html__('Offset', 'shortcode-addons'),
            'type' => Controls::SLIDER,
            'default' => [
                'unit' => 'px',
                'size' => 100,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ],
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
                ]
        );
        $this->add_control(
                $id . '-looping', $data, [
            'label' => esc_html__('Looping', 'shortcode-addons'),
            'type' => Controls::SWITCHER,
            'default' => '',
            'loader' => TRUE,
            'label_on' => esc_html__('Yes', 'shortcode-addons'),
            'label_off' => esc_html__('No', 'shortcode-addons'),
            'return_value' => 'yes',
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
                ]
        );

        $this->end_popover_control();
    }

    /*
     * Shortcode Addons Style Admin Panel Border .
     *
     * @since 2.0.0
     */

    public function border_admin_group_control($id, array $data = [], array $arg = []) {

        $cond = $condition = '';
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $this->start_popover_control(
                $id, [
            'label' => esc_html__('Border', 'shortcode-addons'),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => (array_key_exists('description', $arg) ? esc_html($arg['description']) : ''),
                ]
        );

        $selector_key = $selector = $selectorvalue = $loader = $loadervalue = $render = '';
        if (array_key_exists('selector', $arg)) :
            $selectorvalue = 'selector-value';
            $selector_key = 'selector';
            $selector = $arg['selector'];
        endif;
        if (array_key_exists('loader', $arg)) :
            $loader = 'loader';
            $loadervalue = $arg['loader'];
        endif;
        if (array_key_exists($id . '-type', $data) && $data[$id . '-type'] == '') :
            $render = 'render';
        endif;

        $this->add_control(
                $id . '-type', $data, [
            'label' => esc_html__('Type', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => esc_html__('None', 'shortcode-addons'),
                'solid' => esc_html__('Solid', 'shortcode-addons'),
                'dotted' => esc_html__('Dotted', 'shortcode-addons'),
                'dashed' => esc_html__('Dashed', 'shortcode-addons'),
                'double' => esc_html__('Double', 'shortcode-addons'),
                'groove' => esc_html__('Groove', 'shortcode-addons'),
                'ridge' => esc_html__('Ridge', 'shortcode-addons'),
                'inset' => esc_html__('Inset', 'shortcode-addons'),
                'outset' => esc_html__('Outset', 'shortcode-addons'),
                'hidden' => esc_html__('Hidden', 'shortcode-addons'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'border-style: {{VALUE}};',
            $selector_key => $selector,
                ]
        );
        $this->add_responsive_control(
                $id . '-width', $data, [
            'label' => esc_html__('Width', 'shortcode-addons'),
            'type' => Controls::DIMENSIONS,
            $render => FALSE,
            'default' => [
                'unit' => 'px',
                'size' => '',
            ],
            'range' => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                    'step' => 1,
                ],
                'em' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.01,
                ],
            ],
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
            $loader => $loadervalue,
            $selectorvalue => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            $selector_key => $selector,
                ]
        );
        $this->add_control(
                $id . '-color', $data, [
            'label' => esc_html__('Color', 'shortcode-addons'),
            'type' => Controls::COLOR,
            $render => FALSE,
            'default' => '',
            $loader => $loadervalue,
            $selectorvalue => 'border-color: {{VALUE}};',
            $selector_key => $selector,
            'condition' => [
                $id . '-type' => 'EMPTY',
            ],
                ]
        );
        $this->end_popover_control();
    }

    /*
     * Shortcode Addons Style Admin Panel Background .
     *
     * @since 2.0.0
     */

    public function background_admin_group_control($id, array $data = [], array $arg = []) {

        $backround = '';
        $render = FALSE;
        if (array_key_exists($id . '-color', $data)) :
            $color = $data[$id . '-color'];
            if (array_key_exists($id . '-img', $data) && $data[$id . '-img'] != '0') :
                if (strpos(strtolower($color), 'gradient') === FALSE) :
                    $color = 'linear-gradient(0deg, ' . $color . ' 0%, ' . $color . ' 100%)';
                endif;

                if ($data[$id . '-select'] == 'media-library') :
                    $backround .= 'background: ' . $color . ', url(\'' . $data[$id . '-image'] . '\') ' . $data[$id . '-repeat'] . ' ' . $data[$id . '-position'] . ';';
                else :
                    $backround .= 'background: ' . $color . ', url(\'' . $data[$id . '-url'] . '\') ' . $data[$id . '-repeat'] . ' ' . $data[$id . '-position'] . ';';
                endif;
            else :
                $backround .= 'background: ' . $color . ';';
            endif;
        endif;
        if (array_key_exists('selector', $arg)) :
            foreach ($arg['selector'] as $key => $val) {
                $key = (strpos($key, '{{KEY}}') ? str_replace('{{KEY}}', explode('saarsa', $id)[1], $key) : $key);
                $class = str_replace('{{WRAPPER}}', $this->WRAPPER, $key);
                $this->CSSDATA['laptop'][$class][$backround] = $backround;
                $render = TRUE;
            }
        endif;

        $selector_key = $selector = $selectorvalue = $loader = $loadervalue = '';
        if (array_key_exists('selector', $arg)) :
            $selectorvalue = 'selector-value';
            $selector_key = 'selector';
            $selector = $arg['selector'];
        endif;
        if (array_key_exists('loader', $arg)) :
            $loader = 'loader';
            $loadervalue = $arg['loader'];
        endif;
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $this->start_popover_control(
                $id, [
            'label' => esc_html__('Background', 'shortcode-addons'),
            'condition' => array_key_exists('condition', $arg) ? $arg['condition'] : '',
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => (array_key_exists('description', $arg) ? esc_html($arg['description']) : ''),
                ]
        );
        $this->add_control(
                $id . '-color', $data, [
            'label' => esc_html__('Color', 'shortcode-addons'),
            'type' => Controls::GRADIENT,
            'gradient' => $id,
            'oparetor' => 'RGB',
            'render' => FALSE,
            $selectorvalue => '',
            $selector_key => $selector,
                ]
        );

        $this->add_control(
                $id . '-img', $data, [
            'label' => esc_html__('Image', 'shortcode-addons'),
            'type' => Controls::SWITCHER,
            'loader' => TRUE,
            'label_on' => esc_html__('Yes', 'shortcode-addons'),
            'label_off' => esc_html__('No', 'shortcode-addons'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                $id . '-select', $data, [
            'label' => esc_html__('Photo Source', 'shortcode-addons'),
            'separator' => TRUE,
            'loader' => TRUE,
            'type' => Controls::CHOOSE,
            'default' => 'media-library',
            'options' => [
                'media-library' => [
                    'title' => esc_html__('Media Library', 'shortcode-addons'),
                    'icon' => 'fa fa-align-left',
                ],
                'custom-url' => [
                    'title' => esc_html__('Custom URL', 'shortcode-addons'),
                    'icon' => 'fa fa-align-center',
                ]
            ],
            'condition' => [
                $id . '-img' => 'yes',
            ],
                ]
        );
        $this->add_control(
                $id . '-image', $data, [
            'label' => esc_html__('Image', 'shortcode-addons'),
            'type' => Controls::IMAGE,
            'default' => '',
            'loader' => TRUE,
            'condition' => [
                $id . '-select' => 'media-library',
                $id . '-img' => 'yes',
            ],
                ]
        );
        $this->add_control(
                $id . '-url', $data, [
            'label' => esc_html__('Image URL', 'shortcode-addons'),
            'type' => Controls::TEXT,
            'default' => '',
            'loader' => TRUE,
            'placeholder' => 'www.example.com/image.jpg',
            'condition' => [
                $id . '-select' => 'custom-url',
                $id . '-img' => 'yes',
            ],
                ]
        );
        $this->add_control(
                $id . '-position', $data, [
            'label' => esc_html__('Position', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'default' => 'center center',
            'render' => $render,
            'options' => [
                '' => esc_html__('Default', 'shortcode-addons'),
                'top left' => esc_html__('Top Left', 'shortcode-addons'),
                'top center' => esc_html__('Top Center', 'shortcode-addons'),
                'top right' => esc_html__('Top Right', 'shortcode-addons'),
                'center left' => esc_html__('Center Left', 'shortcode-addons'),
                'center center' => esc_html__('Center Center', 'shortcode-addons'),
                'center right' => esc_html__('Center Right', 'shortcode-addons'),
                'bottom left' => esc_html__('Bottom Left', 'shortcode-addons'),
                'bottom center' => esc_html__('Bottom Center', 'shortcode-addons'),
                'bottom right' => esc_html__('Bottom Right', 'shortcode-addons'),
            ],
            'loader' => TRUE,
            'condition' => [
                $id . '-img' => 'yes',
                '((' . $id . '-select === \'media-library\' && ' . $id . '-image !== \'\') || (' . $id . '-select === \'custom-url\' && ' . $id . '-url !== \'\'))' => 'COMPILED',
            ],
                ]
        );
        $this->add_control(
                $id . '-attachment', $data, [
            'label' => esc_html__('Attachment', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'default' => '',
            'render' => $render,
            'options' => [
                '' => esc_html__('Default', 'shortcode-addons'),
                'scroll' => esc_html__('Scroll', 'shortcode-addons'),
                'fixed' => esc_html__('Fixed', 'shortcode-addons'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'background-attachment: {{VALUE}};',
            $selector_key => $selector,
            'condition' => [
                $id . '-img' => 'yes',
                '((' . $id . '-select === \'media-library\' && ' . $id . '-image !== \'\') || (' . $id . '-select === \'custom-url\' && ' . $id . '-url !== \'\'))' => 'COMPILED',
            ],
                ]
        );
        $this->add_control(
                $id . '-repeat', $data, [
            'label' => esc_html__('Repeat', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'default' => 'no-repeat',
            'render' => $render,
            'options' => [
                '' => esc_html__('Default', 'shortcode-addons'),
                'no-repeat' => esc_html__('No-Repeat', 'shortcode-addons'),
                'repeat' => esc_html__('Repeat', 'shortcode-addons'),
                'repeat-x' => esc_html__('Repeat-x', 'shortcode-addons'),
                'repeat-y' => esc_html__('Repeat-y', 'shortcode-addons'),
            ],
            'loader' => TRUE,
            'condition' => [
                $id . '-img' => 'yes',
                '((' . $id . '-select === \'media-library\' && ' . $id . '-image !== \'\') || (' . $id . '-select === \'custom-url\' && ' . $id . '-url !== \'\'))' => 'COMPILED',
            ],
                ]
        );
        $this->add_responsive_control(
                $id . '-size', $data, [
            'label' => esc_html__('Size', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'default' => 'cover',
            'render' => $render,
            'options' => [
                '' => esc_html__('Default', 'shortcode-addons'),
                'auto' => esc_html__('Auto', 'shortcode-addons'),
                'cover' => esc_html__('Cover', 'shortcode-addons'),
                'contain' => esc_html__('Contain', 'shortcode-addons'),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'background-size: {{VALUE}};',
            $selector_key => $selector,
            'condition' => [
                $id . '-img' => 'yes',
                '((' . $id . '-select === \'media-library\' && ' . $id . '-image !== \'\') || (' . $id . '-select === \'custom-url\' && ' . $id . '-url !== \'\'))' => 'COMPILED',
            ],
                ]
        );
        $this->end_popover_control();
    }

    /*
     * Shortcode Addons Style Admin Panel URL.
     *
     * @since 2.0.0
     */

    public function url_admin_group_control($id, array $data = [], array $arg = []) {
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        else :
            $cond = $condition = '';
        endif;
        $form_condition = array_key_exists('form_condition', $arg) ? $arg['form_condition'] : '';
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;
        $this->add_control(
                $id . '-url', $data, [
            'label' => esc_html__('Link', 'shortcode-addons'),
            'type' => Controls::TEXT,
            'default' => '',
            'link' => TRUE,
            'separator' => $separator,
            'placeholder' => 'www.example.com/',
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            $cond => $condition,
            'description' => (array_key_exists('description', $arg) ? esc_html($arg['description']) : ''),
                ]
        );
        echo '<div class="shortcode-form-control-content shortcode-form-control-content-popover-body">';

        $this->add_control(
                $id . '-target', $data, [
            'label' => esc_html__('New Window?', 'shortcode-addons'),
            'type' => Controls::SWITCHER,
            'default' => '',
            'loader' => TRUE,
            'label_on' => esc_html__('Yes', 'shortcode-addons'),
            'label_off' => esc_html__('No', 'shortcode-addons'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                $id . '-follow', $data, [
            'label' => esc_html__('No Follow', 'shortcode-addons'),
            'type' => Controls::SWITCHER,
            'default' => 'yes',
            'loader' => TRUE,
            'label_on' => esc_html__('Yes', 'shortcode-addons'),
            'label_off' => esc_html__('No', 'shortcode-addons'),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                $id . '-id', $data, [
            'label' => esc_html__('CSS ID', 'shortcode-addons'),
            'type' => Controls::TEXT,
            'default' => '',
            'placeholder' => 'abcd-css-id',
                ]
        );
        echo '</div></div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Column Size.
     *
     * @since 2.0.0
     */

    public function column_admin_group_control($id, array $data = [], array $arg = []) {
        $selector = array_key_exists('selector', $arg) ? $arg['selector'] : '';
        $select = array_key_exists('selector', $arg) ? 'selector' : '';
        $cond = $condition = '';
        if (array_key_exists('condition', $arg)) :
            $cond = 'condition';
            $condition = $arg['condition'];
        endif;

        $this->add_control(
                $lap = $id . '-lap', $data, [
            'label' => esc_html__('Column Size', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'responsive' => 'laptop',
            'default' => 'oxi-bt-col-lg-12',
            'options' => [
                'oxi-bt-col-lg-12' => esc_html__('Col 1', 'shortcode-addons'),
                'oxi-bt-col-lg-6' => esc_html__('Col 2', 'shortcode-addons'),
                'oxi-bt-col-lg-4' => esc_html__('Col 3', 'shortcode-addons'),
                'oxi-bt-col-lg-3' => esc_html__('Col 4', 'shortcode-addons'),
                'oxi-bt-col-lg-2' => esc_html__('Col 6', 'shortcode-addons'),
                'oxi-bt-col-lg-1' => esc_html__('Col 12', 'shortcode-addons'),
            ],
            $select => $selector,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            $cond => $condition,
            'description' => 'Define how much column you want to show into single rows. Customize possible with desktop or tab or mobile Settings.',
                ]
        );
        $this->add_control(
                $tab = $id . '-tab', $data, [
            'label' => esc_html__('Column Size', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'responsive' => 'tab',
            'default' => 'oxi-bt-col-md-12',
            'options' => [
                'oxi-bt-col-md-12' => esc_html__('Col 1', 'shortcode-addons'),
                'oxi-bt-col-md-6' => esc_html__('Col 2', 'shortcode-addons'),
                'oxi-bt-col-md-4' => esc_html__('Col 3', 'shortcode-addons'),
                'oxi-bt-col-md-3' => esc_html__('Col 4', 'shortcode-addons'),
                'oxi-bt-col-md-2' => esc_html__('Col 6', 'shortcode-addons'),
                'oxi-bt-col-md-1' => esc_html__('Col 12', 'shortcode-addons'),
            ],
            $select => $selector,
            'description' => 'Define how much column you want to show into single rows. Customize possible with desktop or tab or mobile Settings.',
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            $cond => $condition
                ]
        );
        $this->add_control(
                $mob = $id . '-mob', $data, [
            'label' => esc_html__('Column Size', 'shortcode-addons'),
            'type' => Controls::SELECT,
            'default' => 'oxi-bt-col-lg-12',
            'responsive' => 'mobile',
            'options' => [
                'oxi-bt-col-sm-12' => esc_html__('Col 1', 'shortcode-addons'),
                'oxi-bt-col-sm-6' => esc_html__('Col 2', 'shortcode-addons'),
                'oxi-bt-col-sm-4' => esc_html__('Col 3', 'shortcode-addons'),
                'oxi-bt-col-sm-3' => esc_html__('Col 4', 'shortcode-addons'),
                'oxi-bt-col-sm-2' => esc_html__('Col 6', 'shortcode-addons'),
                'oxi-bt-col-sm-1' => esc_html__('Col 12', 'shortcode-addons'),
            ],
            $select => $selector,
            'description' => 'Define how much column you want to show into single rows. Customize possible with desktop or tab or mobile Settings.',
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            $cond => $condition
                ]
        );
    }

    /*
     *
     *
     * Templates Substitute Data
     *
     *
     *
     *
     */
    /*
     * Shortcode Addons Style Admin Panel Template Substitute Control.
     *
     * @since 2.0.0
     */

    public function add_substitute_control($id, array $data = [], array $arg = []) {
        $fun = $arg['type'] . '_substitute_control';
        $this->$fun($id, $data, $arg);
    }

    /*
     * Shortcode Addons Style Admin Panel Template Substitute Modal Opener.
     *
     * @since 2.0.0
     */

    public function modalopener_substitute_control($id, array $data = [], array $arg = []) {
        $default = [
            'showing' => FALSE,
            'title' => 'Add New Items',
            'sub-title' => 'Add New Items'
        ];
        $arg = array_merge($default, $arg);
        /*
         * $arg['title'] = 'Add New Items';
         * $arg['sub-title'] = 'Add New Items 02';
         *
         */
        echo ' <div class = "oxi-addons-item-form shortcode-addons-templates-right-panel ' . (($arg['showing']) ? '' : 'oxi-admin-head-d-none') . '">
                    <div class = "oxi-addons-item-form-heading shortcode-addons-templates-right-panel-heading">
                        ' . esc_html($arg['title']) . '
                         <div class = "oxi-head-toggle"></div>
                         </div>
                    <div class = "oxi-addons-item-form-item shortcode-addons-templates-right-panel-body" id = "oxi-addons-list-data-modal-open">
                        <span>
                            <i class = "dashicons dashicons-plus-alt oxi-icons"></i>
                            ' . esc_html($arg['sub-title']) . '
                        </span>
                    </div>
                </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Template Shortcode name.
     *
     * @since 2.0.0
     */

    public function shortcodename_substitute_control($id, array $data = [], array $arg = []) {
        $default = [
            'showing' => FALSE,
            'title' => 'Shortcode Name',
            'placeholder' => 'Set Your Shortcode Name'
        ];
        $arg = array_merge($default, $arg);
        /*
         * $arg['title'] = 'Add New Items';
         * $arg['sub-title'] = 'Add New Items 02';
         *
         */
        echo '  <div class = "oxi-addons-shortcode  shortcode-addons-templates-right-panel ' . (($arg['showing']) ? '' : 'oxi-admin-head-d-none') . '">
                    <div class = "oxi-addons-shortcode-heading  shortcode-addons-templates-right-panel-heading">
                        ' . esc_html($arg['title']) . '
                        <div class = "oxi-head-toggle"></div>
                    </div>
                    <div class = "oxi-addons-shortcode-body  shortcode-addons-templates-right-panel-body">
                        <form method = "post" id = "shortcode-addons-name-change-submit">
                            <div class = "input-group my-2">
                                <input type = "hidden" class = "form-control" name = "addonsstylenameid" value = "' . esc_attr($data['id']) . '">
                                <input type = "text" class = "form-control" name = "addonsstylename" placeholder = " ' . esc_html($arg['placeholder']) . '" value = "' . esc_attr($data['name']) . '">
                                <div class = "input-group-append">
                                   <button type = "button" class = "btn btn-success" id = "addonsstylenamechange">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Template Shortcode Info.
     *
     * @since 2.0.0
     */

    public function shortcodeinfo_substitute_control($id, array $data = [], array $arg = []) {
        $default = [
            'showing' => FALSE,
            'title' => 'Shortcode',
        ];
        $arg = array_merge($default, $arg);
        /*
         * $arg['title'] = 'Add New Items';
         * $arg['sub-title'] = 'Add New Items 02';
         *
         */
        echo '  <div class = "oxi-addons-shortcode shortcode-addons-templates-right-panel ' . (($arg['showing']) ? '' : 'oxi-admin-head-d-none') . '">
                    <div class = "oxi-addons-shortcode-heading  shortcode-addons-templates-right-panel-heading">
                        ' . esc_html($arg['title']) . '
                        <div class = "oxi-head-toggle"></div>
                    </div>
                    <div class = "oxi-addons-shortcode-body shortcode-addons-templates-right-panel-body">
                        <em>Shortcode for posts/pages/plugins</em>
                        <p>Copy &amp;
                        paste the shortcode directly into any WordPress post, page or Page Builder.</p>
                        <input type = "text" class = "form-control" onclick = "this.setSelectionRange(0, this.value.length)" value = "[oxi_addons id=&quot;' . esc_attr($id) . '&quot;]">
                        <span></span>
                        <em>Shortcode for templates/themes</em>
                        <p>Copy &amp;
                        paste this code into a template file to include the slideshow within your theme.</p>
                        <input type = "text" class = "form-control" onclick = "this.setSelectionRange(0, this.value.length)" value = "<?php echo do_shortcode(\'[oxi_addons  id=&quot;' . esc_attr($id) . '&quot;]\'); ?>">
                        <span></span>
                    </div>
                </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel Rearrange.
     *
     * @since 2.1.0
     */

    public function rearrange_substitute_control($id, array $data = [], array $arg = []) {
        $default = [
            'showing' => FALSE,
            'title' => 'Flipbox Rearrange',
            'sub-title' => 'Flip Data Rearrange'
        ];
        $arg = array_merge($default, $arg);
        /*
         * $arg['title'] = 'Add New Items';
         * $arg['sub-title'] = 'Add New Items 02';
         *
         */
        echo ' <div class="oxi-addons-item-form shortcode-addons-templates-right-panel ' . (($arg['showing']) ? '' : 'oxi-admin-head-d-none') . '">
            <div class="oxi-addons-item-form-heading shortcode-addons-templates-right-panel-heading">
                ' . esc_html($arg['title']) . '
                 <div class="oxi-head-toggle"></div>
            </div>
            <div class="oxi-addons-item-form-item shortcode-addons-templates-right-panel-body" id="oxi-addons-rearrange-data-modal-open">
                <span>
                    <i class="dashicons dashicons-plus-alt oxi-icons"></i>
                    ' . esc_html($arg['sub-title']) . '
                </span>
            </div>
        </div>
        <div id="oxi-addons-list-rearrange-modal" class="modal fade bd-example-modal-sm" role="dialog">
            <div class="modal-dialog modal-sm">
                <form id="oxi-addons-form-rearrange-submit">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Flipbox Rearrange</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12 alert text-center" id="oxi-addons-list-rearrange-saving">
                               <i class="fa fa-spinner fa-spin"></i>
                            </div>
                            <ul class="col-12 list-group" id="oxi-addons-modal-rearrange">
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="oxi-addons-list-rearrange-data">
                            <button type="button" id="oxi-addons-list-rearrange-close" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <input type="submit" id="oxi-addons-list-rearrange-submit" class="btn btn-primary" value="Save">
                        </div>
                    </div>
                </form>
                <div id="modal-rearrange-store-file">
                    ' . esc_attr($id) . '
                </div>
            </div>
         </div>';
    }

}
