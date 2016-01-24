/*!
 * multiselect.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin change dynamically the select tag with "multiple" attribute.
 *
 * Example of JS:
 *      $('select[multiple="true"]').olMultiselect({
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
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlMultiSelect = function ($el,options){
        //vars
        var _ol = this;
        _ol.$el = $el;
        _ol.options = options;

        //create hidden submittable field
        _ol.create();

        //make it Selectizable
        _ol.$sel = _ol.$el.selectize({
            //options
            plugins: [
                'drag_drop',
                'remove_button',
                'restore_on_backspace'
            ],
            create: false,
            //events
            onChange: $.proxy(_ol.onchange, _ol),
        });

        //refresh options
        _ol.refresh();
    };

    OlMultiSelect.prototype.options = null;
    OlMultiSelect.prototype.name = null;
    OlMultiSelect.prototype.$el = null;
    OlMultiSelect.prototype.$sel = null;
    OlMultiSelect.prototype.$tar = null;

    OlMultiSelect.prototype.create = function (){
        var _ol = this;

        _ol.name = _ol.$el.attr('name');

        //create elements
        _ol.hiddenize();

        //remove name element
        _ol.$el.removeAttr('name');

        //unselect all selected options
        _ol.$el.find('option:selected').removeAttr('selected');
    };

    OlMultiSelect.prototype.hiddenize = function (){
        var _ol = this,
            _val = _ol.$el.attr('data-value').split(',');

        //remove all old hidden values
        _ol.$el.closest(_ol.options.container).find('input[name="' + _ol.name + '"]').remove();

        //create new ordered elements
        $.each(_val, function (ind,elm){
            //create element
            var $tar = $(document.createElement('input')).attr({
                type: 'hidden',
                name: _ol.name,
                value: elm
            });

            //append it
            _ol.$el.closest(_ol.options.container).append($tar);
        });
    };

    OlMultiSelect.prototype.refresh = function (){
        var _ol = this,
            _val = _ol.$el.attr('data-value').split(','),
            _sel = _ol.$sel[0].selectize;

        //get all selected value
        for (var i = 0, len = _val.length; i < len; i++) {
            _sel.addItem(_val[i]);
        }
    };

    OlMultiSelect.prototype.onchange = function (arg){
        var _ol = this,
            _val = null === arg || [] == arg ? '' : arg.join();

        //update the value
        _ol.$el.attr('data-value', _val);

        //update the positions
        _ol.hiddenize();
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

                new OlMultiSelect($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olMultiselect = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olMultiselect');
            return false;
        }
    };
})(window.jQuery);
