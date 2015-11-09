/*!
 * tto.dragndrop.js v1.0.0
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds a complete integration with Drag'n drop JS WordPress component.
 *
 * Example of JS:
 *      $('.dragndrop').tto_dragndrop({
 *          handle: false,                              //create sortable lists with handles
 *          items: '.dragndrop',                        //specifiy which items inside the element should be sortable
 *          reorder: {
 *              parent: '.uploads',
 *              element: '.upload-items',
 *              items: '.item',
 *          }
 *      });
 *
 * Example of HTML:
 *      --
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTODragNDrop = function ($el,options){
        //vars
        var _tto = this,
            _sor = jQuery().sortable;

        //element
        _tto.$el = $el;
        _tto.options = options;

        //check Drag n drop plugin
        if (!_sor) {
            return;
        }

        //make the magic
        var _sort = $el.sortable(options);

        //bind event when its needed
        if (options.reorder) {
            _sort.bind('sortupdate', $.proxy(_tto.sortItems, _tto));
        }
    };

    TTODragNDrop.prototype.$el = null;
    TTODragNDrop.prototype.options = null;

    TTODragNDrop.prototype.sortItems = function (e,i){
        var _tto = this;

        var $item = $(i.item),
            $list = _tto.$el.closest(_tto.options.reorder.parent).find(_tto.options.reorder.element),
            $targ = $list.find(_tto.options.reorder.items + '[data-id="' + $item.attr('data-id') + '"]'),
            _coun = $list.find(_tto.options.reorder.items).length,
            _indx = $item.index();

        //reorder elements
        if (0 == _indx) {
            $targ.prependTo($list);
        }
        else if ((_coun - 1) == _indx) {
            $targ.appendTo($list);
        }
        else {
            $targ.insertBefore($list.find(_tto.options.reorder.items + ':eq(' + _indx + ')'));
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

                new TTODragNDrop($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_dragndrop = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_dragndrop');
            return false;
        }
    };
})(window.jQuery);
