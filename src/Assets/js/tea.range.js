/* =====================================================
 * tea.range.js v1.0.1
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin adds range display in all range flieds.
 * =====================================================
 * Example:
 *      $('input[type="range"]').tea_range();
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_range = function ($el){
        //vars
        var _tea = this;
        _tea.$el = $el;

        //initialize
        _tea.init();
    };

    Tea_range.prototype.$el = null;
    Tea_range.prototype.$output = null;

    Tea_range.prototype.init = function (){
        var _tea = this;

        //create output
        _tea.$output = $(document.createElement('<output></output>'));
        _tea.$output.insertAfter(_tea.$el);

        //bind the change event
        _tea.$el.on('change', $.proxy(_tea.change, _tea));
    };

    Tea_range.prototype.change = function (){
        var _tea = this;
        _tea.$output.text(_tea.$el.val());
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new Tea_range($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_range = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_range');
            return false;
        }
    };
})(window.jQuery);
