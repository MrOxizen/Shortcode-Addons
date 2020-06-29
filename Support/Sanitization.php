<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SHORTCODE_ADDONS\Support;

/**
 *
 * @author biplo
 */
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
            echo '<li ref="#shortcode-addons-section-' . $key . '">' . $value . '</li>';
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
                echo $value;
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
// echo $i;
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
        echo '<div class="oxi-addons-content-div ' . (($arg['showing']) ? '' : 'oxi-admin-head-d-none') . '" ' . $condition . '>
                    <div class="oxi-head">
                    ' . $arg['label'] . '
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
				' . $value . '
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
        echo '  <div class="shortcode-form-control shortcode-control-type-popover ' . $separator . '" ' . $condition . '>
                    <div class="shortcode-form-control-content shortcode-form-control-content-popover">
                        <div class="shortcode-form-control-field">
                            <label for="" class="shortcode-form-control-title">' . $arg['label'] . '</label>  
                            <div class="shortcode-form-control-input-wrapper">
                                <span class="dashicons popover-set"></span>
                            </div>
                        </div>
                        ' . (array_key_exists('description', $arg) ? '<div class="shortcode-form-control-description">' . $arg['description'] . '</div>' : '') . '
                        
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
            $responsive = '<div class="shortcode-form-control-responsive-switchers">
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
        $defualt = [
            'type' => 'text',
            'label' => 'Input Text',
            'default' => '',
            'label_on' => __('Yes', SHORTCODE_ADDOONS),
            'label_off' => __('No', SHORTCODE_ADDOONS),
            'placeholder' => __('', SHORTCODE_ADDOONS),
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
                '' => __('None', SHORTCODE_ADDOONS),
                'bounce' => __('Bounce', SHORTCODE_ADDOONS),
                'flash' => __('Flash', SHORTCODE_ADDOONS),
                'pulse' => __('Pulse', SHORTCODE_ADDOONS),
                'rubberBand' => __('RubberBand', SHORTCODE_ADDOONS),
                'shake' => __('Shake', SHORTCODE_ADDOONS),
                'swing' => __('Swing', SHORTCODE_ADDOONS),
                'tada' => __('Tada', SHORTCODE_ADDOONS),
                'wobble' => __('Wobble', SHORTCODE_ADDOONS),
                'jello' => __('Jello', SHORTCODE_ADDOONS),
            ];
        endif;
        $fun = $arg['type'] . '_admin_control';
        $condition = $this->forms_condition($arg);
        $toggle = (array_key_exists('toggle', $arg) ? 'shortcode-addons-form-toggle' : '');
        $separator = (array_key_exists('separator', $arg) ? ($arg['separator'] === TRUE ? 'shortcode-form-control-separator-before' : '') : '');

        $loader = (array_key_exists('loader', $arg) ? $arg['loader'] == TRUE ? ' shortcode-addons-control-loader ' : '' : '');
        echo '<div class="shortcode-form-control shortcode-control-type-' . $arg['type'] . ' ' . $separator . ' ' . $toggle . ' ' . $responsiveclass . ' ' . $loader . '" ' . $condition . '>
                <div class="shortcode-form-control-content">
                    <div class="shortcode-form-control-field">
                    <label for="" class="shortcode-form-control-title">' . $arg['label'] . '</label>';
        echo $responsive;
        echo $this->$fun($id, $data, $arg);
        echo '      </div>
                ' . (array_key_exists('description', $arg) ? '<div class="shortcode-form-control-description">' . $arg['description'] . '</div>' : '') . '
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
        echo $this->$fun($id, $data, $arg);
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
        echo '<div class="shortcode-form-control shortcode-control-type-' . $arg['type'] . ' ' . $separator . '" ' . $condition . ' id="' . $id . '">
                <div class="shortcode-form-control-content">
                    <div class="shortcode-form-control-field">
                        <label for="" class="shortcode-form-control-title">' . $arg['label'] . '</label>
                    </div>
                    <div class="shortcode-form-repeater-fields-wrapper">';
        if (array_key_exists($id, $data)) :
            foreach ($data[$id] as $k => $vl) {
                $style = [];
                foreach ($vl as $c => $v) {
                    $style[$id . 'saarsa' . $k . 'saarsa' . $c] = $v;
                }
                echo '  <div class="shortcode-form-repeater-fields" tab-title="' . $arg['title_field'] . '">
                            <div class="shortcode-form-repeater-controls">
                                <div class="shortcode-form-repeater-controls-title">
                                    ' . ($vl[$arg['title_field']]) . '
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
                    $child = $id . 'saarsa' . $k . 'saarsa' . $key;
                    $value['conditional'] = (array_key_exists('conditional', $value) ? ($value['conditional'] == 'outside') ? 'outside' : 'inside' : '');
                    $value['form_condition'] = (array_key_exists('conditional', $value) ? ($value['conditional'] == 'inside') ? $id . 'saarsa' . $k . 'saarsa' : '' : '');

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
        echo '      <div class="shortcode-form-repeater-button-wrapper"><a href="#" parent-id="' . $id . '" class="shortcode-form-repeater-button"><span class="dashicons dashicons-plus"></span> ' . $buttontext . '</a></div>';

        echo '  </div>
             </div>';

        $this->repeater .= '<div id="repeater-' . $id . '-initial-data">
                                <div class="shortcode-form-repeater-fields" tab-title="' . $arg['title_field'] . '">
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
        echo '<div class="shortcode-form-control shortcode-control-type-' . $arg['type'] . ' ' . $separator . '" ' . $condition . '>
                <div class="shortcode-form-control-content">
                    <div class="shortcode-form-control-field">
                        <label for="" class="shortcode-form-control-title">' . $arg['label'] . '</label>
                    </div>
                    <div class="shortcode-form-rearrange-fields-wrapper" vlid="#' . $id . '">';
        $rearrange = explode(',', $value);
        foreach ($rearrange as $k => $vl) {
            if ($vl != ''):
                echo '  <div class="shortcode-form-repeater-fields" id="' . $vl . '">
                            <div class="shortcode-form-repeater-controls">
                                <div class="shortcode-form-repeater-controls-title">
                                    ' . ($arg['fields'][$vl]['label']) . '
                                </div>
                            </div>
                        </div>';
            endif;
        }
        echo '          <div class="shortcode-form-control-input-wrapper">
                            <input type="hidden" value="' . $value . '" name="' . $id . '" id="' . $id . '">
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

        $val = preg_replace_callback('/\{\{\K(.*?)(?=}})/', function($match)use ($data) {
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
                        <input type="checkbox" ' . ($value == $arg['return_value'] ? 'checked ckdflt="true"' : '') . ' value="' . $arg['return_value'] . '"  name="' . $id . '" id="' . $id . '"/>
                        <span data-on="' . $arg['label_on'] . '" data-off="' . $arg['label_off'] . '"></span>
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
                     <input type="text"  name="' . $id . '" id="' . $id . '" value="' . htmlspecialchars($value) . '" placeholder="' . $arg['placeholder'] . '" retundata=\'' . $retunvalue . '\'>
                     <span class="dashicons dashicons-admin-generic"></span>';
        else :
            echo '<div class="shortcode-form-control-input-wrapper">
                <input type="text"  name="' . $id . '" id="' . $id . '" value="' . htmlspecialchars($value) . '" placeholder="' . $arg['placeholder'] . '" retundata=\'' . $retunvalue . '\'>
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
                     <input type="password"  name="' . $id . '" id="' . $id . '" value="' . htmlspecialchars($value) . '" placeholder="' . $arg['placeholder'] . '" retundata=\'' . $retunvalue . '\'>
                     <span class="dashicons dashicons-admin-generic"></span>';
        else :
            echo '<div class="shortcode-form-control-input-wrapper">
                <input type="password"  name="' . $id . '" id="' . $id . '" value="' . htmlspecialchars($value) . '" placeholder="' . $arg['placeholder'] . '" retundata=\'' . $retunvalue . '\'>
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
                   <input type="hidden" value="' . $value . '" name="' . $id . '" id="' . $id . '">
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
                 <textarea  name="' . $id . '" id="' . $id . '" retundata=\'' . $retunvalue . '\' class="shortcode-form-control-tag-area" rows="' . (int) ((strlen($value) / 50) + 2) . '" placeholder="' . $arg['placeholder'] . '">' . str_replace('&nbsp;', '  ', str_replace('<br>', '&#13;&#10;', $value)) . '</textarea>
              </div>';
    }

    /*
     * Shortcode Addons Style Admin Panel WYSIWYG Input.
     * 
     * @since 2.0.0
     */

    public function wysiwyg_admin_control($id, array $data = [], array $arg = []) {
        $value = array_key_exists($id, $data) ? $data[$id] : $arg['default'];
        $retunvalue = array_key_exists('selector', $arg) ? htmlspecialchars(json_encode($arg['selector'])) : '';
        echo ' <div class="shortcode-form-control-input-wrapper"  retundata=\'' . $retunvalue . '\'>';
        echo wp_editor(
                $value, $id, $settings = array(
            'textarea_name' => $id,
            'wpautop' => false,
            'textarea_rows' => 7,
            'force_br_newlines' => true,
            'force_p_newlines' => false
                )
        );
        echo ' </div>';
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
            $img = 'style="background-image: url(' . $value . ');" ckdflt="background-image: url(' . $value . ');"';
            $type = '';
            $altfile = '<input type="hidden" class="shortcode-addons-media-control-link-alt" id="' . $id . '-alt" name="' . $id . '-alt" value="' . $alt . '" >';
        endif;
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <div class="shortcode-addons-media-control ' . (empty($value) ? 'shortcode-addons-media-control-hidden-button' : '') . ' shortcode-addons-media-control-type-' . $type . '">
                        <div class="shortcode-addons-media-control-pre-load">
                        </div>
                        <div class="shortcode-addons-media-control-image-load" ' . $img . '>
                            <div class="shortcode-addons-media-control-image-load-delete-button">
                            </div>
                        </div>
                        <div class="shortcode-addons-media-control-choose-image">
                            Choose ' . (isset($arg['select']) ? ucfirst($arg['select']) : 'Image') . '
                        </div>
                    </div>
                    <input type="hidden" data-type="' . $type . '" class="shortcode-addons-media-control-link" id="' . $id . '" name="' . $id . '" value="' . $value . '" >
                    ' . $altfile . '
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
                    <input id="' . $id . '" name="' . $id . '" type="number" min="' . $arg['min'] . '" max="' . $arg['max'] . '" step="' . $arg['step'] . '" value="' . $value . '"  responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
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
                    echo '<input id="' . $id . '-choices-' . $rand . '" type="radio" name="' . $id . '-choices' . '"  value="' . $key . '" ' . ($key == $unit ? 'checked' : '') . '  min="' . $val['min'] . '" max="' . $val['max'] . '" step="' . $val['step'] . '">
                      <label class="shortcode-form-units-choices-label" for="' . $id . '-choices-' . $rand . '">' . $key . '</label>';
                }
                echo '</div>';
            endif;
        endif;
        $unitvalue = array_key_exists($id . '-choices', $data) ? 'unit="' . $data[$id . '-choices'] . '"' : '';
        echo '  <div class="shortcode-form-control-input-wrapper">
                    <div class="shortcode-form-slider" id="' . $id . '-slider' . '"></div>
                    <div class="shortcode-form-slider-input">
                        <input name="' . $id . '-size' . '" custom="' . (array_key_exists('custom', $arg) ? '' . $arg['custom'] . '' : '') . '" id="' . $id . '-size' . '" type="number" min="' . $arg['range'][$unit]['min'] . '" max="' . $arg['range'][$unit]['max'] . '" step="' . $arg['range'][$unit]['step'] . '" value="' . $size . '" default-value="' . $size . '" ' . $unitvalue . ' responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
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
                <select id="' . $id . '" class="shortcode-addons-select-input ' . ($multiple ? 'js-example-basic-multiple' : '' ) . '" ' . ($multiple ? 'multiple' : '' ) . ' name="' . $id . '' . ($multiple ? '[]' : '' ) . '"  responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>';
        foreach ($arg['options'] as $key => $val) {
            if (is_array($val)):
                if (isset($val[0]) && $val[0] == true):
                    echo '<optgroup label="' . $val[1] . '">';
                else:
                    echo '</optgroup>';
                endif;
            else:
                if (is_array($value)):
                    $new = array_flip($value);
                    echo ' <option value="' . $key . '" ' . (array_key_exists($key, $new) ? 'selected' : '') . '>' . $val . '</option>';
                else:
                    echo ' <option value="' . $key . '" ' . ($value == $key ? 'selected' : '') . '>' . $val . '</option>';
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
                <div class="shortcode-form-choices" responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>';
        foreach ($arg['options'] as $key => $val) {
            echo '  <input id="' . $id . '-' . $key . '" type="radio" name="' . $id . '" value="' . $key . '" ' . ($value == $key ? 'checked  ckdflt="true"' : '') . '>
                                    <label class="shortcode-form-choices-label" for="' . $id . '-' . $key . '" tooltip="' . $val['title'] . '">
                                        ' . (($operator == 'text') ? $val['title'] : '<i class="' . $val['icon'] . '" aria-hidden="true"></i>') . '
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
                        echo $t;
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
                <input ' . $type . ' type="text"  class="oxi-addons-minicolor" id="' . $id . '" name="' . $id . '" value="' . $value . '" responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\' custom="' . (array_key_exists('custom', $arg) ? '' . $arg['custom'] . '' : '') . '">
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
                    <input type="text"  class="oxi-admin-icon-selector" id="' . $id . '" name="' . $id . '" value="' . $value . '">
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
                    <input type="text"  class="shortcode-addons-family" id="' . $id . '" name="' . $id . '" value="' . $value . '" responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
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
                    <input type="' . $format . '"  id="' . $id . '" name="' . $id . '" value="' . $value . '">
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
                    <input type="text" background="' . $background . '"  class="shortcode-addons-gradient-color" id="' . $id . '" name="' . $id . '" value="' . $value . '" responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
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
                    echo '<input id="' . $id . '-choices-' . $rand . '" type="radio" name="' . $id . '-choices"  value="' . $key . '" ' . ($key == $unit ? 'checked' : '') . '  min="' . $val['min'] . '" max="' . $val['max'] . '" step="' . $val['step'] . '">
                      <label class="shortcode-form-units-choices-label" for="' . $id . '-choices-' . $rand . '">' . $key . '</label>';
                }
                echo '</div>';
            endif;
        endif;
        $unitvalue = array_key_exists($id . '-choices', $data) ? 'unit="' . $data[$id . '-choices'] . '"' : $arg['default']['unit'];
        echo '<div class="shortcode-form-control-input-wrapper">
                <ul class="shortcode-form-control-dimensions">
                    <li class="shortcode-form-control-dimension">
                        <input id="' . $id . '-top" input-id="' . $id . '" name="' . $id . '-top" type="number"  min="' . $arg['range'][$unit]['min'] . '" max="' . $arg['range'][$unit]['max'] . '" step="' . $arg['range'][$unit]['step'] . '" value="' . $top . '" default-value="' . $top . '" ' . $unitvalue . ' responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
                        <label for="' . $id . '-top" class="shortcode-form-control-dimension-label">Top</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                       <input id="' . $id . '-right" input-id="' . $id . '" name="' . $id . '-right" type="number"  min="' . $arg['range'][$unit]['min'] . '" max="' . $arg['range'][$unit]['max'] . '" step="' . $arg['range'][$unit]['step'] . '" value="' . $right . '" default-value="' . $right . '" ' . $unitvalue . ' responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
                         <label for="' . $id . '-right" class="shortcode-form-control-dimension-label">Right</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                       <input id="' . $id . '-bottom" input-id="' . $id . '" name="' . $id . '-bottom" type="number"  min="' . $arg['range'][$unit]['min'] . '" max="' . $arg['range'][$unit]['max'] . '" step="' . $arg['range'][$unit]['step'] . '" value="' . $bottom . '" default-value="' . $bottom . '" ' . $unitvalue . ' responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
                       <label for="' . $id . '-bottom" class="shortcode-form-control-dimension-label">Bottom</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                        <input id="' . $id . '-left" input-id="' . $id . '" name="' . $id . '-left" type="number"  min="' . $arg['range'][$unit]['min'] . '" max="' . $arg['range'][$unit]['max'] . '" step="' . $arg['range'][$unit]['step'] . '" value="' . $left . '" default-value="' . $left . '" ' . $unitvalue . ' responsive="' . $arg['responsive'] . '" retundata=\'' . $retunvalue . '\'>
                         <label for="' . $id . '-left" class="shortcode-form-control-dimension-label">Left</label>
                    </li>
                    <li class="shortcode-form-control-dimension">
                        <button type="button" class="shortcode-form-link-dimensions ' . $unlink . '"  data-tooltip="Link values together"></button>
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
            'label' => __('Typography', SHORTCODE_ADDOONS),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => (array_key_exists('description', $arg)? $arg['description']: ''),
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
            'label' => __('Font Family', SHORTCODE_ADDOONS),
            'type' => Controls::FONT,
            $selectorvalue => 'font-family:"{{VALUE}}";',
            $selector_key => $selector,
            $loader => $loadervalue
                ]
        );
        $this->add_responsive_control(
                $id . '-size', $data, [
            'label' => __('Size', SHORTCODE_ADDOONS),
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
            'label' => __('Weight', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            $selectorvalue => 'font-weight: {{VALUE}};',
            $loader => $loadervalue,
            $selector_key => $selector,
            'options' => [
                '100' => __('100', SHORTCODE_ADDOONS),
                '200' => __('200', SHORTCODE_ADDOONS),
                '300' => __('300', SHORTCODE_ADDOONS),
                '400' => __('400', SHORTCODE_ADDOONS),
                '500' => __('500', SHORTCODE_ADDOONS),
                '600' => __('600', SHORTCODE_ADDOONS),
                '700' => __('700', SHORTCODE_ADDOONS),
                '800' => __('800', SHORTCODE_ADDOONS),
                '900' => __('900', SHORTCODE_ADDOONS),
                '' => __('Default', SHORTCODE_ADDOONS),
                'normal' => __('Normal', SHORTCODE_ADDOONS),
                'bold' => __('Bold', SHORTCODE_ADDOONS)
            ],
                ]
        );
        $this->add_control(
                $id . '-transform', $data, [
            'label' => __('Transform', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => __('Default', SHORTCODE_ADDOONS),
                'uppercase' => __('Uppercase', SHORTCODE_ADDOONS),
                'lowercase' => __('Lowercase', SHORTCODE_ADDOONS),
                'capitalize' => __('Capitalize', SHORTCODE_ADDOONS),
                'none' => __('Normal', SHORTCODE_ADDOONS),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'text-transform: {{VALUE}};',
            $selector_key => $selector,
                ]
        );
        $this->add_control(
                $id . '-style', $data, [
            'label' => __('Style', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => __('Default', SHORTCODE_ADDOONS),
                'normal' => __('normal', SHORTCODE_ADDOONS),
                'italic' => __('Italic', SHORTCODE_ADDOONS),
                'oblique' => __('Oblique', SHORTCODE_ADDOONS),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'font-style: {{VALUE}};',
            $selector_key => $selector,
                ]
        );
        $this->add_control(
                $id . '-decoration', $data, [
            'label' => __('Decoration', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => __('Default', SHORTCODE_ADDOONS),
                'underline' => __('Underline', SHORTCODE_ADDOONS),
                'overline' => __('Overline', SHORTCODE_ADDOONS),
                'line-through' => __('Line Through', SHORTCODE_ADDOONS),
                'none' => __('None', SHORTCODE_ADDOONS),
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
                    'label' => __('Text Align', SHORTCODE_ADDOONS),
                    'type' => Controls::SELECT,
                    'default' => '',
                    'options' => [
                        '' => __('Default', SHORTCODE_ADDOONS),
                        'left' => __('Left', SHORTCODE_ADDOONS),
                        'center' => __('Center', SHORTCODE_ADDOONS),
                        'right' => __('Right', SHORTCODE_ADDOONS),
                    ],
                    $loader => $loadervalue,
                    $selectorvalue => 'text-align: {{VALUE}};',
                    $selector_key => $selector,
                        ]
                );
            else :
                $this->add_responsive_control(
                        $id . '-justify', $data, [
                    'label' => __('Justify Content', SHORTCODE_ADDOONS),
                    'type' => Controls::SELECT,
                    'default' => '',
                    'options' => [
                        '' => __('Default', SHORTCODE_ADDOONS),
                        'flex-start' => __('Flex Start', SHORTCODE_ADDOONS),
                        'flex-end' => __('Flex End', SHORTCODE_ADDOONS),
                        'center' => __('Center', SHORTCODE_ADDOONS),
                        'space-around' => __('Space Around', SHORTCODE_ADDOONS),
                        'space-between' => __('Space Between', SHORTCODE_ADDOONS),
                    ],
                    $loader => $loadervalue,
                    $selectorvalue => 'justify-content: {{VALUE}};',
                    $selector_key => $selector,
                        ]
                );
                $this->add_responsive_control(
                        $id . '-align', $data, [
                    'label' => __('Align Items', SHORTCODE_ADDOONS),
                    'type' => Controls::SELECT,
                    'default' => '',
                    'options' => [
                        '' => __('Default', SHORTCODE_ADDOONS),
                        'stretch' => __('Stretch', SHORTCODE_ADDOONS),
                        'baseline' => __('Baseline', SHORTCODE_ADDOONS),
                        'center' => __('Center', SHORTCODE_ADDOONS),
                        'flex-start' => __('Flex Start', SHORTCODE_ADDOONS),
                        'flex-end' => __('Flex End', SHORTCODE_ADDOONS),
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
            'label' => __('Line Height', SHORTCODE_ADDOONS),
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
            'label' => __('Letter Spacing', SHORTCODE_ADDOONS),
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
        $level = array_key_exists('label', $arg) ? $arg['label'] : 'Photo Source';
        $separator = array_key_exists('separator', $arg) ? $arg['separator'] : FALSE;

        echo '<div class="shortcode-form-control" style="padding: 0;" ' . $this->forms_condition($arg) . '>';
        $this->add_control(
                $id . '-select', $data, [
            'label' => __($level, SHORTCODE_ADDOONS),
            'type' => Controls::CHOOSE,
            'loader' => TRUE,
            'default' => $type,
            'separator' => $separator,
            'options' => [
                'media-library' => [
                    'title' => __('Media Library', SHORTCODE_ADDOONS),
                    'icon' => 'fa fa-align-left',
                ],
                'custom-url' => [
                    'title' => __('Custom URL', SHORTCODE_ADDOONS),
                    'icon' => 'fa fa-align-center',
                ]
            ],
                ]
        );
        $this->add_control(
                $id . '-image', $data, [
            'label' => __('Image', SHORTCODE_ADDOONS),
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
            'label' => __('Image URL', SHORTCODE_ADDOONS),
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

        echo '<div class="shortcode-form-control" style="padding: 0;" ' . $this->forms_condition($arg) . '>';
        $this->add_control(
                $id . '-select', $data, [
            'label' => __(ucfirst($filetype) . ' Source', SHORTCODE_ADDOONS),
            'type' => Controls::CHOOSE,
            'loader' => TRUE,
            'default' => $type,
            'separator' => $separator,
            'options' => [
                'media-library' => [
                    'title' => __('Media', SHORTCODE_ADDOONS),
                    'icon' => 'fa fa-align-left',
                ],
                'custom-url' => [
                    'title' => __('Custom', SHORTCODE_ADDOONS),
                    'icon' => 'fa fa-align-center',
                ]
            ],
                ]
        );
        $this->add_control(
                $id . '-media', $data, [
            'label' => __(ucfirst($filetype), SHORTCODE_ADDOONS),
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
            'label' => __(ucfirst($filetype) . ' URL', SHORTCODE_ADDOONS),
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
            'label' => __('Box Shadow', SHORTCODE_ADDOONS),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => (array_key_exists('description', $arg)? $arg['description']: ''),
                ]
        );
        $this->add_control(
                $id . '-shadow', $data, [
            'label' => __('Shadow', SHORTCODE_ADDOONS),
            'type' => Controls::SWITCHER,
            'loader' => TRUE,
            'default' => 'yes',
            'label_on' => __('Yes', SHORTCODE_ADDOONS),
            'label_off' => __('None', SHORTCODE_ADDOONS),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                $id . '-type', $data, [
            'label' => __('Type', SHORTCODE_ADDOONS),
            'type' => Controls::CHOOSE,
            'loader' => TRUE,
            'default' => '',
            'options' => [
                '' => [
                    'title' => __('Outline', SHORTCODE_ADDOONS),
                    'icon' => 'fa fa-align-left',
                ],
                'inset' => [
                    'title' => __('Inset', SHORTCODE_ADDOONS),
                    'icon' => 'fa fa-align-center',
                ],
            ],
            'condition' => [$id . '-shadow' => 'yes']
                ]
        );

        $this->add_control(
                $id . '-horizontal', $data, [
            'label' => __('Horizontal', SHORTCODE_ADDOONS),
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
            'label' => __('Vertical', SHORTCODE_ADDOONS),
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
            'label' => __('Blur', SHORTCODE_ADDOONS),
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
            'label' => __('Spread', SHORTCODE_ADDOONS),
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
            'label' => __('Color', SHORTCODE_ADDOONS),
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
            'label' => __('Text Shadow', SHORTCODE_ADDOONS),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => (array_key_exists('description', $arg)? $arg['description']: ''),
                ]
        );
        $this->add_control(
                $id . '-color', $data, [
            'label' => __('Color', SHORTCODE_ADDOONS),
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
            'label' => __('Blur', SHORTCODE_ADDOONS),
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
            'label' => __('Horizontal', SHORTCODE_ADDOONS),
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
            'label' => __('Vertical', SHORTCODE_ADDOONS),
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
            'label' => __('Animation', SHORTCODE_ADDOONS),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => 'Customize animation with animation type, Animation Duration with Delay and Looping Options',
                ]
        );
        $this->add_control(
                $id . '-type', $data, [
            'label' => __('Type', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                'optgroup0' => [true, 'Attention Seekers'],
                '' => __('None', SHORTCODE_ADDOONS),
                'optgroup1' => [false],
                'optgroup2' => [true, 'Attention Seekers'],
                'bounce' => __('Bounce', SHORTCODE_ADDOONS),
                'flash' => __('Flash', SHORTCODE_ADDOONS),
                'pulse' => __('Pulse', SHORTCODE_ADDOONS),
                'rubberBand' => __('RubberBand', SHORTCODE_ADDOONS),
                'shake' => __('Shake', SHORTCODE_ADDOONS),
                'swing' => __('Swing', SHORTCODE_ADDOONS),
                'tada' => __('Tada', SHORTCODE_ADDOONS),
                'wobble' => __('Wobble', SHORTCODE_ADDOONS),
                'jello' => __('Jello', SHORTCODE_ADDOONS),
                'optgroup3' => [false],
                'optgroup4' => [true, 'Bouncing Entrances'],
                'bounceIn' => __('BounceIn', SHORTCODE_ADDOONS),
                'bounceInDown' => __('BounceInDown', SHORTCODE_ADDOONS),
                'bounceInLeft' => __('BounceInLeft', SHORTCODE_ADDOONS),
                'bounceInRight' => __('BounceInRight', SHORTCODE_ADDOONS),
                'bounceInUp' => __('BounceInUp', SHORTCODE_ADDOONS),
                'optgroup5' => [false],
                'optgroup6' => [true, 'Fading Entrances'],
                'fadeIn' => __('FadeIn', SHORTCODE_ADDOONS),
                'fadeInDown' => __('FadeInDown', SHORTCODE_ADDOONS),
                'fadeInDownBig' => __('FadeInDownBig', SHORTCODE_ADDOONS),
                'fadeInLeft' => __('FadeInLeft', SHORTCODE_ADDOONS),
                'fadeInLeftBig' => __('FadeInLeftBig', SHORTCODE_ADDOONS),
                'fadeInRight' => __('FadeInRight', SHORTCODE_ADDOONS),
                'fadeInRightBig' => __('FadeInRightBig', SHORTCODE_ADDOONS),
                'fadeInUp' => __('FadeInUp', SHORTCODE_ADDOONS),
                'fadeInUpBig' => __('FadeInUpBig', SHORTCODE_ADDOONS),
                'optgroup7' => [false],
                'optgroup8' => [true, 'Flippers'],
                'flip' => __('Flip', SHORTCODE_ADDOONS),
                'flipInX' => __('FlipInX', SHORTCODE_ADDOONS),
                'flipInY' => __('FlipInY', SHORTCODE_ADDOONS),
                'optgroup9' => [false],
                'optgroup10' => [true, 'Lightspeed'],
                'lightSpeedIn' => __('LightSpeedIn', SHORTCODE_ADDOONS),
                'optgroup11' => [false],
                'optgroup12' => [true, 'Rotating Entrances'],
                'rotateIn' => __('RotateIn', SHORTCODE_ADDOONS),
                'rotateInDownLeft' => __('RotateInDownLeft', SHORTCODE_ADDOONS),
                'rotateInDownRight' => __('RotateInDownRight', SHORTCODE_ADDOONS),
                'rotateInUpLeft' => __('RotateInUpLeft', SHORTCODE_ADDOONS),
                'rotateInUpRight' => __('RotateInUpRight', SHORTCODE_ADDOONS),
                'optgroup13' => [false],
                'optgroup14' => [true, 'Sliding Entrances'],
                'slideInUp' => __('SlideInUp', SHORTCODE_ADDOONS),
                'slideInDown' => __('SlideInDown', SHORTCODE_ADDOONS),
                'slideInLeft' => __('SlideInLeft', SHORTCODE_ADDOONS),
                'slideInRight' => __('SlideInRight', SHORTCODE_ADDOONS),
                'optgroup15' => [false],
                'optgroup16' => [true, 'Zoom Entrances'],
                'zoomIn' => __('ZoomIn', SHORTCODE_ADDOONS),
                'zoomInDown' => __('ZoomInDown', SHORTCODE_ADDOONS),
                'zoomInLeft' => __('ZoomInLeft', SHORTCODE_ADDOONS),
                'zoomInRight' => __('ZoomInRight', SHORTCODE_ADDOONS),
                'zoomInUp' => __('ZoomInUp', SHORTCODE_ADDOONS),
                'optgroup17' => [false],
                'optgroup18' => [true, 'Specials'],
                'hinge' => __('Hinge', SHORTCODE_ADDOONS),
                'rollIn' => __('RollIn', SHORTCODE_ADDOONS),
                'optgroup19' => [false],
            ],
                ]
        );
        $this->add_control(
                $id . '-duration', $data, [
            'label' => __('Duration (ms)', SHORTCODE_ADDOONS),
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
            'label' => __('Delay (ms)', SHORTCODE_ADDOONS),
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
            'label' => __('Offset', SHORTCODE_ADDOONS),
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
            'label' => __('Looping', SHORTCODE_ADDOONS),
            'type' => Controls::SWITCHER,
            'default' => '',
            'loader' => TRUE,
            'label_on' => __('Yes', SHORTCODE_ADDOONS),
            'label_off' => __('No', SHORTCODE_ADDOONS),
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
            'label' => __('Border', SHORTCODE_ADDOONS),
            $cond => $condition,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => (array_key_exists('description', $arg)? $arg['description']: ''),
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
            'label' => __('Type', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'default' => '',
            'options' => [
                '' => __('None', SHORTCODE_ADDOONS),
                'solid' => __('Solid', SHORTCODE_ADDOONS),
                'dotted' => __('Dotted', SHORTCODE_ADDOONS),
                'dashed' => __('Dashed', SHORTCODE_ADDOONS),
                'double' => __('Double', SHORTCODE_ADDOONS),
                'groove' => __('Groove', SHORTCODE_ADDOONS),
                'ridge' => __('Ridge', SHORTCODE_ADDOONS),
                'inset' => __('Inset', SHORTCODE_ADDOONS),
                'outset' => __('Outset', SHORTCODE_ADDOONS),
                'hidden' => __('Hidden', SHORTCODE_ADDOONS),
            ],
            $loader => $loadervalue,
            $selectorvalue => 'border-style: {{VALUE}};',
            $selector_key => $selector,
                ]
        );
        $this->add_responsive_control(
                $id . '-width', $data, [
            'label' => __('Width', SHORTCODE_ADDOONS),
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
            'label' => __('Color', SHORTCODE_ADDOONS),
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
            'label' => __('Background', SHORTCODE_ADDOONS),
            'condition' => array_key_exists('condition', $arg) ? $arg['condition'] : '',
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            'separator' => $separator,
            'description' => (array_key_exists('description', $arg)? $arg['description']: ''),
                ]
        );
        $this->add_control(
                $id . '-color', $data, [
            'label' => __('Color', SHORTCODE_ADDOONS),
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
            'label' => __('Image', SHORTCODE_ADDOONS),
            'type' => Controls::SWITCHER,
            'loader' => TRUE,
            'label_on' => __('Yes', SHORTCODE_ADDOONS),
            'label_off' => __('No', SHORTCODE_ADDOONS),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                $id . '-select', $data, [
            'label' => __('Photo Source', SHORTCODE_ADDOONS),
            'separator' => TRUE,
            'loader' => TRUE,
            'type' => Controls::CHOOSE,
            'default' => 'media-library',
            'options' => [
                'media-library' => [
                    'title' => __('Media Library', SHORTCODE_ADDOONS),
                    'icon' => 'fa fa-align-left',
                ],
                'custom-url' => [
                    'title' => __('Custom URL', SHORTCODE_ADDOONS),
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
            'label' => __('Image', SHORTCODE_ADDOONS),
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
            'label' => __('Image URL', SHORTCODE_ADDOONS),
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
            'label' => __('Position', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'default' => 'center center',
            'render' => $render,
            'options' => [
                '' => __('Default', SHORTCODE_ADDOONS),
                'top left' => __('Top Left', SHORTCODE_ADDOONS),
                'top center' => __('Top Center', SHORTCODE_ADDOONS),
                'top right' => __('Top Right', SHORTCODE_ADDOONS),
                'center left' => __('Center Left', SHORTCODE_ADDOONS),
                'center center' => __('Center Center', SHORTCODE_ADDOONS),
                'center right' => __('Center Right', SHORTCODE_ADDOONS),
                'bottom left' => __('Bottom Left', SHORTCODE_ADDOONS),
                'bottom center' => __('Bottom Center', SHORTCODE_ADDOONS),
                'bottom right' => __('Bottom Right', SHORTCODE_ADDOONS),
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
            'label' => __('Attachment', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'default' => '',
            'render' => $render,
            'options' => [
                '' => __('Default', SHORTCODE_ADDOONS),
                'scroll' => __('Scroll', SHORTCODE_ADDOONS),
                'fixed' => __('Fixed', SHORTCODE_ADDOONS),
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
            'label' => __('Repeat', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'default' => 'no-repeat',
            'render' => $render,
            'options' => [
                '' => __('Default', SHORTCODE_ADDOONS),
                'no-repeat' => __('No-Repeat', SHORTCODE_ADDOONS),
                'repeat' => __('Repeat', SHORTCODE_ADDOONS),
                'repeat-x' => __('Repeat-x', SHORTCODE_ADDOONS),
                'repeat-y' => __('Repeat-y', SHORTCODE_ADDOONS),
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
            'label' => __('Size', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'default' => 'cover',
            'render' => $render,
            'options' => [
                '' => __('Default', SHORTCODE_ADDOONS),
                'auto' => __('Auto', SHORTCODE_ADDOONS),
                'cover' => __('Cover', SHORTCODE_ADDOONS),
                'contain' => __('Contain', SHORTCODE_ADDOONS),
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
            'label' => __('Link', SHORTCODE_ADDOONS),
            'type' => Controls::TEXT,
            'default' => '',
            'link' => TRUE,
            'separator' => $separator,
            'placeholder' => 'www.example.com/',
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            $cond => $condition,
            'description' => (array_key_exists('description', $arg)? $arg['description']: ''),
                ]
        );
        echo '<div class="shortcode-form-control-content shortcode-form-control-content-popover-body">';

        $this->add_control(
                $id . '-target', $data, [
            'label' => __('New Window?', SHORTCODE_ADDOONS),
            'type' => Controls::SWITCHER,
            'default' => '',
            'loader' => TRUE,
            'label_on' => __('Yes', SHORTCODE_ADDOONS),
            'label_off' => __('No', SHORTCODE_ADDOONS),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                $id . '-follow', $data, [
            'label' => __('No Follow', SHORTCODE_ADDOONS),
            'type' => Controls::SWITCHER,
            'default' => 'yes',
            'loader' => TRUE,
            'label_on' => __('Yes', SHORTCODE_ADDOONS),
            'label_off' => __('No', SHORTCODE_ADDOONS),
            'return_value' => 'yes',
                ]
        );
        $this->add_control(
                $id . '-id', $data, [
            'label' => __('CSS ID', SHORTCODE_ADDOONS),
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
            'label' => __('Column Size', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'responsive' => 'laptop',
            'default' => 'oxi-bt-col-lg-12',
            'options' => [
                'oxi-bt-col-lg-12' => __('Col 1', SHORTCODE_ADDOONS),
                'oxi-bt-col-lg-6' => __('Col 2', SHORTCODE_ADDOONS),
                'oxi-bt-col-lg-4' => __('Col 3', SHORTCODE_ADDOONS),
                'oxi-bt-col-lg-3' => __('Col 4', SHORTCODE_ADDOONS),
                'oxi-bt-col-lg-2' => __('Col 6', SHORTCODE_ADDOONS),
                'oxi-bt-col-lg-1' => __('Col 12', SHORTCODE_ADDOONS),
            ],
            $select => $selector,
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            $cond => $condition,
            'description' => 'Define how much column you want to show into single rows. Customize possible with desktop or tab or mobile Settings.',
                ]
        );
        $this->add_control(
                $tab = $id . '-tab', $data, [
            'label' => __('Column Size', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'responsive' => 'tab',
            'default' => 'oxi-bt-col-md-12',
            'options' => [
                'oxi-bt-col-md-12' => __('Col 1', SHORTCODE_ADDOONS),
                'oxi-bt-col-md-6' => __('Col 2', SHORTCODE_ADDOONS),
                'oxi-bt-col-md-4' => __('Col 3', SHORTCODE_ADDOONS),
                'oxi-bt-col-md-3' => __('Col 4', SHORTCODE_ADDOONS),
                'oxi-bt-col-md-2' => __('Col 6', SHORTCODE_ADDOONS),
                'oxi-bt-col-md-1' => __('Col 12', SHORTCODE_ADDOONS),
            ],
            $select => $selector,
            'description' => 'Define how much column you want to show into single rows. Customize possible with desktop or tab or mobile Settings.',
            'form_condition' => (array_key_exists('form_condition', $arg) ? $arg['form_condition'] : ''),
            $cond => $condition
                ]
        );
        $this->add_control(
                $mob = $id . '-mob', $data, [
            'label' => __('Column Size', SHORTCODE_ADDOONS),
            'type' => Controls::SELECT,
            'default' => 'oxi-bt-col-lg-12',
            'responsive' => 'mobile',
            'options' => [
                'oxi-bt-col-sm-12' => __('Col 1', SHORTCODE_ADDOONS),
                'oxi-bt-col-sm-6' => __('Col 2', SHORTCODE_ADDOONS),
                'oxi-bt-col-sm-4' => __('Col 3', SHORTCODE_ADDOONS),
                'oxi-bt-col-sm-3' => __('Col 4', SHORTCODE_ADDOONS),
                'oxi-bt-col-sm-2' => __('Col 6', SHORTCODE_ADDOONS),
                'oxi-bt-col-sm-1' => __('Col 12', SHORTCODE_ADDOONS),
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
        echo $this->$fun($id, $data, $arg);
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
                        ' . $arg['title'] . '
                         <div class = "oxi-head-toggle"></div>
                         </div>
                    <div class = "oxi-addons-item-form-item shortcode-addons-templates-right-panel-body" id = "oxi-addons-list-data-modal-open">
                        <span>
                            <i class = "dashicons dashicons-plus-alt oxi-icons"></i>
                            ' . $arg['sub-title'] . '
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
                        ' . $arg['title'] . '
                        <div class = "oxi-head-toggle"></div>
                    </div>
                    <div class = "oxi-addons-shortcode-body  shortcode-addons-templates-right-panel-body">
                        <form method = "post" id = "shortcode-addons-name-change-submit">
                            <div class = "input-group my-2">
                                <input type = "hidden" class = "form-control" name = "addonsstylenameid" value = "' . $data['id'] . '">
                                <input type = "text" class = "form-control" name = "addonsstylename" placeholder = " ' . $arg['placeholder'] . '" value = "' . $data['name'] . '">
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
                        ' . $arg['title'] . '
                        <div class = "oxi-head-toggle"></div>
                    </div>
                    <div class = "oxi-addons-shortcode-body shortcode-addons-templates-right-panel-body">
                        <em>Shortcode for posts/pages/plugins</em>
                        <p>Copy &amp;
                        paste the shortcode directly into any WordPress post, page or Page Builder.</p>
                        <input type = "text" class = "form-control" onclick = "this.setSelectionRange(0, this.value.length)" value = "[oxi_addons id=&quot;' . $id . '&quot;]">
                        <span></span>
                        <em>Shortcode for templates/themes</em>
                        <p>Copy &amp;
                        paste this code into a template file to include the slideshow within your theme.</p>
                        <input type = "text" class = "form-control" onclick = "this.setSelectionRange(0, this.value.length)" value = "<?php echo do_shortcode(\'[oxi_addons  id=&quot;' . $id . '&quot;]\'); ?>">
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
                ' . $arg['title'] . '
                 <div class="oxi-head-toggle"></div>
            </div>
            <div class="oxi-addons-item-form-item shortcode-addons-templates-right-panel-body" id="oxi-addons-rearrange-data-modal-open">
                <span>
                    <i class="dashicons dashicons-plus-alt oxi-icons"></i>
                    ' . $arg['sub-title'] . '
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
                    ' . $id . '
                </div>
            </div>
         </div>';
    }

}
