/*!
 * tto.range.js v1.0.1
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds range display in all range flieds.
 *
 * Example of JS:
 *      $('input[type="range"]').tto_range();
 *
 * Example of HTML:
 *      <fieldset>
 *          <input type="range"/>
 *          <output></output>
 *      </fieldset>
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTORange = function ($el){
        //vars
        var _tto = this;
        _tto.$el = $el;

        //initialize
        _tto.init();
    };

    TTORange.prototype.$el = null;
    TTORange.prototype.$output = null;

    TTORange.prototype.init = function (){
        var _tto = this,
            $output = _tto.$el.parent().find('output');

        //check output or create it
        if ($output.length) {
            _tto.$output = $output;
        }
        else {
            _tto.$output = $(document.createElement('output'));
            _tto.$output.insertAfter(_tto.$el);
        }

        //update
        _tto.$output.text(_tto.$el.val());

        //bind the change event
        _tto.$el.on('change', $.proxy(_tto.change, _tto));
    };

    TTORange.prototype.change = function (){
        var _tto = this;
        _tto.$output.text(_tto.$el.val());
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new TTORange($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_range = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_range');
            return false;
        }
    };
})(window.jQuery);
