/* ===================================================
 * tea.modal.js v1.0.0
 * https://github.com/Takeatea/tea_theme_options
 * ===================================================
 * Copyright 20xx Take a Tea, Inc.
 * ===================================================
 * Example:
 *      $('.modal-box').tea_modal();
 * =================================================== */

!function ($){
    "use strict";

    var tea_modal = function ($el,options){
        var _tea = this;
        _tea.$el = $el;
        _tea.options = options;

        //Open modal
        _tea.open();
    };

    tea_modal.prototype.$el = null;
    tea_modal.prototype.options = null;

    tea_modal.prototype.open = function (){
        //e.preventDefault();
        var _tea = this;

        //check if modal is already shown
        if (true == _tea.$el.data('isShown')) {
            return;
        }

        //Open modal
        $('.tea-modal-backdrop').addClass('opened');
        _tea.$el.show();
        _tea.$el.data('isShown', true);

        //Bind close button
        _tea.$el.find('header .close').on('click', $.proxy(_tea.close, _tea));
        $('.tea-modal-backdrop').on('click', $.proxy(_tea.close, _tea));
    };

    tea_modal.prototype.close = function (e){
        e.preventDefault();
        var _tea = this;

        _tea.$el.hide();
        _tea.$el.data('isShown', false);
        $('.tea-modal-backdrop').removeClass('opened');

        //Check if close option is defined and is a function, then execute it
        if ('function' === typeof _tea.options.afterclose) {
            _tea.options.afterclose();
        }
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                afterclose: ''
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new tea_modal($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_modal = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_modal');
            return false;
        }
    };
}(window.jQuery);
