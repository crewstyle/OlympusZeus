/*!
 * tto.color.js v1.0.1
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds a complete integration with WordPress colorPicker or Farbastic.
 *
 * Example of JS:
 *      $('.colorpicker').tto_color({
 *          afterchange: '',                            //function to execute after changing value
 *          afterclear: '',                             //function to execute after clearing value
 *      });
 *
 * Example of HTML:
 *      <fieldset>
 *          <input type="text" class="colorpicker" />
 *      </fieldset>
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOColor = function ($el,options){
        //vars
        var _tto = this,
            _wpcolor = jQuery().wpColorPicker;

        //element
        _tto.$el = $el;
        _tto.options = options;

        //WP version < 3.5
        if (!_wpcolor) {
            _tto.farbastic();
        }
        //WP version >= 3.5
        else {
            _tto.colorpicker();

            $('a.iris-square-value').on('click', function (e){
                e.preventDefault();
            });
        }
    };

    TTOColor.prototype.$el = null;
    TTOColor.prototype.options = null;

    TTOColor.prototype.farbastic = function (){
        var _tto = this;

        //use functions plugin
        var _id = _tto.$el.attr('id');
        var $farb = $(document.createElement('div')).attr('id', _id + '_farb');
        $farb.insertAfter(_tto.$el);
        $farb.farbtastic('#' + _id);
    };

    TTOColor.prototype.colorpicker = function (){
        var _tto = this;

        //use functions plugin
        _tto.$el.wpColorPicker({
            change: function (){
                var _value = _tto.$el.wpColorPicker('color');
                _tto.$el.val(_value);

                //check if change option is defined and is a function, then execute it
                if ('function' === typeof _tto.options.afterchange) {
                    _tto.options.afterchange(_value);
                }
            },
            clear: function (){
                _tto.$el.val('');

                //check if change option is defined and is a function, then execute it
                if ('function' === typeof _tto.options.afterclear) {
                    _tto.options.afterclear();
                }
            }
        });
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                afterchange: '',
                afterclear: '',
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new TTOColor($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_color = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_color');
            return false;
        }
    };
})(window.jQuery);
