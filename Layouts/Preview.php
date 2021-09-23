<?php

namespace SHORTCODE_ADDONS\Layouts;

/**
 * Description of Preview
 *
 * @author biplo
 */
use SHORTCODE_ADDONS\Helper\Database as Database;

class Preview extends Database {

    /**
     * Current Elements ID
     *
     * @since 3.3.0
     */
    public $oxiid;

    /**
     * Shortcode Addons previews
     *
     * @since 2.1.0
     */
    public function templates() {
        add_action('admin_init', array($this, 'maybe_load_template'));
        add_action('admin_menu', array($this, 'add_dashboard_page'));
        add_action('network_admin_menu', array($this, 'add_dashboard_page'));
    }

    /**
     * Register page through WordPress's hooks.
     */
    public function add_dashboard_page() {
        add_dashboard_page('', '', 'read', 'oxi-addons-style-view', '');
    }

    public function maybe_load_template() {
        $this->oxiid = (!empty($_GET['styleid']) ? (int) $_GET['styleid'] : '');
        $page = (isset($_GET['page']) ? $_GET['page'] : '');
        if ('oxi-addons-style-view' !== $page || $this->oxiid < 0) {
            return;
        }
         // Don't load the interface if doing an ajax call.
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }
        set_current_screen();
        // Remove an action in the Gutenberg plugin ( not core Gutenberg ) which throws an error.
        remove_action('admin_print_styles', 'gutenberg_block_editor_admin_print_styles');
        $this->load_template();
    }

    private function load_template() {
        $this->enqueue_scripts();
        $this->template_header();
        $this->template_content();
        $this->template_footer();
        exit;
    }

    public function enqueue_scripts() {
        wp_enqueue_style('oxilab-tabs-bootstrap', SA_ADDONS_URL . 'assets/backend/css/bootstrap.min.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('font-awsome.min', SA_ADDONS_URL . 'assets/front/css/font-awsome.min.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_style('oxilab-template-css', SA_ADDONS_URL . 'assets/backend/css/template.css', false, SA_ADDONS_PLUGIN_VERSION);
        wp_enqueue_script('oxilab-template-js', SA_ADDONS_URL . 'assets/backend/custom/frontend.js', false, SA_ADDONS_PLUGIN_VERSION);
    }

    public function template_header() {
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
            <meta name="viewport" content="width=device-width"/>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title><?php esc_html_e('Shortcode Addons Admin Previews', SHORTCODE_ADDOONS); ?></title>
            <?php wp_head(); ?>
        </head>
        <body class="shortcode-addons-template-body" id="shortcode-addons-template-body">
            <?php
        }

        /**
         * Outputs the content of the current step.
         */
        public function template_content() {
            if ($this->oxiid > 0):
                $styledata = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->parent_table WHERE id = %d ", $this->oxiid), ARRAY_A);
                $listdata = $this->wpdb->get_results("SELECT * FROM $this->child_table WHERE styleid= '$this->oxiid'  ORDER by id ASC", ARRAY_A);
                $shortcode = '';
                if (is_array($styledata)) {
                    $element = ucfirst(strtolower(str_replace('-', '_', $styledata['type'])));
                    $cls = '\SHORTCODE_ADDONS_UPLOAD\\' . $element . '\Templates\\' . ucfirst(str_replace('-', '_', $styledata['style_name'])) . '';
                    if (!class_exists($cls)):
                        $this->file_check($element);
                    else:
                        $CLASS = new $cls;
                        $CLASS->__construct($styledata, $listdata, 'user');
                    endif;
                } else {

                    $shortcode .= '<div class="oxi-addons-container">
                                <div class="oxi-addons-error">
                                    **<strong>Empty</strong> data found. Kindly check shortcode and put right shortcode with id from Shortcode Addons Elements** 
                                </div>
                            </div>';
                }
                echo $shortcode;
                return ob_get_clean();
            endif;
        }

        /**
         * Outputs the simplified footer.
         */
        public function template_footer() {
            ?>
            <?php wp_footer(); ?>
        </body>
        </html>
        <?php
    }

}
