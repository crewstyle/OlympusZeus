/*!
 * tto.tooltip.js v1.0.1
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds a tooltip when it's asked.
 *
 * Example of JS:
 *      $('.tooltip').tto_tooltip({
 *          css: 'tooltip',                             //CSS class name assigned to tooltip
 *          delayIn: 0,                                 //delay in milliseconds before opening tooltip
 *          delayOut: 0,                                //delay in milliseconds before closing tooltip
 *          fade: false,                                //transition animation                                          true|false
 *          position: 'top',                            //tooltip position                                              'top'|'bottom'|'left'|'right'
 *          offset: 0,                                  //tooltip offset between element and itself
 *          onHidden: null,                             //callback called when the tooltip is hidden
 *          onShown: null,                              //callback called when the tooltip is shown
 *          trigger: 'hover'                            //event to bind to open or close tooltip                        'hover'|'click'|'focus'
 *      });
 *
 * Example of HTML:
 *      <a href="https://github.com/crewstyle/TeaThemeOptions " title="TTO homepage" class="tooltip">
 *          Click here.
 *      </a>
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOTooltip = function ($el,options){
        //vars
        var _tto = this;
        _tto.$el = $el;
        _tto.options = options;

        //initialize
        _tto.init();
    };

    TTOTooltip.prototype.$body = null;
    TTOTooltip.prototype.$el = null;
    TTOTooltip.prototype.$tooltip = null;
    TTOTooltip.prototype.$win = null;
    TTOTooltip.prototype.options = null;
    TTOTooltip.prototype.state = null;
    TTOTooltip.prototype.timer = null;
    TTOTooltip.prototype.trigger = null;

    TTOTooltip.prototype.init = function (){
        var _tto = this;

        //init globals
        _tto.$win = $(window);
        _tto.$body = $('body');
        _tto.state = 'hidden';

        //create tooltip with css class
        _tto.$tooltip = $(document.createElement('div')).css({
            zIndex: '9999',
            position: 'absolute',
        });
        _tto.$tooltip.addClass(_tto.options.css);

        //set the right trigger
        if ('click' == _tto.options.trigger) {
            _tto.trigger = {
                bind: 'click',
            };
        }
        else if ('focus' == _tto.options.trigger) {
            _tto.trigger = {
                open: 'focus',
                close: 'blur',
            };
        }
        else {
            _tto.trigger = {
                open: 'mouseenter',
                close: 'mouseleave',
            };
        }

        //bind the custom trigger event
        if ('click' == _tto.options.trigger) {
            _tto.$el.on(_tto.trigger.bind, $.proxy(_tto.trigger_toggle, _tto));
        }
        else {
            _tto.$el.on(_tto.trigger.open, $.proxy(_tto.trigger_open, _tto));
            _tto.$el.on(_tto.trigger.close, $.proxy(_tto.trigger_close, _tto));
        }

        //bind event on resize window
        _tto.$win.on('resize', $.proxy(_tto.set_position, _tto));
    };

    TTOTooltip.prototype.change_state = function (state){
        var _tto = this,
            _coords = {};

        //update tooltip' state
        if ('visible' === state) {
            _tto.state = 'visible';

            //append it to body
            _tto.$body.append(_tto.$tooltip);

            //set tooltips contents
            _tto.set_content();

            //get and set element position
            _tto.set_position();

            //callback when show tooltip
            //_tto.options.onShown.call(_tto);
        }
        else {
            _tto.state = 'hidden';

            //detach element from dom
            _tto.$tooltip.detach();

            //callback when hide tooltip
            //_tto.options.onHidden.call(_tto);
        }
    };

    TTOTooltip.prototype.get_position = function (){
        var _tto = this,
            _off = _tto.$el.offset(),
            coords = {};

        //cancel all arrow classes
        _tto.$tooltip.removeClass('arrow-top arrow-bottom arrow-left arrow-right');

        //usefull vars
        var _height = _tto.$el.outerHeight(),
            _width = _tto.$el.outerWidth(),
            _tt_height = _tto.$tooltip.outerHeight(),
            _tt_width = _tto.$tooltip.outerWidth();

        //return positions
        if ('top' == _tto.options.position) {
            _tto.$tooltip.addClass('arrow-bottom');

            //top
            return {
                left: _off.left + (_width / 2) - (_tt_width / 2),
                top: _off.top - _tt_height - _tto.options.offset,
            };
        }
        else if ('bottom' == _tto.options.position) {
            _tto.$tooltip.addClass('arrow-top');

            //bottom
            return {
                left: _off.left + (_width / 2) - (_tt_width / 2),
                top: _off.top + _height + _tto.options.offset,
            };
        }
        else if ('left' == _tto.options.position) {
            _tto.$tooltip.addClass('arrow-right');

            //left
            return {
                left: _off.left - _tt_width - _tto.options.offset,
                top: _off.top + (_height / 2) - (_tt_height / 2),
            };
        }
        else {
            _tto.$tooltip.addClass('arrow-left');

            //right
            return {
                left: _off.left + _width + _tto.options.offset,
                top: _off.top + (_height / 2) - (_tt_height / 2),
            };
        }
    };

    TTOTooltip.prototype.set_content = function (){
        var _tto = this;
        _tto.$tooltip.html(_tto.$el.attr('title'));
        _tto.$el.removeAttr('title');
    };

    TTOTooltip.prototype.set_position = function (){
        var _tto = this;

        //set coordinates
        var _coords = _tto.get_position();
        _tto.$tooltip.css(_coords);
    };

    TTOTooltip.prototype.trigger_close = function (e){
        e.preventDefault();
        var _tto = this,
            _delay = _tto.options.delayOut;

        //clear timer in all cases
        clearTimeout(_tto.timer);

        //close with timer if needed
        if (0 === _delay) {
            _tto.change_state('hidden');
        }
        else {
            _tto.timer = setTimeout(function (){
                _tto.change_state('hidden');
            }, _delay);
        }
    };

    TTOTooltip.prototype.trigger_open = function (e){
        e.preventDefault();
        var _tto = this,
            _delay = _tto.options.delayIn;

        //clear timer in all cases
        clearTimeout(_tto.timer);

        //open with timer if needed
        if (0 === _delay) {
            _tto.change_state('visible');
        }
        else {
            _tto.timer = setTimeout(function (){
                _tto.change_state('visible');
            }, _delay);
        }
    };

    TTOTooltip.prototype.trigger_toggle = function (e){
        e.preventDefault();
        var _tto = this;

        //make the good action works
        _tto.state === 'visible' ? _tto.trigger_close(e) : _tto.trigger_open(e);
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                css: 'tto-tooltip',
                delayIn: 0,
                delayOut: 0,
                fade: false,
                position: 'top',
                offset: 0,
                onHidden: null,
                onShown: null,
                trigger: 'hover'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new TTOTooltip($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_tooltip = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_tooltip');
            return false;
        }
    };
})(window.jQuery);
