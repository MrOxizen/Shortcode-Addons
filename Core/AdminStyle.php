<?php

namespace SHORTCODE_ADDONS\Core;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Admin Style
 *
 * @author biplobadhikari
 */
use SHORTCODE_ADDONS\Core\Admin\Controls as Controls;

class AdminStyle {

    use \SHORTCODE_ADDONS\Helper\Admin_Scripts;
    use \SHORTCODE_ADDONS\Layouts\Template\AdminHelper;
    use \SHORTCODE_ADDONS\Layouts\Template\Sanitization;

    /**
     * Current Elements ID
     *
     * @since 2.0.0
     */
    public $oxiid;

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
    public $dbdata;

    /**
     * Current Elements Child Data
     *
     * @since 2.0.0
     */
    public $child;

    /**
     * Current Elements Global CSS Data
     *
     * @since 2.0.0
     */
    public $CSSDATA = [];

    /**
     * Current Elements Global DATA WRAPPER
     *
     * @since 2.0.0
     */
    public $WRAPPER;

    /**
     * Database Parent Table
     *
     * @since 2.0.0
     */
    public $parent_table;

    /**
     * Database Import Table
     *
     * @since 2.0.0
     */
    public $import_table;

    /**
     * Database Child Table
     *
     * @since 2.0.0
     */
    public $child_table;

    /**
     * Define $wpdb
     *
     * @since 2.0.0
     */
    public $wpdb;

    /**
     * Define Shortcode Addons Elements Type
     *
     * @since 2.0.0
     */
    public $oxitype;

    /**
     * Define Shortcode Addons Elements form
     *
     * @since 2.0.0
     */
    public $form;

    /**
     * Define Shortcode Addons Elements Font Family
     *
     * @since 2.0.0
     */
    public $font = [];

    /**
     * Define Shortcode Addons Imported Font Family
     *
     * @since 2.0.0
     */
    public $font_family = [];

    /**
     * Define Shortcode Addons Google Font Family
     *
     * @since 2.0.0
     */
    public $google_font = [];

    /**
     * Define Shortcode Addons Repeater Control
     *
     * @since 2.0.0
     */
    public $repeater;

    /**
     * Define Shortcode Addons Elements Type
     *
     * @since 2.0.0
     */
    public $StyleName;
    public $template_css_render;

    /**
     * Shortcode Addons Construct
     *
     * @since 2.1.0
     */
    public function __construct($type = '') {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->parent_table = $this->wpdb->prefix . 'oxi_div_style';
        $this->child_table = $this->wpdb->prefix . 'oxi_div_list';
        $this->import_table = $this->wpdb->prefix . 'oxi_div_import';
        $this->oxiid = (!empty($_GET['styleid']) ? (int)$_GET['styleid'] : '');
        $this->WRAPPER = '.shortcode-addons-wrapper-' . $this->oxiid;
        if ($type != 'admin') {
            $this->hooks();
            $this->render();
        }
    }

    /**
     * Template hooks
     *
     * @since 2.0.0
     */
    public function hooks() {
        $this->template_scripts();
        $this->dbdata = $this->wpdb->get_row($this->wpdb->prepare('SELECT * FROM ' . $this->parent_table . ' WHERE id = %d ', $this->oxiid), ARRAY_A);
        $this->child = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $this->child_table WHERE styleid = %d ORDER by id ASC", $this->oxiid), ARRAY_A);
        if (!empty($this->dbdata['rawdata'])):
            $this->dbdata['rawdata'] = str_replace(['\\(spBac)', '\\spBac', '\\(spTac)', '\\spTac'], ['<br> ', '<br> ', '&nbsp;', '&nbsp;'], $this->dbdata['rawdata']);
            $this->style = json_decode(stripslashes($this->dbdata['rawdata']), true);
        endif;
        $this->StyleName = ucfirst(str_replace('-', '_', $this->dbdata['style_name']));
        $this->oxitype = ucfirst(strtolower($this->dbdata['type']));
        $this->import_font_family();
    }

