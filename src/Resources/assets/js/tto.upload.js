/*!
 * tto.upload.js v1.1.0
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin make the WordPress medialib popin usable in all backoffice pages.
 *
 * Example of JS:
 *      $('.upload').tto_upload({
 *          addbutton: '.add-media',                    //add media button
 *          color: '#ffaaaa',                           //background color used when deleting a media
 *          container: '.container',                    //node element of main container
 *          delallbutton: '.del-all-medias',            //delete all medias button
 *          delbutton: '.del-media',                    //delete media button
 *          items: 'fieldset',                          //node elements of items
 *          source: '#template-id',                     //node script element in DOM containing handlebars JS temlpate
 *
 *          //Options usefull for WordPress medialib
 *          media: null,                                //media WordPress object used to open modal
 *          multiple: false,                            //define if user can have multiple selection in modal
 *          //target: null,                               //
 *          title: false,                               //title of the media popin
 *          type: 'image',                              //define the kind of items to display in modal
 *          wpid: null,                                 //contains Wordpress textarea ID
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

    var TTOUpload = function ($el,options){
        //vars
        var _tto = this;

        //check medialib: this plugin works ONLY with WordPress medialib
        if (!wp || !wp.media) {
            return;
        }

        _tto.$el = $el;
        _tto.options = options;

        //initialize
        _tto.init();
    };

    TTOUpload.prototype.$el = null;
    TTOUpload.prototype.media = null;
    TTOUpload.prototype.options = null;
    TTOUpload.prototype.selections = null;
    TTOUpload.prototype.source = null;

    TTOUpload.prototype.init = function (){
        var _tto = this;

        //get wp id
        _tto.options.wpid = wp.media.model.settings.post.id;

        //set the template
        _tto.source = $(_tto.options.source).html();

        //bind events on click
        _tto.$el.find(_tto.options.addbutton).on('click', $.proxy(_tto.open_medialib, _tto));
        _tto.$el.find(_tto.options.delbutton).on('click', $.proxy(_tto.remove_media, _tto));
        _tto.$el.find(_tto.options.delallbutton).on('click', $.proxy(_tto.remove_all, _tto));
    };

    TTOUpload.prototype.open_medialib = function (e){
        e.preventDefault();
        var _tto = this;

        //check if the medialib object has already been created
        if (_tto.media) {
            _tto.opened_medialib();
            _tto.media.open();
            return;
        }

        //create and open medialib
        _tto.media = wp.media.frames.file_frame = wp.media({
            library: {
                type: _tto.options.type,
            },
            multiple: _tto.options.multiple,
            title: _tto.options.title
        });

        //check selection
        _tto.opened_medialib();

        //bind event when medias are selected
        _tto.media.on('select', function() {
            //get all selected medias
            _tto.selections = _tto.media.state().get('selection');

            //JSONify and display them
            _tto._attach_items(_tto.selections.toJSON());

            //restore the main post ID
            wp.media.model.settings.post.id = _tto.options.wpid;
        });

        //open the modal
        _tto.media.open();
    };

    TTOUpload.prototype.opened_medialib = function ($items){
        var _tto = this;

        //bind event when medialib popin is opened
        _tto.media.on('open', function (){
            var $items = _tto.$el.find(_tto.options.items);

            //check selections
            if (!$items.length) {
                return;
            }

            //get selected items
            _tto.selections = _tto.media.state().get('selection');

            //get all selected medias on multiple choices
            $.each($items, function (){
                var _id = $(this).attr('data-u'),
                    _attach = wp.media.attachment(_id);

                _attach.fetch();
                _tto.selections.add(_attach ? [_attach] : []);
            });
        });
    };

    TTOUpload.prototype.remove_all = function (e){
        e.preventDefault();
        var _tto = this;

        //iterate on all
        _tto.$el.find(_tto.options.delbutton).click();
    };

    TTOUpload.prototype.remove_media = function (e){
        e.preventDefault();
        var _tto = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $item = $self.closest(_tto.options.items);

        //Deleting animation
        $item.css('background', _tto.options.color);
        $item.stop().animate({
            opacity: '0'
        }, 'slow', function (){
            $item.remove();
        });
    };

    TTOUpload.prototype._attach_items = function (_attach){
        var _tto = this;

        //check attachments
        if (!_attach.length) {
            return;
        }

        //get container
        var $target = _tto.$el.find(_tto.options.container);

        //iterate
        $.each(_attach, function (ind,elm){
            //check if element already exists
            if ($target.find(_tto.options.items + '[data-u="' + elm.id + '"]').length) {
                return;
            }
            //in single case, remove the other media
            else if (!_tto.options.multiple) {
                _tto.$el.find(_tto.options.delbutton).click();
            }

            //build JSON values
            var resp = {
                alt: elm.alt,
                caption: elm.caption,
                display: 'image' != _tto.options.type ? elm.icon : (elm.sizes.thumbnail ? elm.sizes.thumbnail.url : elm.url),
                icon: elm.icon,
                height: elm.height,
                id: elm.id,
                name: elm.name,
                sizes: [],
                url: elm.url,
                width: elm.width,
            };

            //check sizes
            $.each(elm.sizes, function (idx,el){
                resp.sizes.push({
                    height: el.height,
                    id: elm.id,
                    key: idx,
                    name: el.name,
                    url: el.url,
                    width: el.width,
                });
            });

            //update modal content and add binding event
            var template = Handlebars.compile(_tto.source);
            var upload = template(resp);

            //append all to target
            $target.append(upload);

            //Tootltip
            var $ups = $target.find(_tto.options.items).last();
            $ups.find('.tea-to-tooltip').tto_tooltip();
            $ups.find(_tto.options.delbutton).on('click', $.proxy(_tto.remove_media, _tto));
        });
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                addbutton: '.add-media',
                color: '#ffaaaa',
                container: '.container',
                delallbutton: '.del-all-medias',
                delbutton: '.del-media',
                items: 'fieldset',
                source: '#template-id',

                //options usefull for WordPress medialib
                media: null,
                multiple: false,
                //target: null,
                title: false,
                type: 'image',
                wpid: null,
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new TTOUpload($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_upload = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_upload');
            return false;
        }
    };
})(window.jQuery);
