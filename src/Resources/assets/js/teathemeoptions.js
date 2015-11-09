/*!
 * Tea Theme Options jQuery
 * https://github.com/crewstyle/TeaThemeOptions
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    $(document).ready(function (){
        var $body = $('body');


        //Mobile menu
        $.each($('.tea-to-menu-resp, .tea-to-fallback'), function (){
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


        //Background
        $.each($('.tea-to-field-content.background'), function (){
            var $self = $(this);

            $self.tto_background({
                color: '.bg-color input.color-picker',
                position: '.bg-position input',
                preview: '.bg-preview',
                repeat: '.bg-repeat select',
                size: '.bg-size input',
            });
        });


        //Checkbox & Radio & Image input
        $('.tea-to-field-content:not(.toggle) input:checkbox').tto_checkit({
            container:'.tea-to-field-content',
            closest:'label',
            selected:'selected'
        });
        $('.tea-to-field-content:not(.toggle) input:radio').tto_checkit({
            container:'.tea-to-field-content',
            closest:'label',
            selected:'selected'
        });


        //Checkbox check all
        $('.tea-to-checkall input:checkbox').tto_checkall({
            container:'.tea-to-field',
            items:'.tea-to-field-content input:checkbox',
            closest:'label',
            selected:'selected'
        });


        //Code input
        $.each($('.tea-to-field-content.code'), function (){
            var $self = $(this);

            //CodeMirror
            $self.find('textarea.code').tto_code({
                container: '.tea-to-field-content',
                mode: $self.find('.change-mode').val(),
            });
        });


        //Color input
        $('.tea-to-field-content.color .color-picker').tto_color();


        //Date input
        $.each($('.tea-to-field-content input.pickadate'), function (){
            var $self = $(this);

            //pickadate
            $self.tto_date({
                format: $self.attr('data-format') || 'd mmmm, yyyy',
                formatSubmit: $self.attr('data-submit') || 'yyyy.mm.dd',
                today: $self.attr('data-today') || 'Today',
                clear: $self.attr('data-clear') || 'Clear',
                close: $self.attr('data-close') || 'Close'
            });
        });


        //Gallery input
        $.each($('.tea-to-field-content.gallery'), function (){
            var $self = $(this);

            $self.tto_gallery({
                wpid: null,
                media: wp.media,
                title: $self.attr('data-title') || 'Gallery'
            });
        });


        //Link input
        $.each($('.tea-to-field-content.link'), function (){
            var $self = $(this),
                _id = $self.attr('data-id');

            $self.tto_link({
                container: 'fieldset',
                source: '#i-links-' + _id,
            });
        });


        //Maps input
        $.each($('.tea-to-field-content.maps'), function (){
            var $self = $(this),
                _id = $self.attr('data-id');

            $self.tto_maps({
                id: _id,
                inputs: ':input',
                leafletid: 'tea-to-leaflet-' + _id,
                map: '.tea-maps',
                modal: '#m-maps-' + _id,
                reload: 'a.tea-to-reload'
            });
        })


        //Multiselect input
        $('.tea-to-field-content select[multiple="true"]').tto_multiselect({
            container: '.tea-to-field-content',
        });


        //Range input
        $('.tea-to-field-content input[type="range"]').tto_range();


        //Select input
        $('.tea-to-field-content select:not([multiple="true"],.no-selectize)').selectize({
            //options
            plugins: [],
            create: false,
        });


        //Social input
        $.each($('.tea-to-field-content.social'), function (){
            var $self = $(this),
                _id = $self.attr('data-id');

            $self.tto_social({
                addbutton: 'a.add-social',
                content: 'fieldset',
                label: _id,
                items: 'fieldset > div',
                modal: '#m-socials-' + _id,
                source: '#i-socials-' + _id
            });
        });


        //Textarea input
        $('.tea-to-field-content.textarea textarea').tto_textarea();


        //Toggle input
        $('.tea-to-field-content.toggle fieldset').tto_toggle({
            off: 'is-off',
            on: 'is-on'
        });


        //Upload input
        $.each($('.tea-to-field-content.background'), function (){
            var $self = $(this),
                _id = $self.attr('data-id');

            $self.tto_upload({
                container: '.bg-preview figure',
                items: 'span',
                multiple: false,
                source: '#i-background-' + _id,
                type: 'image',
                wpid: null,
            });
        });
        $.each($('.tea-to-field-content.upload'), function (){
            var $self = $(this),
                _id = $self.attr('data-id'),
                _multiple = $self.attr('data-m') && '1' == $self.attr('data-m') ? true : false;

            $self.tto_upload({
                container: '.u-results',
                multiple: _multiple,
                source: '#i-uploads-' + _id,
                type: $self.attr('data-t'),
                wpid: null,
            });
        });


        //Media-frame input
        $.each($('.tea-to-media-frame'), function (){
            var $self = $(this),
                $links = $self.find('.media-frame-router'),
                $blocks = $self.find('.media-frame-content');

            $links.find('a').bind('click', function (e){
                e.preventDefault();
                var $this = $(this),
                    $target = $('' + $this.attr('href'));

                $links.find('a.active').removeClass('active');
                $blocks.find('.media-menu-content.active').removeClass('active');

                $this.addClass('active');
                $target.addClass('active');
            });
        });
        //Screen-meta input
        $.each($('.tea-to-screen-meta'), function (){
            var $self = $(this),
                $links = $self.find('.contextual-help-tabs'),
                $blocks = $self.find('.contextual-help-tabs-wrap');

            $links.find('ul a').bind('click', function (e){
                e.preventDefault();
                var $this = $(this),
                    $target = $('' + $this.attr('href'));

                $links.find('ul li.active').removeClass('active');
                $blocks.find('> .active').removeClass('active');

                $this.closest('li').addClass('active');
                $target.addClass('active');
            });
        });


        //Drag n drop
        $('.tea-to-field-content.link fieldset').tto_dragndrop();
        $('.tea-to-field-content.social fieldset').tto_dragndrop();
        $('.tea-to-field-content.gallery ul.upload-listing').tto_dragndrop({
            items: 'li:not(.upload-time)',
            reorder: {
                parent: '.uploads',
                element: '.upload-items',
                items: '.item'
            }
        });
        $('.tea-to-field-content.upload .u-results').tto_dragndrop();


        //Modal
        $('a.tea-to-link-modal').on('click', function (e){
            e.preventDefault();

            $($(this).attr('href')).tto_modal({
                backdrop: '.tea-to-modal-backdrop'
            });
        });


        //Search
        $('.tea-to-field-content.search.toggle input:radio').on('change', function (e){
            $(this).closest('form').submit();
        });


        //Tooltip
        $('.tea-to-field-content.background .tea-to-tooltip').tto_tooltip();
        $('.tea-to-field-content.link .tea-to-tooltip').tto_tooltip({position: 'right'});
        $('.tea-to-field-content.upload .tea-to-tooltip').tto_tooltip();
        $('.tea-to-choose-fields .tea-to-tooltip').tto_tooltip();
        $('.tea-to-table .tea-to-tooltip').tto_tooltip();
    });
})(jQuery);
