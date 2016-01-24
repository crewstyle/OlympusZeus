/*!
 * link.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin change dynamically the href attribute for the link URL.
 *
 * Example of JS:
 *      $('input[type="url"]').olLink({
 *          addbutton: '.add-link',                     //add link button
 *          color: '#ffaaaa',                           //background color used when deleting a social network
 *          container: 'fieldset',                      //node element containing all items
 *          delallbutton: 'a.del-all-links',            //delete all links button
 *          delbutton: 'a.del-link',                    //delete link button
 *          items: '.link-container',                   //node element which is used to count all elements
 *          linkbutton: 'input',                        //link button to make url clickable
 *          source: '#template-id'                      //node script element in DOM containing handlebars JS temlpate
 *      });
 *
 * Example of HTML:
 *      <div class="links">
 *          <fieldset>
 *              <div class="link-container">
 *                  <input type="url" />
 *              </div>
 *          </fieldset>
 *      </div>
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlLink = function ($el,options){
        //vars
        var _ol = this;
        _ol.$el = $el;
        _ol.id = $el.attr('data-id');
        _ol.options = options;

        //update container
        _ol.$container = _ol.$el.find(_ol.options.container);

        //update number
        _ol.num = _ol.$container.find(_ol.options.items).length + 1;

        //set the template
        _ol.source = $(_ol.options.source).html();

        //bind click event
        _ol.$el.find(_ol.options.linkbutton).on('keyup', $.proxy(_ol.linketize, _ol));
        _ol.$el.find(_ol.options.addbutton).on('click', $.proxy(_ol.add_block, _ol));
        _ol.$el.find(_ol.options.delbutton).on('click', $.proxy(_ol.remove_block, _ol));
        _ol.$el.find(_ol.options.delallbutton).on('click', $.proxy(_ol.remove_all, _ol));
    };

    OlLink.prototype.$el = null;
    OlLink.prototype.$container = null;
    OlLink.prototype.id = null;
    OlLink.prototype.options = null;
    OlLink.prototype.num = 0;
    OlLink.prototype.source = null;

    OlLink.prototype.linketize = function (e){
        e.preventDefault();
        var _ol = this;

        //vars
        var $self = $(e.target || e.currentTarget);

        //change href attribute
        $self.next('a').attr('href', $self.val());
    };

    OlLink.prototype.add_block = function (e){
        e.preventDefault();
        var _ol = this,
            _id = '',
            _name = '';

        //vars
        var $self = $(e.target || e.currentTarget);

        //update number
        _ol.num++;

        //build contents
        var resp = {
            id: _ol.id,
            lfor: _ol.id + '_' + _ol.num,
            num: _ol.num
        };

        //update modal content and add binding event
        var template = Handlebars.compile(_ol.source);
        var link = template(resp);

        //append all to target
        _ol.$container.append(link);

        //bind events
        var $link = _ol.$container.find(_ol.options.items).last();
        $link.find(_ol.options.linkbutton).on('keyup', $.proxy(_ol.linketize, _ol));
        $link.find(_ol.options.delbutton).on('click', $.proxy(_ol.remove_block, _ol));

        //Tootltip
        $link.find('.olz-tooltip').olTooltip({position: 'right'});
    };

    OlLink.prototype.remove_all = function (e){
        e.preventDefault();
        var _ol = this;

        //iterate on all
        _ol.$el.find(_ol.options.delbutton).click();
    };

    OlLink.prototype.remove_block = function (e){
        e.preventDefault();
        var _ol = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $parent = $self.closest(_ol.options.items);

        //deleting animation
        $parent.css('background', _ol.options.color);
        $parent.animate({
            opacity: '0'
        }, 'slow', function (){
            $parent.remove();
        });
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                addbutton: '.add-link',
                color: '#ffaaaa',
                container: 'fieldset',
                delallbutton: 'a.del-all-links',
                delbutton: 'a.del-link',
                items: '.link-container',
                linkbutton: '.block-link input',
                source: '#template-id'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new OlLink($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olLink = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olLink');
            return false;
        }
    };
})(window.jQuery);
