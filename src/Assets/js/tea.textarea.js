/* =====================================================
 * tea.textarea.js v1.0.1
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin adds a counter in all textarea flieds.
 * =====================================================
 * Example:
 *      $('.my-textarea').tea_textarea();
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_textarea = function ($el){
        var _tea = this;
        _tea.$el = $el;

        //initialize
        _tea.init();
    };

    Tea_textarea.prototype.$el = null;
    Tea_textarea.prototype.$counter = null;

    Tea_textarea.prototype.init = function (){
        var _tea = this;

        //create counter
        _tea.$counter = $(document.createElement('span')).addClass('counter');
        _tea.$counter.text(_tea.$el.val().length);

        //append counter
        _tea.$counter.insertBefore(_tea.$el);

        //bind all event
        _tea.$el.on('blur', $.proxy(_tea.getBlur, _tea));
        _tea.$el.on('focus', $.proxy(_tea.getFocus, _tea));
        _tea.$el.on('keyup', $.proxy(_tea.charCounter, _tea));
    };

    Tea_textarea.prototype.charCounter = function (){
        var _tea = this;
        _tea.$counter.text(_tea.$el.val().length);
    };

    Tea_textarea.prototype.getBlur = function (){
        var _tea = this;
        _tea.$counter.removeClass('on');
    };

    Tea_textarea.prototype.getFocus = function (){
        var _tea = this;
        _tea.$counter.addClass('on');
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new Tea_textarea($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_textarea = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on Tea_textarea');
            return false;
        }
    };
})(window.jQuery);
