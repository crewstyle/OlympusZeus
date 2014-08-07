/* ===================================================
 * tea.upload.js v1.0.0
 * https://github.com/Takeatea/tea_theme_options
 * ===================================================
 * Copyright 2014 Take a Tea (http://takeatea.com)
 * ===================================================
 * Example:
 *      $('.tea-inside.upload').tea_upload({
 *          wpid: null,                         //Contains Wordpress textarea ID
 *          media: wp.media,                    //Contains Wordpress media element
 *          multiple: false,                    //Define if user can have multiple selection in modal
 *          title: 'Media',                     //Title of the media popin
 *          type: 'image'                       //Define the kind of items to display in modal
 *      });
 * =================================================== */

(function ($){
    "use strict";

    var tea_upload = function ($el,options){
        var _tea = this;
        _tea.$el = $el;
        _tea.options = options;

        //get wp id
        _tea.options.wpid = _tea.options.media.model.settings.post.id;

        //bind event on click
        if (_tea.options.multiple) {
            _tea.$el.find('a.add_image').on('click', $.proxy(_tea.add_multiple_images, _tea));
            _tea.$el.find('a.del_image').on('click', $.proxy(_tea.del_multiple_images, _tea));
            _tea.$el.parent('div').find('a.del_all_images').on('click', $.proxy(_tea.del_all_images, _tea));
        }
        else {
            _tea.$el.find('a.add_image').on('click', $.proxy(_tea.add_single_image, _tea));
            _tea.$el.find('a.del_image').on('click', $.proxy(_tea.del_single_image, _tea));
        }
    };

    tea_upload.prototype.$el = null;
    tea_upload.prototype.options = null;

    tea_upload.prototype.add_single_image = function (e){
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
                type: _tea.options.type
            },
            multiple: false
        });

        //bind event when medialib popin is opened
        file_frame.on('open', function (){
            //Check if there are results
            if (!$result.length) {
                return;
            }

            //get selected items
            var _selection = file_frame.state().get('selection');
        });

        //bind event when an media is selected
        file_frame.on('select', function (evt) {
            var _selection = file_frame.state().get('selection');

            //Delete all HTML in result block
            $result.find('figure').remove();

            //Get the first selection
            var _attachment = _selection.first().toJSON();

            //Display it
            var $fig = $(document.createElement('figure'));
            var $del = $(document.createElement('a')).addClass('del_image').attr({
                href: '#',
                'data-target': _idtarget
            }).html('&times;');
            var $img = $(document.createElement('img')).attr({
                src: _attachment.url,
                id: _attachment.id,
                alt: ''
            });
            $fig.append($img);
            $fig.append($del);
            $fig.insertBefore($result.find('div.upload-time'));

            //Add click event
            $del.on('click', $.proxy(_tea.del_single_image, _tea));

            //Put result in hidden input
            $('#' + _idtarget).val('' + _attachment.url);
            $parent.addClass('item-added');

            //Restore the main post ID and delete file_frame
            _wp.model.settings.post.id = _tea.options.wpid;
            delete this;
        });

        //Open the modal
        file_frame.open();
    };

    tea_upload.prototype.add_multiple_images = function (e){
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
                type: _tea.options.type
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
            $.each($result.find('img'), function (){
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
            var _hidden = '';
            var $li = null,
                $img = null,
                $del = null;

            //Iterates on them
            for (var i = 0, len = _attachments.length; i < len; i++) {
                if ($result.find('li[data-id="' + _attachments[i].id + '"]').length) {
                    continue;
                }

                _hidden += _attachments[i].id + ';' + _attachments[i].url + '||';

                //Built item
                $li = $(document.createElement('li')).attr('data-id', _attachments[i].id);
                $img = $(document.createElement('img')).attr({
                    src: _attachments[i].url,
                    id: _attachments[i].id
                });
                $del = $(document.createElement('a')).addClass('del_image').attr({
                    href: '#',
                    'data-target': _idtarget
                }).html('&times;');

                //Add click event
                $del.on('click', $.proxy(_tea.del_multiple_images, _tea));

                //Display item
                $li.append($img);
                $li.append($del);
                $li.insertBefore($result.find('li.upload-time'));
            }

            //Put result in hidden input
            $('#' + _idtarget).val('' + _hidden);

            //Restore the main post ID and delete file_frame
            _wp.model.settings.post.id = _tea.options.wpid;
            delete this;
        });

        //Open the modal
        file_frame.open();
    };

    tea_upload.prototype.del_single_image = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $parent = $self.parent();
        var $upload = _tea.$el.find('.upload-time');
        var $hidden = $('#' + $self.attr('data-target'));

        //Delete value
        $hidden.val('NONE');

        //Deleting animation
        $parent.css('background', _tea.options.color);
        $parent.animate({
            opacity: '0'
        }, 'slow', function (){
            $parent.remove();
            $upload.removeClass('item-added');
        });
    };

    tea_upload.prototype.del_multiple_images = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $parent = $self.parent();
        var $hidden = $('#' + $self.attr('data-target'));

        //Delete value
        var todelete = $parent.find('img').attr('id') + ';' + $parent.find('img').attr('src') + '||';
        var newval = $hidden.val().replace(todelete, '');
        newval = '' == newval ? 'NONE' : newval;
        $hidden.val('' + newval);

        //Deleting animation
        $parent.css('background', _tea.options.color);
        $parent.animate({
            opacity: '0'
        }, 'slow', function (){
            $parent.remove();
        });
    };

    tea_upload.prototype.del_all_images = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $hidden = $('#' + $self.attr('data-target'));
        var $target = $('#' + $self.attr('data-target') + '_upload_content').find('.upload_image_result li:not(.upload-time)');

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
                multiple: false,
                color: '#ffaaaa',
                title: 'Title',
                type: 'image'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new tea_upload($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_upload = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_upload');
            return false;
        }
    };
})(window.jQuery);