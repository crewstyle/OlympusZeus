/*!
 * tto.toggle.js v1.0.1
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds a display comportment with checkbox and radio buttons.
 *
 * Example of JS:
 *      $('fieldset.toggle').tto_toggle({
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
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOToggle = function ($el,options){
        //vars
        var _tto = this;
        _tto.$el = $el;
        _tto.options = options;

        //bind the change event
        _tto.$el.find('input').on('change', $.proxy(_tto.change, _tto));
    };

    TTOToggle.prototype.$el = null;
    TTOToggle.prototype.options = null;

    TTOToggle.prototype.change = function (e){
        var _tto = this;

        //check type
        if (_tto.$el.hasClass(_tto.options.off)) {
            _tto.$el.removeClass(_tto.options.off);
            _tto.$el.addClass(_tto.options.on);
        }
        else {
            _tto.$el.removeClass(_tto.options.on);
            _tto.$el.addClass(_tto.options.off);
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

                new TTOToggle($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_toggle = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_toggle');
            return false;
        }
    };
})(window.jQuery);
