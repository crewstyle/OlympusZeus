/*!
 * code.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds a complete integration with codemirror JS component:
 * http://codemirror.net/
 *
 * Example of JS:
 *      $('textarea.code').olCode({
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
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlCode = function ($el,options){
        //vars
        var _ol = this,
            _codemirror = CodeMirror;

        //check pickadate
        if (!CodeMirror) {
            return false;
        }

        //transform
        _ol.$el = $el;
        _ol.options = options;

        //readonly?
        _ol.options.readOnly = _ol.$el.attr('readonly') ? 'nocursor' : false;

        //codemirror
        _ol.editor = CodeMirror.fromTextArea(_ol.$el.get(0), _ol.options);
        _ol.$change = _ol.$el.closest(_ol.options.container).find(options.changer);

        //bind blur/focus event
        _ol.editor.on('blur', $.proxy(_ol.blurMode, _ol));
        _ol.editor.on('focus', $.proxy(_ol.focusMode, _ol));

        //bind on change event
        if (_ol.$change.length) {
            _ol.$change.on('change', $.proxy(_ol.changeMode, _ol));
        }
    };

    OlCode.prototype.$el = null;
    OlCode.prototype.$change = null;
    OlCode.prototype.editor = null;
    OlCode.prototype.options = null;

    OlCode.prototype.blurMode = function (e){
        var _ol = this;
        _ol.$el.parent().removeClass(_ol.options.focused);
    };

    OlCode.prototype.focusMode = function (e){
        var _ol = this;
        _ol.$el.parent().addClass(_ol.options.focused);
    };

    OlCode.prototype.changeMode = function (e){
        var _ol = this;
        _ol.editor.setOption('mode', _ol.$change.val());
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

                new OlCode($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olCode = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olCode');
            return false;
        }
    };
})(window.jQuery);
