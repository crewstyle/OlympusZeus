/*
 * jQuery Tea Theme Options
 *
 * Copyright 2013 Take a Tea (http://takeatea.com)
 * Dual licensed under the MIT or GPL Version 2 licenses
 *
*/
;(function ($){
    $(document).ready(function (){
        //Usefull vars
        var file_frame;
        var _wpcolor = jQuery().wpColorPicker ? true : false;
        var _defcolor = '000000';
        var _delcolor = 'ffaaaa';

        //Checkbox & Image input
        $.each($('.inside input[type="checkbox"]'), function (){
            var $self = $(this);

            //Bind the change event
            $self.bind('change', function (){
                $self.is(':checked') ? $self.closest('label').addClass('selected') : $self.closest('label').removeClass('selected');
            });
        });

        //Checkbox check all
        $.each($('.checkboxes .checkall input[type="checkbox"]'), function (){
            var $self = $(this);
            var $checks = $self.closest('.checkboxes').find('.inside input[type="checkbox"]');

            //Bind the change event
            $self.bind('change', function (){
                $checks.attr('checked', $self.is(':checked'));
                $self.is(':checked') ? $checks.closest('label').addClass('selected') : $checks.closest('label').removeClass('selected');
            });
        });

        //Checkbox add labels
        $('.label-edit-options .label-button').live('click', function (e){
            e.preventDefault();

            //Get infos
            var $self = $(this);
            var $parent = $self.closest('.label-edit-options');

            //Transform content
            var _count = $parent.find('> .label-option').length;
            var _model = $parent.find('> .label-model').html();
            _model = _model.replace(/__OPTNUM__/g, _count);

            //Repeat sequence
            $self.before(_model);
        });

        //Color input
        $.each($('.inside .color-picker'), function (){
            var $self = $(this);

            //Wordpress version < 3.5
            if (!_wpcolor) {
                //Use functions plugin
                var _id = $self.attr('id');
                $farb = $(document.createElement('div')).attr('id', _id + '_farb');
                $farb.insertAfter($self);
                $farb.farbtastic('#' + _id);
            }
            //Wordpress version >= 3.5
            else {
                //Use functions plugin
                $self.wpColorPicker({
                    change: function (event,ui){
                        $self.val($self.wpColorPicker('color'));
                    },
                    clear: function() {
                        $self.val('NONE');
                    }
                });
            }
        });

        //Connection input
        $.each($('.tea_to .tea-screen-meta'), function (){
            var $self = $(this);
            var $links = $self.find('.contextual-help-tabs');
            var $blocks = $self.find('.contextual-help-tabs-wrap');

            $links.find('ul a').bind('click', function (e){
                e.preventDefault();
                var $this = $(this);
                var $target = $('' + $this.attr('href'));

                $links.find('ul li.active').removeClass('active');
                $blocks.find('> .active').removeClass('active');

                $this.closest('li').addClass('active');
                $target.addClass('active');
            });
        });

        //Dashboard input - Add content
        $.each($('.inside-dashboard .dashboard-add-content'), function (){
            var $self = $(this);
            var $select = $self.find('select.tea_add_content_type');
            var $button = $self.find('.button-secondary');
            var $target = $self.closest('.tea-post-body-content').find('.dashboard-contents-all');
            var _ajax = $self.attr('data-ajax');
            var _delete = $self.attr('data-delete');

            //Check the content type
            if (!$select.length)
            {
                return;
            }

            //Add index
            var _index = $target.find('> .dashboard-content').length || 0;

            //Bind the click event
            $self.find('input[type="submit"]').bind('click', function (e){
                e.preventDefault();

                //Check the content type
                if (!$select.find('option:selected').length || '' == $select.find('option:selected').val())
                {
                    return;
                }

                //Get the value
                var _value = $select.find('option:selected').val();

                //Make an Ajax call to get all field options
                $.ajax({
                    type: 'GET',
                    url: _ajax,
                    dataType: 'html',
                    cache: false,
                    data: {
                        action: $self.find('input.tea_add_action').val(),
                        nonce: $self.find('input.tea_add_nonce').val(),
                        content: _value,
                    },
                    statusCode: {
                        403: function (response){
                            alert('403');
                        },
                        500: function (response){
                            alert('500');
                        }
                    },
                    beforeSend: function (){
                        $button.attr('disabled', true);
                    },
                    success: function (response){
                        //Create our new element
                        var $content = $(document.createElement('div')).addClass('dashboard-content');
                        var $del = $(document.createElement('a')).attr('href', '#').addClass('delete').text(_delete);

                        //Treat html
                        var _html = response;
                        _html = _html.replace(/__NUM__/g, _index);
                        _index++;

                        //Display response, append it to container and enable button
                        $content.append($del);
                        $content.append(_html);
                        $target.append($content);

                        //Get defaults
                        $button.attr('disabled', false);
                        $select.val('');
                    }
                });
            });
        });

        //Dashboard input - Delete content
        $('.inside-dashboard .dashboard-contents-all .delete').live('click', function (e){
            e.preventDefault();
            var $self = $(this);
            var $parent = $self.closest('.dashboard-content');

            $parent.css('backgroundColor', '#'+_delcolor);
            $parent.animate({
                opacity: '0'
            }, 'low', function (){
                $parent.remove();
            });
        });

        //Dashboard input - Display options
        $('.inside-dashboard .select-options').live('change', function (e){
            e.preventDefault();
            var $self = $(this);
            var $target = $self.closest('.dashboard-content').find('.label-edit-options');

            //Check if we need options
            if ($self.find('option:selected').hasClass('display-options'))
            {
                $target.css('display', 'block');
            }
            else
            {
                $target.css('display', 'none');
            }
        });

        //Dashboard input - Edit page
        $.each($('.inside-dashboard .tea-edit-screen-link a'), function (){
            var $self = $(this);
            var $target = $self.closest('.tea-nav-aside').find('> ' + $self.attr('data-target'));
            var _class = 'screen-meta-active';
            var _isopened = $self.hasClass(_class) ? true : false;

            //Bind the click event
            $self.bind('click', function (e){
                e.preventDefault();

                //Close panel
                if (_isopened)
                {
                    $target.slideUp('fast', function (){
                        $self.removeClass(_class);
                        _isopened = false;
                    });
                }
                //Open panel
                else
                {
                    $target.slideDown('fast', function (){
                        $self.addClass(_class);
                        _isopened = true;
                    });
                }
            });
        });

        //Dashboard input - Page listing
        $.each($('.inside-dashboard .dashboard-page-listing li'), function (){
            var $self = $(this);
            var $link = $self.find('a');

            //Check if link
            if (!$link.length)
            {
                return;
            }

            //Get target
            var _href = $link.attr('href').replace('#', '');
            var $ul = $self.closest('ul');
            var $target = $self.closest('.nav-menus-php').find('.tea-menu-management-liquid');

            //Bind the click event
            $link.bind('click', function (e){
                e.preventDefault();

                //Update links
                $ul.find('li').removeClass('active');
                $self.addClass('active');

                //Display contents
                $target.find('.tea-dashoard-content').removeClass('open');
                $target.find('.tea-dashoard-content.' + _href).addClass('open');
            });
        });

        //Features input
        $.each($('.features-list li'), function (){
            var $self = $(this);
            var $link = $self.find('a');

            //Check if link
            if (!$link.length)
            {
                return;
            }

            //Get infos
            var $code = $self.find('pre');

            //Bind the click event
            $self.bind('click', function (e){
                e.preventDefault();
                e.stopPropagation();

                $self.teamodal({
                    title: $self.find('h4').text(),
                    content: [
                        {
                            label: $self.find('p').html(),
                            type: 'html',
                            code: $code.html()
                        }
                    ],
                    submitbutton: false
                });
            });
        });

        //Radio & Image input
        $.each($('.inside input[type="radio"]'), function (){
            var $self = $(this);

            //Bind the change event
            $self.bind('change', function (){
                $self.closest('.inside').find('.selected').removeClass('selected');
                $self.closest('label').addClass('selected');
            });
        });

        //Range input
        $.each($('.inside input[type="range"]'), function (){
            var $self = $(this);
            var $target = $('#' + this.id + '_output');

            $self.bind('change', function (){
                $target.text($self.val());
            });
        });

        //Upload input: Wordpress version < 3.5
        $.each($('.upload a.add_media.thickbox'), function (){
            var $self = $(this);
            var _id = $self.attr('data-editor');

            //Set the upload button ID
            $self.attr('id', '' + _id + this.id);

            //Bind the click event
            $self.bind('click', function (){
                formfield = _id;
                tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true&amp;post_id=0');
                return false;
            });

            //Override the default submit function to get the image details
            window.send_to_editor = function (html){
                //Get the image details
                imgurl = undefined == html.src ? html.href : html.src;
                $('#' + _id).val(imgurl);
                //Delete popin
                tb_remove();
            }
        });

        //Upload input: Wordpress version >= 3.5
        $.each($('.inside a.add_media:not(.thickbox)'), function (){
            var $self = $(this);

            //Check if parent has the "customized" css class from Tea TO
            if (!$self.parent().hasClass('customized')) {
                return false;
            }

            //Get parent and target jQuery elements
            var $parent = $self.closest('.customized');
            var $result = $parent.parent().find('.upload_image_result');
            var $target = $('#' + $parent.attr('data-target'));

            //Set default vars
            var _wpid = wp.media.model.settings.post.id;
            var _delete = $self.closest('.upload').attr('data-del');
            var _title = $parent.attr('data-title') || 'Media';
            var _multiple = $parent.attr('data-multiple') && '1' == $parent.attr('data-multiple') ? true : false;
            var _type = $parent.attr('data-type') || 'image';
            var _idtarget = $parent.attr('data-target');

            //Bind click event on button
            $self.bind('click', function (e){
                e.preventDefault();

                //Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = _wpid;

                //Create the media frame.
                var file_frame = wp.media.frames.file_frame = wp.media({
                    title: _title,
                    library: {
                        type: _type
                    },
                    /*frame: 'post', BUG: medialib popin does NOT send response if this option is enabled*/
                    multiple: _multiple
                });

                //Bind event when medialib popin is opened
                file_frame.on('open', function (){
                    //Check if there are results
                    if (!$result.length) {
                        return;
                    }

                    //Get selected items
                    var _selection = file_frame.state().get('selection');

                    //Get all selected medias on multiple choices
                    if (_multiple) {
                        $.each($result.find('img'), function (index,elem) {
                            attachment = wp.media.attachment(this.id);
                            attachment.fetch();
                            _selection.add(attachment ? [attachment] : []);
                        });
                    }
                });

                //Bind event when an media is selected
                file_frame.on('select', function (evt) {
                    var _selection = file_frame.state().get('selection');

                    //Check if jQuery result element exists
                    if (!$result.length) {
                        $result = $('<div class="upload_image_result"></div>');
                        $parent.before($result);
                    }

                    //Delete all HTML in result block
                    $result.html('');

                    //Check if multiple selection is allowed
                    if (_multiple) {
                        //Get all selections
                        var _attachments = _selection.toJSON();
                        var _hidden = '';
                        var $li = $img = del = null;
                        $result.append('<ul></ul>');

                        //Iterates on them
                        for (var i = 0, len = _attachments.length; i < len; i++) {
                            _hidden += _attachments[i].id + ';' + _attachments[i].url + '||';

                            //Built item
                            $li = $('<li></li>');
                            $img = $('<img src="' + _attachments[i].url + '" id="' + _attachments[i].id + '" atl="" />');
                            $del = $('<a href="#" class="delete" data-target="' + _idtarget + '">' + _delete + '</a>');

                            //Display item
                            $li.append($img);
                            $li.append($del);
                            $result.find('ul').append($li);
                        }

                        //Put result in hidden input
                        $target.val('' + _hidden);
                    }
                    else {
                        //Get the first selection
                        var _attachment = _selection.first().toJSON();

                        //Display it
                        var $fig = $('<figure></figure>');
                        var $del = $('<a href="#" class="delete" data-target="' + _idtarget + '">' + _delete + '</a>');
                        var $img = $('<img />').attr({
                            src: _attachment.url,
                            id: _attachment.id,
                            alt: ''
                        });
                        $fig.append($img);
                        $fig.append($del);
                        $result.append($fig);

                        //Put result in hidden input
                        $target.val('' + _attachment.url);
                    }

                    //Restore the main post ID and delete file_frame
                    wp.media.model.settings.post.id = _wpid;
                    delete file_frame;
                });

                //Open the modal
                file_frame.open();
            });
        });

        //Upload input: delete button
        $.each($('.inside a.delete'), function (){
            var $self = $(this);

            $self.bind('click', function (e){
                e.preventDefault();
                var $parent = $self.parent();
                var $hidden = $('#' + $self.attr('data-target'));

                //Check if there is multiple medias or not
                var _multiple = 'FIGURE' == $parent[0].nodeName ? false : true;

                //Check if there are multiple medias or not
                if (_multiple) {
                    //Delete value
                    var todelete = $parent.find('img').attr('id') + ';' + $parent.find('img').attr('src') + '||';
                    var newval = $hidden.val().replace(todelete, '');
                    newval = '' == newval ? 'NONE' : newval;
                    $hidden.val('' + newval);
                }
                else {
                    //Delete value
                    $hidden.val('NONE');
                }

                //Deleting animation
                $parent.css('backgroundColor', '#'+_delcolor);
                $parent.animate({
                    opacity: '0'
                }, 'low', function (){
                    $parent.remove();
                });
            });
        });

        //Upload input: delete all button
        $.each($('.inside a.delall'), function (){
            var $self = $(this);
            var $target = $('#' + $self.attr('data-target') + '_upload_content').find('.upload_image_result li');
            var $hidden = $('#' + $self.attr('data-target'));

            //Bind click event
            $self.bind('click', function (e){
                e.preventDefault();

                //Delete all values
                $hidden.val('NONE');

                //Deleting animation
                $.each($target, function (){
                    var $that = $(this);

                    //Animate and delete item
                    $that.css('backgroundColor', '#'+_delcolor);
                    $that.animate({
                        opacity: '0'
                    }, 'low', function (){
                        $that.remove();
                    });
                });
            });
        });

        //Upload Wordpress default button
        $.each($('.inside a.insert-media:not(.thickbox)'), function (){
            var $self = $(this);

            $self.bind('click', function (){
                if ($self.parent().hasClass('customized')) {
                    return false;
                }
            });
        });
    });
})(jQuery);
