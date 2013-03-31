/*
 * jQuery Tea Theme Options
 *
 * Copyright 2013 Take a Tea (http://takeatea.com)
 *
 * Dual licensed under the MIT or GPL Version 2 licenses
 *
*/
;(function($){
    $(document).ready(function (){
        //Checkbox & Image input
        $('.inside.checkbox label, .inside.image-checkbox label').bind('click', function (e){
            var $self = $(this);
            $self.find('input:checked').length ? $self.addClass('selected') : $self.removeClass('selected');
        });

        //Radio & Image input
        $('.inside.radio label, .inside.image-radio label').bind('click', function (e){
            var $self = $(this);
            $self.closest('.inside').find('.selected').removeClass('selected');
            $self.addClass('selected');
        });

        //Checkbox check all
        $('.checkboxes .checkall input').bind('change', function (e){
            var $self = $(this);
            var $checks = $self.closest('.checkboxes').find('.inside input');
            $checks.attr('checked', $self.is(':checked'));
        });

        //Color input
        $('.inside.color .old.color-picker').miniColors({
            readonly: true,
            change: function(hex, rgb)
            {
                $(this).val('' + hex);
                $(this).css('color', hex);
            }
        });
        $.each($('.inside.color .color-picker'), function (index,elem){
            var $self = $(this);
            var default_color = 'fbfbfb';

            $self.wpColorPicker({
                change: function(event, ui) {
                    $self.val($self.wpColorPicker('color'));
                },
                clear: function() {
                    $self.val('');
                }
            });

            $self.bind('click', function (e){
                var _value = $self.val().replace('#', '');
                if ('' == _value) {
                    $self.val('' + default_color);
                }
                else {
                    $self.val('' + $self.val());
                }
            });
            $self.click();
        });

        //Upload input
        $.each($('.inside.upload a.thickbox.add_media'), function (index, elem){
            var $self = $(this);
            var _id = $self.attr('data-editor');

            //Set the upload button ID
            $self.attr('id', '' + _id + this.id);

            //Bind the click event
            $(this).bind('click', function (e){
                formfield = _id;
                tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true&amp;post_id=0');
                return false;
            });
            window.send_to_editor = function (html){
                imgurl = undefined == html.src ? html.href : html.src;
                $('#' + _id).val(imgurl);
                tb_remove();
            }
        });
        $('a.insert-media:not(.thickbox)').bind('click', function (e){
            var $self = $(this);

            if ($self.parent().hasClass('customized'))
            {
                return false;
            }
        });
        $.each($('.inside.upload a.add_media:not(.thickbox)'), function (index, elem){
            var $self = $(elem);
            var _id = $self.attr('data-editor');
            var $target = $('#' + _id);
            var file_frame;
            var _wp_id = wp.media.model.settings.post.id;

            $self.bind('click', function (e){
                e.preventDefault();
                wp.media.model.settings.post.id = _wp_id;

                // If the media frame already exists, reopen it.
                if (file_frame)
                {
                    // Set the post ID to what we want
                    file_frame.uploader.uploader.param('post_id', _id);
                    // Open frame
                    file_frame.open();
                    return;
                }
                else
                {
                    // Set the wp.media post id so the uploader grabs the ID we want when initialised
                    wp.media.model.settings.post.id = _id;
                }

                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: $self.attr('data-title') || 'Media',
                    multiple: false
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function (evt){
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();

                    // Do something with attachment.id and/or attachment.url here
                    $target.val(attachment.url);
                    $target.closest('.inside.upload').find('.upload_image_result a').attr('href', attachment.url);
                    $target.closest('.inside.upload').find('.upload_image_result a').attr('id', attachment.id);
                    $target.closest('.inside.upload').find('.upload_image_result img').attr('src', attachment.url);
                    //console.log(_id, attachment.id, attachment.url);

                    // Restore the main post ID
                    wp.media.model.settings.post.id = _wp_id;
                });

                // Finally, open the modal
                file_frame.open();
            });
        });
    });
})(jQuery);