    /**
     * Template Modal opener
     * Define Multiple Data With Single Data
     *
     * @since 2.0.0
     */
    public function modal_opener() {
        $this->add_substitute_control('', [], [
            'type' => Controls::MODALOPENER,
            'title' => __('Add New Data', 'shortcode-addons'),
            'sub-title' => __('Open Data Form', 'shortcode-addons'),
            'showing' => TRUE,
        ]);
    }

    /**
     * Template Shortcode Name
     * Define Shortcode Name
     *
     * @since 2.0.0
     */
    public function shortcode_name() {
        $this->add_substitute_control('', $this->dbdata, [
            'type' => Controls::SHORTCODENAME,
            'title' => __('Shortcode Name', 'shortcode-addons'),
            'placeholder' => __('Set Your Shortcode Name', 'shortcode-addons'),
            'showing' => TRUE,
        ]);
    }

    /**
     * Template Shortcode Information
     * Parent Sector where users will get Shortcode Information
     *
     * @since 2.0.0
     */
    public function shortcode_info() {
        $this->add_substitute_control($this->oxiid, $this->dbdata, [
            'type' => Controls::SHORTCODEINFO,
            'title' => __('Shortcode', 'shortcode-addons'),
            'showing' => TRUE,
        ]);
    }

    /**
     * Template Modal Form Data
     * return always false and abstract with current Style Template
     *
     * @since 2.0.0
     */
    public function modal_form_data() {
        $this->form = 'single';
    }

    /**
     * Template Parent Modal Form
     *
     * @since 2.0.0
     */
    public function modal_form() {

        echo '<div class="modal fade" id="oxi-addons-list-data-modal" >
                <div class="modal-dialog">
                    <form method="post" id="shortcode-addons-template-modal-form">
                         <div class="modal-content">';
        $this->modal_form_data();
        echo '          <div class="modal-footer">
                                <input type="hidden" id="shortcodeitemid" name="shortcodeitemid" value="">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-success" id="shortcode-template-modal-submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
              </div>';
    }

    /**
     * Template Parent Item Data Rearrange
     *
     * @since 2.0.0
     */
    public function shortcode_rearrange() {
        echo '';
    }

