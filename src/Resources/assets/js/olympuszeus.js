/*!
 * Olympus Zeus jQuery
 * https://github.com/crewstyle/OlympusZeus
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    $(document).ready(function (){
        var $body = $('body');


        //Mobile menu
        $.each($('.olz-menu-resp, .olz-fallback'), function (){
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
        $.each($('.olz-field-content.background'), function (){
            var $self = $(this);

            $self.heraBackground({
                color: '.bg-color input.color-picker',
                position: '.bg-position input',
                preview: '.bg-preview',
                repeat: '.bg-repeat select',
                size: '.bg-size input',
            });
        });


        //Checkbox & Radio & Image input
        $('.olz-field-content:not(.toggle) input:checkbox').heraCheckit({
            container:'.olz-field-content',
            closest:'label',
            selected:'selected'
        });
        $('.olz-field-content:not(.toggle) input:radio').heraCheckit({
            container:'.olz-field-content',
            closest:'label',
            selected:'selected'
        });


        //Checkbox check all
        $('.olz-checkall input:checkbox').heraCheckall({
            container:'.olz-field',
            items:'.olz-field-content input:checkbox',
            closest:'label',
            selected:'selected'
        });


        //Code input
        $.each($('.olz-field-content.code'), function (){
            var $self = $(this);

            //CodeMirror
            $self.find('textarea.code').heraCode({
                container: '.olz-field-content',
                mode: $self.find('.change-mode').val(),
            });
        });


        //Color input
        $('.olz-field-content.color .color-picker').heraColor();


        //Date input
        $.each($('.olz-field-content input.pickadate'), function (){
            var $self = $(this);

            //pickadate
            $self.heraDate({
                format: $self.attr('data-format') || 'd mmmm, yyyy',
                formatSubmit: $self.attr('data-submit') || 'yyyy.mm.dd',
                today: $self.attr('data-today') || 'Today',
                clear: $self.attr('data-clear') || 'Clear',
                close: $self.attr('data-close') || 'Close'
            });
        });


        //Gallery input
        /*$.each($('.olz-field-content.gallery'), function (){
            var $self = $(this);

            $self.heraGallery({
                wpid: null,
                media: wp.media,
                title: $self.attr('data-title') || 'Gallery'
            });
        });*/


        //Link input
        $.each($('.olz-field-content.link'), function (){
            var $self = $(this),
                _id = $self.attr('data-id');

            $self.heraLink({
                container: 'fieldset',
                source: '#i-links-' + _id,
            });
        });


        //Maps input
        $.each($('.olz-field-content.maps'), function (){
            var $self = $(this),
                _id = $self.attr('data-id');

            $self.heraMaps({
                id: _id,
                inputs: ':input',
                leafletid: 'olz-leaflet-' + _id,
                map: '.olz-maps',
                modal: '#m-maps-' + _id,
                reload: 'a.olz-reload'
            });
        });


        //Multiselect input
        $('.olz-field-content select[multiple="true"]').heraMultiselect({
            container: '.olz-field-content',
        });


        //Range input
        $('.olz-field-content input[type="range"]').heraRange();


        //Select input
        $('.olz-field-content select:not([multiple="true"],.no-selectize)').selectize({
            //options
            plugins: [],
            create: false,
        });


        //Social input
        $.each($('.olz-field-content.social'), function (){
            var $self = $(this),
                _id = $self.attr('data-id');

            $self.heraSocial({
                addbutton: 'a.add-social',
                content: 'fieldset',
                label: _id,
                items: 'fieldset > div',
                modal: '#m-socials-' + _id,
                source: '#i-socials-' + _id
            });
        });


        //Textarea input
        $('.olz-field-content.textarea textarea').heraTextarea();


        //Toggle input
        $('.olz-field-content.toggle fieldset').heraToggle({
            off: 'is-off',
            on: 'is-on'
        });


        //Upload input
        $.each($('.olz-field-content.background'), function (){
            var $self = $(this),
                _id = $self.attr('data-id');

            $self.heraUpload({
                container: '.bg-preview figure',
                items: 'span',
                multiple: false,
                source: '#i-background-' + _id,
                type: 'image',
                wpid: null,
            });
        });
        $.each($('.olz-field-content.upload'), function (){
            var $self = $(this),
                _id = $self.attr('data-id'),
                _multiple = $self.attr('data-m') && '1' == $self.attr('data-m') ? true : false;

            $self.heraUpload({
                container: '.u-results',
                multiple: _multiple,
                source: '#i-uploads-' + _id,
                type: $self.attr('data-t'),
                wpid: null,
            });
        });


        //Media-frame input
        $.each($('.olz-media-frame'), function (){
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
        $.each($('.olz-screen-meta'), function (){
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
        $('.olz-field-content.link fieldset').heraDragndrop();
        $('.olz-field-content.social fieldset').heraDragndrop();
        $('.olz-field-content.gallery ul.upload-listing').heraDragndrop({
            items: 'li:not(.upload-time)',
            reorder: {
                parent: '.uploads',
                element: '.upload-items',
                items: '.item'
            }
        });
        $('.olz-field-content.upload .u-results').heraDragndrop();


        //Modal
        $('a.olz-link-modal').on('click', function (e){
            e.preventDefault();

            $($(this).attr('href')).heraModal({
                backdrop: '.olz-modal-backdrop'
            });
        });


        //Search
        $('.olz-field-content.search.toggle input:radio').on('change', function (e){
            $(this).closest('form').submit();
        });


        //Tooltip
        $('.olz-tooltip').heraTooltip();
    });
})(jQuery);
