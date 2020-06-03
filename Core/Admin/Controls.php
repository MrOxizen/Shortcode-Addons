<?php

namespace SHORTCODE_ADDONS\Core\Admin;

/**
 * Description of Admin Controller
 * @author biplob018
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Shortcode Addons Controls.
 *
 * @since 2.0.0
 */
class Controls {

    /**
     * Text control.
     */
    const TEXT = 'text';

    /**
     * Textarea control.
     */
    const TEXTAREA = 'textarea';

    /**
     * WYSIWYG control.
     */
    const WYSIWYG = 'wysiwyg';

    /**
     * Hidden control.
     */
    const HIDDEN = 'hidden';

    /**
     * Number control.
     */
    const NUMBER = 'number';

    /**
     * Slider control.
     */
    const SLIDER = 'slider';

    /**
     * Select control.
     */
    const SELECT = 'select';

    /**
     * Switcher control.
     */
    const SWITCHER = 'switcher';

    /**
     * Choose control.
     */
    const CHOOSE = 'choose';

    /**
     * Choose Operator Text.
     */
    const OPERATOR_TEXT = 'text';

    /**
     * Choose Operator Icon.
     */
    const OPERATOR_ICON = 'icon';

    /**
     * Popover Toggle control.
     */
    const POPOVER = 'popover';

    /**
     * Color control.
     */
    const COLOR = 'color';

    /**
     * Gradient Color control.
     */
    const GRADIENT = 'gradient';

    /**
     * Dimensions control.
     */
    const DIMENSIONS = 'dimensions';

    /**
     * Font control.
     */
    const FONT = 'font';

    /**
     * Image URL control.
     */
    const IMAGE = 'image';

    /**
     * Icon control.
     */
    const ICON = 'icon';

    /**
     * Date/Time control.
     */
    const DATE_TIME = 'date_time';

    /**
     * Select2 control.
     */
    const SELECT2 = 'select2';

    /**
     * URL control.
     */
    const URL = 'url';

    /**
     * Repeater control.
     */
    const REPEATER = 'repeater';

    /**
     * Gallery control.
     */
    const GALLERY = 'gallery';
    
     /**
     * Heading Control.
     */
    const HEADING = 'heading';

    /**
     * File Upload.
     */
    const FILEUPLOAD = 'fileupload';
    /**
     * Video Upload.
     */
    const VIDEO = 'video';
    /**
     * Video Upload.
     */
    const AUDIO = 'audio';
    
    /**
     * PDF Upload.
     */
    const FILE = 'file';

/**
     * Password control.
     */
    const PASSWORD = 'password';


    /*
     * 
     * 
     * 
     * 
     * Group Control
     * 
     * 
     * 
     * 
     * 
     */

    /**
     *  Box Shadow control.
     */
    const BOXSHADOW = 'boxshadow';

    /**
     *  Text Shadow control.
     */
    const TEXTSHADOW = 'textshadow';

    /**
     * Typography control.
     */
    const TYPOGRAPHY = 'typography';

    /**
     * Border control.
     */
    const BORDER = 'border';

    /**
     * Entrance animation control.
     */
    const ANIMATION = 'animation';

    /**
     * Media control.
     */
    const MEDIA = 'media';

    /**
     * Background control.
     */
    const BACKGROUND = 'background';

    /**
     * Column control.
     */
    const COLUMN = 'column';
    
    /**
     * Rearrange control.
     */
    const REARRANGE = 'rearrange';


    /*
     * 
     * 
     * 
     * 
     * Typhography Include Turms
     * 
     * 
     * 
     * 
     * 
     */

    /**
     * Normal Text Align Control.
     */
    const ALIGNNORMAL = 'align_normal';

    /**
     * Display felx align control.
     */
    const ALIGNFLEX = 'align_flex';



    /*
     * 
     * 
     * 
     * 
     * 
     * Templates Substitute Data 
     * 
     * 
     * 
     * 
     * 
     * 
     */

    /**
     * Modal Opener.
     */
    const MODALOPENER = 'modalopener';

    /**
     * Shortcode Name.
     */
    const SHORTCODENAME = 'shortcodename';
    
    

    /**
     * Shortcode Info.
     */
    const SHORTCODEINFO = 'shortcodeinfo';
    
    /**
     *  Separator control.
     */
    const SEPARATOR = 'separator';
    /**
     * INSIDE Control (Repeater Condition).
     */
    const INSIDE = 'inside';
    /**
     *  OUTSIDE Control (Repeater Condition).
     */
    const OUTSIDE = 'outside';

}
