/* =====================================================
 * tea.labelize.js v1.0.1
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * In the plugin version, all fields are dynamically
 * created from the TeaTO interface. This plugin is
 * usefull in this case especially.
 * =====================================================
 * Example:
 *      $('.label-button').tea_labelize({
 *          parent: '.label-edit-options',              //coming soon...
 *          count: '.label-option',                     //coming soon...
 *          model: '.label-model'                       //coming soon...
 *      });
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_labelize = function ($el,options){
        //vars
        var _tea = this;
        _tea.$el = $el;
        _tea.options = options;

        //bind click event
        _tea.$el.on('click', $.proxy(_tea.labelize, _tea));
    };

    Tea_labelize.prototype.$el = null;
    Tea_labelize.prototype.options = null;

    Tea_labelize.prototype.labelize = function (e){
        e.preventDefault();
        var _tea = this;

        //get infos
        var $parent = _tea.$el.closest(_tea.options.parent);

        //transform content
        var count = $parent.find('> ' + _tea.options.count).length;
        var model = $parent.find('> ' + _tea.options.model).html();
        model = model.replace(/__OPTNUM__/g, count);

        //repeat sequence
        _tea.$el.before(model);
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                parent: '.label-edit-options',
                count: '.label-option',
                model: '.label-model'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Tea_labelize($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_labelize = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on Tea_labelize');
            return false;
        }
    };
})(window.jQuery);
