/* ===================================================
 * tea.checkit.js v1.0.0
 * https://github.com/Takeatea/tea_theme_options
 * ===================================================
 * Copyright 20xx Take a Tea, Inc.
 * ===================================================
 * Example:
 *      $('input[type="radio"],input[type="checkbox"]').tea_checkit({
 *          container: '.inside',
 *          closest: 'label',
 *          selected: 'selected'
 *      });
 * =================================================== */

(function ($){
    "use strict";

    var tea_checkit = function ($el,options){
        //Vars
        var _container = options.container;
        var _closest = options.closest;
        var _selected = options.selected;

        //Treat all elements
        $.each($el, function (){
            var $self = $(this);

            //Bind the change event
            $self.bind('change', function (){
                if ('radio' == $self.attr('type')) {
                    $self.closest(_container).find('.' + _selected).removeClass(_selected);
                    $self.closest(_closest).addClass(_selected);
                }
                else if ('checkbox' == $self.attr('type')) {
                    $self.is(':checked') ? $self.closest(_closest).addClass(_selected) : $self.closest(_closest).removeClass(_selected);
                }
            });
        });
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

                new tea_checkit($(this), settings);
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
            $.error('Method ' + method + ' does not exist on tea_checkit');
            return false;
        }
    };
})(window.jQuery);