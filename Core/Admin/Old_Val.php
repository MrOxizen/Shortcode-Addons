<?php

/**
 * Description of Old Data
 * @author biplob018
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The code that runs during view font familly.
 *
 * @since 2.0.0
 */
if (!function_exists('oxi_addons_font_familly')):

    function oxi_addons_font_familly($data) {

        $load = get_option('oxi_addons_google_font');
        if ($load == '' && $data != ''):
            wp_enqueue_style('' . $data . '', 'https://fonts.googleapis.com/css?family=' . $data . '');
        endif;
        $data = str_replace('+', ' ', $data);
        $data = explode(':', $data);
        $data = $data[0];
        $data = '"' . $data . '"';
        return $data;
    }

endif;

/**
 * The code that runs during explode text align data from text shadow.
 *
 * @since 2.0.0
 */
if (!function_exists('OxiAddonsTextAlignFixed')):

    function OxiAddonsTextAlignFixed($data) {
        $file = explode(":", $data);
        return $file[0];
    }

endif;

/**
 * The code that runs during export text data.
 * this also works with shortocde also
 *
 * @since 2.0.0
 */
if (!function_exists('oxi_addons_html_decode')):

    function oxi_addons_html_decode($data) {
        $data = html_entity_decode($data);
        $data = do_shortcode($data, $ignore_html = false);
        return $data;
    }

endif;

/**
 * The code that runs during export font familly.
 * this also works with font family link also
 *
 * @since 2.0.0
 */
if (!function_exists('oxi_addons_font_awesome')):

    function oxi_addons_font_awesome($data) {
        $icon = explode(' ', $data);
        $fadata = get_option('oxi_addons_font_awesome');
        $faversion = get_option('oxi_addons_font_awesome_version');
        $faversion = explode('||', $faversion);
        if ($fadata == 'yes'):
            wp_enqueue_style('font-awesome-' . $faversion[0], $faversion[1]);
        endif;
        $files = '<i class="' . $data . ' oxi-icons"></i>';
        return $files;
    }

endif;

/**
 * The code that runs during export text data.
 *
 * @since 2.0.0
 */
if (!function_exists('OxiAddonsTextConvert')):

    function OxiAddonsTextConvert($data) {
        $data = html_entity_decode($data);
        $data = do_shortcode($data, $ignore_html = false);
        return $data;
    }

endif;

/**
 * The code that runs during background color image viewing.
 * also works with linear gradient also
 * 
 * @since 2.0.0
 */
if (!function_exists('OxiAddonsBGImage')):

    function OxiAddonsBGImage($styledata, $number) {
        if ($styledata[($number + 1)] == ''):
            $value = 'background: ' . $styledata[$number] . ';';
        else:
            if (strlen($styledata[$number]) < 27):
                $value = 'background: linear-gradient(' . $styledata[$number] . ', ' . $styledata[$number] . '), url("' . OxiAddonsUrlConvert($styledata[($number + 1)]) . '") no-repeat center center;
                       -webkit-background-size: cover;
                       -moz-background-size: cover;
                       -o-background-size: cover;
                       background-size: cover;';
            else:
                $value = 'background: ' . $styledata[$number] . ', url("' . OxiAddonsUrlConvert($styledata[($number + 1)]) . '") no-repeat center center;
                       -webkit-background-size: cover;
                        -moz-background-size: cover;
                        -o-background-size: cover;
                        background-size: cover;';
            endif;
        endif;
        return $value;
    }

endif;

/**
 * The code that runs during box shadow viewing.
 * 
 * @since 2.0.0
 */
if (!function_exists('OxiAddonsBoxShadowSanitize')):

    function OxiAddonsBoxShadowSanitize($styledata, $number) {
        if (strpos($styledata[$number], ":") !== FALSE):
            $colorinset = explode(':', $styledata[$number]);
        else :
            $colorinset = array($styledata[$number], '');
        endif;
        $value = 'box-shadow: ' . $colorinset[1] . ' ' . $styledata[($number + 1)] . 'px ' . $styledata[($number + 2)] . 'px ' . $styledata[($number + 3)] . 'px ' . $styledata[($number + 4)] . 'px ' . $colorinset[0] . ';';
        return $value;
    }

endif;

/**
 * The code that runs during border, border radius, padding, margin.
 * 
 * @since 2.0.0
 */
if (!function_exists('OxiAddonsPaddingMarginSanitize')):

    function OxiAddonsPaddingMarginSanitize($styledata, $number) {
        $value = '' . $styledata[$number] . 'px ' . $styledata[($number + 12)] . 'px ' . $styledata[($number + 4)] . 'px ' . $styledata[($number + 8)] . 'px';
        return $value;
    }

