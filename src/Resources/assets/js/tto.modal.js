/*!
 * tto.modal.js v1.0.3
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin displays a modal box when it is asked.
 *
 * Example of JS:
 *      $('.modal-box').tto_modal({
 *          afterclose: '',                             //function to execute after closing modal
 *          backdrop: '.modal-backdrop',                //backdrop dom element
 *      });
 *
 * Example of HTML:
 *      --
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOModal = function ($el,options){
        var _tto = this;
        _tto.$el = $el;
        _tto.options = options;

        _tto.$backdrop = $(_tto.options.backdrop);
        _tto.$body = $('body');

        //open modal
        _tto.open();
    };

    TTOModal.prototype.$el = null;
    TTOModal.prototype.$backdrop = null;
    TTOModal.prototype.$body = null;
    TTOModal.prototype.options = null;

    TTOModal.prototype.open = function (){
        var _tto = this;

        //check if modal is already shown
        if (true === _tto.$el.data('isShown')) {
            return;
        }

        //open modal
        _tto.$body.addClass('modal-open');
        _tto.$backdrop.addClass('opened');
        _tto.$el.show();
        _tto.$el.data('isShown', true);

        //bind close button
        _tto.$el.find('footer .close').on('click', $.proxy(_tto.close, _tto));
        _tto.$el.find('header .close').on('click', $.proxy(_tto.close, _tto));
        _tto.$backdrop.on('click', $.proxy(_tto.close, _tto));
    };

    TTOModal.prototype.close = function (e){
        e.preventDefault();
        var _tto = this;

        //close modal
        _tto.$body.removeClass('modal-open');
        _tto.$el.hide();
        _tto.$el.data('isShown', false);
        _tto.$backdrop.removeClass('opened');

        //check if close option is defined and is a function, then execute it
        if ('function' === typeof _tto.options.afterclose) {
            _tto.options.afterclose();
        }
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                afterclose: '',
                backdrop: '.modal-backdrop',
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new TTOModal($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_modal = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_modal');
            return false;
        }
    };
})(window.jQuery);
