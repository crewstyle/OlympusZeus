/*!
 * tto.multiselect.js v1.0.2
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin change dynamically the select tag with "multiple" attribute.
 *
 * Example of JS:
 *      $('select[multiple="true"]').tto_multiselect({
 *          container: '.container'                     //node element containing all data
 *      });
 *
 * Example of HTML:
 *      <fieldset class="container">
 *          <select multiple="true" data-value="1,2">
 *              <option value="1">Value 1</option>
 *              <option value="2">Value 2</option>
 *              <option value="3">Value 3</option>
 *          </select>
 *      </fieldset>
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOMultiSelect = function ($el,options){
        //vars
        var _tto = this;
        _tto.$el = $el;
        _tto.options = options;

        //create hidden submittable field
        _tto.create();

        //make it Selectizable
        _tto.$sel = _tto.$el.selectize({
            //options
            plugins: [
                'drag_drop',
                'remove_button',
                'restore_on_backspace'
            ],
            create: false,
            //events
            onChange: $.proxy(_tto.onchange, _tto),
        });

        //refresh options
        _tto.refresh();
    };

    TTOMultiSelect.prototype.options = null;
    TTOMultiSelect.prototype.name = null;
    TTOMultiSelect.prototype.$el = null;
    TTOMultiSelect.prototype.$sel = null;
    TTOMultiSelect.prototype.$tar = null;

    TTOMultiSelect.prototype.create = function (){
        var _tto = this;

        _tto.name = _tto.$el.attr('name');

        //create elements
        _tto.hiddenize();

        //remove name element
        _tto.$el.removeAttr('name');

        //unselect all selected options
        _tto.$el.find('option:selected').removeAttr('selected');
    };

    TTOMultiSelect.prototype.hiddenize = function (){
        var _tto = this,
            _val = _tto.$el.attr('data-value').split(',');

        //remove all old hidden values
        _tto.$el.closest(_tto.options.container).find('input[name="' + _tto.name + '"]').remove();

        //create new ordered elements
        $.each(_val, function (ind,elm){
            //create element
            var $tar = $(document.createElement('input')).attr({
                type: 'hidden',
                name: _tto.name,
                value: elm
            });

            //append it
            _tto.$el.closest(_tto.options.container).append($tar);
        });
    };

    TTOMultiSelect.prototype.refresh = function (){
        var _tto = this,
            _val = _tto.$el.attr('data-value').split(','),
            _sel = _tto.$sel[0].selectize;

        //get all selected value
        for (var i = 0, len = _val.length; i < len; i++) {
            _sel.addItem(_val[i]);
        }
    };

    TTOMultiSelect.prototype.onchange = function (arg){
        var _tto = this,
            _val = null === arg || [] == arg ? '' : arg.join();

        //update the value
        _tto.$el.attr('data-value', _val);

        //update the positions
        _tto.hiddenize();
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                container: '.container',
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new TTOMultiSelect($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_multiselect = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_multiselect');
            return false;
        }
    };
})(window.jQuery);
