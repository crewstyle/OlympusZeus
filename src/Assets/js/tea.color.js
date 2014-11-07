/* =====================================================
 * tea.color.js v1.0.1
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin adds a complete integration with Wordpres
 * colorPicker or Farbastic.
 * =====================================================
 * Example:
 *      $('.colorpicker').tea_color();
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_color = function ($el){
        //vars
        var _tea = this,
            _wpcolor = jQuery().wpColorPicker;

        //element
        _tea.$el = $el;

        //WP version < 3.5
        if (!_wpcolor) {
            _tea.farbastic();
        }
        //WP version >= 3.5
        else {
            _tea.colorpicker();
        }
    };

    Tea_color.prototype.$el = null;

    Tea_color.prototype.farbastic = function (){
        var _tea = this;

        //use functions plugin
        var _id = _tea.$el.attr('id');
        var $farb = $(document.createElement('div')).attr('id', _id + '_farb');
        $farb.insertAfter(_tea.$el);
        $farb.farbtastic('#' + _id);
    };

    Tea_color.prototype.colorpicker = function (){
        var _tea = this;

        //use functions plugin
        _tea.$el.wpColorPicker({
            change: function (){
                _tea.$el.val(_tea.$el.wpColorPicker('color'));
            },
            clear: function (){
                _tea.$el.val('NONE');
            }
        });
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new Tea_color($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_color = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on Tea_color');
            return false;
        }
    };
})(window.jQuery);
