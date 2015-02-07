/* =====================================================
 * tea.multiselect.js v1.0.1
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin change dynamically the select tag
 * with "multiple" attribute.
 * =====================================================
 * Example:
 *      $('select').tea_multiselect();
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_multiselect = function ($el){
        //vars
        var _tea = this;
        _tea.$el = $el;

        //create hidden submittable field
        _tea.create();

        //make it Selectizable
        _tea.$sel = _tea.$el.selectize({
            //options
            plugins: [
                'drag_drop',
                'remove_button',
                'restore_on_backspace'
            ],
            create: false,
            //events
            onChange: $.proxy(_tea.onchange, _tea),
        });

        //refresh options
        _tea.refresh();
    };

    Tea_multiselect.prototype.$el = null;
    Tea_multiselect.prototype.name = null;
    Tea_multiselect.prototype.$sel = null;
    Tea_multiselect.prototype.$tar = null;

    Tea_multiselect.prototype.create = function (){
        var _tea = this,
            _val = _tea.$el.attr('data-value'),
            _all = _val.split(',');

        _tea.name = _tea.$el.attr('name');

        //create elements
        _tea.hiddenize();

        //remove name element
        _tea.$el.removeAttr('name');

        //unselect all selected options
        _tea.$el.find('option:selected').removeAttr('selected');
    };

    Tea_multiselect.prototype.hiddenize = function (){
        var _tea = this,
            _val = _tea.$el.attr('data-value').split(',');

        //remove all old hidden values
        _tea.$el.closest('.tea-inside').find('input[name="' + _tea.name + '"]').remove();

        //create new ordered elements
        $.each(_val, function (ind,elm){
            //create element
            var $tar = $(document.createElement('input')).attr({
                type: 'hidden',
                name: _tea.name,
                value: elm
            });

            //append it
            _tea.$el.closest('.tea-inside').append($tar);
        });
    };

    Tea_multiselect.prototype.refresh = function (){
        var _tea = this,
            _val = _tea.$el.attr('data-value').split(','),
            _sel = _tea.$sel[0].selectize;

        //get all selected value
        for (var i = 0, len = _val.length; i < len; i++) {
            _sel.addItem(_val[i]);
        }
    };

    Tea_multiselect.prototype.onchange = function (arg){
        var _tea = this,
            _val = null === arg || [] == arg ? '' : arg.join();

        //update the value
        _tea.$el.attr('data-value', _val);

        //update the positions
        _tea.hiddenize();
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new Tea_multiselect($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_multiselect = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_multiselect');
            return false;
        }
    };
})(window.jQuery);
