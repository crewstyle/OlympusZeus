/* =====================================================
 * tea.gallery.js v1.3.0
 * https://github.com/Takeatea/tea_theme_options
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin adds a complete upload integration with
 * TinyMCE compatibility. Each item have its own
 * contents.
 * =====================================================
 * Example:
 *      $('.tea-inside.upload').tea_gallery({
 *          wpid: null,                                 //Contains Wordpress textarea ID
 *          media: wp.media,                            //Contains Wordpress media element
 *          color: '#ffaaaa',                           //Color defined for the delete animation
 *          title: 'Media',                             //Title of the media popin
 *          add: 'add_item',                            //Define the add image button CSS class
 *          del: 'del_item',                            //Define the delete image button CSS class
 *          delall: 'del_all_items'                     //Define the delete all images button CSS class
 *      });
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_gallery = function ($el,options){
        var _tea = this;
        _tea.$el = $el;
        _tea.options = options;

        //initialize
        _tea.init();
    };

    Tea_gallery.prototype.$el = null;
    Tea_gallery.prototype.options = null;
    Tea_gallery.prototype.current = '';

    Tea_gallery.prototype.init = function (){
        var _tea = this;
        var $items = _tea.$el.find('.upload-items .item');

        //get wp id
        _tea.options.wpid = _tea.options.media.model.settings.post.id;

        //bind event on click
        _tea.$el.find(_tea.options.add).on('click', $.proxy(_tea.add_item, _tea));
        _tea.$el.find(_tea.options.del).on('click', $.proxy(_tea.del_item, _tea));
        _tea.$el.parent('div').find(_tea.options.delall).on('click', $.proxy(_tea.del_items_all, _tea));

        //bind event on load
        if ($items.length) {
            //Get first child
            var $firsti = _tea.$el.find('.upload-items .item:first-child');
            var $firstl = _tea.$el.find('.upload-listing li:first-child');

            //Change selected
            $firsti.addClass('selected');
            $firstl.addClass('selected');

            //Add tinyMCE
            _tea.add_tinymce($firsti.attr('data-id'));

        }
    };

    Tea_gallery.prototype.add_item = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $parent  = _tea.$el.find('.upload-time');
        var $result  = _tea.$el.find('.upload-items');
        var $listing = _tea.$el.find('.upload-listing');
        var _wp = _tea.options.media,
            _id = $parent.attr('data-target');

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
        file_frame.on('select', function () {
            var _selection = file_frame.state().get('selection');

            //Get all selections
            var attach = _selection.toJSON();
            var idtity = '';
            var $itm = null,
                $lis = null,
                $in1 = null,
                $in2 = null,
                $lnk = null,
                $img = null,
                $txt = null,
                $del = null;

            //Iterates on them
            for (var i = 0, len = attach.length; i < len; i++) {
                if ($result.find('.item[data-id="' + attach[i].id + '"]').length) {
                    continue;
                }

                //id definition
                idtity = _id + '_' + attach[i].id + '_content';

                //Build item
                $itm = $(document.createElement('div')).addClass('item').attr('data-id', attach[i].id);
                $in1 = $(document.createElement('input')).attr({
                    type: 'hidden',
                    name: _id + '[' + attach[i].id + '][image]',
                    value: attach[i].url
                });
                $in2 = $(document.createElement('input')).attr({
                    type: 'hidden',
                    name: _id + '[' + attach[i].id + '][id]',
                    value: attach[i].id
                });
                $txt = $(document.createElement('div')).addClass('gallery-editor').html('<textarea id="' + idtity + '" rows="4" class="wp-editor-area" name="' + _id + '[' + attach[i].id + '][content]' + '"></textarea>');

                //Build list
                $lis = $(document.createElement('li')).attr('data-id', attach[i].id);
                $lnk = $(document.createElement('a')).attr('href', '#').addClass('itm');
                $img = $(document.createElement('img')).attr({
                    src: attach[i].url
                });
                $del = $(document.createElement('a')).addClass(_tea.options.del.replace('.', '')).attr({
                    href: '#',
                    'data-target': _id
                }).html('&times;');

                //Add click event
                $lis.on('click', $.proxy(_tea.change_item, _tea));
                $del.on('click', $.proxy(_tea.del_item, _tea));

                //Display item
                $itm.append($in1);
                $itm.append($in2);
                $itm.append($txt);
                $result.prepend($itm);

                //Display list
                $lnk.append($img);
                $lis.append($del);
                $lis.append($lnk);
                $listing.prepend($lis);

                //Init tinyMCE textarea
                /*if ('undefined' !== typeof tinyMCE) {
                    tinyMCE.init({
                        //language: 'wp-langs-en',
                        selector: 'textarea#' + idtity,
                        mode: 'teeny',
                        resize: 'vertical',
                        wpautop: false,
                        menubar: false,
                        toolbar1: 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv',
                        toolbar2: 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                        toolbar3: '',
                        toolbar4: '',
                        body_class: 'post-format-standard'
                    });
                }*/
            }

            //Open the first one
            $result.find('.item.selected').removeClass('selected');
            $result.find('.item:first-child').addClass('selected');
            $listing.find('li.selected').removeClass('selected');
            $listing.find('li:first-child').addClass('selected');

            //Add tinyMCE
            _tea.add_tinymce($result.find('.item.selected').attr('data-id'));

            //Restore the main post ID and delete file_frame
            _wp.model.settings.post.id = _tea.options.wpid;
        });

        //Open the modal
        file_frame.open();
    };

    Tea_gallery.prototype.add_tinymce = function (id){
        var _tea = this;

        //Init tinyMCE textarea
        if ('undefined' !== typeof tinyMCE) {
            var idtity = _tea.$el.find('.upload-time').attr('data-target') + '_' + id + '_content';

            //Remove to the old one
            if ('' != _tea.current) {
                tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, _tea.current);
            }

            //Init tinyMCE
            tinyMCE.init({
                //language: 'wp-langs-en',
                selector: 'textarea#' + idtity,
                mode: 'teeny',
                resize: 'vertical',
                wpautop: false,
                menubar: false,
                toolbar1: 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv',
                toolbar2: 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                toolbar3: '',
                toolbar4: '',
                body_class: 'post-format-standard'
            });

            //Add controls
            tinyMCE.EditorManager.execCommand('mceAddEditor', true, idtity);

            //Fix current
            _tea.current = idtity;
        }
    };

    Tea_gallery.prototype.change_item = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $lis = $self.closest('li');

        //Remove selected
        _tea.$el.find('.upload-listing li.selected').removeClass('selected');
        _tea.$el.find('.upload-items .item.selected').removeClass('selected');

        //Change selected
        _tea.$el.find('.upload-items .item[data-id="' + $lis.attr('data-id') + '"]').addClass('selected');
        $lis.addClass('selected');

        //Add tinyMCE
        _tea.add_tinymce($lis.attr('data-id'));
    };

    Tea_gallery.prototype.del_item = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $lis = $self.closest('li');
        var $itm = _tea.$el.find('.upload-items .item[data-id="' + $lis.attr('data-id') + '"]');

        //Deleting animation
        $lis.css('background', _tea.options.color);
        $lis.animate({
            opacity: '0'
        }, 'slow', function (){
            if ($lis.hasClass('selected')) {
                $lis.next().addClass('selected');
            }

            $lis.remove();
        });
        $itm.css('background', _tea.options.color);
        $itm.animate({
            opacity: '0'
        }, 'slow', function (){
            if ($itm.hasClass('selected')) {
                $itm.next().addClass('selected');
            }

            $itm.remove();
        });
    };

    Tea_gallery.prototype.del_items_all = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $result  = _tea.$el.find('.upload-items .item');
        var $listing = _tea.$el.find('.upload-listing li:not(.upload-time)');

        //Deleting animation
        $.each($result, function (){
            var $that = $(this);

            //Animate and delete item
            $that.css('background', _tea.options.color);
            $that.animate({
                opacity: '0'
            }, 'slow', function (){
                $that.remove();
            });
        });
        $.each($listing, function (){
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
                title: 'Title',
                add: '.add_item',
                del: '.del_item',
                delall: '.del_all_items'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Tea_gallery($(this), settings);
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
            $.error('Method ' + method + ' does not exist on Tea_gallery');
            return false;
        }
    };
})(window.jQuery);
