/* =====================================================
 * tea.date.js v1.0.1
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin adds a complete integration with
 * pickadate JS component by Amsul:
 * http://amsul.ca/pickadate.js/
 * =====================================================
 * Example:
 *      $('.pickadate').tea_date();
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_date = function ($el,options){
        //vars
        var _pickadate = jQuery().pickadate;

        //check pickadate
        if (!_pickadate) {
            return false;
        }

        //transform
        $el.pickadate(options);
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                format: 'd mmmm, yyyy',
                formatSubmit: 'yyyy.mm.dd',
                today: 'Today',
                clear: 'Clear',
                close: 'Close',
                hiddenName: true,
                selectYears: true,
                selectMonths: true
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Tea_date($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_date = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_date');
            return false;
        }
    };
})(window.jQuery);
