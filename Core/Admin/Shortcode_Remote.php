<?php

namespace SHORTCODE_ADDONS\Core\Admin;

/**
 * Description of Shortcode Remote
 * @author biplob018
 */
class Shortcode_Remote {

    protected static $lfe_instance = NULL;

    const SHORTCODE_TRANSIENT_ELEMENTS = 'shortcode_addons_elements';
    const SHORTCODE_TRANSIENT_MENU = 'get_oxilab_addons_menu';
    const SHORTCODE_TRANSIENT_GOOGLE_FONT = 'shortcode_addons_google_font';
    const API = 'https://www.shortcode-addons.com/wp-json/api/';

    public function __construct() {
        
    }

    /**
     * Access plugin instance. You can create further instances by calling
     * 
     *  @since 2.0.0
     */
    public static function get_instance() {
        if (NULL === self::$lfe_instance)
            self::$lfe_instance = new self;

        return self::$lfe_instance;
    }

    /**
     * Get  template Elements List.
     * @return mixed
     * 
     *  @since 2.0.0
     */
    public function categories_list($force_update = FALSE) {
        $response = get_transient(self::SHORTCODE_TRANSIENT_ELEMENTS);
        if (!$response || $force_update) {
            $URL = self::API . 'elements';
            $request = wp_remote_request($URL);
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                set_transient(self::SHORTCODE_TRANSIENT_ELEMENTS, $response, 10 * DAY_IN_SECONDS);
            } else {
                $response = $request->get_error_message();
            }
        }
        return $response;
    }

    /**
     * Get  template google font.
     * @return mixed
     * 
     *  @since 2.0.0
     */
    public function shortcode_addons_google_font($force_update = FALSE) {
        $response = get_transient(self::SHORTCODE_TRANSIENT_GOOGLE_FONT);
        if (!$response || $force_update) {
            $URL = self::API . 'fonts';
            $request = wp_remote_request($URL);
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                set_transient(self::SHORTCODE_TRANSIENT_GOOGLE_FONT, $response, 10 * DAY_IN_SECONDS);
            } else {
                $response = $request->get_error_message();
            }
        }
        return $response;
    }

    /**
     * Get  Shortcode Addons Menu.
     * @return mixed
     * 
     *  @since 2.0.0
     */
    public function Menu($force_update = FALSE) {
        $res = get_transient(self::SHORTCODE_TRANSIENT_MENU);
        $response = (!$res ? [] : $res);
        if ($force_update) {
            $response['Shortcode']['Elements'] = [
                'name' => 'Elements',
                'homepage' => 'shortcode-addons'
            ];
            $response['Shortcode']['Import'] = [
                'name' => 'Import',
                'homepage' => 'shortcode-addons-import'
            ];
            $response['Shortcode']['Extension'] = [
                'name' => 'Extension',
                'homepage' => 'shortcode-addons-extension'
            ];
            set_transient(self::SHORTCODE_TRANSIENT_MENU, $response, 10 * DAY_IN_SECONDS);
        }
        return $response;
    }

}
