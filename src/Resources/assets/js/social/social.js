/*!
 * social.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds a complete integration width a social buttons modal box.
 *
 * Example of JS:
 *      $('.social-block').olSocial({
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
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlSocial = function ($el,options){
        var _ol = this;
        _ol.$el = $el;
        _ol.options = options;

        //initialize
        _ol.init();
    };

    OlSocial.prototype.$el = null;
    OlSocial.prototype.$modal = null;
    OlSocial.prototype.options = null;
    OlSocial.prototype.source = null;

    OlSocial.prototype.init = function (){
        var _ol = this;

        //set the template
        _ol.source = $(_ol.options.source).html();

        //get modal
        _ol.$modal = $(_ol.options.modal);
        _ol.$modal.addClass(_ol.options.modalclass);

        //list networks
        $.each(_ol.$el.find(_ol.options.items), function (){
            _ol.$modal.find('a[data-nk="' + $(this).attr('data-nk') + '"]').addClass(_ol.options.active);
        });

        //bind events on click
        _ol.$el.find(_ol.options.addbutton).on('click', $.proxy(_ol.open_modal, _ol));
        _ol.$el.find(_ol.options.delbutton).on('click', $.proxy(_ol.remove_social, _ol));
        _ol.$el.find(_ol.options.delallbutton).on('click', $.proxy(_ol.remove_all, _ol));
        _ol.$modal.find('a[data-nk]').on('click', $.proxy(_ol.toggle_social, _ol));
    };

    OlSocial.prototype.open_modal = function (e){
        e.preventDefault();
        var _ol = this;

        //list networks
        $.each(_ol.$el.find(_ol.options.items), function (){
            _ol.$modal.find('a[data-nk="' + $(this).attr('data-nk') + '"]').addClass(_ol.options.active);
        });

        //open modal box
        _ol.$modal.olModal({
            backdrop: '.olz-modal-backdrop'
        });
    };

    OlSocial.prototype.remove_all = function (e){
        e.preventDefault();
        var _ol = this;

        //iterate on all
        _ol.$el.find(_ol.options.delbutton).click();
    };

    OlSocial.prototype.remove_social = function (e){
        e.preventDefault();
        var _ol = this;

        //get event object
        var $even = $(e.target);
        var $self = 'I' == $even[0].nodeName ? $even.closest('a') : $even;

        //network
        var _nwk = $self.attr('data-nk');

        //remove button
        _ol._toggle('remove', _nwk);
    };

    OlSocial.prototype.toggle_social = function (e){
        e.preventDefault();
        var _ol = this;

        //get event object
        var $even = $(e.target);
        var $self = 'I' == $even[0].nodeName ? $even.closest('a') : $even;

        //network
        var _nwk = $self.attr('data-nk');

        //check what to do
        if ($self.hasClass(_ol.options.active)) {
            _ol._toggle('remove', _nwk);
        }
        else {
            _ol._toggle('create', _nwk);
        }
    };

    OlSocial.prototype._create = function (nwk, $button){
        var _ol = this;

        //check if network is already added
        if (_ol.$el.find(_ol.options.items + '[data-nk="' + nwk + '"]').length) {
            $button.removeClass(_ol.options.active);
            return;
        }

        //get target
        var $target = _ol.$el.find(_ol.options.content);

        //build contents
        var resp = {
            nwk: nwk,
            lfor: _ol.options.label + '_' + nwk,
            title: $button.text(),
            style: $button.attr('style'),
            id: _ol.options.label,
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
        var template = Handlebars.compile(_ol.source);
        var social = template(resp);

        //append all to target
        $target.append(social);

        //bind event on click
        var $social = _ol.$el.find(_ol.options.items + '[data-nk="' + nwk + '"]');
        $social.find(_ol.options.delbutton).on('click', $.proxy(_ol.remove_social, _ol));
    };

    OlSocial.prototype._destroy = function (nwk){
        var _ol = this;

        //get target
        var $target = _ol.$el.find(_ol.options.content + ' > div[data-nk="' + nwk + '"]');

        //change button and add bind event
        $target.find(_ol.options.delbutton).off('click', $.proxy(_ol.remove_social, _ol));

        //remove element
        $target.css('backgroundColor', _ol.options.color);
        $target.stop().animate({
            opacity: '0'
        }, 'slow', function (){
            $target.remove();
        });
    };

    OlSocial.prototype._toggle = function (action, nwk){
        var _ol = this;

        //get targets
        var $button = _ol.$modal.find('a[data-nk="' + nwk + '"]');

        //get target
        if ('create' === action) {
            //update button state
            $button.addClass(_ol.options.active);
            _ol._create(nwk, $button);
        }
        else if ('remove' === action) {
            //update button state
            $button.removeClass(_ol.options.active);
            _ol._destroy(nwk);
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

                new OlSocial($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olSocial = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olSocial');
            return false;
        }
    };
})(window.jQuery);
