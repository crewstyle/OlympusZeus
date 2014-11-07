/* ===================================================
 * Tea Theme Options jQuery
 * https://github.com/Takeatea/tea_theme_options
 * ===================================================
 * Copyright 2014 Take a Tea (http://takeatea.com)
 * =================================================== */

(function ($){
    "use strict";

    $(document).ready(function (){
        var $body = $('body');

        //Mobile menu
        $.each($('.tea-main-nav .tea-menu-resp, .tea-main-nav .close a'), function (){
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
        $('.tea-inside input[type="checkbox"], .tea-inside input[type="radio"]').tea_checkit({
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

        //Color input
        $('.tea-inside .color-picker').tea_color();

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

        //Upload input
        $.each($('.tea-inside.background .bg-upload'), function (){
            var $self = $(this);

            $self.tea_upload({
                wpid: null,
                media: wp.media,
                multiple: false,
                title: $self.attr('data-title') || 'Media',
                type: 'image'
            });
        });

        //Upload input
        $.each($('.tea-inside.upload'), function (){
            var $self = $(this);

            $self.tea_upload({
                wpid: null,
                media: wp.media,
                multiple: $self.attr('data-multiple') || false,
                title: $self.attr('data-title') || 'Media',
                type: $self.attr('data-type') || 'image'
            });
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
