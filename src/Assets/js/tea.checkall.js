/* ===================================================
 * tea.checkall.js v1.0.0
 * https://github.com/Takeatea/tea_theme_options
 * ===================================================
 * Copyright 2014 Take a Tea (http://takeatea.com)
 * ===================================================
 * Example:
 *      $('.master input[type="checkbox"]').tea_checkall({
 *          container: '.container',            //node containing all items to un/check
 *          items: 'input[type="checkbox"]',    //item node to un/check
 *          closest: 'label',                   //closest node to item to add the selected class
 *          selected: 'selected'                //selected class
 *      });
 * =================================================== */

(function ($){
    "use strict";

    var tea_checkall = function ($el,options){
        //Vars
        var _tea = this;
        _tea.$el = $el;
        _tea.options = options;

        var _container = options.container;
        var _items = options.items;
        var _closest = options.closest;
        var _selected = options.selected;

        //Treat all elements
        var _bind = 'INPUT' == $el[0].nodeName ? 'change' : 'click';

        //Bind the change/click event
        _tea.$el.on(_bind, $.proxy(_tea.click, _tea));
    };

    tea_checkall.prototype.click = function (e){
        e.preventDefault();
        var _tea = this;
        var $checks = null;

        //Define targets
        if (_tea.$el.attr('data-target')) {
            $checks = $('' + _tea.$el.attr('data-target'));
        }
        else {
            $checks = _tea.$el.closest(_tea.options.container).find(_tea.options.items)
        }

        //Check or uncheck targets
        $checks.attr('checked', _tea.$el.is(':checked'));

        //Add or remove selected CSS class
        if (_tea.$el.is(':checked')) {
            $checks.closest(_tea.options.closest).addClass(_tea.options.selected);
        }
        else {
            $checks.closest(_tea.options.closest).removeClass(_tea.options.selected);
        }
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

                new tea_checkall($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_checkall = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_checkall');
            return false;
        }
    };
})(window.jQuery);