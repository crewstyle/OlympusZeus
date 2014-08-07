/* ===================================================
 * tea.range.js v1.0.0
 * https://github.com/Takeatea/tea_theme_options
 * ===================================================
 * Copyright 20xx Take a Tea, Inc.
 * ===================================================
 * Example:
 *      $('input[type="range"]').tea_range();
 * =================================================== */

(function ($){
    "use strict";

    var tea_range = function ($el){
        //Vars

        //Treat all elements
        $.each($el, function (){
            var $self = $(this);
            var $output = $(document.createElement('<output></output>'));
            $output.insertAfter($self);

            //Bind the change event
            $self.bind('change', function (){
                $output.text($self.val());
            });
        });
    };

    var methods = {
        init: function (){
            if (!this.length) {
                return false;
            }

            return this.each(function (){
                new tea_range($(this));
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_range = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_range');
            return false;
        }
    };
})(window.jQuery);