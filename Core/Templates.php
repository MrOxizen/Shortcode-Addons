<?php

namespace SHORTCODE_ADDONS\Core;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Templates
 *
 * @author biplobadhikari
 */
class Templates {

    use \SHORTCODE_ADDONS\Support\Validation;

    /**
     * Current Elements id
     *
     * @since 2.0.0
     */
    public $oxiid;
    /**
     * Current Elements type
     *
     * @since 2.0.0
     */
    public $oxitype;

    /**
     * Current Elements Style Data
     *
     * @since 2.0.0
     */
    public $style = [];

    /**
     * Current Elements Database Data
     *
     * @since 2.0.0
     */
    public $dbdata = [];

    /**
     * Current Elements multiple list data
     * Child Data
     * @since 2.0.0
     */
    public $child = [];

    /**
     * Current Elements Global CSS Data
     *
     * @since 2.0.0
     */
    public $CSSDATA = [];

    /**
     * Current Elements Inline Css Data
     *
     * @since 2.0.0
     */
    public $inline_css;

    /**
     * Current Elements Global JS Handle
     *
     * @since 2.0.0
     */
    public $JSHANDLE = 'shortcode-addons-jquery';

    /**
     * Current Elements Global DATA WRAPPER
     *
     * @since 2.0.0
     */
    public $WRAPPER;

    /**
     * Current Elements Admin Control
     *
     * @since 2.0.0
     */
    public $admin;
     /**
     * Current Elements Admin Control
     *
     * @since 2.0.0
     */
    public $CoreAdminRecall;

    /**
     * load constructor
     *
     * @since 2.0.0
     */
    public function __construct(array $dbdata = [], array $child = [], $admin = 'user') {
        if (count($dbdata) > 0):
            $this->dbdata = $dbdata;
            $this->child = $child;
            $this->admin = ($admin == 'basic'? 'admin': $admin);
            $this->CoreAdminRecall = $admin;
            if (!empty($dbdata['rawdata'])):
                $this->loader();
            else:
                $this->old_loader();
            endif;
        endif;
    }

    /**
     * Current element loader
     *
     * @since 2.0.0
     */
    public function loader() {
        $this->style = json_decode(stripslashes($this->dbdata['rawdata']), true);
        if (array_key_exists('id', $this->dbdata)):
            $this->oxiid = $this->dbdata['id'];
        else:
            $this->oxiid = rand(100000, 200000);
        endif;
        $this->CSSDATA = $this->dbdata['stylesheet'];
        $this->WRAPPER = 'shortcode-addons-wrapper-' . $this->dbdata['id'];
        $this->oxitype = ucfirst(strtolower($this->dbdata['type']));
        $this->hooks();
    }

    /**
     * load old data since 1.7
     *
     * @since 2.0.0
     */
    public function old_loader() {
        include SA_ADDONS_PATH . 'Core/Admin/Old_Val.php';
        $this->public_frontend_loader();
        $this->old_render();
    }

    /**
     * load css and js hooks
     *
     * @since 2.0.0
     */
    public function hooks() {
        $this->public_jquery();
        $this->public_css();
        $this->public_frontend_loader();
        $this->render();
        $inlinecss = $this->inline_public_css() . $this->inline_css;
        $inlinejs = $this->inline_public_jquery();
        wp_enqueue_style('shortcode-addons-' . $this->oxitype . ucfirst(str_replace('-', '_', $this->dbdata['style_name'])), SA_ADDONS_UPLOAD_URL . $this->oxitype . '/Css/' . ucfirst(str_replace('-', '_', $this->dbdata['style_name'])) . '.css', false, SA_ADDONS_PLUGIN_VERSION);
        if ($this->CSSDATA == '' && $this->admin == 'admin') {
            $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $this->oxitype . '\Admin\\' . ucfirst(str_replace('-', '_', $this->dbdata['style_name'])) . '';
            $CLASS = new $cls('admin');
            $inlinecss .= $CLASS->inline_template_css_render($this->style);
        } else {
            $inlinecss .= $this->CSSDATA;
        }
        echo $this->font_familly_validation(json_decode(($this->dbdata['font_family'] != '' ? $this->dbdata['font_family'] : "[]"), true));

        if ($inlinejs != ''):
            if ($this->CoreAdminRecall != 'basic'):
                //only load while ajax called
                echo _('<script>
                        (function ($) {
                            setTimeout(function () {');
                echo $inlinejs;
                echo _('    }, 1000);
                        })(jQuery)</script>');
            else:
                $jquery = '(function ($) {' . $inlinejs . '})(jQuery);';
                wp_add_inline_script($this->JSHANDLE, $jquery);
            endif;

        endif;
        if ($inlinecss != ''):
            $inlinecss = html_entity_decode($inlinecss);
            if ($this->admin == 'admin'):
                //only load while ajax called
                echo _('<style>');
                echo $inlinecss;
                echo _('</style>');
            else:
                wp_add_inline_style('shortcode-addons-style', $inlinecss);
            endif;
        endif;
    }

    /**
     * front end loader css and js
     *
     * @since 2.0.0
     */
    public function public_frontend_loader() {
        wp_enqueue_script("jquery");
        wp_enqueue_style('animation', SA_ADDONS_URL . '/assets/front/css/animation.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('shortcode-addons-style', SA_ADDONS_URL . '/assets/front/css/style.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('waypoints.min', SA_ADDONS_URL . '/assets/front/js/waypoints.min.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('shortcode-addons-jquery', SA_ADDONS_URL . '/assets/front/js/jquery.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_localize_script('shortcode-addons-jquery', 'shortcode_addons_data', array(
            'ajaxurl' => admin_url('admin-ajax.php'), 
            'nonce' => wp_create_nonce('shortcode-addons-data'),
            'saupload'=> SA_ADDONS_UPLOAD_URL,
            ));
    }

    /**
     * old empty old render
     *
     * @since 2.0.0
     */
    public function old_render() {
        echo '';
    }

    /**
     * load current element render since 2.0.0
     *
     * @since 2.0.0
     */
    public function render() {
        echo '<div class="oxi-addons-container ' . $this->WRAPPER . '">
                 <div class="oxi-addons-row">';
        $this->default_render($this->style, $this->child, $this->admin);
        echo '   </div>
              </div>';
    }

    /**
     * load public jquery
     *
     * @since 2.0.0
     */
    public function public_jquery() {
        echo '';
    }

    /**
     * load public css
     *
     * @since 2.0.0
     */
    public function public_css() {
        echo '';
    }

    /**
     * load inline public jquery
     *
     * @since 2.0.0
     */
    public function inline_public_jquery() {
        echo '';
    }

    /**
     * load inline public css
     *
     * @since 2.0.0
     */
    public function inline_public_css() {
        echo '';
    }

    /**
     * load old public frontend loader
     *
     * @since 2.0.0
     */
    public function public_frontend_old_loader() {
        echo '';
    }

    /**
     * load default render
     *
     * @since 2.0.0
     */
    public function default_render($style, $child, $admin) {
        echo '';
    }

    /**
     * load default render
     *
     * @since 2.0.0
     */
    public function Json_Decode($rawdata) {
        return $rawdata != '' ? json_decode(stripcslashes($rawdata), true) : [];
    }

}
