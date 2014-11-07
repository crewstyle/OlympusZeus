/* =====================================================
 * tea.link.js v1.0.0
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin change dynamically the href attribute
 * for the link URL.
 * =====================================================
 * Example:
 *      $('input[type="url"]').tea_link();
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_link = function ($el){
        //vars
        var _tea = this;
        _tea.$el = $el;
        _tea.$target = $el.closest('p').find('a');

        //bind click event
        _tea.$el.on('change', $.proxy(_tea.linketize, _tea));
    };

    Tea_link.prototype.$el = null;
    Tea_link.prototype.$target = null;

    Tea_link.prototype.linketize = function (e){
        e.preventDefault();
        var _tea = this;
        _tea.$target.attr('href', _tea.$el.val());
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new Tea_link($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_link = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on Tea_link');
            return false;
        }
    };
})(window.jQuery);
