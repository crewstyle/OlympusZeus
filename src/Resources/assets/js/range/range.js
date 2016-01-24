/*!
 * range.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds range display in all range flieds.
 *
 * Example of JS:
 *      $('input[type="range"]').olRange();
 *
 * Example of HTML:
 *      <fieldset>
 *          <input type="range"/>
 *          <output></output>
 *      </fieldset>
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlRange = function ($el){
        //vars
        var _ol = this;
        _ol.$el = $el;

        //initialize
        _ol.init();
    };

    OlRange.prototype.$el = null;
    OlRange.prototype.$output = null;

    OlRange.prototype.init = function (){
        var _ol = this,
            $output = _ol.$el.parent().find('output');

        //check output or create it
        if ($output.length) {
            _ol.$output = $output;
        }
        else {
            _ol.$output = $(document.createElement('output'));
            _ol.$output.insertAfter(_ol.$el);
        }

        //update
        _ol.$output.text(_ol.$el.val());

        //bind the change event
        _ol.$el.on('change', $.proxy(_ol.change, _ol));
    };

    OlRange.prototype.change = function (){
        var _ol = this;
        _ol.$output.text(_ol.$el.val());
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new OlRange($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olRange = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olRange');
            return false;
        }
    };
})(window.jQuery);
