<?php

namespace SHORTCODE_ADDONS\Layouts;

if (!defined('ABSPATH')) {
    exit;
}

use \SHORTCODE_ADDONS\Core\Console as Console;

class Import extends Console {

    use \SHORTCODE_ADDONS\Helper\Admin_Scripts;

    public $elements;

    /**
     * Shortcode Addons Extension Constructor.
     *
     * @since 2.0.0
     */
    public function element_page() {
        do_action('shortcode-addons/before_init');
        // Load Elements

        $this->admin();

        $this->render();
    }

    public function admin() {
        $this->import();
        $this->admin_scripts();
    }

    /**
     * Generate safe path
     * @since v1.0.0
     */
    public function safe_path($path) {

        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
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
                if ($_FILES["validuploaddata"]["name"] && current_user_can('upload_files')) {

                    if (!current_user_can('upload_files')):
                        wp_die(esc_html('You do not have permission to upload files.'));
                    endif;

                    $filename = $_FILES["validuploaddata"]["name"];
                    $source = $_FILES["validuploaddata"]["tmp_name"];
                    $type = $_FILES["validuploaddata"]["type"];
                    if ($type == 'application/json') {
                        $content = json_decode(file_get_contents($_FILES['validuploaddata']['tmp_name']), true);

                        if (empty($content)) {
                            return new \WP_Error('file_error', 'Invalid File');
                        }


                        if (!is_array($content) || $content['type'] != 'shortcode-addons') {
                            return new \WP_Error('file_error', 'Invalid Content In File');
                        }

                        $ImportApi = new \SHORTCODE_ADDONS\Core\RestApi();
                        $return = $ImportApi->import_json_template($content);
                        echo '<script type="text/javascript"> document.location.href = "' . $return . '"; </script>';
                        exit;
                    } else {
                        $name = explode(".", $filename);
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
                        $this->shortcode_elements(true);
                        $return = admin_url("admin.php?page=shortcode-addons&oxitype=" . strtolower($name[0]));
                        echo '<script type="text/javascript"> document.location.href = "' . $return . '"; </script>';
                        exit;
                    }
                }
            }
        }
    }

    public function render() {
        ?>
        <div class="wrap">
            <?php
            apply_filters('shortcode-addons/admin_menu', false);
            ?>
            <div class="oxi-addons-wrapper">
                <div class="oxi-addons-import-layouts">
                    <h1>Import Elements or Template</h1>
                    <p> The Import Elements allows you to easily Import your Elements or Templates. You can import local Or manually elements if your automatic tools not works properly. Once Imported your Elements will works properly into shortcode home page.</p>

                    <!----- Import Form ---->
                    <form method="post" id="oxi-addons-import-elements-form" enctype="multipart/form-data">
                        <div class="oxi-addons-import-data">
                            <div class="oxi-headig">
                                Elemensts or Template
                            </div>
                            <div class="oxi-content-box">
                                <div class="oxi-content">
                                    <div class="form-group">
                                        <input type="file" class="form-control-file" id="ShortcodeAddonsUploa"  name="validuploaddata">
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
        </div>
        <?php
    }

}
