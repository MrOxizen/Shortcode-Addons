<?php

namespace SHORTCODE_ADDONS\Layouts;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Settings
 *
 * @author biplo
 */
class Settings {

    use \SHORTCODE_ADDONS\Helper\Admin_Scripts;

    public $roles;
    public $saved_role;
    public $license;
    public $status;

    public function __construct() {

        do_action('shortcode-addons/before_init');
        // Load Elements

        $this->admin();

        $this->render();
    }

    public function admin() {
        $this->admin_settings();

        global $wp_roles;
        $this->roles = $wp_roles->get_names();
        $this->saved_role = get_option('oxi_addons_user_permission');
        $this->license = get_option('shortcode_addons_license_key');
        $this->status = get_option('oxi_addons_license_status');
    }

    public function render() {
        ?>
        <div class="wrap">
            <?php
            apply_filters('shortcode-addons/admin_menu', false);
            ?>
            <div class="oxi-addons-row oxi-addons-admin-settings">
                <form method="post">
                    <h2>General</h2>
                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="oxi_addons_user_permission">Who Can Edit?</label>
                                </th>
                                <td>
                                    <fieldset>
                                        <select name="oxi_addons_user_permission">
                                            <?php foreach ($this->roles as $key => $role) { ?>
                                                <option value="<?php echo esc_attr($key); ?>" <?php selected($this->saved_role, $key); ?>><?php echo esc_html($role); ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="oxi-addons-settings-connfirmation oxi_addons_user_permission"></span>
                                        <br>
                                        <p class="description">Select the Role who can manage This Plugins.<a target="_blank" href="https://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table">Help</a></p>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="oxi_addons_google_font">Google Font Support</label>
                                </th>
                                <td>
                                    <fieldset>
                                        <label for="oxi_addons_google_font[yes]">
                                            <input type="radio" class="radio" id="oxi_addons_google_font[yes]" name="oxi_addons_google_font" value="" <?php checked('', get_option('oxi_addons_google_font'), true); ?>>Yes</label>
                                        <label for="oxi_addons_google_font[no]">
                                            <input type="radio" class="radio" id="oxi_addons_google_font[no]" name="oxi_addons_google_font" value="no"  <?php checked('no', get_option('oxi_addons_google_font'), true); ?>>No
                                        </label>
                                        <span class="oxi-addons-settings-connfirmation oxi_addons_google_font"></span>
                                        <br>
                                        <p class="description">Load Google Font CSS at shortcode loading, If your theme already loaded select No for faster loading</p>
                                    </fieldset>
                                </td>
                            </tr>




                            <tr>
                                <th scope="row">
                                    <label for="oxi_addons_font_awesome">Font Awesome Support</label>
                                </th>
                                <td>
                                    <fieldset>
                                        <label for="oxi_addons_font_awesome[yes]">
                                            <input type="radio" class="radio" id="oxi_addons_font_awesome[yes]" name="oxi_addons_font_awesome" value="" <?php checked('', get_option('oxi_addons_font_awesome'), true); ?>>Yes</label>
                                        <label for="oxi_addons_font_awesome[no]">
                                            <input type="radio" class="radio" id="oxi_addons_font_awesome[no]" name="oxi_addons_font_awesome" value="no"  <?php checked('no', get_option('oxi_addons_font_awesome'), true); ?>>No
                                        </label>
                                        <span class="oxi-addons-settings-connfirmation oxi_addons_font_awesome"></span>
                                        <br>
                                        <p class="description">Load Font Awesome CSS at shortcode loading, If your theme already loaded select No for faster loading</p>
                                    </fieldset>
                                </td>
                            </tr>



                            <tr>
                                <th scope="row">
                                    <label for="oxi_addons_bootstrap">Bootstrap 4 Support</label>
                                </th>
                                <td>
                                    <fieldset>
                                        <label for="oxi_addons_bootstrap[yes]">
                                            <input type="radio" class="radio" id="oxi_addons_bootstrap[yes]" name="oxi_addons_bootstrap" value="" <?php checked('', get_option('oxi_addons_bootstrap'), true); ?>>Yes</label>
                                        <label for="oxi_addons_bootstrap[no]">
                                            <input type="radio" class="radio" id="oxi_addons_bootstrap[no]" name="oxi_addons_bootstrap" value="no"  <?php checked('no', get_option('oxi_addons_bootstrap'), true); ?>>No
                                        </label>
                                        <span class="oxi-addons-settings-connfirmation oxi_addons_bootstrap"></span>
                                        <br>
                                        <p class="description">Add Bootstrap Style and JQuery with Shortcode Using, Its Bootstrap 4 Version</p>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="oxi_addons_waypoints">Waypoints Support</label>
                                </th>
                                <td>
                                    <fieldset>
                                        <label for="oxi_addons_waypoints[yes]">
                                            <input type="radio" class="radio" id="oxi_addons_waypoints[yes]" name="oxi_addons_waypoints" value="" <?php checked('', get_option('oxi_addons_waypoints'), true); ?>>Yes</label>
                                        <label for="oxi_addons_waypoints[no]">
                                            <input type="radio" class="radio" id="oxi_addons_waypoints[no]" name="oxi_addons_waypoints" value="no"  <?php checked('no', get_option('oxi_addons_waypoints'), true); ?>>No
                                        </label>
                                        <span class="oxi-addons-settings-connfirmation oxi_addons_waypoints"></span>
                                        <br>
                                        <p class="description">Do you want to load Waypoints. If your theme already loaded set it No.</p>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="oxi_addons_conflict_class">Conflict Class Support</label>
                                </th>
                                <td>
                                    <fieldset>
                                        <label for="oxi_addons_conflict_class">
                                            <input type="text" name="oxi_addons_conflict_class" value="<?php echo esc_attr(get_option('oxi_addons_conflict_class')); ?>">
                                        </label>
                                        <span class="oxi-addons-settings-connfirmation oxi_addons_conflict_class"></span>
                                        <br>
                                        <p class="description">Add Custom Parent Class for avoid Conflict.</p>
                                    </fieldset>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <br>

                    <h2>Product License</h2>
                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="shortcode_addons_license_key">License Key</label>
                                </th>
                                <td class="valid">
                                    <input type="text" class="regular-text" id="shortcode_addons_license_key" name="shortcode_addons_license_key" value="<?php echo esc_attr($this->license); ?>">
                                    <span class="oxi-addons-settings-connfirmation shortcode_addons_license_massage">
                                        <?php
                                        if ($this->status == 'valid' && empty($this->license)):
                                            echo '<span class="oxi-confirmation-success"></span>';
                                        elseif ($this->status == 'valid' && !empty($this->license)):
                                            echo '<span class="oxi-confirmation-success"></span>';
                                        elseif (!empty($this->license)):
                                            echo '<span class="oxi-confirmation-failed"></span>';
                                        else:
                                            echo '<span class="oxi-confirmation-blank"></span>';
                                        endif;
                                        ?>
                                    </span>
                                    <span class="oxi-addons-settings-connfirmation shortcode_addons_license_text">
                                        <?php
                                        if ($this->status == 'valid' && empty($this->license)):
                                            echo '<span class="oxi-addons-settings-massage">Pre Active</span>';
                                        elseif ($this->status == 'valid' && !empty($this->license)):
                                            echo '<span class="oxi-addons-settings-massage">Active</span>';
                                        elseif (!empty($this->license)):
                                            echo '<span class="oxi-addons-settings-massage">' . esc_attr($this->status) . '</span>';
                                        else:
                                            echo '<span class="oxi-addons-settings-massage"></span>';
                                        endif;
                                        ?>
                                    </span>
                                    <p class="description">Activate your License to get direct plugin updates and official support.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <?php
    }

}
