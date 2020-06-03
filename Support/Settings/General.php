<?php

namespace SHORTCODE_ADDONS\Support\Settings;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of General
 * Content of Shortcode Addons Plugins
 *
 * @author $biplob018
 */
class General {

    /**
     * Shortcode Addons Extension Constructor.
     *
     * @since 2.0.0
     */
    public function __construct() {
        $this->hooks();
    }
    /**
     * Shortcode Addons Hooks.
     *
     * @since 2.1.0
     */
    public function hooks() {
        $this->js_load();
        $this->Render();
    }
    /**
     * Shortcode Addons Load jQuery.
     *
     * @since 2.1.0
     */
    public function js_load() {
        $jquery = ' jQuery(".oxi-addons-settings-tab-wrapper .nav-tab:first").addClass("nav-tab-active");
                    jQuery(".oxi-addons-settings-tab:first").fadeIn();
                    jQuery(".oxi-addons-settings-tab-wrapper .nav-tab").click(function () {
                        var activeTab = jQuery(this).attr("oxi-data");
                        if(jQuery(this).hasClass("nav-tab-active")){
                            return false
                        }else{
                            jQuery(".oxi-addons-settings-tab-wrapper .nav-tab").removeClass("nav-tab-active");
                            jQuery(this).toggleClass("nav-tab-active");
                            jQuery(".oxi-addons-settings-tab").fadeOut();
                            jQuery(activeTab).fadeIn();
                        }
                    });';
        if (!current_user_can('manage_options')):
            $jquery .= 'jQuery("#oxigeneraldatauser  *").prop("disabled", true);';
        endif;
        wp_add_inline_script('shortcode-addons-vendor', $jquery);
    }
    /**
     * Shortcode Addons Render.
     *
     * @since 2.1.0
     */
    public function Render() {
        global $wp_roles;
        $roles = $wp_roles->get_names();
        $saved_role = get_option('oxi_addons_user_permission');
        ?>
        <div class="oxi-addons-wrapper">
            <div class="oxi-addons-row">

                <h1><?php _e('Shortcode Addons Settings'); ?></h1>
                <p>Set Shortcode Addons With Your Theme and Development.</p>

                <?php
                if (!current_user_can('manage_options')) {
                    echo '<p style="color:red">**Note: You can only add or midify Any Settings. For admin access You can\'t modify others. If you want so kindly contact with your admin.</p>';
                    die();
                }
                ?>
                <form method="post" action="options.php" id="oxigeneraldatauser">
                    <div  class="nav-tab-wrapper oxi-addons-settings-tab-wrapper">
                        <div class="nav-tab" oxi-data="#oxi-tab-general">Capabilities</div>
                        <div  class="nav-tab" oxi-data="#oxi-tab-font-family">Library Support </div>
                        <div  class="nav-tab" oxi-data="#oxi-tab-license-key">Conflict Class</div>
                    </div>

                    <div class="oxi-addons-settings-tab" id="oxi-tab-general">
                        <!--- first tab of settings page---->

                        <?php settings_fields('shortcode-addons-settings-group'); ?>
                        <?php do_settings_sections('shortcode-addons-settings-group'); ?>
                        <table class="form-table">
                            <tr valign="top">
                                <td scope="row">Who Can Edit?</td>
                                <td>
                                    <select name="oxi_addons_user_permission">
                                        <?php foreach ($roles as $key => $role) { ?>
                                            <option value="<?php echo $key; ?>" <?php selected($saved_role, $key); ?>><?php echo $role; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <label class="description" for="oxi_addons_user_permission"><?php _e('Select the Role who can manage This Plugins.'); ?> <a target="_blank" href="https://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table">Help</a></label>
                                </td>
                            </tr> 
                        </table>
                    </div>

                    <div class="oxi-addons-settings-tab" id="oxi-tab-font-family">
                        <table class="form-table">
                            <tr valign="top">
                                <td scope="row">Google Font Support</td>
                                <td>
                                    <input type="radio" name="oxi_addons_google_font" value="" <?php checked('', get_option('oxi_addons_google_font'), true); ?>>YES
                                    <input type="radio" name="oxi_addons_google_font" value="no" <?php checked('no', get_option('oxi_addons_google_font'), true); ?>>No
                                </td>
                                <td>
                                    <label class="description" for="oxi_addons_google_font"><?php _e('Load Google Font CSS at shortcode loading, If your theme already loaded select No for faster loading'); ?></label>
                                </td>
                            </tr> 
                            <tr valign="top">
                                <td scope="row">Font Awesome Support</td>
                                <td>
                                    <input type="radio" name="oxi_addons_font_awesome" value="yes" <?php checked('yes', get_option('oxi_addons_font_awesome'), true); ?>>YES
                                    <input type="radio" name="oxi_addons_font_awesome" value="" <?php checked('', get_option('oxi_addons_font_awesome'), true); ?>>No
                                </td>
                                <td>
                                    <label class="description" for="oxi_addons_font_awesome"><?php _e('Load Font Awesome CSS at shortcode loading, If your theme already loaded select No for faster loading'); ?></label>
                                </td>
                            </tr> 

                            <tr valign="top">
                                <td scope="row">Bootstrap 4 Support</td>
                                <td>
                                    <input type="radio" name="oxi_addons_bootstrap" value="yes" <?php checked('yes', get_option('oxi_addons_bootstrap'), true); ?>>YES
                                    <input type="radio" name="oxi_addons_bootstrap" value="" <?php checked('', get_option('oxi_addons_bootstrap'), true); ?>>No
                                </td>
                                <td>
                                    <label class="description" for="oxi_addons_bootstrap"><?php _e('Add Bootstrap Style and JQuery with Shortcode Using, Its Bootstrap 4 Version'); ?></label>
                                </td>
                            </tr> 
                            <tr valign="top">
                                <td scope="row">Linear Gradient Support</td>
                                <td>
                                    <input type="radio" name="oxi_addons_linear_gradient" value="yes" <?php checked('yes', get_option('oxi_addons_linear_gradient'), true); ?>>YES
                                    <input type="radio" name="oxi_addons_linear_gradient" value="" <?php checked('', get_option('oxi_addons_linear_gradient'), true); ?>>No
                                </td>
                                <td>
                                    <label class="description" for="oxi_addons_linear_gradient"><?php _e('Use Linear Gradient at Background Selector, You can also use RGBA'); ?></label>
                                </td>
                            </tr> 
                            <tr valign="top">
                                <td scope="row">Waypoints Support</td>
                                <td>
                                    <input type="radio" name="oxi_addons_waypoints" value="yes" <?php checked('yes', get_option('oxi_addons_waypoints'), true); ?>>YES
                                    <input type="radio" name="oxi_addons_waypoints" value="" <?php checked('', get_option('oxi_addons_waypoints'), true); ?>>No
                                </td>
                                <td>
                                    <label class="description" for="oxi_addons_waypoints"><?php _e('Do you want to load Waypoints. If your theme already loaded set it No.'); ?></label>
                                </td>
                            </tr> 
                        </table>
                    </div>
                    <div class="oxi-addons-settings-tab" id="oxi-tab-license-key">
                        <table class="form-table">
                            <tr valign="top">
                                <td scope="row">Conflict Class Support</td>
                                <td>
                                    <input type="text" name="oxi_addons_conflict_class" value="<?php echo get_option('oxi_addons_conflict_class'); ?>">
                                </td>
                                <td>
                                    <label class="description" for="oxi_addons_conflict_class"><?php _e('Add Class for avoid Conflict.'); ?></label>
                                </td>
                            </tr> 
                        </table>
                    </div>
                    <?php
                    submit_button();
                    ?>
                </form>

                <h1><?php _e('Product License Activation'); ?></h1>
                <p>Activate your copy to get direct plugin updates and official support.</p>
                <form method="post" action="options.php">
                    <?php 
                    
                    settings_fields('shortcode_addons_license'); 
                    
                    if(!empty($_GET['message'])):
                        echo '<div class="notice notice-warning is-dismissible">
                                <p>'.$_GET['message'].'</p>
                              </div>';
                    endif;
                    
                    ?>
                    
                    
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row" valign="top">
                                    <?php _e('License Key'); ?>
                                </th>
                                <td>
                                    <input id="sa_el_oxilab_license_key" name="shortcode_addons_license_key" type="text" class="regular-text" value="<?php echo get_option('shortcode_addons_license_key'); ?>" />
                                    <label class="description" for="shortcode_addons_license_key"><?php _e('Enter your license key'); ?></label>
                                </td>
                            </tr>
                            <?php if (!empty(get_option('shortcode_addons_license_key'))) { ?>
                                <tr valign="top">
                                    <th scope="row" valign="top">
                                        <?php _e('Activate License'); ?>
                                    </th>
                                    <td>
                                        <?php
                                        wp_nonce_field('shortcode_addons_license_key_nonce', 'shortcode_addons_license_key_nonce');
                                        if (get_option('oxi_addons_license_status') !== false && get_option('oxi_addons_license_status') == 'valid') {
                                            ?>
                                            <span style="color:green;"><?php _e('active'); ?></span>
                                            <input type="submit" class="button-secondary" name="shortcode_addons_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
                                            <?php
                                        } else {
                                            ?>
                                            <input type="submit" class="button-secondary" name="shortcode_addons_license_activate" value="<?php _e('Activate License'); ?>"/>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php submit_button(); ?>
                </form>

            </div>
        </div> 
        <?php
    }

}
