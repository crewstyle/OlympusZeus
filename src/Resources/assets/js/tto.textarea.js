/*!
 * tto.textarea.js v1.0.1
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds a counter in all textarea flieds.
 *
 * Example of JS:
 *      $('textarea').tto_textarea();
 *
 * Example of HTML:
 *      <fieldset>
 *          <textarea></textarea>
 *      </fieldset>
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOTextarea = function ($el){
        var _tto = this;
        _tto.$el = $el;

        //initialize
        _tto.init();
    };

    TTOTextarea.prototype.$el = null;
    TTOTextarea.prototype.$counter = null;

    TTOTextarea.prototype.init = function (){
        var _tto = this;

        //create counter
        _tto.$counter = $(document.createElement('span')).addClass('counter');
        _tto.$counter.text(_tto.$el.val().length);

        //append counter
        _tto.$counter.insertBefore(_tto.$el);

        //bind all event
        _tto.$el.on('blur', $.proxy(_tto.getBlur, _tto));
        _tto.$el.on('focus', $.proxy(_tto.getFocus, _tto));
        _tto.$el.on('keyup', $.proxy(_tto.charCounter, _tto));
    };

    TTOTextarea.prototype.charCounter = function (){
        var _tto = this;
        _tto.$counter.text(_tto.$el.val().length);
    };

    TTOTextarea.prototype.getBlur = function (){
        var _tto = this;
        _tto.$counter.removeClass('on');
    };

    TTOTextarea.prototype.getFocus = function (){
        var _tto = this;
        _tto.$counter.addClass('on');
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new TTOTextarea($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_textarea = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_textarea');
            return false;
        }
    };
})(window.jQuery);
