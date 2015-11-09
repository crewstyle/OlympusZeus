/*!
 * tto.date.js v1.0.1
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds a complete integration with pickadate JS component by Amsul:
 * http://amsul.ca/pickadate.js/
 *
 * Example of JS:
 *      $('.pickadate').tto_date({
 *          //The following options are described here: http://amsul.ca/pickadate.js/date/#options
 *          format: 'd mmmm, yyyy',
 *          formatSubmit: 'yyyy.mm.dd',
 *          today: 'Today',
 *          clear: 'Clear',
 *          close: 'Close',
 *          hiddenName: true,
 *          selectYears: true,
 *          selectMonths: true
 *      });
 *
 * Example of HTML:
 *      <fieldset>
 *          <input type="text" class="pickadate" />
 *      </fieldset>
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTODate = function ($el,options){
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
                //find more explanations here: http://amsul.ca/pickadate.js/date/#options
                format: 'd mmmm, yyyy',
                formatSubmit: 'yyyy.mm.dd',
                today: 'Today',
                clear: 'Clear',
                close: 'Close',
                hiddenName: true,
                selectYears: true,
                selectMonths: true,
                klass: {
                    selectMonth: 'picker__select--month no-selectize',
                    selectYear: 'picker__select--year no-selectize',
                }
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new TTODate($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_date = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_date');
            return false;
        }
    };
})(window.jQuery);
