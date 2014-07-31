/* ===================================================
 * tea.gallery.js v1.0.0
 * http://git.tools.takeatea.com/crewstyle/tea_theme_options
 * ===================================================
 * Copyright 20xx Take a Tea, Inc.
 * ===================================================
 * Example:
 *      $('.tea-inside.upload').tea_gallery({
 *          wpid: null,
 *          media: wp.media,
 *          title: $self.attr('data-title') || 'Media',
 *      });
 * =================================================== */

(function ($){
    "use strict";

    var tea_gallery = function ($el,options){
        var _tea = this;
        _tea.$el = $el;
        _tea.options = options;

        //get wp id
        _tea.options.wpid = _tea.options.media.model.settings.post.id;

        //bind event on click
        _tea.$el.find('a.add_item').on('click', $.proxy(_tea.add_item, _tea));
        _tea.$el.find('a.del_item').on('click', $.proxy(_tea.del_item, _tea));
        _tea.$el.parent('div').find('a.del_all_items').on('click', $.proxy(_tea.del_all_items, _tea));
    };

    tea_gallery.prototype.$el = null;
    tea_gallery.prototype.options = null;

    tea_gallery.prototype.add_item = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $parent = _tea.$el.find('.upload-time');
        var $result = _tea.$el.find('.upload_image_result');
        var $inside = _tea.$el;
        var _wp = _tea.options.media;
        var _idtarget = $parent.attr('data-target');

        //create the media frame.
        var file_frame = _wp.frames.file_frame = _wp({
            title: _tea.options.title,
            library: {
                type: 'image'
            },
            multiple: true
        });

        //bind event when medialib popin is opened
        file_frame.on('open', function (){
            //Check if there are results
            if (!$result.length) {
                return;
            }

            //get selected items
            var _selection = file_frame.state().get('selection');

            //get all selected medias on multiple choices
            $.each($result.find('.item'), function (){
                var attachment = _wp.attachment($(this).attr('data-id'));
                attachment.fetch();
                _selection.add(attachment ? [attachment] : []);
            });
        });

        //bind event when an media is selected
        file_frame.on('select', function (evt) {
            var _selection = file_frame.state().get('selection');

            //Get all selections
            var _attachments = _selection.toJSON();
            var _identity = '';
            var $itm = null,
                $in1 = null,
                $in2 = null,
                $img = null,
                $txt = null,
                $del = null;

            //tinyMCE initialization
            /*tinyMCE.init({
                selector:'textarea',
                language: 'wp-langs-en',
                mode : 'teeny',
                theme_advanced_buttons1 : "bold,italic,strikethrough,bullist,numlist,blockquote,justifyleft,justifycenter,justifyright,link,unlink,close,|,youtube",
                theme_advanced_buttons2 : "formatselect,underline,justifyfull,forecolor",
                theme_advanced_buttons3 : ""
            });*/

            //Iterates on them
            for (var i = 0, len = _attachments.length; i < len; i++) {
                if ($result.find('.item[data-id="' + _attachments[i].id + '"]').length) {
                    continue;
                }

                _identity = _idtarget + '_' + _attachments[i].id + '_' + Math.floor(Math.random()*99999999);

                //Built item
                $itm = $(document.createElement('div')).addClass('item').attr('data-id', _attachments[i].id);
                $del = $(document.createElement('a')).addClass('del_image').attr({
                    href: '#',
                    'data-target': _idtarget
                }).html('&times;');
                $in1 = $(document.createElement('input')).attr({
                    type: 'hidden',
                    name: _idtarget + '[' + _attachments[i].id + '][0]',
                    value: _attachments[i].url
                });
                $in2 = $(document.createElement('input')).attr({
                    type: 'hidden',
                    name: _idtarget + '[' + _attachments[i].id + '][1]',
                    value: _attachments[i].id
                });
                $img = $(document.createElement('img')).attr({
                    src: _attachments[i].url,
                    id: _attachments[i].id
                });
                $txt = $(document.createElement('div')).addClass('gallery-editor').html('<textarea rows="4" class="wp-editor-area" name="' + _idtarget + '[' + _attachments[i].id + '][2]' + '" id="' + _identity + '"></textarea>');

                //Add click event
                $del.on('click', $.proxy(_tea.del_item, _tea));

                //Display item
                $itm.append($del);
                $itm.append($in1);
                $itm.append($in2);
                $itm.append($img);
                $itm.append($txt);
                $itm.insertBefore($result.find('.upload-time'));

                //Init tinyMCE textarea
                //tinyMCE.execCommand('mceAddControl', false, _identity);
            }

            //Restore the main post ID and delete file_frame
            _wp.model.settings.post.id = _tea.options.wpid;
            delete this;
        });

        //Open the modal
        file_frame.open();
    };

    tea_gallery.prototype.del_item = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $parent = $self.parent();
        var $upload = _tea.$el.find('.upload-time');

        //Delete value
        $upload.removeClass('item-added');

        //Deleting animation
        $parent.css('background', _tea.options.color);
        $parent.animate({
            opacity: '0'
        }, 'slow', function (){
            $parent.remove();
        });

        //update data
        if (!_tea.$el.find('.upload_image_result .item').length) {
            $('#' + $self.attr('data-target')).val('NONE');
        }
    };

    tea_gallery.prototype.del_all_items = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $hidden = $('#' + $self.attr('data-target'));
        var $target = $('#' + $self.attr('data-target') + '_gallery_content').find('.upload_image_result .item');

        //update data
        $hidden.val('NONE');

        //Deleting animation
        $.each($target, function (){
            var $that = $(this);

            //Animate and delete item
            $that.css('background', _tea.options.color);
            $that.animate({
                opacity: '0'
            }, 'slow', function (){
                $that.remove();
            });
        });
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                wpid: null,
                media: null,
                target: null,
                color: '#ffaaaa',
                title: 'Title'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new tea_gallery($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_gallery = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_gallery');
            return false;
        }
    };
})(window.jQuery);