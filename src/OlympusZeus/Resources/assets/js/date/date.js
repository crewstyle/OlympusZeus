/*!
 * date.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds a complete integration with pickadate JS component by Amsul:
 * http://amsul.ca/pickadate.js/
 *
 * Example of JS:
 *      $('.pickadate').olDate({
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
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlDate = function ($el,options){
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

                new OlDate($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olDate = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olDate');
            return false;
        }
    };
})(window.jQuery);
