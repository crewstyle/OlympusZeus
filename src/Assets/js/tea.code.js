/* =====================================================
 * tea.code.js v1.0.0
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin adds a complete integration with
 * codemirror JS component:
 * http://codemirror.net/
 * =====================================================
 * Example:
 *      $('code').tea_code();
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_code = function ($el,options){
        //vars
        var _tea = this;
        var _codemirror = CodeMirror;

        //check pickadate
        if (!CodeMirror) {
            return false;
        }

        //transform
        _tea.$el = $el;
        _tea.editor = CodeMirror.fromTextArea(_tea.$el.get(0), options);
        _tea.$change = _tea.$el.parent().find(options.changer);

        //bind blur/focus event
        _tea.editor.on('blur', $.proxy(_tea.blurMode, _tea));
        _tea.editor.on('focus', $.proxy(_tea.focusMode, _tea));

        //bind on change event
        if (_tea.$change.length) {
            _tea.$change.on('change', $.proxy(_tea.changeMode, _tea));
        }
    };

    Tea_code.prototype.$el = null;
    Tea_code.prototype.$change = null;
    Tea_code.prototype.editor = null;

    Tea_code.prototype.blurMode = function (e){
        var _tea = this;
        _tea.$el.parent().removeClass('focused');
    };

    Tea_code.prototype.focusMode = function (e){
        var _tea = this;
        _tea.$el.parent().addClass('focused');
    };

    Tea_code.prototype.changeMode = function (e){
        var _tea = this;
        _tea.editor.setOption('mode', _tea.$change.val());
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                changer: '.change-mode',
                enterMode: 'keep',
                indentUnit: 4,
                indentWithTabs: false,
                lineNumbers: true,
                lineWrapping: true,
                matchBrackets: true,
                mode: 'application/json',
                tabMode: 'shift',
                theme: 'monokai'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Tea_code($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_code = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on Tea_code');
            return false;
        }
    };
})(window.jQuery);
