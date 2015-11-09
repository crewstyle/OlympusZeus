/*!
 * tto.code.js v1.0.1
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds a complete integration with codemirror JS component:
 * http://codemirror.net/
 *
 * Example of JS:
 *      $('textarea.code').tto_code({
 *          changer: '.change-mode',                    //node containing a list of all enabled modes
 *          container: '.container',                    //node containing all dom elements
 *          focused: 'focused',                         //selected class
 *          theme: 'monokai',                           //only Monokai theme is enabled
 *
 *          //The following options are described here: https://codemirror.net/doc/manual.html#config
 *          enterMode: 'keep',
 *          indentUnit: 4,
 *          indentWithTabs: false,
 *          lineNumbers: true,
 *          lineWrapping: true,
 *          matchBrackets: true,
 *          mode: 'application/json',
 *          tabMode: 'shift'
 *      });
 *
 * Example of HTML:
 *      <fieldset class="container">
 *          <select class="change-mode">
 *              <option value="text/css">CSS</option>
 *              <option value="application/json" selected="selected">JSON</option>
 *              <option value="text/x-python">Python</option>
 *              <option value="text/x-sh">Shell</option>
 *              <option value="application/xml">XML</option>
 *          </select>
 *          <textarea class="code"></textarea>
 *      </fieldset>
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOCode = function ($el,options){
        //vars
        var _tto = this,
            _codemirror = CodeMirror;

        //check pickadate
        if (!CodeMirror) {
            return false;
        }

        //transform
        _tto.$el = $el;
        _tto.options = options;

        //readonly?
        _tto.options.readOnly = _tto.$el.attr('readonly') ? 'nocursor' : false;

        //codemirror
        _tto.editor = CodeMirror.fromTextArea(_tto.$el.get(0), _tto.options);
        _tto.$change = _tto.$el.closest(_tto.options.container).find(options.changer);

        //bind blur/focus event
        _tto.editor.on('blur', $.proxy(_tto.blurMode, _tto));
        _tto.editor.on('focus', $.proxy(_tto.focusMode, _tto));

        //bind on change event
        if (_tto.$change.length) {
            _tto.$change.on('change', $.proxy(_tto.changeMode, _tto));
        }
    };

    TTOCode.prototype.$el = null;
    TTOCode.prototype.$change = null;
    TTOCode.prototype.editor = null;
    TTOCode.prototype.options = null;

    TTOCode.prototype.blurMode = function (e){
        var _tto = this;
        _tto.$el.parent().removeClass(_tto.options.focused);
    };

    TTOCode.prototype.focusMode = function (e){
        var _tto = this;
        _tto.$el.parent().addClass(_tto.options.focused);
    };

    TTOCode.prototype.changeMode = function (e){
        var _tto = this;
        _tto.editor.setOption('mode', _tto.$change.val());
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                changer: '.change-mode',
                container: '.container',
                focused: 'focused',
                theme: 'monokai',

                //find more explanations here: https://codemirror.net/doc/manual.html#config
                enterMode: 'keep',
                indentUnit: 4,
                indentWithTabs: false,
                lineNumbers: true,
                lineWrapping: true,
                matchBrackets: true,
                mode: 'application/json',
                tabMode: 'shift'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new TTOCode($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_code = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_code');
            return false;
        }
    };
})(window.jQuery);
