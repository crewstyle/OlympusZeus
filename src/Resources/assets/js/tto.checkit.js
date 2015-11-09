/*!
 * tto.checkit.js v1.0.2
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds a display comportment with checkbox and radio buttons.
 *
 * Example of JS:
 *      $(':checkbox, :radio').tto_checkit({
 *          container: '.container',                    //node containing all items to un/check
 *          closest: 'label',                           //closest node to item to add the selected class
 *          selected: 'selected'                        //selected class
 *      });
 *
 * Example of HTML:
 *      <fieldset class="container">
 *          <label><input type="checkbox" /> Value 1</label>
 *          <label><input type="checkbox" /> Value 2</label>
 *          <label><input type="checkbox" /> Value 3</label>
 *      </fieldset>
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOCheckIt = function ($el,options){
        //vars
        var _tto = this;
        _tto.$el = $el;
        _tto.options = options;

        //bind the change event
        _tto.$el.on('change', $.proxy(_tto.change, _tto));
    };

    TTOCheckIt.prototype.$el = null;
    TTOCheckIt.prototype.options = null;

    TTOCheckIt.prototype.change = function (){
        var _tto = this;

        //vars
        var _ctn = _tto.options.container,
            _clt = _tto.options.closest,
            _sel = _tto.options.selected;

        //check type
        if ('radio' == _tto.$el.attr('type')) {
            _tto.$el.closest(_ctn).find('.' + _sel).removeClass(_sel);
            _tto.$el.closest(_clt).addClass(_sel);
        }
        else if ('checkbox' == _tto.$el.attr('type')) {
            if (_tto.$el.is(':checked')) {
                _tto.$el.closest(_clt).addClass(_sel);
            }
            else {
                _tto.$el.closest(_clt).removeClass(_sel);
            }
        }
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                container: '.container',
                closest: 'label',
                selected: 'selected'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new TTOCheckIt($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_checkit = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_checkit');
            return false;
        }
    };
})(window.jQuery);
