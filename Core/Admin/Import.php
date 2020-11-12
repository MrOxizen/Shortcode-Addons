<?php

namespace SHORTCODE_ADDONS\Core\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Import
 * @author biplob018
 */
class Import {

    use \SHORTCODE_ADDONS\Support\Validation;
    use \SHORTCODE_ADDONS\Support\JSS_CSS_LOADER;

    /**
     * Shortcode Addons Extension Constructor.
     *
     * @since 2.0.0
     */
    public function __construct() {
        do_action('shortcode-addons/before_init');
        $this->hooks();
    }

    /**
     * Shortcode Addons Extension hooks.
     *
     * @since 2.0.0
     */
    public function hooks() {
        $this->admin_import_media();
        $this->import();
        $this->render();
    }

    /**
     * Shortcode Addons Extension render.
     *
     * @since 2.1.0
     */
    public function import() {

        if (!empty($_REQUEST['_wpnonce'])) {
            $nonce = $_REQUEST['_wpnonce'];
        }
        if (!empty($_POST['data-upload']) && $_POST['data-upload'] == 'Save') {
            if (!wp_verify_nonce($nonce, 'oxi-addons-upload-nonce')) {
                die('You do not have sufficient permissions to access this page.');
            } else {
                if ($_FILES["validuploaddata"]["name"]) {
                    $filename = $_FILES["validuploaddata"]["name"];
                    $source = $_FILES["validuploaddata"]["tmp_name"];
                    $type = $_FILES["validuploaddata"]["type"];
                    $name = explode(".", $filename);
                    $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
                    foreach ($accepted_types as $mime_type) {
                        if ($mime_type == $type) {
                            $okay = true;
                            break;
                        }
                    }
                    $continue = strtolower($name[1]) == 'zip' ? true : false;
                    if (!$continue) {
                        $message = "The file you are trying to upload is not a .zip file. Please try again.";
                    }
                    global $wp_filesystem;
                    require_once ( ABSPATH . '/wp-admin/includes/file.php' );
                    WP_Filesystem();
                    $fileconpress = SA_ADDONS_UPLOAD_PATH . $filename;
                    if (file_exists($fileconpress)) {
                        unlink($fileconpress);
                    }
                    move_uploaded_file($source, $fileconpress);
                    unzip_file($fileconpress, SA_ADDONS_UPLOAD_PATH);
                    if (file_exists($fileconpress)) {
                        unlink($fileconpress);
                    }
                }
            }
            $upload = get_home_path();
            $upload_dir = $upload . 'Shortcode-Addons';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777);
            }
            $upload_dir = $upload . 'Shortcode-Addons/Elements';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777);
            }
            $backup_dir = $upload . 'Shortcode-Addons/Backup';
            if (!is_dir($backup_dir)) {
                mkdir($backup_dir, 0777);
            }
        }
    }

    public function render() {
        ?>
        <div class="wrap">  
            <?php
            apply_filters('shortcode-addons/admin_nav_menu', false);
            ?>
            <div class="oxi-addons-wrapper">   
                <div class="oxi-addons-import-layouts">
                    <h1>Import Elements</h1>
                    <p> The Import Elements allows you to easily Import your Elements. You can import local Or manually elements if your automatic tools not works properly. Once Imported your Elements will works properly into shortcode home page.</p>

                    <?php
                    if (apply_filters('shortcode-addons/pro_enabled', false) == false) {
                        echo '<div class="oxi-addons-updated">
                                    <p>Hey, Thank you very much, to using <strong>Shortcode Addons- with Visual Composer, Divi, Beaver Builder and Elementor Extension </strong>! Import Elements will works only at Pro or Premium version. Our Premium version comes with lots of features and 16/6 Dedicated Support.</p>
                              </div>';
                    }
                    ?>
                    <!----- Import Form ---->
                    <form method="post" id="oxi-addons-import-elements-form" enctype="multipart/form-data">
                        <div class="oxi-addons-import-data">
                            <div class="oxi-headig">
                                Import Elements
                            </div>
                            <div class="oxi-content-box">
                                <div class="oxi-content">
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="ShortcodeAddonsUploa" name="validuploaddata">
                                            <label class="custom-file-label" for="ShortcodeAddonsUploa">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="oxi-buttom">
                                    <?php wp_nonce_field("oxi-addons-upload-nonce") ?>
                                    <input type="submit" class="btn btn-success" name="data-upload" value="Save">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="oxi-addons-wrapper">   
                <div class="oxi-addons-import-layouts">
                    <h1>Import Layouts</h1>
                    <p> The Import tool allows you to easily manage your Shortcode content. Its too easy as copy templete files from our online style list or local files and paste it into our import box. Once Imported your data will shown automatically with new shortcode.</p>

                    <?php
                    if (apply_filters('shortcode-addons/pro_enabled', false) == false) {
                        echo '<div class="oxi-addons-updated">
                                    <p>Hey, Thank you very much, to using <strong>Shortcode Addons- with Visual Composer, Divi, Beaver Builder and Elementor Extension </strong>! Import style or layouts will works only at Pro or Premium version. Our Premium version comes with lots of features and 16/6 Dedicated Support.</p>
                              </div>';
                    }
                    ?>
                    <!----- Import Form ---->
                    <form method="post" id="oxi-addons-import-data-form">
                        <div class="oxi-addons-import-data">
                            <div class="oxi-headig">
                                Import Data Form
                            </div>
                            <div class="oxi-content">
                                <textarea placeholder="Paste your style files..." name="shortcode-addons-content" id="shortcode-addons-content"></textarea>
                            </div>
                            <div class="oxi-buttom">
                                <a href="" class="btn btn-danger"> Reset </a>
                                <input type="submit" class="btn btn-success" name="submit" value="Save">
                            </div>
                        </div>
                    </form>
                    <div class="feature-section">
                        <h3>Get Trouble to Import Style?</h3>
                        <p>Your suggestions will make this plugin even better, Even if you get any bugs on Shortcode Addons so let us to know, We will try to solved within few hours</p>
                        <p class="oxi-feature-button">
                            <a href="https://www.shortcode-addons.com/docs/shortcode-addons/import-layouts/" target="_blank" rel="noopener" class="ihewc-image-features-button button button-primary">Documentation</a>
                            <a href="https://wordpress.org/plugins/shortcode-addons/" target="_blank" rel="noopener" class="ihewc-image-features-button button button-primary">Support Forum</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
