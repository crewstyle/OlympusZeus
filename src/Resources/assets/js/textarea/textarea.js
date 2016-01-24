/*!
 * textarea.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds a counter in all textarea flieds.
 *
 * Example of JS:
 *      $('textarea').olTextarea();
 *
 * Example of HTML:
 *      <fieldset>
 *          <textarea></textarea>
 *      </fieldset>
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlTextarea = function ($el){
        var _ol = this;
        _ol.$el = $el;

        //initialize
        _ol.init();
    };

    OlTextarea.prototype.$el = null;
    OlTextarea.prototype.$counter = null;

    OlTextarea.prototype.init = function (){
        var _ol = this;

        //create counter
        _ol.$counter = $(document.createElement('span')).addClass('counter');
        _ol.$counter.text(_ol.$el.val().length);

        //append counter
        _ol.$counter.insertAfter(_ol.$el);

        //bind all event
        _ol.$el.on('blur', $.proxy(_ol.getBlur, _ol));
        _ol.$el.on('focus', $.proxy(_ol.getFocus, _ol));
        _ol.$el.on('keyup', $.proxy(_ol.charCounter, _ol));
    };

    OlTextarea.prototype.charCounter = function (){
        var _ol = this;
        _ol.$counter.text(_ol.$el.val().length);
    };

    OlTextarea.prototype.getBlur = function (){
        var _ol = this;
        _ol.$counter.removeClass('on');
    };

    OlTextarea.prototype.getFocus = function (){
        var _ol = this;
        _ol.$counter.addClass('on');
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new OlTextarea($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olTextarea = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olTextarea');
            return false;
        }
    };
})(window.jQuery);
