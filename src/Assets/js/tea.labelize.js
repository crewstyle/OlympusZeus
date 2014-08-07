/* ===================================================
 * tea.labelize.js v1.0.0
 * https://github.com/Takeatea/tea_theme_options
 * ===================================================
 * Copyright 20xx Take a Tea, Inc.
 * ===================================================
 * Example:
 *      $('.label-edit-options .label-button').tea_labelize({
 *          parent: '.label-edit-options',
 *          count: '.label-option',
 *          model: '.label-model'
 *      });
 * =================================================== */

(function ($){
    "use strict";

    var tea_labelize = function ($el,options){
        //Vars
        var _parent = options.parent;
        var _count = options.count;
        var _model = options.model;

        $el.on('click', function (e){
            e.preventDefault();

            //Get infos
            var $parent = $el.closest(_parent);

            //Transform content
            var count = $parent.find('> ' + _count).length;
            var model = $parent.find('> ' + _model).html();
            model = model.replace(/__OPTNUM__/g, count);

            //Repeat sequence
            $el.before(model);
        });
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

                new tea_labelize($(this), settings);
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
            $.error('Method ' + method + ' does not exist on tea_labelize');
            return false;
        }
    };
})(window.jQuery);