<?php

namespace SHORTCODE_ADDONS\Layouts\Elements;

/**
 *
 * @author biplo
 */
trait Template
{
    public function ShortcodeName($data)
    {
        return $this->admin_name_validation($data);
    }

    public function pre_created_templates()
    {
        if (count($this->database_data()) == 0) :
            return;
        endif;

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
                       <a href="' . admin_url("admin.php?page=shortcode-addons&oxitype=$this->oxitype&styleid=$id") . '"  title="Edit"  class="btn btn-info" style="float:left; margin-right: 5px; margin-left: 5px;">Edit</a>
                        <button class="btn btn-danger shortcode-addons-addons-data-delete" style="float:left"  title="Delete"  type="button" value="' . $id . '">Delete</button>  
                       <a href="' . esc_url(esc_url_raw(rest_url()) . 'ShortCodeAddonsUltimate/v2/shortcode_export?styleid=' . $id . '&_wpnonce=' . wp_create_nonce('wp_rest'))  . '"  title="Export"  class="btn btn-info" style="float:left; margin-right: 5px; margin-left: 5px;">Export</a>    
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



    public function Shortcode($rawdata)
    {
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

    public function elements_import()
    {
?>
        <div class="oxi-addons-row">

            <?php
            apply_filters('shortcode-addons/support-and-comments', false);

            foreach ($this->templates() as $value) {
                $settings = json_decode($value, true);
                $layouts = str_replace('-', '_', ucfirst($settings['style']['style_name']));
                if ((array_key_exists($layouts, $this->pre_active_check())) == FALSE) :
                    echo $this->template_rendar($settings);
                endif;
            }

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
                                    Thank you for using Shortcode Addons. As limitation of viewing Layouts or Design we added some layouts. Kindly check more  <a target="_blank" href="https://www.oxilabdemos.com/shortcode-addons/elements/' . str_replace('_', '-', $this->oxitype) . '" >' . $this->admin_name_validation($this->oxitype) . '</a> design from https://www.oxilabdemos.com/shortcode-addons/. Copy <strong>export</strong> code and <strong>import</strong> it, get your preferable layouts.
                                </div>
                            </div>
                            <div class="oxi-addons-view-more-demo-button">
                                <a target="_blank" class="oxi-addons-more-layouts" href="https://www.oxilabdemos.com/shortcode-addons/elements/' . str_replace('_', '-', $this->oxitype) . '" >View layouts</a>
                            </div>
                        </div>
                    </div>');
            ?>
        </div>
    <?php
    }
    public function template_name_optimize($data)
    {
        $data = str_replace('-', ' ', $data);
        $data = str_replace('_', ' ', $data);
        return ucfirst($data);
    }


    public function template_rendar($data = array())
    {
        $layouts = str_replace('-', '_', ucfirst($data['style']['style_name']));
        return __('<div class="oxi-addons-col-1" id="' . $layouts . '">
                                <div class="oxi-addons-style-preview">
                                    <div class="oxi-addons-style-preview-top oxi-addons-center">
                                    ' . ($this->Shortcode($data)) . '
                                    </div>
                                    <div class="oxi-addons-style-preview-bottom">
                                        <div class="oxi-addons-style-preview-bottom-left">
                                        ' . $this->template_name_optimize($data['style']['style_name']) . '
                                        </div>
                                        ' . $this->ShortcodeControl($data) . '
                                    </div>
                                </div>
                             </div>', SHORTCODE_ADDOONS);
    }

    public function ShortcodeControl($data = [])
    {

        $layouts = str_replace('-', '_', ucfirst($data['style']['style_name']));
        $number = rand();
        if ($this->oxiimport) :
            if (apply_filters('shortcode-addons/admin_version', false) === FALSE) :
                if (in_array($layouts, $this->pre_active())) :
                    return _('<div class="oxi-addons-style-preview-bottom-right">
                                <form method="post" class="shortcode-addons-template-active" style=" display: inline-block; ">
                                    <input type="hidden" id="oxitype" name="oxitype" value="' . $this->oxitype . '">
                                    <input type="hidden" name="oxiactivestyle" value="' . $layouts . '">
                                    <button class="btn btn-success" title="Active"  type="submit" value="Active" name="addonsstyleactive">Import Style</button>  
                                </form> 
                            </div>');
                else :
                    return _(' <button type="button" class="btn btn-danger" >Pro Only</button>');
                endif;
            else :
                return _('<div class="oxi-addons-style-preview-bottom-right">
                            <form method="post" class="shortcode-addons-template-active" style=" display: inline-block; ">
                                <input type="hidden" id="oxitype" name="oxitype" value="' . $this->oxitype . '">
                                <input type="hidden" id="oxiactivestyle" name="oxiactivestyle" value="' . $layouts . '">
                                <button class="btn btn-success" title="Active"  type="submit" value="Active" name="addonsstyleactive">Import Style</button>  
                            </form> 
                        </div>');
            endif;
        else :
            return __('<div class="oxi-addons-style-preview-bottom-right">
                        <form method="post" style=" display: inline-block; " class="shortcode-addons-template-deactive">
                            <input type="hidden" id="oxitype" name="oxitype" value="' . $this->oxitype . '">
                            <input type="hidden" name="oxideletestyle" value="' . $layouts . '">
                            <button class="btn btn-warning oxi-addons-addons-style-btn-warning" title="Delete"  type="submit" value="Deactive" name="addonsstyledelete">Deactive</button>  
                        </form>
                           <textarea style="display:none" id="oxistyle' . $number . 'data"  value="">' . htmlentities(json_encode($data)) . '</textarea>
                           <button type="button" class="btn btn-success oxi-addons-addons-template-create" data-toggle="modal" addons-data="oxistyle' . $number . 'data">Create Style</button>
                          </div>');
        endif;
    }

    public function elements_render()
    {
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

                $layouts = str_replace('-', '_', ucfirst($settings['style']['style_name']));
                if (array_key_exists($layouts, $this->pre_active_check())) :
                    $i++;
                    echo $this->template_rendar($settings);
                else :
                    $this->import_template_available = true;
                endif;
            }
            if ($i < 1) :
                $this->pre_active_check(true);
            endif;
            if ($this->import_template_available) :
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
            endif;

            echo _('<div class="modal fade" id="oxi-addons-style-create-modal" >
                        <form method="post" id="oxi-addons-style-modal-form">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">                    
                                        <h4 class="modal-title">New ' . $this->admin_name_validation($this->oxitype) . '</h4>
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
                    </div>');
            ?>

        </div>

<?php
    }
}
