<?php

namespace SHORTCODE_ADDONS\Core;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Elements Frontend
 *
 * @author biplobadhikari
 */
abstract class Elements_Frontend {

    use \SHORTCODE_ADDONS\Support\JSS_CSS_LOADER;
    use \SHORTCODE_ADDONS\Support\Validation;
    use \SHORTCODE_ADDONS\Support\Sanitization;

    /**
     * Store Elements Active Templates.
     *
     * @since 2.0.0
     */
    public $active_templates;

    /**
     * Current Elements type.
     *
     * @since 2.0.0
     */
    public $oxitype;

    /**
     * check if oxi import is true or false
     *
     * @since 2.0.0
     */
    public $oxiimport;

    /**
     * All templates list of current element
     *
     * @since 2.0.0
     */
    public $templates;

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
     * Database child Table.
     *
     * @since 2.0.0
     */
    public $child_table;

    /**
     * Define $wpdb
     *
     * @since 2.0.0
     */
    private $wpdb;

    /**
     * Shortcode Addons Construct
     *
     * @since 2.1.0
     */
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->oxitype = (!empty($_GET['oxitype']) ? ucfirst(sanitize_text_field($_GET['oxitype'])) : '');
        $this->oxiimport = (!empty($_GET['oxiimport']) ? sanitize_text_field($_GET['oxiimport']) : '');
        $this->parent_table = $this->wpdb->prefix . 'oxi_div_style';
        $this->child_table = $this->wpdb->prefix . 'oxi_div_list';
        $this->import_table = $this->wpdb->prefix . 'oxi_div_import';
        $this->hooks();
        $this->rander();
    }

    /**
     * Shortcode Addons Pre Active
     *
     * @since 2.1.0
     */
    public function pre_active() {
        return '';
    }

    /**
     * Shortcode Addons Templates
     *
     * @since 2.1.0
     */
    public function templates() {
        return array();
    }

    /**
     * Shortcode Addons Hooks.
     *
     * @since 2.1.0
     */
    public function hooks() {
        $this->admin_elements_frontend_loader();
        $this->admin_ajax_load();
    }
    /**
     * Shortcode Addons Database Data
     *
     * @since 2.1.0
     */
    public function database_data() {
        return $this->wpdb->get_results("SELECT * FROM $this->parent_table WHERE type = '$this->oxitype' ORDER BY id DESC", ARRAY_A);
    }
    /**
     * Shortcode Addons Pre Active Check.
     *
     * @since 2.1.0
     */
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
    /**
     * Shortcode Addons Pre Created Templates.
     *
     * @since 2.1.0
     */
    public function pre_created_templates() {
        $return = _(' <div class="oxi-addons-row table-responsive abop" style="margin-bottom: 20px; opacity: 0; height: 0px">
                        <table class="table table-hover widefat oxi_addons_table_data" style="background-color: #fff; border: 1px solid #ccc">
                            <thead>
                                <tr>
                                    <th style="width: 10%">ID</th>
                                    <th style="width: 15%">Name</th>
                                    <th style="width: 15%">Templates</th>
                                    <th style="width: 30%">Shortcode</th>
                                    <th style="width: 30%">Edit Delete</th>
                                </tr>
                            </thead>
                            <tbody>');
        foreach ($this->database_data() as $value) {
            $id = $value['id'];
            $return .= _('<tr>');
            $return .= _('<td>' . $id . '</td>');
            $return .= _('<td>' . $this->admin_name_validation($value['name']) . '</td>');
            $return .= _('<td>' . $this->admin_name_validation($value['style_name']) . '</td>');
            $return .= _('<td><span>Shortcode &nbsp;&nbsp;<input type="text" onclick="this.setSelectionRange(0, this.value.length)" value="[oxi_addons id=&quot;' . $id . '&quot;]"></span> <br>'
                    . '<span>Php Code &nbsp;&nbsp; <input type="text" onclick="this.setSelectionRange(0, this.value.length)" value="&lt;?php echo do_shortcode(&#039;[oxi_addons  id=&quot;' . $id . '&quot;]&#039;); ?&gt;"></span></td>');
            $return .= _('<td> 
                        <button type="button" class="btn btn-success oxi-addons-style-clone"  style="float:left" oxiaddonsdataid="' . $id . '">Clone</button>
                        <a href="' . admin_url("admin.php?page=shortcode-addons&oxitype=$this->oxitype&styleid=$id") . '"  title="Edit"  class="btn btn-info" style="float:left; margin-right: 5px; margin-left: 5px;">Edit</a>
                       <form method="post" class="oxi-addons-style-delete">
                               <input type="hidden" name="oxideleteid" value="' . $id . '">
                               <button class="btn btn-danger" style="float:left"  title="Delete"  type="submit" value="delete" name="addonsdatadelete">Delete</button>  
                       </form>
                       <form method="post" class="oxi-addons-style-export">
                               <input type="hidden" name="oxiexportid" value="' . $id . '">
                               <button class="btn btn-info" style="float:left; margin-left: 5px;"  title="Export"  type="submit" value="export" name="export">Export</button>  
                       </form>
                </td>');
            $return .= _(' </tr>');
        }
        $return .= _('      </tbody>
                </table>
            </div>
            <br>
            <br>');
        return $return;
    }
    /**
     * Shortcode Addons Shortcode Name.
     *
     * @since 2.1.0
     */
    public function ShortcodeName($data) {
        return $this->admin_name_validation($data);
    }
    /**
     * Shortcode Addons Shortcode Raw Data
     *
     * @since 2.1.0
     */
    public function Shortcode($rawdata) {
        $rt = '';
        $oxitype = $rawdata['style']['type'];
        $StyleName = $rawdata['style']['style_name'];
        $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . ucfirst($oxitype) . '\Templates\\' . ucfirst(str_replace('-', '_', $StyleName)) . '';
        ob_start();
        $CLASS = new $cls;
        $CLASS->__construct($rawdata['style'], $rawdata['child'], '');
        $rt .= ob_get_clean();
        return $rt;
    }
    /**
     * Shortcode Addons Shortcode Control.
     *
     * @since 2.1.0
     */
    public function ShortcodeControl($data = []) {
        $number = rand();
        if ($this->oxiimport):
            if (apply_filters('shortcode-addons/pro_enabled', false) === FALSE):
                if (in_array($data['style']['style_name'], $this->pre_active())):
                    return _('<div class="oxi-addons-style-preview-bottom-right">
                                <form method="post" class="shortcode-addons-template-active" style=" display: inline-block; ">
                                    <input type="hidden" id="oxitype" name="oxitype" value="' . $this->oxitype . '">
                                    <input type="hidden" name="oxiactivestyle" value="' . $data['style']['style_name'] . '">
                                    <button class="btn btn-success" title="Active"  type="submit" value="Active" name="addonsstyleactive">Import Style</button>  
                                </form> 
                            </div>');
                else:
                    return _(' <button type="button" class="btn btn-danger" >Pro Only</button>');
                endif;
            else:
                return _('<div class="oxi-addons-style-preview-bottom-right">
                            <form method="post" class="shortcode-addons-template-active" style=" display: inline-block; ">
                                <input type="hidden" id="oxitype" name="oxitype" value="' . $this->oxitype . '">
                                <input type="hidden" id="oxiactivestyle" name="oxiactivestyle" value="' . $data['style']['style_name'] . '">
                                <button class="btn btn-success" title="Active"  type="submit" value="Active" name="addonsstyleactive">Import Style</button>  
                            </form> 
                        </div>');
            endif;
        else:
            return __('<div class="oxi-addons-style-preview-bottom-right">
                        <form method="post" style=" display: inline-block; " class="shortcode-addons-template-deactive">
                            <input type="hidden" id="oxitype" name="oxitype" value="' . $this->oxitype . '">
                            <input type="hidden" name="oxideletestyle" value="' . $data['style']['style_name'] . '">
                            <button class="btn btn-warning oxi-addons-addons-style-btn-warning" title="Delete"  type="submit" value="Deactive" name="addonsstyledelete">Deactive</button>  
                        </form>
                           <textarea style="display:none" id="oxistyle' . $number . 'data"  value="">' . htmlentities(json_encode($data)) . '</textarea>
                           <button type="button" class="btn btn-success oxi-addons-addons-template-create" data-toggle="modal" addons-data="oxistyle' . $number . 'data">Create Style</button>
                          </div>');
        endif;
    }

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function admin_ajax_load() {
        wp_enqueue_script('shortcode-addons-template', SA_ADDONS_URL . '/assets/backend/js/template_frontend.js', false, SA_ADDONS_PLUGIN_VERSION);
        wp_localize_script('shortcode-addons-template', 'shortcode_addons_editor', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('shortcode-addons-editor')));
    }
    /**
     * Shortcode Addons Element Import.
     *
     * @since 2.1.0
     */
    public function elements_import() {
        ?>
        <div class="oxi-addons-row">
            <?php
            echo _('<div class="oxi-addons-view-more-demo" style=" padding-top: 35px; padding-bottom: 35px; ">
                        <div class="oxi-addons-view-more-demo-data" >
                            <div class="oxi-addons-view-more-demo-icon">
                                <i class="fas fa-bullhorn oxi-icons"></i>
                            </div>
                            <div class="oxi-addons-view-more-demo-text">
                                <div class="oxi-addons-view-more-demo-heading">
                                    More Layouts
                                </div>
                                <div class="oxi-addons-view-more-demo-content">
                                    Thank you for using Shortcode Addons. As limitation of viewing Layouts or Design we added some layouts. Kindly check more  <a target="_blank" href="https://www.shortcode-addons.com/elements/' . str_replace('_', '-', $this->oxitype) . '" >' . $this->admin_name_validation($this->oxitype) . '</a> design from shortcode-addons.com. Copy <strong>export</strong> code and <strong>import</strong> it, get your preferable layouts.
                                </div>
                            </div>
                            <div class="oxi-addons-view-more-demo-button">
                                <a target="_blank" class="oxi-addons-more-layouts" href="https://www.shortcode-addons.com/elements/' . str_replace('_', '-', $this->oxitype) . '" >View layouts</a>
                            </div>
                        </div>
                    </div>');
            foreach ($this->templates() as $value) {
                $settings = json_decode($value, true);
                if ((array_key_exists($settings['style']['style_name'], $this->pre_active_check())) == FALSE):
                    echo $this->template_rendar($settings);
                endif;
            }
            ?>
        </div>
        <?php
    }
    /**
     * Shortcode Addons Template Rendar.
     *
     * @since 2.1.0
     */
    public function template_rendar($data = array()) {

        return __('<div class="oxi-addons-col-1" id="' . $data['style']['style_name'] . '">
                                <div class="oxi-addons-style-preview">
                                    <div class="oxi-addons-style-preview-top oxi-addons-center">
                                    ' . ($this->Shortcode($data)) . '
                                    </div>
                                    <div class="oxi-addons-style-preview-bottom">
                                        <div class="oxi-addons-style-preview-bottom-left">
                                        ' . $this->ShortcodeName($data['style']['name']) . '
                                        </div>
                                        ' . $this->ShortcodeControl($data) . '
                                    </div>
                                </div>
                             </div>', SHORTCODE_ADDOONS);
    }
    /**
     * Shortcode Addons Element home.
     *
     * @since 2.1.0
     */
    public function elements_home() {
        echo _('<div class="oxi-addons-row">
                    <div class="oxi-addons-wrapper">
                        <div class="oxi-addons-import-layouts">
                            <h1>Shortcode Addons ›
                                ' . $this->admin_name_validation($this->oxitype) . '
                            </h1>
                            <p> View our  ' . $this->admin_name_validation($this->oxitype) . ' from Demo and select Which one You Want</p>
                        </div>
                    </div>');
        echo $this->pre_created_templates();
        echo _(' </div>');
        ?>

        <div class="oxi-addons-row">
            <?php
            $i = 0;
            foreach ($this->templates() as $value) {
                $settings = json_decode($value, true);
                if (array_key_exists($settings['style']['style_name'], $this->pre_active_check())):
                    $i++;
                    echo $this->template_rendar($settings);
                endif;
            }
            if ($i < 1):
                $this->pre_active_check(true);
            endif;
            echo _('<div class="oxi-addons-col-1 oxi-import">
                        <div class="oxi-addons-style-preview">
                            <div class="oxilab-admin-style-preview-top">
                                <a href="' . admin_url("admin.php?page=shortcode-addons&oxitype=$this->oxitype&oxiimport=import") . '">
                                    <div class="oxilab-admin-add-new-item">
                                        <span>
                                            <i class="fas fa-plus-circle oxi-icons"></i>  
                                            Add More Templates
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>');

            echo _('<div class="modal fade" id="oxi-addons-style-create-modal" >
                        <form method="post" id="oxi-addons-style-modal-form">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">                    
                                        <h4 class="modal-title">' . $this->admin_name_validation($this->oxitype) . ' Settings</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class=" form-group row">
                                            <label for="addons-style-name" class="col-sm-6 col-form-label" oxi-addons-tooltip="Give your Shortcode Name Here">Name</label>
                                            <div class="col-sm-6 addons-dtm-laptop-lock">
                                                <input class="form-control" type="text" value="" id="addons-style-name"  name="addons-style-name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" id="addons-oxi-type" name="addons-oxi-type" value="' . $this->oxitype . '">
                                        <input type="hidden" id="oxi-addons-data" name="oxi-addons-data" value="">
                                        <input type="hidden" id="oxistyleid" name="oxistyleid" value="">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success" name="addonsdatasubmit" id="addonsdatasubmit" value="Save">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal fade" id="oxi-addons-style-export-modal" >
                        <form method="post" id="oxi-addons-style-export-form">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">                    
                                        <h4 class="modal-title">Export Data</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea id="OxiAddImportDatacontent" class="oxi-addons-export-data-code"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-info OxiAddImportDatacontent">Copy</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>');
            ?>

        </div>

        <?php
    }
    /**
     * Shortcode Addons Rander.
     *
     * @since 2.1.0
     */
    public function rander() {
        ?>
        <div class="wrap">  
            <div class="oxi-addons-wrapper">
                <?php
                apply_filters('shortcode-addons/admin_nav_menu', false);
                if ($this->oxiimport == 'import'):
                    $this->elements_import();
                else:
                    $this->elements_home();
                endif;
                ?>
            </div>
        </div>
        <?php
    }

}
