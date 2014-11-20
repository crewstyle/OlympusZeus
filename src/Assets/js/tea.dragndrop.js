/* =====================================================
 * tea.dragndrop.js v1.0.0
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin adds a complete integration with Drag'n
 * drop JS component:
 * http://farhadi.ir/projects/html5sortable
 * =====================================================
 * Example:
 *      $('.movendrop').tea_dragndrop({
 *          handle: false,
 *          items: '.movendrop',
 *          reorder: {
 *              parent: '.uploads',
 *              element: '.upload-items',
 *              items: '.item',
 *          }
 *      });
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_dragndrop = function ($el,options){
        //vars
        var _tea = this,
            _sor = jQuery().sortable;

        //element
        _tea.$el = $el;
        _tea.options = options;

        //check Drag n drop plugin
        if (!_sor) {
            return;
        }

        //make the magic
        var _sort = $el.sortable(options);

        //bind event when its needed
        if (options.reorder) {
            _sort.bind('sortupdate', $.proxy(_tea.sortItems, _tea));
        }
    };

    Tea_dragndrop.prototype.$el = null;
    Tea_dragndrop.prototype.options = null;

    Tea_dragndrop.prototype.sortItems = function (e,i){
        var _tea = this;

        var $item = $(i.item);
        var $list = _tea.$el.closest(_tea.options.reorder.parent).find(_tea.options.reorder.element);
        var $targ = $list.find(_tea.options.reorder.items + '[data-id="' + $item.attr('data-id') + '"]');
        var _coun = $list.find(_tea.options.reorder.items).length;
        var _indx = $item.index();

        //reorder elements
        if (0 == _indx) {
            $targ.prependTo($list);
        }
        else if ((_coun - 1) == _indx) {
            $targ.appendTo($list);
        }
        else {
            $targ.insertBefore($list.find(_tea.options.reorder.items + ':eq(' + _indx + ')'));
        }

        //fix TinyMCE bug
        $item.click();
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                handle: false,
                items: '.movendrop',
                reorder: false
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Tea_dragndrop($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_dragndrop = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_dragndrop');
            return false;
        }
    };
})(window.jQuery);
