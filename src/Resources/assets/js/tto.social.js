/*!
 * tto.social.js v1.0.3
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds a complete integration width a social buttons modal box.
 *
 * Example of JS:
 *      $('.social-block').tto_social({
 *          active: 'active',                           //CSS class to selected social networks
 *          addbutton: 'a.add-social',                  //add social network button
 *          color: '#ffaaaa',                           //background color used when deleting a social network
 *          content: 'fieldset',                        //node element of main container
 *          delallbutton: 'a.del-all-socials',          //delete all social networks button
 *          delbutton: 'a.del-social',                  //delete social network button
 *          label: 'modal-id',                          //name used to be sent through the form
 *          items: 'fieldset > div',                    //social networks already selected
 *          modal: '#modal-socials',                    //modal block ID
 *          source: '#template-id'                      //node script element in DOM containing handlebars JS temlpate
 *      });
 *
 * Example of HTML:
 *      --
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOSocial = function ($el,options){
        var _tto = this;
        _tto.$el = $el;
        _tto.options = options;

        //initialize
        _tto.init();
    };

    TTOSocial.prototype.$el = null;
    TTOSocial.prototype.$modal = null;
    TTOSocial.prototype.options = null;
    TTOSocial.prototype.source = null;

    TTOSocial.prototype.init = function (){
        var _tto = this;

        //set the template
        _tto.source = $(_tto.options.source).html();

        //get modal
        _tto.$modal = $(_tto.options.modal);
        _tto.$modal.addClass(_tto.options.modalclass);

        //list networks
        $.each(_tto.$el.find(_tto.options.items), function (){
            _tto.$modal.find('a[data-nk="' + $(this).attr('data-nk') + '"]').addClass(_tto.options.active);
        });

        //bind events on click
        _tto.$el.find(_tto.options.addbutton).on('click', $.proxy(_tto.open_modal, _tto));
        _tto.$el.find(_tto.options.delbutton).on('click', $.proxy(_tto.remove_social, _tto));
        _tto.$el.find(_tto.options.delallbutton).on('click', $.proxy(_tto.remove_all, _tto));
        _tto.$modal.find('a[data-nk]').on('click', $.proxy(_tto.toggle_social, _tto));
    };

    TTOSocial.prototype.open_modal = function (e){
        e.preventDefault();
        var _tto = this;

        //list networks
        $.each(_tto.$el.find(_tto.options.items), function (){
            _tto.$modal.find('a[data-nk="' + $(this).attr('data-nk') + '"]').addClass(_tto.options.active);
        });

        //open Tea TO modal box
        _tto.$modal.tto_modal({
            backdrop: '.tea-to-modal-backdrop'
        });
    };

    TTOSocial.prototype.remove_all = function (e){
        e.preventDefault();
        var _tto = this;

        //iterate on all
        _tto.$el.find(_tto.options.delbutton).click();
    };

    TTOSocial.prototype.remove_social = function (e){
        e.preventDefault();
        var _tto = this;

        //get event object
        var $even = $(e.target);
        var $self = 'I' == $even[0].nodeName ? $even.closest('a') : $even;

        //network
        var _nwk = $self.attr('data-nk');

        //remove button
        _tto._toggle('remove', _nwk);
    };

    TTOSocial.prototype.toggle_social = function (e){
        e.preventDefault();
        var _tto = this;

        //get event object
        var $even = $(e.target);
        var $self = 'I' == $even[0].nodeName ? $even.closest('a') : $even;

        //network
        var _nwk = $self.attr('data-nk');

        //check what to do
        if ($self.hasClass(_tto.options.active)) {
            _tto._toggle('remove', _nwk);
        }
        else {
            _tto._toggle('create', _nwk);
        }
    };

    TTOSocial.prototype._create = function (nwk, $button){
        var _tto = this;

        //check if network is already added
        if (_tto.$el.find(_tto.options.items + '[data-nk="' + nwk + '"]').length) {
            $button.removeClass(_tto.options.active);
            return;
        }

        //get target
        var $target = _tto.$el.find(_tto.options.content);

        //build contents
        var resp = {
            nwk: nwk,
            lfor: _tto.options.label + '_' + nwk,
            title: $button.text(),
            style: $button.attr('style'),
            id: _tto.options.label,
            label: {
                value: '',
                placeholder: $button.attr('data-ll'),
            },
            link: {
                value: '',
                placeholder: $button.attr('data-lk'),
            },
        };

        //update modal content and add binding event
        var template = Handlebars.compile(_tto.source);
        var social = template(resp);

        //append all to target
        $target.append(social);

        //bind event on click
        var $social = _tto.$el.find(_tto.options.items + '[data-nk="' + nwk + '"]');
        $social.find(_tto.options.delbutton).on('click', $.proxy(_tto.remove_social, _tto));
    };

    TTOSocial.prototype._destroy = function (nwk){
        var _tto = this;

        //get target
        var $target = _tto.$el.find(_tto.options.content + ' > div[data-nk="' + nwk + '"]');

        //change button and add bind event
        $target.find(_tto.options.delbutton).off('click', $.proxy(_tto.remove_social, _tto));

        //remove element
        $target.css('backgroundColor', _tto.options.color);
        $target.stop().animate({
            opacity: '0'
        }, 'slow', function (){
            $target.remove();
        });
    };

    TTOSocial.prototype._toggle = function (action, nwk){
        var _tto = this;

        //get targets
        var $button = _tto.$modal.find('a[data-nk="' + nwk + '"]');

        //get target
        if ('create' === action) {
            //update button state
            $button.addClass(_tto.options.active);
            _tto._create(nwk, $button);
        }
        else if ('remove' === action) {
            //update button state
            $button.removeClass(_tto.options.active);
            _tto._destroy(nwk);
        }
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                active: 'active',
                addbutton: 'a.add-social',
                color: '#ffaaaa',
                content: 'fieldset',
                delallbutton: 'a.del-all-socials',
                delbutton: 'a.del-social',
                label: 'modal-id',
                items: 'fieldset > div',
                modal: '#modal-socials',
                source: '#template-id'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new TTOSocial($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_social = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_social');
            return false;
        }
    };
})(window.jQuery);
