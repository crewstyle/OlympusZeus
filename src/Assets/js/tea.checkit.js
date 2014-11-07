/* =====================================================
 * tea.checkit.js v1.0.1
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin adds a display comportment with checkbox
 * and radio buttons.
 * =====================================================
 * Example:
 *      $(':checkbox,:radio').tea_checkit({
 *          container: '.inside',                       //node containing all items to un/check
 *          closest: 'label',                           //closest node to item to add the selected class
 *          selected: 'selected'                        //selected class
 *      });
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_checkit = function ($el,options){
        //vars
        var _tea = this;
        _tea.$el = $el;
        _tea.options = options;

        //bind the change event
        _tea.$el.on('change', $.proxy(_tea.change, _tea));
    };

    Tea_checkit.prototype.$el = null;
    Tea_checkit.prototype.options = null;

    Tea_checkit.prototype.change = function (){
        var _tea = this;

        //vars
        var _ctn = _tea.options.container;
        var _clt = _tea.options.closest;
        var _sel = _tea.options.selected;

        //check type
        if ('radio' == _tea.$el.attr('type')) {
            _tea.$el.closest(_ctn).find('.' + _sel).removeClass(_sel);
            _tea.$el.closest(_clt).addClass(_sel);
        }
        else if ('checkbox' == _tea.$el.attr('type')) {
            if (_tea.$el.is(':checked')) {
                _tea.$el.closest(_clt).addClass(_sel);
            }
            else {
                _tea.$el.closest(_clt).removeClass(_sel);
            }
        }
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                container: '.inside',
                closest: 'label',
                selected: 'selected'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Tea_checkit($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_checkit = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on Tea_checkit');
            return false;
        }
    };
})(window.jQuery);
