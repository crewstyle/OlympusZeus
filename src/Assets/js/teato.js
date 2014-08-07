/* ===================================================
 * jQuery Tea Theme Options
 * https://github.com/Takeatea/tea_theme_options
 * ===================================================
 * Copyright 20xx Take a Tea (http://takeatea.com)
 * ===================================================
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =================================================== */

(function ($){
    "use strict";

    $(document).ready(function (){
        //Plugin
        $('.label-edit-options .label-button').tea_labelize({
            parent: '.label-edit-options',
            count: '.label-option',
            model: '.label-model'
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

        //Elasticsearch template
        $('.tea-inside .elastica-template').on('click', function (e){
            e.preventDefault();
            var $self = $(this);

            //Open Tea TO modal box
            $('#modal-elasticsearch').tea_modal();
        });

        //Upload input
        $.each($('.tea-inside.gallery'), function (){
            var $self = $(this);

            $self.tea_gallery({
                wpid: null,
                media: wp.media,
                title: $self.attr('data-title') || 'Gallery'
            });
        });

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