/*!
 * modal.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin displays a modal box when it is asked.
 *
 * Example of JS:
 *      $('.modal-box').olModal({
 *          afterclose: '',                             //function to execute after closing modal
 *          backdrop: '.modal-backdrop',                //backdrop dom element
 *      });
 *
 * Example of HTML:
 *      --
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlModal = function ($el,options){
        var _ol = this;
        _ol.$el = $el;
        _ol.options = options;

        _ol.$backdrop = $(_ol.options.backdrop);
        _ol.$body = $('body');

        //open modal
        _ol.open();
    };

    OlModal.prototype.$el = null;
    OlModal.prototype.$backdrop = null;
    OlModal.prototype.$body = null;
    OlModal.prototype.options = null;

    OlModal.prototype.open = function (){
        var _ol = this;

        //check if modal is already shown
        if (true === _ol.$el.data('isShown')) {
            return;
        }

        //open modal
        _ol.$body.addClass('modal-open');
        _ol.$backdrop.addClass('opened');
        _ol.$el.show();
        _ol.$el.data('isShown', true);

        //bind close button
        _ol.$el.find('footer .close').on('click', $.proxy(_ol.close, _ol));
        _ol.$el.find('header .close').on('click', $.proxy(_ol.close, _ol));
        _ol.$backdrop.on('click', $.proxy(_ol.close, _ol));
    };

    OlModal.prototype.close = function (e){
        e.preventDefault();
        var _ol = this;

        //close modal
        _ol.$body.removeClass('modal-open');
        _ol.$el.hide();
        _ol.$el.data('isShown', false);
        _ol.$backdrop.removeClass('opened');

        //check if close option is defined and is a function, then execute it
        if ('function' === typeof _ol.options.afterclose) {
            _ol.options.afterclose();
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

                new OlModal($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olModal = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olModal');
            return false;
        }
    };
})(window.jQuery);
