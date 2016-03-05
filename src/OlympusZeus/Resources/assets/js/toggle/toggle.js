/*!
 * toggle.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds a display comportment with checkbox and radio buttons.
 *
 * Example of JS:
 *      $('fieldset.toggle').olToggle({
 *          off: 'is-off',                              //class CSS used when toggle is off
 *          on: 'is-on'                                 //class CSS used when toggle is on
 *      });
 *
 * Example of HTML:
 *      <fieldset class="toggle">
 *          <label><input type="radio" name"toggle" value="off"/> Off</label>
 *          <label><input type="radio" name"toggle" value="on"/> On</label>
 *      </fieldset>
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlToggle = function ($el,options){
        //vars
        var _ol = this;
        _ol.$el = $el;
        _ol.options = options;

        //bind the change event
        _ol.$el.find('input').on('change', $.proxy(_ol.change, _ol));
    };

    OlToggle.prototype.$el = null;
    OlToggle.prototype.options = null;

    OlToggle.prototype.change = function (e){
        var _ol = this;

        //check type
        if (_ol.$el.hasClass(_ol.options.off)) {
            _ol.$el.removeClass(_ol.options.off);
            _ol.$el.addClass(_ol.options.on);
        }
        else {
            _ol.$el.removeClass(_ol.options.on);
            _ol.$el.addClass(_ol.options.off);
        }
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                off: 'is-off',
                on: 'is-on'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new OlToggle($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olToggle = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olToggle');
            return false;
        }
    };
})(window.jQuery);
