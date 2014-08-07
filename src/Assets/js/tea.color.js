/* ===================================================
 * tea.color.js v1.0.0
 * https://github.com/Takeatea/tea_theme_options
 * ===================================================
 * Copyright 20xx Take a Tea, Inc.
 * ===================================================
 * Example:
 *      $('.colorpicker').tea_color();
 * =================================================== */

(function ($){
    "use strict";

    var tea_color = function ($el){
        //Vars
        var _wpcolor = jQuery().wpColorPicker ? true : false;

        //Treat all elements
        $.each($el, function (){
            var $self = $(this);

            //Wordpress version < 3.5
            if (!_wpcolor) {
                //Use functions plugin
                var _id = $self.attr('id');
                var $farb = $(document.createElement('div')).attr('id', _id + '_farb');
                $farb.insertAfter($self);
                $farb.farbtastic('#' + _id);
            }
            //Wordpress version >= 3.5
            else {
                //Use functions plugin
                $self.wpColorPicker({
                    change: function (){
                        $self.val($self.wpColorPicker('color'));
                    },
                    clear: function (){
                        $self.val('NONE');
                    }
                });
            }
        });
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new tea_color($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_color = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_color');
            return false;
        }
    };
})(window.jQuery);