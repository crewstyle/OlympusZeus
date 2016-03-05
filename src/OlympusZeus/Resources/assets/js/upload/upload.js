/*!
 * upload.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin make the WordPress medialib popin usable in all backoffice pages.
 *
 * Example of JS:
 *      $('.upload').olUpload({
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
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlUpload = function ($el,options){
        //vars
        var _ol = this;

        //check medialib: this plugin works ONLY with WordPress medialib
        if (!wp || !wp.media) {
            return;
        }

        _ol.$el = $el;
        _ol.options = options;

        //initialize
        _ol.init();
    };

    OlUpload.prototype.$el = null;
    OlUpload.prototype.media = null;
    OlUpload.prototype.options = null;
    OlUpload.prototype.selections = null;
    OlUpload.prototype.source = null;

    OlUpload.prototype.init = function (){
        var _ol = this;

        //get wp id
        _ol.options.wpid = wp.media.model.settings.post.id;

        //set the template
        _ol.source = $(_ol.options.source).html();

        //bind events on click
        _ol.$el.find(_ol.options.addbutton).on('click', $.proxy(_ol.open_medialib, _ol));
        _ol.$el.find(_ol.options.delbutton).on('click', $.proxy(_ol.remove_media, _ol));
        _ol.$el.find(_ol.options.delallbutton).on('click', $.proxy(_ol.remove_all, _ol));
    };

    OlUpload.prototype.open_medialib = function (e){
        e.preventDefault();
        var _ol = this;

        //check if the medialib object has already been created
        if (_ol.media) {
            _ol.opened_medialib();
            _ol.media.open();
            return;
        }

        //create and open medialib
        _ol.media = wp.media.frames.file_frame = wp.media({
            library: {
                type: _ol.options.type,
            },
            multiple: _ol.options.multiple,
            title: _ol.options.title
        });

        //check selection
        _ol.opened_medialib();

        //bind event when medias are selected
        _ol.media.on('select', function() {
            //get all selected medias
            _ol.selections = _ol.media.state().get('selection');

            //JSONify and display them
            _ol._attach_items(_ol.selections.toJSON());

            //restore the main post ID
            wp.media.model.settings.post.id = _ol.options.wpid;
        });

        //open the modal
        _ol.media.open();
    };

    OlUpload.prototype.opened_medialib = function ($items){
        var _ol = this;

        //bind event when medialib popin is opened
        _ol.media.on('open', function (){
            var $items = _ol.$el.find(_ol.options.items);

            //check selections
            if (!$items.length) {
                return;
            }

            //get selected items
            _ol.selections = _ol.media.state().get('selection');

            //get all selected medias on multiple choices
            $.each($items, function (){
                var _id = $(this).attr('data-u'),
                    _attach = wp.media.attachment(_id);

                _attach.fetch();
                _ol.selections.add(_attach ? [_attach] : []);
            });
        });
    };

    OlUpload.prototype.remove_all = function (e){
        e.preventDefault();
        var _ol = this;

        //iterate on all
        _ol.$el.find(_ol.options.delbutton).click();
    };

    OlUpload.prototype.remove_media = function (e){
        e.preventDefault();
        var _ol = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $item = $self.closest(_ol.options.items);

        //Deleting animation
        $item.css('background', _ol.options.color);
        $item.stop().animate({
            opacity: '0'
        }, 'slow', function (){
            $item.remove();
        });
    };

    OlUpload.prototype._attach_items = function (_attach){
        var _ol = this;

        //check attachments
        if (!_attach.length) {
            return;
        }

        //get container
        var $target = _ol.$el.find(_ol.options.container);

        //iterate
        $.each(_attach, function (ind,elm){
            //check if element already exists
            if ($target.find(_ol.options.items + '[data-u="' + elm.id + '"]').length) {
                return;
            }
            //in single case, remove the other media
            else if (!_ol.options.multiple) {
                _ol.$el.find(_ol.options.delbutton).click();
            }

            //build JSON values
            var resp = {
                alt: elm.alt,
                caption: elm.caption,
                display: 'image' != _ol.options.type ? elm.icon : (elm.sizes.thumbnail ? elm.sizes.thumbnail.url : elm.url),
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
            var template = Handlebars.compile(_ol.source);
            var upload = template(resp);

            //append all to target
            $target.append(upload);

            //Tootltip
            var $ups = $target.find(_ol.options.items).last();
            $ups.find('.olz-tooltip').olTooltip();
            $ups.find(_ol.options.delbutton).on('click', $.proxy(_ol.remove_media, _ol));
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

                new OlUpload($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olUpload = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olUpload');
            return false;
        }
    };
})(window.jQuery);