endif;
/**
 * The code that runs during animation viewing.
 * works with full animation data
 * 
 * @since 2.0.0
 */
if (!function_exists('OxiAddonsAnimation')):

    function OxiAddonsAnimation($styledata, $firstvalue) {
        $data = '';
        $number = rand();
        $number2 = rand();
        $danimation = explode(':', $styledata[($firstvalue + 1)]);
        if (count($danimation) > 3):
            if ($danimation[1] == 'true'):
                $jquery = 'jQuery(".oxi-d-animation-' . $number . '-' . $number2 . '").Oxiplate({
                                                inverse: ' . $danimation[2] . ',
                                                perspective: ' . $danimation[3] . ',
                                                maxRotation: ' . $danimation[4] . ',
                                                animationDuration: ' . ($danimation[5] * 1000) . '
                                              });';
                wp_add_inline_script('oxi-addons-animation', $jquery);
                $data .= ' oxi-addons-d-animation="oxi-d-animation-' . $number . '-' . $number2 . '" ';
            endif;
        endif;
        if (!empty($styledata[($firstvalue)])):
            $css = '';
            $animation = explode('//', $styledata[($firstvalue + 2)]);
            $data .= 'oxi-addons-animation="oxi-animation-' . $number . '-' . $number2 . ' ' . $styledata[$firstvalue] . '"';
            $loop = $animation[1] == 'infinite' ? ' infinite ' : ' 1 ';
            $css .= ' .oxi-addons-animation.oxi-animation-' . $number . '-' . $number2 . '{ 
                             -webkit-animation: ' . $styledata[$firstvalue] . ' ' . $danimation[0] . 's ' . $loop . ' ' . $animation[0] . 's; 
                             -moz-animation:    ' . $styledata[$firstvalue] . ' ' . $danimation[0] . 's ' . $loop . ' ' . $animation[0] . 's;
                             -o-animation:      ' . $styledata[$firstvalue] . ' ' . $danimation[0] . 's ' . $loop . ' ' . $animation[0] . 's;
                             animation:         ' . $styledata[$firstvalue] . ' ' . $danimation[0] . 's ' . $loop . ' ' . $animation[0] . 's;
                            -webkit-transition: opacity  ' . $danimation[0] . 's ease-in-out ' . $animation[0] . 's;
                            -moz-transition: opacity  ' . $danimation[0] . 's ease-in-out ' . $animation[0] . 's;
                            transition: opacity  ' . $danimation[0] . 's ease-in-out ' . $animation[0] . 's'
                    . '         }';
            wp_add_inline_style('oxi-addons', $css);
        else:
            $data .= '';
        endif;
        return $data;
    }

endif;
/**
 * The code that runs during font value.
 * 
 * 
 * @since 2.1.0 
 */
if (!function_exists('OxiAddonsAdminDefine')):

    function OxiAddonsAdminDefine($firstvalue) {
        echo '';
    }

endif;

/**
 * The code that runs during font settings.
 * capable to output font family,font style, line height font style, text shadow, text align
 * 
 * @since 2.0.0 
 */
if (!function_exists('OxiAddonsFontSettings')):

    function OxiAddonsFontSettings($styledata, $firstvalue) {
        $data = 'font-family:' . oxi_addons_font_familly($styledata[$firstvalue]) . '; font-weight:' . $styledata[($firstvalue + 1)] . ';';
        $clonetrue = strpos($styledata[($firstvalue + 3)], ":");
        if ($clonetrue !== FALSE):
            $datacompile = explode(":", $styledata[($firstvalue + 3)]);
            $data .= ' font-style:' . $datacompile[0] . ';';
            $data .= ' line-height:' . $datacompile[1] . ';';
        else:
            $data .= ' font-style:' . $styledata[($firstvalue + 3)] . ';';
        endif;
        $clonetrue = strpos($styledata[($firstvalue + 4)], ":");
        if ($clonetrue !== FALSE):
            $datacompile = explode(":", $styledata[($firstvalue + 4)]);
            if (!empty($datacompile[0])):
                $data .= ' text-align:' . $datacompile[0] . ';';
            endif;
            $shadowcheck = strpos($datacompile[1], '0()0()0');
            if ($shadowcheck === FALSE && !empty($datacompile[1])):
                $texts = explode('()', $datacompile[1]);
                $data .= ' text-shadow:' . $texts[0] . 'px ' . $texts[1] . 'px ' . $texts[2] . 'px  ' . $texts[3] . ';';
            endif;
            if (!empty($datacompile[2])):
                if ($datacompile[2] != 0):
                    $data .= 'letter-spacing:' . $datacompile[2] . 'px;';
                endif;
            endif;
        else:
            $data .= ' text-align:' . $styledata[($firstvalue + 4)] . ';';
        endif;
        return $data;
    }

