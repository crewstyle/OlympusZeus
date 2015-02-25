/* =====================================================
 * tea.toggle.js v1.0.0
 * https://github.com/TeaThemeOptions/TeaThemeOptions
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin adds a display comportment with checkbox
 * and radio buttons.
 * =====================================================
 * Example:
 *      $('fieldset.toggle').tea_toggle();
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_toggle = function ($el){
        //vars
        var _tea = this;
        _tea.$el = $el;

        //bind the change event
        _tea.$el.find('input').on('change', $.proxy(_tea.change, _tea));
    };

    Tea_toggle.prototype.$el = null;

    Tea_toggle.prototype.change = function (e){
        var _tea = this;

        //check type
        if (_tea.$el.hasClass('is-off')) {
            _tea.$el.removeClass('is-off');
            _tea.$el.addClass('is-on');
        }
        else {
            _tea.$el.removeClass('is-on');
            _tea.$el.addClass('is-off');
        }
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new Tea_toggle($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_toggle = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_toggle');
            return false;
        }
    };
})(window.jQuery);
