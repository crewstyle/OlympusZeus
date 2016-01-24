/*!
 * dragndrop.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds a complete integration with Drag'n drop JS WordPress component.
 *
 * Example of JS:
 *      $('.dragndrop').olDragndrop({
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
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlDragNDrop = function ($el,options){
        //vars
        var _ol = this,
            _sor = jQuery().sortable;

        //element
        _ol.$el = $el;
        _ol.options = options;

        //check Drag n drop plugin
        if (!_sor) {
            return;
        }

        //make the magic
        var _sort = $el.sortable(options);

        //bind event when its needed
        if (options.reorder) {
            _sort.bind('sortupdate', $.proxy(_ol.sortItems, _ol));
        }
    };

    OlDragNDrop.prototype.$el = null;
    OlDragNDrop.prototype.options = null;

    OlDragNDrop.prototype.sortItems = function (e,i){
        var _ol = this;

        var $item = $(i.item),
            $list = _ol.$el.closest(_ol.options.reorder.parent).find(_ol.options.reorder.element),
            $targ = $list.find(_ol.options.reorder.items + '[data-id="' + $item.attr('data-id') + '"]'),
            _coun = $list.find(_ol.options.reorder.items).length,
            _indx = $item.index();

        //reorder elements
        if (0 === _indx) {
            $targ.prependTo($list);
        }
        else if ((_coun - 1) == _indx) {
            $targ.appendTo($list);
        }
        else {
            $targ.insertBefore($list.find(_ol.options.reorder.items + ':eq(' + _indx + ')'));
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

                new OlDragNDrop($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olDragndrop = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olDragndrop');
            return false;
        }
    };
})(window.jQuery);
