/*!
 * checkall.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds a check all feature with checkbox and radio fields.
 *
 * Example of JS:
 *      $('.master :checkbox').olCheckall({
 *          container: '.container',                    //node containing all items to un/check
 *          items: '.list input[type="checkbox"]',      //item node to un/check
 *          closest: 'label',                           //closest node to item to add the selected class
 *          selected: 'selected'                        //selected class
 *      });
 * 
 * Example of HTML:
 *      <div class="container">
 *          <label class="master">
 *              <input type="checkbox" /> Un/check all
 *          </label>
 *          <fieldset class="list">
 *              <label><input type="checkbox" name="value" value="1" /> Value 1</label>
 *              <label><input type="checkbox" name="value" value="2" /> Value 2</label>
 *              <label><input type="checkbox" name="value" value="3" /> Value 3</label>
 *          </fieldset>
 *      </div>
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlCheckAll = function ($el,options){
        //vars
        var _ol = this;
        _ol.$el = $el;
        _ol.options = options;

        //treat all elements
        var _bind = 'INPUT' == $el[0].nodeName ? 'change' : 'click';

        //list all items to check
        _ol.$checks = _ol.$el.closest(_ol.options.container).find(_ol.options.items);

        //bind the change/click event
        _ol.$el.on(_bind, $.proxy(_ol.click, _ol));
        _ol.$checks.on(_bind, $.proxy(_ol.refresh, _ol));
    };

    OlCheckAll.prototype.$checks = null;
    OlCheckAll.prototype.$el = null;
    OlCheckAll.prototype.options = null;

    OlCheckAll.prototype.click = function (e){
        e.preventDefault();
        var _ol = this;

        //check or uncheck targets
        _ol.$checks.filter(':not(:disabled)').attr('checked', _ol.$el.is(':checked'));

        //add or remove selected CSS class
        if (_ol.$el.is(':checked')) {
            _ol.$checks.closest(_ol.options.closest).addClass(_ol.options.selected);
        }
        else {
            _ol.$checks.closest(_ol.options.closest).removeClass(_ol.options.selected);
        }
    };

    OlCheckAll.prototype.refresh = function (e){
        e.preventDefault();
        var _ol = this;

        //check or uncheck targets
        var _check = _ol.$checks.not(':checked').length === 0;

        //update checks
        _ol.$el.attr('checked', _check);
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                container: '.container',
                items: 'input[type="checkbox"]',
                closest: 'label',
                selected: 'selected'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new OlCheckAll($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olCheckall = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olCheckall');
            return false;
        }
    };
})(window.jQuery);
