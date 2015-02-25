/* =====================================================
 * tea.modal.js v1.0.1
 * https://github.com/TeaThemeOptions/TeaThemeOptions
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin adds range display in all range flieds.
 * =====================================================
 * Example:
 *      $('.modal-box').tea_modal();
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_modal = function ($el,options){
        var _tea = this;
        _tea.$el = $el;
        _tea.options = options;
        _tea.$modal = $('.tea-modal-backdrop');

        //Open modal
        _tea.open();
    };

    Tea_modal.prototype.$el = null;
    Tea_modal.prototype.$modal = null;
    Tea_modal.prototype.options = null;

    Tea_modal.prototype.open = function (){
        //e.preventDefault();
        var _tea = this;

        //check if modal is already shown
        if (true === _tea.$el.data('isShown')) {
            return;
        }

        //Open modal
        _tea.$modal.addClass('opened');
        _tea.$el.show();
        _tea.$el.data('isShown', true);

        //Bind close button
        _tea.$el.find('header .close').on('click', $.proxy(_tea.close, _tea));
        _tea.$modal.on('click', $.proxy(_tea.close, _tea));
    };

    Tea_modal.prototype.close = function (e){
        e.preventDefault();
        var _tea = this;

        _tea.$el.hide();
        _tea.$el.data('isShown', false);
        _tea.$modal.removeClass('opened');

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

                new Tea_modal($(this), settings);
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
})(window.jQuery);
