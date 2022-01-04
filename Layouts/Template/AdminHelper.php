<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SHORTCODE_ADDONS\Layouts\Template;

if (!defined('ABSPATH')) {
    exit;
}

use SHORTCODE_ADDONS\Core\Admin\Controls as Controls;

/**
 *
 * @author biplo
 */
trait AdminHelper {

    /**
     * Replace data
     *
     * @since v2.1.0
     */
    public function str_replace_first($from, $to, $content) {
        $from = '/' . preg_quote($from, '/') . '/';

        return preg_replace($from, $to, $content, 1);
    }

    /**
     * Template Modal opener
     * Define Multiple Data With Single Data
     *
     * @since 2.0.0
     */
    public function modal_opener() {
        $this->add_substitute_control('', [], [
            'type' => Controls::MODALOPENER,
            'title' => __('Add New Data', 'shortcode-addons'),
            'sub-title' => __('Open Data Form', 'shortcode-addons'),
            'showing' => TRUE,
        ]);
    }

    /**
     * Template Shortcode Name
     * Define Shortcode Name
     *
     * @since 2.0.0
     */
    public function shortcode_name() {
        $this->add_substitute_control('', $this->dbdata, [
            'type' => Controls::SHORTCODENAME,
            'title' => __('Shortcode Name', 'shortcode-addons'),
            'placeholder' => __('Set Your Shortcode Name', 'shortcode-addons'),
            'showing' => TRUE,
        ]);
    }

    /**
     * Template Shortcode Information
     * Parent Sector where users will get Shortcode Information
     *
     * @since 2.0.0
     */
    public function shortcode_info() {
        $this->add_substitute_control($this->oxiid, $this->dbdata, [
            'type' => Controls::SHORTCODEINFO,
            'title' => __('Shortcode', 'shortcode-addons'),
            'showing' => TRUE,
        ]);
    }

    /**
     * Template Modal Form Data
     * return always false and abstract with current Style Template
     *
     * @since 2.0.0
     */
    public function modal_form_data() {
        $this->form = 'single';
    }

