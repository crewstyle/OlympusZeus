/* ===================================================
 * jQuery Tea Theme Options
 * http://git.tools.takeatea.com/crewstyle/tea_theme_options
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

        /*//Dashboard input - Add content
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

            $parent.css('backgroundColor', '#ffaaaa');
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
        });*/
    });
})(jQuery);