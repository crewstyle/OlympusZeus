/*!
 * color.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds a complete integration with WordPress colorPicker or Farbtastic.
 *
 * Example of JS:
 *      $('.colorpicker').olColor({
 *          afterchange: '',                            //function to execute after changing value
 *          afterclear: '',                             //function to execute after clearing value
 *      });
 *
 * Example of HTML:
 *      <fieldset>
 *          <input type="text" class="colorpicker" />
 *      </fieldset>
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlColor = function ($el,options){
        //vars
        var _ol = this,
            _wpcolor = jQuery().wpColorPicker;

        //element
        _ol.$el = $el;
        _ol.options = options;

        //WP version < 3.5
        if (!_wpcolor) {
            _ol.farbtastic();
        }
        //WP version >= 3.5
        else {
            _ol.colorpicker();

            $('a.iris-square-value').on('click', function (e){
                e.preventDefault();
            });
        }
    };

    OlColor.prototype.$el = null;
    OlColor.prototype.options = null;

    OlColor.prototype.farbastic = function (){
        var _ol = this;

        //use functions plugin
        var _id = _ol.$el.attr('id');
        var $farb = $(document.createElement('div')).attr('id', _id + '_farb');
        $farb.insertAfter(_ol.$el);
        $farb.farbtastic('#' + _id);
    };

    OlColor.prototype.colorpicker = function (){
        var _ol = this;

        //use functions plugin
        _ol.$el.wpColorPicker({
            change: function (){
                var _value = _ol.$el.wpColorPicker('color');
                _ol.$el.val(_value);

                //check if change option is defined and is a function, then execute it
                if ('function' === typeof _ol.options.afterchange) {
                    _ol.options.afterchange(_value);
                }
            },
            clear: function (){
                _ol.$el.val('');

                //check if change option is defined and is a function, then execute it
                if ('function' === typeof _ol.options.afterclear) {
                    _ol.options.afterclear();
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

                new OlColor($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olColor = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olColor');
            return false;
        }
    };
})(window.jQuery);