    /**
     * Template Parent Modal Form
     *
     * @since 2.0.0
     */
    public function modal_form() {
        ?>
        <div class="modal fade" id="oxi-addons-list-data-modal" >
            <div class="modal-dialog">
                <form method="post" id="shortcode-addons-template-modal-form">
                    <div class="modal-content">

                        <?php
                        $this->modal_form_data();
                        ?>
                        <div class="modal-footer">
                            <input type="hidden" id="shortcodeitemid" name="shortcodeitemid" value="">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" id="shortcode-template-modal-submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    /**
     * Template Parent Item Data Rearrange
     *
     * @since 2.0.0
     */
    public function shortcode_rearrange() {
        echo '';
    }

    /**
     * Shortcode Addons Import Font Family
     *
     * @since 2.1.0
     */
    public function import_font_family() {
        $this->font_family = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM $this->import_table WHERE type = %s ORDER by id ASC", 'shortcode-addons'), ARRAY_A);
        $googleFonts = $localFonts = '';
        foreach ($this->font_family as $key => $value) {
            if ($value['name'] == 'custom') {
                $localFonts .= '|' . $value['font'];
            } else {
                $googleFonts .= '|' . $value['font'];
                $this->google_font[$value['font']] = $value['font'];
            }
        }
        if ($googleFonts == ''):
            $googleFonts = '|Roboto|Manjari|Gayathri|Open+Sans|Lato|Chilanka|Montserrat|Roboto+Condensed|Source+Sans+Pro';
            $g = explode('|', $googleFonts);
            foreach ($g as $value) {
                if (!empty($value)):
                    $this->google_font[$value] = $value;
                endif;
            }
        endif;
        $googlefont = admin_url("admin.php?page=shortcode-addons-font");
        $systemFonts = '|Arial|Helvetica+Neue|Courier+New|Times+New+Roman|Comic+Sans+MS|Verdana|Impact|cursive|inherit';

        $data = '(function($){

                                var fontsLoaded = {};

                                $.fn.fontselect = function(options) {
                                        var __bind = function(fn, me) { return function(){ return fn.apply(me, arguments); }; };

                                        var settings = {
                                                style: \'font-select\',
                                                placeholder: \'Select a font\',
                                                placeholderSearch: \'Search...\',
                                                searchable: true,
                                                lookahead: 2,
                                                googleApi: \'https://fonts.googleapis.com/css?family=\',
                                                localFontsUrl: \'/fonts/\',
                                                localFonts: \'' . $this->str_replace_first('|', '', $localFonts) . '\'.split(\'|\'),
                                                systemFonts: \'' . $this->str_replace_first('|', '', $systemFonts) . '\'.split(\'|\'),
                                                googleFonts: \'' . $this->str_replace_first('|', '', $googleFonts) . '\'.split(\'|\')
                                        };

                                        var Fontselect = (function(){

                                                function Fontselect(original, o) {
                                                        if (!o.systemFonts) { o.systemFonts = []; }
                                                        if (!o.localFonts) { o.localFonts = []; }
                                                        if (!o.googleFonts) { o.googleFonts = []; }
                                                        this.options = o;
                                                        this.$original = $(original);
                                                        this.setupHtml();
                                                        this.getVisibleFonts();
                                                        this.bindEvents();
                                                        this.query = \'\';
                                                        this.keyActive = false;
                                                        this.searchBoxHeight = 0;

                                                        var font = this.$original.val();
                                                        if (font) {
                                                                this.updateSelected();
                                                                this.addFontLink(font);
                                                        }
                                                }

                                                Fontselect.prototype = {
                                                        keyDown: function(e) {

                                                                function stop(e) {
                                                                        e.preventDefault();
                                                                        e.stopPropagation();
                                                                }

                                                                this.keyActive = true;
                                                                if (e.keyCode == 27) {// Escape
                                                                        stop(e);
                                                                        this.toggleDropdown(\'hide\');
                                                                        return;
                                                                }
                                                                if (e.keyCode == 38) {// Cursor up
                                                                        stop(e);
                                                                        var $li = $(\'li.active\', this.$results), $pli = $li.prev(\'li\');
                                                                        if ($pli.length > 0) {
                                                                                $li.removeClass(\'active\');
                                                                                this.$results.scrollTop($pli.addClass(\'active\')[0].offsetTop - this.searchBoxHeight);
                                                                        }
                                                                        return;
                                                                }
                                                                if (e.keyCode == 40) {// Cursor down
                                                                        stop(e);
                                                                        var $li = $(\'li.active\', this.$results), $nli = $li.next(\'li\');
                                                                        if ($nli.length > 0) {
                                                                                $li.removeClass(\'active\');
                                                                                this.$results.scrollTop($nli.addClass(\'active\')[0].offsetTop - this.searchBoxHeight);
                                                                        }
                                                                        return;
                                                                }
                                                                if (e.keyCode == 13) {// Enter
                                                                        stop(e);
                                                                        $(\'li.active\', this.$results).trigger(\'click\');
                                                                        return;
                                                                }
                                                                this.query += String.fromCharCode(e.keyCode).toLowerCase();
                                                                var $found = $("li[data-query^=\'"+ this.query +"\']").first();
                                                                if ($found.length > 0) {
                                                                        $(\'li.active\', this.$results).removeClass(\'active\');
                                                                        this.$results.scrollTop($found.addClass(\'active\')[0].offsetTop);
                                                                }
                                                        },

                                                        keyUp: function(e) {
                                                                this.keyActive = false;
                                                        },

                                                        bindEvents: function() {
                                                                var self = this;

                                                                $(\'li\', this.$results)
                                                                .click(__bind(this.selectFont, this))
                                                                .mouseover(__bind(this.activateFont, this));

                                                                this.$select.click(__bind(function() { self.toggleDropdown(\'show\') }, this));

                                                                // Call like so: $("input[name=\'ffSelect\']").trigger(\'setFont\', [fontFamily, fontWeight]);
                                                                this.$original.on(\'setFont\', function(evt, fontFamily, fontWeight) {
                                                                        fontWeight = fontWeight || 400;

                                                                        var fontSpec = fontFamily.replace(/ /g, \'+\') + (fontWeight == 400 ? \'\' : \':\'+fontWeight);

                                                                        var $li = $("li[data-value=\'"+ fontSpec +"\']", self.$results);
                                                                        if ($li.length == 0) {
                                                                                fontSpec = fontFamily.replace(/ /g, \'+\');
                                                                        }
                                                                        $li = $("li[data-value=\'"+ fontSpec +"\']", self.$results);
                                                                        $(\'li.active\', self.$results).removeClass(\'active\');
                                                                        $li.addClass(\'active\');

                                                                        self.$original.val(fontSpec);
                                                                        self.updateSelected();
                                                                        self.addFontLink($li.data(\'value\'));
                                                                        $li.trigger(\'click\');
                                                                });
                                                                this.$original.on(\'change\', function() {
                                                                        self.updateSelected();
                                                                        self.addFontLink($(\'li.active\', self.$results).data(\'value\'));
                                                                });

                                                                if (this.options.searchable) {
                                                                        this.$input.on(\'keyup\', function() {
                                                                                var q = this.value.toLowerCase();
                                                                                // Hide options that don\'t match query:
                                                                                $(\'li\', self.$results).each(function() {
                                                                                        if ($(this).text().toLowerCase().indexOf(q) == -1) {
                                                                                                $(this).hide();
                                                                                        }
                                                                                        else {
                                                                                                $(this).show();
                                                                                        }
                                                                                })
                                                                        })
                                                                }

                                                                $(document).on(\'click\', function(e) {
                                                                        if ($(e.target).closest(\'.\'+self.options.style).length === 0) {
                                                                                self.toggleDropdown(\'hide\');
                                                                        }
                                                                });
                                                        },

                                                        toggleDropdown: function(hideShow) {
                                                                if (hideShow === \'hide\') {
                                                                        // Make inactive
                                                                        this.$element.off(\'keydown keyup\');
                                                                        this.query = \'\';
                                                                        this.keyActive = false;
                                                                        this.$element.removeClass(\'font-select-active\');
                                                                        this.$drop.hide();
                                                                        clearInterval(this.visibleInterval);
                                                                } else {
                                                                        // Make active
                                                                        this.$element.on(\'keydown\', __bind(this.keyDown, this));
                                                                        this.$element.on(\'keyup\', __bind(this.keyUp, this));
                                                                        this.$element.addClass(\'font-select-active\');
                                                                        this.$drop.show();

                                                                        this.visibleInterval = setInterval(__bind(this.getVisibleFonts, this), 500);
                                                                        this.searchBoxHeight = this.$search.outerHeight();
                                                                        this.moveToSelected();

                                                                        /*
                                                                        if (this.options.searchable) {
                                                                                // Focus search box
                                                                                $this.$input.focus();
                                                                        }
                                                                        */
                                                                }
                                                        },

                                                        selectFont: function() {
                                                                var font = $(\'li.active\', this.$results).data(\'value\');
                                                                this.$original.val(font).change();
                                                                this.updateSelected();
                                                                this.toggleDropdown(\'hide\'); // Hide dropdown
                                                        },

                                                        moveToSelected: function() {
                                                                var font = this.$original.val().replace(/ /g, \'+\');
                                                                var $li = font ? $("li[data-value=\'"+ font +"\']", this.$results) : $li = $(\'li\', this.$results).first();
                                                                this.$results.scrollTop($li.addClass(\'active\')[0].offsetTop - this.searchBoxHeight);
                                                        },

                                                        activateFont: function(e) {
                                                                if (this.keyActive) { return; }
                                                                $(\'li.active\', this.$results).removeClass(\'active\');
                                                                $(e.target).addClass(\'active\');
                                                        },

                                                        updateSelected: function() {
                                                                var font = this.$original.val();
                                                                $(\'span\', this.$element).text(this.toReadable(font)).css(this.toStyle(font));
                                                        },

                                                        setupHtml: function() {
                                                                this.$original.hide();
                                                                this.$element = $(\'<div>\', {\'class\': this.options.style});
                                                                this.$select = $(\'<span tabindex="0">\' + this.options.placeholder + \'</span>\');
                                                                this.$search = $(\'<div>\', {\'class\': \'fs-search\'});
                                                                this.$input = $(\'<input>\', {type:\'text\'});
                                                                if (this.options.placeholderSearch) {
                                                                        this.$input.attr(\'placeholder\', this.options.placeholderSearch);
                                                                }
                                                                this.$search.append(this.$input);
                                                                this.$drop = $(\'<div>\', {\'class\': \'fs-drop\'});
                                                                this.$results = $(\'<ul>\', {\'class\': \'fs-results\'});
                                                                this.$original.after(this.$element.append(this.$select, this.$drop));
                                                                this.options.searchable && this.$drop.append(this.$search);
                                                                this.$drop.append(this.$results.append(this.fontsAsHtml())).append(\'<div class="fs-drop-add-more-body"><a  target="_blank" class="fs-drop-add-more" href="' . $googlefont . '">Want More?</a></div>\').hide();
                                                        },

                                                        fontsAsHtml: function() {
                                                                var i, r, s, style, h = \'\';
                                                                var systemFonts = this.options.systemFonts;
                                                                var localFonts = this.options.localFonts;
                                                                var googleFonts = this.options.googleFonts;

                                                                for (i = 0; i < systemFonts.length; i++){
                                                                        r = this.toReadable(systemFonts[i]);
                                                                        s = this.toStyle(systemFonts[i]);
                                                                        style = \'font-family:\' + s[\'font-family\'];
                                                                        if ((localFonts.length > 0 || googleFonts.length > 0) && i == systemFonts.length-1) {
                                                                                style += \';border-bottom:1px solid #444\'; // Separator after last system font
                                                                        }
                                                                        h += \'<li data-value="\'+ systemFonts[i] +\'" data-query="\' + systemFonts[i].toLowerCase() + \'" style="\' + style + \'">\' + r + \'</li>\';
                                                                }

                                                                for (i = 0; i < localFonts.length; i++){
                                                                        r = this.toReadable(localFonts[i]);
                                                                        s = this.toStyle(localFonts[i]);
                                                                        style = \'font-family:\' + s[\'font-family\'];
                                                                        if (googleFonts.length > 0 && i == localFonts.length-1) {
                                                                                style += \';border-bottom:1px solid #444\'; // Separator after last local font
                                                                        }
                                                                        h += \'<li data-value="\'+ localFonts[i] +\'" data-query="\' + localFonts[i].toLowerCase() + \'" style="\' + style + \'">\' + r + \'</li>\';
                                                                }

                                                                for (i = 0; i < googleFonts.length; i++){
                                                                        r = this.toReadable(googleFonts[i]);
                                                                        s = this.toStyle(googleFonts[i]);
                                                                        style = \'font-family:\' + s[\'font-family\'] + \';font-weight:\' + s[\'font-weight\'];
                                                                        h += \'<li data-value="\'+ googleFonts[i] +\'" data-query="\' + googleFonts[i].toLowerCase() + \'" style="\' + style + \'">\' + r + \'</li>\';
                                                                }

                                                                return h;
                                                        },

                                                        toReadable: function(font) {
                                                                return font.replace(/[\+|:]/g, \' \');
                                                        },

                                                        toStyle: function(font) {
                                                                var t = font.split(\':\');
                                                                return {\'font-family\':"\'"+this.toReadable(t[0])+"\'", \'font-weight\': (t[1] || 400)};
                                                        },

                                                        getVisibleFonts: function() {
                                                                if(this.$results.is(\':hidden\')) { return; }

                                                                var fs = this;
                                                                var top = this.$results.scrollTop();
                                                                var bottom = top + this.$results.height();

                                                                if (this.options.lookahead){
                                                                        var li = $(\'li\', this.$results).first().height();
                                                                        bottom += li * this.options.lookahead;
                                                                }

                                                                $(\'li\', this.$results).each(function(){
                                                                        var ft = $(this).position().top+top;
                                                                        var fb = ft + $(this).height();

                                                                        if ((fb >= top) && (ft <= bottom)){
                                                                                fs.addFontLink($(this).data(\'value\'));
                                                                        }
                                                                });
                                                        },

                                                        addFontLink: function(font) {
                                                                if (fontsLoaded[font]) { return; }
                                                                fontsLoaded[font] = true;

                                                                if (this.options.googleFonts.indexOf(font) > -1) {
                                                                        $(\'link:last\').after(\'<link href="\' + this.options.googleApi + font + \'" rel="stylesheet" type="text/css">\');
                                                                }
                                                                else if (this.options.localFonts.indexOf(font) > -1) {
                                                                        font = this.toReadable(font);
                                                                        $(\'head\').append("<style> @font-face { font-family:\'" + font + "\'; font-style:normal; font-weight:400; } </style>");
                                                                }
                                                                // System fonts need not be loaded!
                                                        }
                                                }; // End prototype

                                                return Fontselect;
                                        })();

                                        return this.each(function() {
                                                // If options exist, merge them
                                                if (options) { $.extend(settings, options); }

                                                return new Fontselect(this, settings);
                                        });
                                };
                        })(jQuery);
                        jQuery(\'.shortcode-addons-family\').fontselect();';
        wp_add_inline_script('shortcode-addons-editor', $data);
    }

}
