/*!
 * tto.checkall.js v1.0.3
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds a check all feature with checkbox and radio fields.
 *
 * Example of JS:
 *      $('.master :checkbox').tto_checkall({
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
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOCheckAll = function ($el,options){
        //vars
        var _tto = this;
        _tto.$el = $el;
        _tto.options = options;

        //treat all elements
        var _bind = 'INPUT' == $el[0].nodeName ? 'change' : 'click';

        //list all items to check
        _tto.$checks = _tto.$el.closest(_tto.options.container).find(_tto.options.items);

        //bind the change/click event
        _tto.$el.on(_bind, $.proxy(_tto.click, _tto));
        _tto.$checks.on(_bind, $.proxy(_tto.refresh, _tto));
    };

    TTOCheckAll.prototype.$checks = null;
    TTOCheckAll.prototype.$el = null;
    TTOCheckAll.prototype.options = null;

    TTOCheckAll.prototype.click = function (e){
        e.preventDefault();
        var _tto = this;

        //check or uncheck targets
        _tto.$checks.filter(':not(:disabled)').attr('checked', _tto.$el.is(':checked'));

        //add or remove selected CSS class
        if (_tto.$el.is(':checked')) {
            _tto.$checks.closest(_tto.options.closest).addClass(_tto.options.selected);
        }
        else {
            _tto.$checks.closest(_tto.options.closest).removeClass(_tto.options.selected);
        }
    };

    TTOCheckAll.prototype.refresh = function (e){
        e.preventDefault();
        var _tto = this;

        //check or uncheck targets
        var _check = _tto.$checks.not(':checked').length == 0;

        //update checks
        _tto.$el.attr('checked', _check);
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

                new TTOCheckAll($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_checkall = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_checkall');
            return false;
        }
    };
})(window.jQuery);
