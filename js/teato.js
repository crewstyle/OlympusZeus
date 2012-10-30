/*
 * jQuery Tea Theme Options
 *
 * Copyright 2012 Take a Tea (http://takeatea.com)
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

        //Color input
        $('.inside.color .color-picker').miniColors({
            readonly: true,
            change: function(hex, rgb)
            {
                $(this).val('' + hex);
                $(this).css('color', hex);
            }
        });

        //Upload input
        $.each($('.inside.upload a.thickbox.add_media'), function (index, elem) {
            var $self = $(this);
            var _id = $self.closest('.upload_image_via_wp').find('input').attr('id');

            //Set the upload button ID
            $self.attr('id', '' + _id + $self.attr('id'));

            //Bind the click event
            $(this).bind('click', function (e){
                formfield = _id;
                tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
                return false;
            });
            window.send_to_editor = function (html){
                imgurl = $('img', html).attr('src');
                $('#' + _id).val(imgurl);
                tb_remove();
            }
        });
    });
})(jQuery);
