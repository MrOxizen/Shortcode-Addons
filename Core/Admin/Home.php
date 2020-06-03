<?php

namespace SHORTCODE_ADDONS\Core\Admin;

/**
 * Description of Home
 * @author biplob018
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Home {

    use \SHORTCODE_ADDONS\Support\Validation;
    use \SHORTCODE_ADDONS\Support\JSS_CSS_LOADER;

    //Installed Elements List
    public $installed;
    //Installed Elements List
    public $available;

    /**
     * Shortcode Addons Constructor.
     *
     * @since 2.0.0
     */
    public function __construct() {

        $this->admin_css_loader();
        $this->jss_file();
        do_action('shortcode-addons/before_init');
        $this->hooks();
    }

    /**
     * Shortcode Addons Hooks.
     *
     * @since 2.0.0
     */
    public function hooks() {
        $this->installed_elements();
        $this->available_elements();
        $this->render();
    }

    /**
     * Shortcode Addons Elements.
     *
     * @since 2.0.0
     */
    public function installed_elements() {
        /**
         * Adding more elements with custom slug
         */
        $DIRfiles = glob(SA_ADDONS_UPLOAD_PATH . '*', GLOB_ONLYDIR);

        $importdata = $catarray = $catnewdata = Array();
        foreach ($DIRfiles as $value) {
            $file = explode('shortcode-addons/', $value);
            if (!empty($value)) {
                if (!empty($value) && count($file) == 2) {
                    $vs = array('1.5', 'Custom Elements', false);
                    if (file_exists(SA_ADDONS_UPLOAD_PATH . $file[1] . '/Version.php')) {
                        $version = include_once SA_ADDONS_UPLOAD_PATH . $file[1] . '/Version.php';
                        if (is_array($version)) {
                            if ($version[2] == true) {
                                $vs = $version;
                            }
                        }
                    }
                    $catarray[$vs[1]] = $vs[1];
                    $importdata[$vs[1]][$file[1]] = array(
                        'type' => 'shortcode-addons',
                        'name' => ucfirst($file[1]),
                        'homepage' => strtolower($file[1]),
                        'slug' => 'shortcode-addons',
                        'version' => $vs[0],
                        'control' => $vs[2]
                    );
                }
            }
        }

        $this->installed = $importdata;
    }

    /**
     * Shortcode Addons Available.
     *
     * @since 2.0.0
     */
    public function available_elements() {
        $newfolder = $this->installed;
        $available = \SHORTCODE_ADDONS\Core\Admin\Shortcode_Remote::get_instance()->categories_list();
        foreach ($available as $key => $value) {
            if (is_array($value)):
                foreach ($value as $valueskey => $values) {
                    $newfolder[$key][ucfirst($valueskey)] = array(
                        'type' => 'shortcode-addons',
                        'name' => ucfirst($values['name']),
                        'homepage' => strtolower($values['name']),
                        'icon' => $values['icon'],
                        'version' => $values['version'],
                        'control' => true,
                        'premium' => $values['premium']
                    );
                }
            endif;
        }
        ksort($newfolder);
        $array = array(
            'Content Elements' => 'Content Elements',
            'Creative Elements' => 'Creative Elements',
            'Dynamic Contents' => 'Dynamic Contents',
            'Marketing Elements' => 'Marketing Elements',
            'Image Effects' => 'Image Effects',
            'Post Elements' => 'Post Elements',
            'Image or Video Gallery' => 'Image or Video Gallery',
            'Carousel & Slider' => 'Carousel & Slider',
            'Social Elements' => 'Social Elements',
            'Customer Supports' => 'Customer Supports',
            'Header Elements' => 'Header Elements',
            'User Elements' => 'User Elements',
            'Form Contents' => 'Form Contents',
            'Subscribe form' => 'Subscribe form',
            'Extensions' => 'Extensions');

        $margecat = array_merge($array, $newfolder);
        $finalelements = array();
        foreach ($margecat as $key => $value) {
            $finalelements[$key] = (array_key_exists($key, $newfolder) ? $newfolder[$key] : array());
        }

        $this->available = $finalelements;
    }

    /**
     * Shortcode Addons Render.
     *
     * @since 2.0.0
     */
    public function render() {
        ?>
        <div class="wrap">  
            <?php
            apply_filters('shortcode-addons/admin_nav_menu', false);
            ?>
            <div class="oxi-addons-wrapper">
                <div class="oxi-addons-row">
                    <input class="form-control" type="text" id='oxi_addons_search' placeholder="Search..">
                    <?php
                    foreach ($this->available as $key => $elements) {
                        $elementshtml = '';
                        $elementsnumber = 0;
                        asort($elements);
                        foreach ($elements as $value) {


                            $oxilink = 'admin.php?page=shortcode-addons&oxitype=' . $value['homepage'];
                            $elementsnumber++;
                            $elementshtml .= ' <div class="oxi-addons-shortcode-import" id="' . $value['name'] . '" oxi-addons-search="' . $value['homepage'] . '">
                                                <a class="addons-pre-check ' . ((array_key_exists('premium', $value) && $value['premium'] == true && apply_filters('shortcode-addons/pro_enabled', false) == FALSE) ? 'addons-pre-check-pro-only' : '') . '" href="' . admin_url($oxilink) . '" sub-name="' . $value['name'] . '" sub-type="' . (array_key_exists($key, $this->installed) ? array_key_exists($value['name'], $this->installed[$key]) ? (version_compare($this->installed[$key][$value['name']]['version'], $value['version']) >= 0) ? '' : 'update' : 'install' : 'install') . '">
                                                    <div class="oxi-addons-shortcode-import-top">
                                                       ' . $this->font_awesome_render((array_key_exists('icon', $value) ? $value['icon'] : 'fas fa-cloud-download-alt')) . '
                                                    </div>
                                                    <div class="oxi-addons-shortcode-import-bottom">
                                                        <span>' . $this->name_converter($value['name']) . '</span>
                                                    </div>
                                                </a>
                                               
                                           </div>';
                        }
                        if ($elementsnumber > 0) {
                            echo '  <div class="oxi-addons-text-blocks-body-wrapper">
                                    <div class="oxi-addons-text-blocks-body">
                                        <div class="oxi-addons-text-blocks">
                                            <div class="oxi-addons-text-blocks-heading">' . $key . '</div>
                                            <div class="oxi-addons-text-blocks-border">
                                                <div class="oxi-addons-text-block-border"></div>
                                            </div>
                                            <div class="oxi-addons-text-blocks-content">Available (' . $elementsnumber . ')</div>
                                        </div>
                                    </div>
                                </div>';
                            echo $elementshtml;
                        }
                    }
                    ?>
                </div>
            </div>
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
        <?php
    }

    /**
     * Shortcode Addons JSS Loader.
     *
     * @since 2.0.0
     */
    public function jss_file() {
        $js = 'jQuery.noConflict();
                (function ($) {
                    $("#oxi_addons_search").keyup(function () {
                        var value = $(this).val().toLowerCase();
                        $(".oxi-addons-shortcode-import").filter(function () {
                            $(this).toggle(jQuery(this).attr("oxi-addons-search").toLowerCase().indexOf(value) > -1);
                        });
                        if ($.trim(jQuery(this).val()).length) {
                            $(".oxi-addons-text-blocks-body-wrapper").not(":first").fadeOut("slow")
                        } else {
                            $(".oxi-addons-text-blocks-body-wrapper").fadeIn("slow")
                        }
                    });
                    $("a.addons-pre-check").on("click", function (e) {
                        if($(this).hasClass("addons-pre-check-pro-only")){
                            e.preventDefault();
                            $("#OXIAADDONSCHANGEDPOPUP .icon-box").html(\'<span class="dashicons dashicons-yes"></span>\');
                            $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center h4").html("Premium Only!");
                            $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center p").html("This Elements Works only with Premium Version.");
                            $("#OXIAADDONSCHANGEDPOPUP").modal("show");
                            return true ;
                        }
                       var url = $(this).attr("href");
                       var subtype = $(this).attr("sub-type");
                          if (subtype !== "") {
                            $(this).children(".oxi-addons-shortcode-import-bottom").append(\'<span class="spinner sa-spinner-open-left"></span>\');
                            $.ajax({
                                url: "' . admin_url('admin-ajax.php') . '",
                                type: "post",
                                data: {
                                    action: "shortcode_home_data",
                                    _wpnonce: "' . wp_create_nonce('shortcode-addons-editor') . '",
                                    functionname: "elements",
                                    rawdata: $(this).attr("sub-name"),
                                    styleid: "",
                                    childid: "",
                                },
                                success: function (response) {
                                    setTimeout(function () {
                                        console.log(response);
                                        if(response === "Done"){
                                            jQuery(".oxi-addons-parent-loader-wrap").hide();
                                            document.location.href = url;
                                       }
                                    }, 1000);
                                }
                            });
                            e.preventDefault();
                        }
                    });
                })(jQuery)';
        wp_add_inline_script('shortcode-addons-vendor', $js);
    }

}
