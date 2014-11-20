/* =====================================================
 * tea.upload.js v1.1.0
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin make the medialib popin usable in all
 * backoffice pages.
 * =====================================================
 * Example:
 *      $('.tea-inside.upload').tea_upload({
 *          wpid: null,                                 //Contains Wordpress textarea ID
 *          media: wp.media,                            //Contains Wordpress media element
 *          multiple: false,                            //Define if user can have multiple selection in modal
 *          title: 'Media',                             //Title of the media popin
 *          type: 'image'                               //Define the kind of items to display in modal
 *          add: 'add_image'                            //Define the add image button CSS class
 *          del: 'del_image'                            //Define the delete image button CSS class
 *          delall: 'del_all_images'                    //Define the delete all images button CSS class
 *      });
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_upload = function ($el,options){
        //vars
        var _tea = this;
        _tea.$el = $el;
        _tea.options = options;

        //check multiple
        if (false !== _tea.options.multiple) {
            _tea.options.multiple = _tea.$el.attr(options.multiple);
        }
        else {
            _tea.options.multiple = false;
        }

        //check title
        if (false !== _tea.options.title) {
            _tea.options.title = _tea.$el.attr(options.title);
        }
        else {
            _tea.options.title = 'Media';
        }

        //check type
        if (false !== _tea.options.type) {
            _tea.options.type = _tea.$el.attr(options.type);
        }
        else {
            _tea.options.type = 'image';
        }

        //initialize
        _tea.init();
    };

    Tea_upload.prototype.$el = null;
    Tea_upload.prototype.options = null;

    Tea_upload.prototype.init = function (){
        var _tea = this;

        //get wp id
        _tea.options.wpid = _tea.options.media.model.settings.post.id;

        //bind event on click
        if (_tea.options.multiple) {
            _tea.$el.find('.'+_tea.options.add).on('click', $.proxy(_tea.add_multiple, _tea));
            _tea.$el.find('.'+_tea.options.del).on('click', $.proxy(_tea.del_items, _tea));
            _tea.$el.parent('div').find('.'+_tea.options.delall).on('click', $.proxy(_tea.del_items_all, _tea));
        }
        else {
            _tea.$el.find('.'+_tea.options.add).on('click', $.proxy(_tea.add_single, _tea));
            _tea.$el.find('.'+_tea.options.del).on('click', $.proxy(_tea.del_items, _tea));
        }
    };

    Tea_upload.prototype.add_single = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $result = _tea.$el.find('.upload_image_result');
        var _wp = _tea.options.media,
            _selection = null;

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
            _selection = file_frame.state().get('selection');
        });

        //bind event when an media is selected
        file_frame.on('select', function (){
            _selection = file_frame.state().get('selection');

            //delete all HTML in result block
            $result.find('figure').remove();

            //get the first selection
            var _attach = _selection.first().toJSON();

            //display it
            _tea.createItems(_attach);

            //Restore the main post ID and delete file_frame
            _wp.model.settings.post.id = _tea.options.wpid;
            //delete this;
        });

        //Open the modal
        file_frame.open();
    };

    Tea_upload.prototype.add_multiple = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $result = _tea.$el.find('.upload_image_result');
        var _wp = _tea.options.media,
            _selection = null;

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
            _selection = file_frame.state().get('selection');

            //get all selected medias on multiple choices
            $.each($result.find('img'), function (){
                var attachment = _wp.attachment($(this).attr('data-id'));
                attachment.fetch();
                _selection.add(attachment ? [attachment] : []);
            });
        });

        //bind event when an media is selected
        file_frame.on('select', function () {
            _selection = file_frame.state().get('selection');

            //Get all selections
            var _attach = _selection.toJSON();

            //display it
            _tea.createItems(_attach);

            //restore the main post ID and delete file_frame
            _wp.model.settings.post.id = _tea.options.wpid;
            //delete this;
        });

        //open the modal
        file_frame.open();
    };

    Tea_upload.prototype.createItems = function (attach){
        var _tea = this;
        var $result = _tea.$el.find('.upload_image_result');
        var $parent = _tea.$el.find('.upload-time');
        var target = $parent.attr('data-target');

        //usefull vars
        var $itm = null,
            $idy = null,
            $url = null,
            $name = null,
            $elm = null,
            $del = null;

        //Single
        if (!_tea.options.multiple) {
            $itm = $(document.createElement('figure'));
            $idy = $(document.createElement('input')).attr({
                type: 'hidden',
                name: target + '[id]',
                value: attach.id
            });
            $url = $(document.createElement('input')).attr({
                type: 'hidden',
                name: target + '[url]',
                value: attach.url
            });
            $name = $(document.createElement('input')).attr({
                type: 'hidden',
                name: target + '[name]',
                value: attach.title
            });
            $del = $(document.createElement('a')).addClass(_tea.options.del).attr('href', '#').html('&times;');

            //check library
            if ('image' == _tea.options.type) {
                $elm = $(document.createElement('img')).attr({
                    src: attach.url,
                    id: attach.id,
                    alt: ''
                }).addClass('image');
            }
            else if ('video' == _tea.options.type) {
                $elm = $('<video src="' + attach.url + '" controls></video><br/><small>' + attach.title + '</small>');
            }
            else {
                $elm = $('<img src="' + attach.icon + '" alt=""/><br/><small>' + attach.title + '</small>');
            }

            $itm.append($elm);
            $itm.append($idy);
            $itm.append($url);
            $itm.append($name);
            $itm.append($del);
            $itm.insertBefore($result.find('div.upload-time'), null);

            //add click event
            $del.on('click', $.proxy(_tea.del_items, _tea));

            //put result in hidden input
            $parent.addClass('item-added');
        }
        //Multiple
        else {
            //Iterates on them
            for (var i = 0, len = attach.length; i < len; i++) {
                //check if item already exists
                if ($result.find('li[data-id="' + target + '__' + attach[i].id + '"]').length) {
                    continue;
                }

                //built item
                $itm = $(document.createElement('li')).attr('data-id', target + '__' + attach[i].id).addClass('draggable');

                //check library
                if ('image' == _tea.options.type) {
                    $elm = $(document.createElement('img')).attr({
                        src: attach[i].url,
                        alt: ''
                    }).addClass('image');
                }
                else if ('video' == _tea.options.type) {
                    $elm = $('<video src="' + attach[i].url + '" controls></video><br/><small>' + attach[i].title + '</small>');
                }
                else {
                    $elm = $('<img src="' + attach[i].icon + '" alt=""/><br/><small>' + attach[i].title + '</small>');
                }

                //create hid element
                $idy = $(document.createElement('input')).attr({
                    type: 'hidden',
                    name: target + '[' + attach[i].id + '][id]',
                    value: attach[i].id
                });
                $url = $(document.createElement('input')).attr({
                    type: 'hidden',
                    name: target + '[' + attach[i].id + '][url]',
                    value: attach[i].url
                });
                $name = $(document.createElement('input')).attr({
                    type: 'hidden',
                    name: target + '[' + attach[i].id + '][name]',
                    value: attach[i].title
                });

                //create del element
                $del = $(document.createElement('a')).addClass(_tea.options.del).attr('href', '#').html('&times;');

                //add click event on delete
                $del.on('click', $.proxy(_tea.del_items, _tea));

                //display item
                $itm.append($elm);
                $itm.append($idy);
                $itm.append($url);
                $itm.append($name);
                $itm.append($del);
                $itm.insertBefore($result.find('li.upload-time'));
            }
        }
    };

    Tea_upload.prototype.del_items = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $parent = $self.parent();

        //Deleting animation
        $parent.css('background', _tea.options.color);
        $parent.animate({
            opacity: '0'
        }, 'slow', function (){
            $parent.remove();

            if (!_tea.options.multiple) {
                _tea.$el.find('.upload-time').removeClass('item-added');
            }
        });
    };

    Tea_upload.prototype.del_items_all = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $target = $('#' + $self.attr('data-target') + '_upload_content').find('.upload_image_result li:not(.upload-time)');

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
                title: false,
                type: false,
                color: '#ffaaaa',
                add: 'add_image',
                del: 'del_image',
                delall: 'del_all_images'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Tea_upload($(this), settings);
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