endif;
/**
 * The code that runs during Text Shadow.
 * capable to output Text Shadow
 * 
 * @since 2.0.0
 */
if (!function_exists('OxiAddonsTextShadowSettings')):

    function OxiAddonsTextShadowSettings($styledata, $firstvalue) {
        $shadow = $styledata[$firstvalue] . '|' . $styledata[($firstvalue + 1)] . '|' . $styledata[($firstvalue + 2)];
        $shadowcheck = strpos($shadow, '0|0|0');
        if ($shadowcheck === FALSE):
            $data = ' text-shadow:' . $styledata[$firstvalue] . 'px ' . $styledata[($firstvalue + 1)] . 'px ' . $styledata[($firstvalue + 2)] . 'px  ' . $styledata[($firstvalue + 3)] . ';';
            return $data;
        endif;
    }

endif;

/**
 * The code that runs during output responsive class.
 * 
 * @since 2.0.0
 */
if (!function_exists('OxiAddonsItemRows')):

    function OxiAddonsItemRows($styledata, $firstvalue) {
        $data = ' ' . $styledata[$firstvalue] . ' ' . $styledata[($firstvalue + 1)] . ' ' . $styledata[($firstvalue + 2)] . ' ';
        return $data;
    }

endif;
/**
 * The code that runs during url or link output.
 * 
 * @since 2.0.0
 */
if (!function_exists('OxiAddonsUrlConvert')):

    function OxiAddonsUrlConvert($data) {
        $homeurl = home_url();
        $url = 'OxiAddonsUrl./';
        $data = str_replace($url, $homeurl, $data);
        return $data;
    }

endif;

/**
 * create unique class from text
 * 
 * @since 2.0.0
 */
if (!function_exists('OxiStringToClassReplacce')):

    function OxiStringToClassReplacce($string, $number = '000') {
        $entities = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', "t");
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]", " ");
        return 'oxi-STCR-' . str_replace($replacements, $entities, urlencode($string)) . $number;
    }

endif;
/**
 * The code that runs during rating.
 * 
 * @since 2.0.0
 */
if (!function_exists('OxiAddonsPublicRate')):

    function OxiAddonsPublicRate($value = '', $style = 'style1') {
        $faversion = get_option('oxi_addons_font_awesome_version');
        $faversion = explode('||', $faversion);
        $ftawversion = $faversion[0];
        if ($style == 'style1'):
            if ($ftawversion == '4.7.0'):
                $ratefull = 'fa fa-star';
                $ratehalf = 'fa fa-star-half-o';
                $rateO = 'fa fa-star-o';
            else:
                $ratefull = 'fas fa-star';
                $ratehalf = 'fas fa-star-half-alt';
                $rateO = 'far fa-star';
            endif;
        endif;
        if ($value > 4.75):
            return oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull);
        elseif ($value <= 4.75 && $value > 4.25):
            return oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratehalf);
        elseif ($value <= 4.25 && $value > 3.75):
            return oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($rateO);
        elseif ($value <= 3.75 && $value > 3.25):
            return oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratehalf) . oxi_addons_font_awesome($rateO);
        elseif ($value <= 3.25 && $value > 2.75):
            return oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($rateO) . oxi_addons_font_awesome($rateO);
        elseif ($value <= 2.75 && $value > 2.25):
            return oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratehalf) . oxi_addons_font_awesome($rateO) . oxi_addons_font_awesome($rateO);
        elseif ($value <= 2.25 && $value > 1.75):
            return oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($rateO) . oxi_addons_font_awesome($rateO) . oxi_addons_font_awesome($rateO);
        elseif ($value <= 1.75 && $value > 1.25):
            return oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($ratehalf) . oxi_addons_font_awesome($rateO) . oxi_addons_font_awesome($rateO) . oxi_addons_font_awesome($rateO);
        elseif ($value <= 1.25):
            return oxi_addons_font_awesome($ratefull) . oxi_addons_font_awesome($rateO) . oxi_addons_font_awesome($rateO) . oxi_addons_font_awesome($rateO) . oxi_addons_font_awesome($rateO);
        endif;
    }











endif;
