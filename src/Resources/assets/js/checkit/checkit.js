/*!
 * checkit.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds a display comportment with checkbox and radio buttons.
 *
 * Example of JS:
 *      $(':checkbox, :radio').olCheckit({
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
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlCheckIt = function ($el,options){
        //vars
        var _ol = this;
        _ol.$el = $el;
        _ol.options = options;

        //bind the change event
        _ol.$el.on('change', $.proxy(_ol.change, _ol));
    };

    OlCheckIt.prototype.$el = null;
    OlCheckIt.prototype.options = null;

    OlCheckIt.prototype.change = function (){
        var _ol = this;

        //vars
        var _ctn = _ol.options.container,
            _clt = _ol.options.closest,
            _sel = _ol.options.selected;

        //check type
        if ('radio' == _ol.$el.attr('type')) {
            _ol.$el.closest(_ctn).find('.' + _sel).removeClass(_sel);
            _ol.$el.closest(_clt).addClass(_sel);
        }
        else if ('checkbox' == _ol.$el.attr('type')) {
            if (_ol.$el.is(':checked')) {
                _ol.$el.closest(_clt).addClass(_sel);
            }
            else {
                _ol.$el.closest(_clt).removeClass(_sel);
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

                new OlCheckIt($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olCheckit = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olCheckit');
            return false;
        }
    };
})(window.jQuery);
