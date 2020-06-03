<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SHORTCODE_ADDONS\Support;

/**
 *
 * @author biplob018
 */
trait Validation {
    /*
     * Shortcode Addons name converter.
     * 
     * @since 2.1.0
     */
    public function name_converter($data) {
        $data = str_replace('_', ' ', $data);
        $data = str_replace('-', ' ', $data);
        $data = str_replace('+', ' ', $data);
        return ucwords($data);
    }
    /*
     * Shortcode Addons font family validation.
     * 
     * @since 2.1.0
     */
    public function font_familly_validation($data = []) {
        foreach ($data as $value) {
            wp_enqueue_style('' . $value . '', 'https://fonts.googleapis.com/css?family=' . $value . '');
        }
    }
    /*
     * Shortcode Addons admin name Validation.
     * 
     * @since 2.1.0
     */
    public function admin_name_validation($data) {
        $data = str_replace('_', ' ', $data);
        $data = str_replace('-', ' ', $data);
        $data = str_replace('+', ' ', $data);
        return ucwords($data);
    }
    /*
     * Shortcode Addons Array render.
     * 
     * @since 2.1.0
     */
    public function array_render($id, $style) {
        if (array_key_exists($id, $style)):
            return $style[$id];
        endif;
    }
    /*
     * Shortcode Addons Media Render.
     * image
     * @since 2.1.0
     */
    public function media_render($id, $style) {
        $url = '';
        if (array_key_exists($id . '-select', $style)):
            if ($style[$id . '-select'] == 'media-library'):
                return $style[$id . '-image'];
            else:
                return $style[$id . '-url'];
            endif;
        endif;
    }
    /*
     * Shortcode Addons Media Render.
     * image
     * @since 2.1.0
     */
    public function file_render($id, $style) {
        $url = '';
        if (array_key_exists($id . '-select', $style)):
            if ($style[$id . '-select'] == 'media-library'):
                return $style[$id . '-media'];
            else:
                return $style[$id . '-url'];
            endif;
        endif;
    }
    /*
     * Shortcode Addons text render.
     * 
     * @since 2.1.0
     */
    public function text_render($data) {
        return do_shortcode(str_replace('spTac', '&nbsp;', str_replace('spBac', '<br>', html_entity_decode($data))), $ignore_html = false);
    }
    /*
     * Shortcode Addons fontawesome Icon Render.
     * 
     * @since 2.1.0
     */
    public function font_awesome_render($data) {
        $fadata = get_option('oxi_addons_font_awesome');
        if ($fadata == 'yes'):
            wp_enqueue_style('font-awsome.min', SA_ADDONS_URL . '/assets/front/css/font-awsome.min.css', false, SA_ADDONS_PLUGIN_VERSION);
        endif;
        $files = '<i class="' . $data . ' oxi-icons"></i>';
        return $files;
    }
    /*
     * Shortcode Addons column Render.
     * 
     * @since 2.1.0
     */
    public function column_render($id, $style) {
        $file = $style[$id . '-lap'] . ' ';
        $file .= $style[$id . '-tab'] . ' ';
        $file .= $style[$id . '-mob'] . ' ';
        return $file;
    }
    /*
     * Shortcode Addons url render.
     * 
     * @since 2.1.0
     */
    public function url_render($id, $style) {
        $link = '';
        if (array_key_exists($id . '-url', $style) && $style[$id . '-url'] != ''):
            $link .= ' href="' . $style[$id . '-url'] . '"';
            if (array_key_exists($id . '-target', $style) && $style[$id . '-target'] != '0'):
                $link .= ' target="_blank"';
            endif;
            if (array_key_exists($id . '-follow', $style) && $style[$id . '-follow'] != '0'):
                $link .= ' rel="nofollow"';
            endif;
            if (array_key_exists($id . '-id', $style) && $style[$id . '-id']):
                $link .= ($style[$id . '-id'] != '' ? ' id="' . $style[$id . '-id'] . '"' : '');
            endif;
        endif;

        return $link;
    }
    /*
     * Shortcode Addons Animation render.
     * 
     * @since 2.1.0
     */
    public function animation_render($id, $style) {
        $return = (array_key_exists($id . '-type', $style) && $style[$id . '-type'] != '' ? ' sa-data-animation="' . $style[$id . '-type'] . ' ' . (array_key_exists($id . '-looping', $style) && $style[$id . '-looping'] != '0' ? 'infinite' : '') . '"' : '');
        if ($return != ''):
            $return .= (array_key_exists($id . '-offset-size', $style) ? ' sa-data-animation-offset="' . $style[$id . '-offset-size'] . '%"' : '');
            $return .= (array_key_exists($id . '-delay-size', $style) ? ' sa-data-animation-delay="' . $style[$id . '-delay-size'] . 'ms"' : '');
            $return .= (array_key_exists($id . '-duration-size', $style) ? ' sa-data-animation-duration="' . $style[$id . '-duration-size'] . 'ms"' : '');
            return $return;
        endif;
    }
    /*
     * Shortcode Addons Background render.
     * 
     * @since 2.1.0
     */
    public function background_render($id, $style, $class) {
        $backround = '';
        if (array_key_exists($id . '-color', $style)):
            $color = $style[$id . '-color'];
            if (array_key_exists($id . '-img', $style) && $style[$id . '-img'] != '0'):
                if (strpos(strtolower($color), 'gradient') === FALSE):
                    $color = 'linear-gradient(0deg, ' . $color . ' 0%, ' . $color . ' 100%)';
                endif;
                if ($style[$id . '-select'] == 'media-library'):
                    $backround .= $class . '{background: ' . $color . ', url(\'' . $style[$id . '-image'] . '\') ' . $style[$id . '-repeat'] . ' ' . $style[$id . '-position'] . ';
                                           background-attachment: ' . $style[$id . '-attachment'] . ';
                                           background-size:  ' . $style[$id . '-size-lap'] . ';}';
                    $backround .= '@media only screen and (min-width : 669px) and (max-width : 993px){';
                    $backround .= $class . '{background-size:  ' . $style[$id . '-size-tab'] . ';}';
                    $backround .= '}';
                    $backround .= '@media only screen and (max-width : 668px){';
                    $backround .= $class . '{background-size:  ' . $style[$id . '-size-mob'] . ';}';
                    $backround .= '}';
                else:
                    $backround .= $class . '{background: ' . $color . ', url(\'' . $style[$id . '-url'] . '\') ' . $style[$id . '-repeat'] . ' ' . $style[$id . '-position'] . '; 
                                           background-attachment: ' . $style[$id . '-attachment'] . ';
                                           background-size:  ' . $style[$id . '-size-lap'] . ';}';
                    $backround .= '@media only screen and (min-width : 669px) and (max-width : 993px){';
                    $backround .= $class . '{background-size:  ' . $style[$id . '-size-tab'] . ';}';
                    $backround .= '}';
                    $backround .= '@media only screen and (max-width : 668px){';
                    $backround .= $class . '{background-size:  ' . $style[$id . '-size-mob'] . ';}';
                    $backround .= '}';
                endif;
            else:
                $backround .= $class . '{background: ' . $color . ';}';
            endif;
        endif;
        return $backround;
    }
    /*
     * Shortcode Addons replace category stirng to calss.
     * 
     * @since 2.1.0
     */
    public function CatStringToClassReplacce($string, $number = '000') {
        $entities = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', "t");
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]", " ");
        return 'sa_STCR_' . str_replace($replacements, $entities, urlencode($string)) . $number;
    }

}
