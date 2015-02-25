/* ===================================================
 * Tea Theme Options jQuery
 * https://github.com/TeaThemeOptions/TeaThemeOptions
 * ===================================================
 * Copyright 2015 Take a Tea (http://takeatea.com)
 * =================================================== */

(function ($){
    "use strict";

    $(document).ready(function (){
        var $body = $('body');

        //Mobile menu
        $.each($('.tea-main-nav .tea-menu-resp, .tea-main-nav .fallback'), function (){
            var $self = $(this);

            //bind click event
            $self.bind('click', function (e){
                e.preventDefault();

                //opening & closing
                if (!$body.hasClass('opened')) {
                    $body.addClass('opened');
                }
                else {
                    $body.removeClass('opened');
                }

                //bind orientation change and close menu
                $(window).bind('orientationchange', function (){
                    $body.removeClass('opened');
                });
            });
        });

        //Checkbox & Radio & Image input
        $('.tea-inside:not(.toggle) input[type="checkbox"], .tea-inside:not(.toggle) input[type="radio"]').tea_checkit({
            container:'.inside',
            closest:'label',
            selected:'selected'
        });

        //Checkbox & Radio & Image input
        $('.tea-connection input[type="checkbox"]').tea_checkit({
            container:'.tea-connection',
            closest:'label',
            selected:'is-active'
        });
        $('.tea-background-container input[type="radio"]').tea_checkit({
            container:'.tea-background-container',
            closest:'label',
            selected:'selected'
        });

        //Checkbox check all
        $('.tea_to_wrap .checkall input[type="checkbox"]').tea_checkall({
            container:'.checkboxes',
            items:'.inside input[type="checkbox"]',
            closest:'label',
            selected:'selected'
        });

        //Checkbox & Radio & Image input
        $('.tea-connect .checkall input[type="checkbox"]').tea_checkall({
            container:'.tea-connect',
            items:'.tea-connection input[type="checkbox"]',
            closest:'label',
            selected:'is-active'
        });

        //Code input
        $.each($('.tea-inside.code'), function (){
            var $self = $(this);

            //CodeMirror
            $self.find('textarea.code').tea_code({
                mode: $self.find('.change-mode').val(),
            });
        });

        //Color input
        $('.tea-inside .color-picker, .tea-conf .color-picker').tea_color();

        //Date input
        $.each($('.tea-inside input.pickadate'), function (){
            var $self = $(this);

            //pickadate
            $self.tea_date({
                format: $self.attr('data-format') || 'd mmmm, yyyy',
                formatSubmit: $self.attr('data-submit') || 'yyyy.mm.dd',
                today: $self.attr('data-today') || 'Today',
                clear: $self.attr('data-clear') || 'Clear',
                close: $self.attr('data-close') || 'Close'
            });
        });

        //Drag n drop
        $('.tea-inside.social-checkbox fieldset').tea_dragndrop();
        $('.tea-inside.gallery ul.upload-listing').tea_dragndrop({
            items: 'li:not(.upload-time)',
            reorder: {
                parent: '.uploads',
                element: '.upload-items',
                items: '.item'
            }
        });
        $('.tea-inside.upload .upload_image_result ul').tea_dragndrop({
            items: 'li:not(.upload-time)'
        });

        //Elasticsearch template
        $('.tea-inside .elastica-template').on('click', function (e){
            e.preventDefault();
            $('#modal-elasticsearch').tea_modal();
        });

        //Gallery input
        $.each($('.tea-inside.gallery'), function (){
            var $self = $(this);

            $self.tea_gallery({
                wpid: null,
                media: wp.media,
                title: $self.attr('data-title') || 'Gallery'
            });
        });

        //Link input
        $('.tea-inside.link .block-link input').tea_link();

        //Maps input
        $('.tea-maps-container').tea_maps();

        //Multiselect input
        $('.tea-inside select[multiple="true"]').tea_multiselect();

        //Range input
        $('.tea-inside input[type="range"]').tea_range();

        //Social input
        $.each($('.tea-inside.social'), function (){
            var $self = $(this);

            $self.tea_social({
                content: 'fieldset',
                label: $self.attr('data-id'),
                items: 'fieldset > div',
                modal: '#modal-socials'
            });
        });

        //Textarea input
        $('.tea-inside textarea.textarea').tea_textarea();

        //Toggle input
        $('.tea-inside.toggle fieldset').tea_toggle();

        //Upload Background input
        $('.tea-background-container .bg-upload').tea_upload({
            wpid: null,
            media: wp.media,
            title: 'data-title'
        });

        //Upload input
        $('.tea-inside.upload').tea_upload({
            wpid: null,
            media: wp.media,
            multiple: 'data-multiple',
            title: 'data-title',
            type: 'data-type'
        });

        /* OLD ONES? Huh! */

        //Plugin
        $('.label-edit-options .label-button').tea_labelize({
            parent: '.label-edit-options',
            count: '.label-option',
            model: '.label-model'
        });

        //Screen-meta input
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
    });
})(jQuery);
