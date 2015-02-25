/* =====================================================
 * tea.social.js v1.0.2
 * https://github.com/TeaThemeOptions/TeaThemeOptions
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin adds a complete integration width a
 * social buttons modal box.
 * =====================================================
 * Example:
 *      $('.tea-inside.social').tea_social({
 *          modal: '#modal-social'                      //modal block ID
 *      });
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_social = function ($el,options){
        var _tea = this;
        _tea.$el = $el;
        _tea.options = options;

        //Bind event on click
        _tea.$el.find('a.add_social').on('click', $.proxy(_tea.open_social, _tea));
    };

    Tea_social.prototype.$el = null;
    Tea_social.prototype.options = null;
    Tea_social.prototype.networks = [];

    Tea_social.prototype.open_social = function (e){
        e.preventDefault();
        var _tea = this;
        _tea.networks = [];

        //Get modal
        _tea.$modal = $($(_tea.options.modal).html()).clone();
        _tea.$modal.insertBefore(_tea.options.modal).addClass('social-modal');

        //Get contents
        var $ctn = _tea.$el.find(_tea.options.items);
        var $mod = _tea.$modal.find('.content a');

        //List networks
        $.each($ctn, function (){
            _tea.networks[$(this).attr('data-network')] = true;
        });

        //Update modal content and add binding event
        $.each($mod, function (){
            var $lnk = $(this);

            //Check if network is already used
            if ($lnk.attr('data-network') in _tea.networks) {
                $lnk.removeClass('button-secondary').addClass('button-primary');
                $lnk.on('click', $.proxy(_tea.remove_social, _tea));
            }
            else {
                $lnk.on('click', $.proxy(_tea.add_social, _tea));
            }
        });

        //Open Tea TO modal box
        _tea.$modal.tea_modal({
            afterclose: function (){
                _tea.$modal.remove();
            }
        });
    };

    Tea_social.prototype.add_social = function (e){
        e.preventDefault();
        var _tea = this;

        //Get event object
        var $even = $(e.target);
        var $self = 'I' == $even[0].nodeName ? $even.closest('a') : $even;

        //Get target
        var $target = _tea.$el.find(_tea.options.content);
        var _key = $self.attr('data-network');

        //Check if network is already added
        if ($target.find('a[data-network="' + _key + '"]').length) {
            return false;
        }

        //Update modal content and add binding event
        var $div = $(document.createElement('div')).attr('data-network', _key).addClass('movendrop');
        var $main = $(document.createElement('label')).attr('for', _tea.options.label + '_' + _key);
        var $key = $(document.createElement('i')).addClass('fa fa-' + ('vimeo' == _key ? 'vimeo-square' : _key) + ' fa-lg');
        var $hidden = $(document.createElement('input')).attr({
            type: 'hidden',
            name: _tea.options.label + '[' + _key + '][display]',
            value: ''
        });
        var $check = $(document.createElement('input')).attr({
            type: 'checkbox',
            name: _tea.options.label + '[' + _key + '][display]',
            id: _tea.options.label + '_' + _key,
            value: _key
        });

        //Bind event on check
        $check.tea_checkit({
            container:'.inside',
            closest:'label',
            selected:'selected'
        });

        //Append elements
        $main.append($hidden);
        $main.append($check);
        $main.append($key);
        $div.append($main);

        //Check label
        if ($self.attr('data-label')) {
            var $label = $(document.createElement('input')).attr({
                type: 'text',
                name: _tea.options.label + '[' + _key + '][label]',
                placeholder: $self.attr('data-label'),
                value: ''
            });

            $div.append($label);
        }

        //Check link
        if ($self.attr('data-link')) {
            var $link = $(document.createElement('input')).attr({
                type: 'text',
                name: _tea.options.label + '[' + _key + '][link]',
                placeholder: $self.attr('data-link'),
                value: ''
            });

            $div.append($link);
        }

        //Append all to target
        $target.append($div);

        //Change button and add bind event
        $self
            .removeClass('button-secondary')
            .addClass('button-primary')
            .off('click', $.proxy(_tea.add_social, _tea))
            .on('click', $.proxy(_tea.remove_social, _tea));
    };

    Tea_social.prototype.remove_social = function (e){
        e.preventDefault();
        var _tea = this;

        //Get event object
        var $even = $(e.target);
        var $self = 'I' == $even[0].nodeName ? $even.closest('a') : $even;

        //Get target
        var $target = _tea.$el.find(_tea.options.content + ' > div[data-network="' + $self.attr('data-network') + '"]');

        //Remove element
        $target.css('backgroundColor', _tea.options.color);
        $target.animate({
            opacity: '0'
        }, 'slow', function (){
            $target.remove();

            //Change button and add bind event
            $self
                .removeClass('button-primary')
                .addClass('button-secondary')
                .off('click', $.proxy(_tea.remove_social, _tea))
                .on('click', $.proxy(_tea.add_social, _tea));
        });
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                color: '#ffaaaa',
                content: 'fieldset',
                label: 'modal-id',
                items: 'fieldset > div',
                modal: '#modal-socials'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Tea_social($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_social = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_social');
            return false;
        }
    };
})(window.jQuery);