    /**
     * Template CSS Render
     *
     * @since 2.0.0
     */
    public function template_css_render($style) {
        $styleid = $style['shortcode-addons-elements-id'];
        $this->oxiid = $styleid;
        $this->WRAPPER = '.shortcode-addons-wrapper-' . $this->oxiid;
        $this->style = $style;
        ob_start();
        $this->template_css_render = 'css_render';
        $dt = $this->import_font_family();
        $dt .= $this->register_controls();
        ob_end_clean();

        $fullcssfile = '';
        foreach ($this->CSSDATA as $key => $responsive) {
            $tempcss = '';
            foreach ($responsive as $class => $classes) {
                $tempcss .= $class . '{';
                foreach ($classes as $properties) {
                    $tempcss .= $properties;
                }
                $tempcss .= '}';
            }
            if ($key == 'laptop'):
                $fullcssfile .= $tempcss;
            elseif ($key == 'tab'):
                $fullcssfile .= '@media only screen and (min-width : 669px) and (max-width : 993px){';
                $fullcssfile .= $tempcss;
                $fullcssfile .= '}';
            elseif ($key == 'mobile'):
                $fullcssfile .= '@media only screen and (max-width : 668px){';
                $fullcssfile .= $tempcss;
                $fullcssfile .= '}';
            endif;
        }
        $font = json_encode($this->font);
        $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->parent_table} SET stylesheet = %s WHERE id = %d", $fullcssfile, $styleid));
        $this->wpdb->query($this->wpdb->prepare("UPDATE {$this->parent_table} SET font_family = %s WHERE id = %d", $font, $styleid));
        return 'success';
    }

    /**
     * Inline Template CSS Render
     *
     * @since 2.0.0
     */
    public function inline_template_css_render($style) {
        $styleid = $style['shortcode-addons-elements-id'];
        $this->style = $style;
        $this->oxiid = $styleid;
        $this->WRAPPER = '.shortcode-addons-wrapper-' . $this->oxiid;
        ob_start();
        $this->template_css_render = 'css_render';
        $dt = $this->register_controls();
        ob_end_clean();
        $fullcssfile = '';
        foreach ($this->CSSDATA as $key => $responsive) {
            $tempcss = '';
            foreach ($responsive as $class => $classes) {
                $tempcss .= $class . '{';
                foreach ($classes as $properties) {
                    $tempcss .= $properties;
                }
                $tempcss .= '}';
            }
            if ($key == 'laptop'):
                $fullcssfile .= $tempcss;
            elseif ($key == 'tab'):
                $fullcssfile .= '@media only screen and (min-width : 669px) and (max-width : 993px){';
                $fullcssfile .= $tempcss;
                $fullcssfile .= '}';
            elseif ($key == 'mobile'):
                $fullcssfile .= '@media only screen and (max-width : 668px){';
                $fullcssfile .= $tempcss;
                $fullcssfile .= '}';
            endif;
        }
        return $fullcssfile;
    }

    /**
     * Template Parent Render
     *
     * @since 2.0.0
     */
    public function render() {
        ?>
        <div class="wrap">
            <div class="oxi-addons-wrapper">
                <div class="oxi-addons-parent-loader-wrap" style="display: none">
                    <div class="oxi-addons-parent-loader">
                        <div id="loading">
                            <div id="loading-center">
                                <div id="loading-center-absolute">
                                    <div class="object" id="object_one"></div>
                                    <div class="object" id="object_two"></div>
                                    <div class="object" id="object_three"></div>
                                    <div class="object" id="object_four"></div>
                                    <div class="object" id="object_five"></div>
                                    <div class="object" id="object_six"></div>
                                    <div class="object" id="object_seven"></div>
                                    <div class="object" id="object_eight"></div>
                                    <div class="object" id="object_big"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                apply_filters('shortcode-addons/admin_menu', false);
                $this->modal_form();
                ?>
                <div class="oxi-addons-style-20-spacer"></div>
                <div class="oxi-addons-row">
                    <?php
                    apply_filters('shortcode-addons/support-and-comments', false);
                    ?>
                    <div class="oxi-addons-wrapper shortcode-addons-tabs-mode">
                        <div class="oxi-addons-settings" id="oxisettingsreload">
                            <div class="oxi-addons-style-left">
                                <form method="post" id="oxi-addons-form-submit">
                                    <div class="oxi-addons-style-settings">
                                        <div class="oxi-addons-tabs-wrapper">
                                            <?php
                                            $this->register_controls();
                                            ?>
                                        </div>
                                        <div class="oxi-addons-setting-save">
                                            <?php
                                            if (array_key_exists('css', $this->dbdata) && $this->dbdata['css'] != '' && $this->dbdata['rawdata'] != ''):
                                                echo '<button type="button" class="btn btn-danger" id="oxi-addons-setting-old-version">Old Version</button>';
                                            endif;
                                            ?>
                                            <button type="button" class="btn btn-danger" id="oxi-addons-setting-reload">Reload</button>
                                            <input type="hidden"  id="shortcode-addons-2-0-preview" name="shortcode-addons-2-0-preview" value="<?php echo(is_array($this->style) ? array_key_exists('shortcode-addons-2-0-preview', $this->style) ? esc_attr($this->style['shortcode-addons-2-0-preview']) : '#FFF' : '#FFF'); ?>">
                                            <input type="hidden"  id="shortcode-addons-elements-name" name="shortcode-addons-elements-name" value="<?php echo esc_attr(ucfirst(strtolower($this->dbdata['type']))); ?>">
                                            <input type="hidden"  id="shortcode-addons-elements-id" name="shortcode-addons-elements-id" value="<?php echo esc_attr(ucfirst($this->dbdata['id'])); ?>">
                                            <input type="hidden"  id="shortcode-addons-elements-template" name="shortcode-addons-elements-template" value="<?php echo esc_attr(ucfirst(str_replace('-', '_', $this->dbdata['style_name']))); ?>">
                                            <button type="button" class="btn btn-success" id="shortcode-addons-templates-submit"> Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="oxi-addons-style-right">
                                <?php
                                if ($this->form == 'single'):
                                    $this->shortcode_name();
                                    $this->shortcode_info();
                                else:
                                    $this->modal_opener();
                                    $this->shortcode_name();
                                    $this->shortcode_info();
                                    $this->shortcode_rearrange();
                                endif;
                                ?>
                            </div>
                        </div>
                        <div class="oxi-addons-Preview" id="oxipreviewreload">
                            <div class="oxi-addons-wrapper">
                                <div class="oxi-addons-style-left-preview">
                                    <div class="oxi-addons-style-left-preview-heading">
                                        <div class="oxi-addons-style-left-preview-heading-left shortcode-addons-tabs-sortable-title">
                                            Preview
                                        </div>
                                        <div class="shortcode-form-control shortcode-control-type-choose    ">
                                            <div class="shortcode-form-control-content">
                                                <div class="shortcode-form-control-field">
                                                    <div class="shortcode-form-control-input-wrapper">
                                                        <div class="shortcode-form-choices" retundata="">
                                                            <input id="shortcode-addons-preview-align-top" type="radio" name="shortcode-addons-preview-align" value="oxi-shortcode-admin-layouts-design-float">
                                                            <label class="shortcode-form-choices-label" for="shortcode-addons-preview-align-top" tooltip="Top">
                                                                <i class="fas fa-arrow-up" aria-hidden="true"></i>
                                                            </label>
                                                            <input id="shortcode-addons-preview-align-center" type="radio" name="shortcode-addons-preview-align" value="oxi-shortcode-admin-layouts-design-grid" >
                                                            <label class="shortcode-form-choices-label" for="shortcode-addons-preview-align-center" tooltip="Center">
                                                                <i class="fas fa-align-center" aria-hidden="true"></i>
                                                            </label>
                                                            <input id="shortcode-addons-preview-align-bottom" type="radio" name="shortcode-addons-preview-align" value="oxi-shortcode-admin-layouts-design-flex">
                                                            <label class="shortcode-form-choices-label" for="shortcode-addons-preview-align-bottom" tooltip="Bottom">
                                                                <i class="fas fa-arrow-down" aria-hidden="true"></i>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="oxi-addons-style-left-preview-heading-right">
                                            <input type="text" data-format="rgb" data-opacity="TRUE" class="oxi-addons-minicolor" id="shortcode-addons-2-0-color" name="shortcode-addons-2-0-color" value="<?php echo(is_array($this->style) ? array_key_exists('shortcode-addons-2-0-preview', $this->style) ? esc_attr($this->style['shortcode-addons-2-0-preview']) : '#FFF' : '#FFF'); ?>">
                                        </div>
                                    </div>
                                    <div class="oxi-addons-preview-data" id="oxi-addons-preview-data" template-wrapper="<?php echo esc_attr($this->WRAPPER); ?>">
                                        <?php
                                        $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $this->oxitype . '\Templates\\' . $this->StyleName . '';
                                        new $cls($this->dbdata, $this->child, 'basic');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="shortcode-addons-form-repeater-store" style="display: none">
                        <?php
                         echo $this->repeater;
                        ?>
                    </div>
                    <div id="OXIAADDONSCHANGEDPOPUP" class="modal fade">
                        <div class="modal-dialog modal-confirm  bounceIn ">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div class="icon-box">

                                    </div>
                                </div>
                                <div class="modal-body text-center">
                                    <h4></h4>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
