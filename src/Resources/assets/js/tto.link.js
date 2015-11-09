/*!
 * tto.link.js v1.0.1
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin change dynamically the href attribute for the link URL.
 *
 * Example of JS:
 *      $('input[type="url"]').tto_link({
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
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOLink = function ($el,options){
        //vars
        var _tto = this;
        _tto.$el = $el;
        _tto.id = $el.attr('data-id');
        _tto.options = options;

        //update container
        _tto.$container = _tto.$el.find(_tto.options.container);

        //update number
        _tto.num = _tto.$container.find(_tto.options.items).length + 1;

        //set the template
        _tto.source = $(_tto.options.source).html();

        //bind click event
        _tto.$el.find(_tto.options.linkbutton).on('keyup', $.proxy(_tto.linketize, _tto));
        _tto.$el.find(_tto.options.addbutton).on('click', $.proxy(_tto.add_block, _tto));
        _tto.$el.find(_tto.options.delbutton).on('click', $.proxy(_tto.remove_block, _tto));
        _tto.$el.find(_tto.options.delallbutton).on('click', $.proxy(_tto.remove_all, _tto));
    };

    TTOLink.prototype.$el = null;
    TTOLink.prototype.$container = null;
    TTOLink.prototype.id = null;
    TTOLink.prototype.options = null;
    TTOLink.prototype.num = 0;
    TTOLink.prototype.source = null;

    TTOLink.prototype.linketize = function (e){
        e.preventDefault();
        var _tto = this;

        //vars
        var $self = $(e.target || e.currentTarget);

        //change href attribute
        $self.next('a').attr('href', $self.val());
    };

    TTOLink.prototype.add_block = function (e){
        e.preventDefault();
        var _tto = this,
            _id = '',
            _name = '';

        //vars
        var $self = $(e.target || e.currentTarget);

        //update number
        _tto.num++;

        //build contents
        var resp = {
            id: _tto.id,
            lfor: _tto.id + '_' + _tto.num,
            num: _tto.num
        };

        //update modal content and add binding event
        var template = Handlebars.compile(_tto.source);
        var link = template(resp);

        //append all to target
        _tto.$container.append(link);

        //bind events
        var $link = _tto.$container.find(_tto.options.items).last();
        $link.find(_tto.options.linkbutton).on('keyup', $.proxy(_tto.linketize, _tto));
        $link.find(_tto.options.delbutton).on('click', $.proxy(_tto.remove_block, _tto));

        //Tootltip
        $link.find('.tea-to-tooltip').tto_tooltip({position: 'right'});
    };

    TTOLink.prototype.remove_all = function (e){
        e.preventDefault();
        var _tto = this;

        //iterate on all
        _tto.$el.find(_tto.options.delbutton).click();
    };

    TTOLink.prototype.remove_block = function (e){
        e.preventDefault();
        var _tto = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $parent = $self.closest(_tto.options.items);

        //deleting animation
        $parent.css('background', _tto.options.color);
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

                new TTOLink($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_link = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_link');
            return false;
        }
    };
})(window.jQuery);
