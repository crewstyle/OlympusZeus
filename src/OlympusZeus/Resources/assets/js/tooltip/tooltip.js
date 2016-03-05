/*!
 * tooltip.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds a tooltip when it's asked.
 *
 * Example of JS:
 *      $('.tooltip').olTooltip({
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
 *      <a href="https://github.com/crewstyle " title="Achraf Chouk's profile on Github.com" class="tooltip">
 *          Click here.
 *      </a>
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlTooltip = function ($el,options){
        //vars
        var _ol = this;
        _ol.$el = $el;
        _ol.options = options;

        //initialize
        _ol.init();
    };

    OlTooltip.prototype.$body = null;
    OlTooltip.prototype.$el = null;
    OlTooltip.prototype.$tooltip = null;
    OlTooltip.prototype.$win = null;
    OlTooltip.prototype.options = null;
    OlTooltip.prototype.state = null;
    OlTooltip.prototype.timer = null;
    OlTooltip.prototype.trigger = null;

    OlTooltip.prototype.init = function (){
        var _ol = this;

        //init globals
        _ol.$win = $(window);
        _ol.$body = $('body');
        _ol.state = 'hidden';

        //create tooltip with css class
        _ol.$tooltip = $(document.createElement('div')).css({
            zIndex: '9999',
            position: 'absolute',
        });
        _ol.$tooltip.addClass(_ol.options.css);

        //set the right trigger
        if ('click' == _ol.options.trigger) {
            _ol.trigger = {
                bind: 'click',
            };
        }
        else if ('focus' == _ol.options.trigger) {
            _ol.trigger = {
                open: 'focus',
                close: 'blur',
            };
        }
        else {
            _ol.trigger = {
                open: 'mouseenter',
                close: 'mouseleave',
            };
        }

        //bind the custom trigger event
        if ('click' == _ol.options.trigger) {
            _ol.$el.on(_ol.trigger.bind, $.proxy(_ol.trigger_toggle, _ol));
        }
        else {
            _ol.$el.on(_ol.trigger.open, $.proxy(_ol.trigger_open, _ol));
            _ol.$el.on(_ol.trigger.close, $.proxy(_ol.trigger_close, _ol));
        }

        //bind event on resize window
        _ol.$win.on('resize', $.proxy(_ol.set_position, _ol));
    };

    OlTooltip.prototype.change_state = function (state){
        var _ol = this,
            _coords = {};

        //update tooltip' state
        if ('visible' === state) {
            _ol.state = 'visible';

            //append it to body
            _ol.$body.append(_ol.$tooltip);

            //set tooltips contents
            _ol.set_content();

            //get and set element position
            _ol.set_position();

            //callback when show tooltip
            //_ol.options.onShown.call(_ol);
        }
        else {
            _ol.state = 'hidden';

            //detach element from dom
            _ol.$tooltip.detach();

            //callback when hide tooltip
            //_ol.options.onHidden.call(_ol);
        }
    };

    OlTooltip.prototype.get_position = function (){
        var _ol = this,
            _off = _ol.$el.offset(),
            coords = {};

        //cancel all arrow classes
        _ol.$tooltip.removeClass('arrow-top arrow-bottom arrow-left arrow-right');

        //usefull vars
        var _height = _ol.$el.outerHeight(),
            _width = _ol.$el.outerWidth(),
            _tt_height = _ol.$tooltip.outerHeight(),
            _tt_width = _ol.$tooltip.outerWidth();

        //return positions
        if ('top' == _ol.options.position) {
            _ol.$tooltip.addClass('arrow-bottom');

            //top
            return {
                left: _off.left + (_width / 2) - (_tt_width / 2),
                top: _off.top - _tt_height - _ol.options.offset,
            };
        }
        else if ('bottom' == _ol.options.position) {
            _ol.$tooltip.addClass('arrow-top');

            //bottom
            return {
                left: _off.left + (_width / 2) - (_tt_width / 2),
                top: _off.top + _height + _ol.options.offset,
            };
        }
        else if ('left' == _ol.options.position) {
            _ol.$tooltip.addClass('arrow-right');

            //left
            return {
                left: _off.left - _tt_width - _ol.options.offset,
                top: _off.top + (_height / 2) - (_tt_height / 2),
            };
        }
        else {
            _ol.$tooltip.addClass('arrow-left');

            //right
            return {
                left: _off.left + _width + _ol.options.offset,
                top: _off.top + (_height / 2) - (_tt_height / 2),
            };
        }
    };

    OlTooltip.prototype.set_content = function (){
        var _ol = this;
        _ol.$tooltip.html(_ol.$el.attr('title'));
        _ol.$el.removeAttr('title');
    };

    OlTooltip.prototype.set_position = function (){
        var _ol = this;

        //set coordinates
        var _coords = _ol.get_position();
        _ol.$tooltip.css(_coords);
    };

    OlTooltip.prototype.trigger_close = function (e){
        e.preventDefault();
        var _ol = this,
            _delay = _ol.options.delayOut;

        //clear timer in all cases
        clearTimeout(_ol.timer);

        //close with timer if needed
        if (0 === _delay) {
            _ol.change_state('hidden');
        }
        else {
            _ol.timer = setTimeout(function (){
                _ol.change_state('hidden');
            }, _delay);
        }
    };

    OlTooltip.prototype.trigger_open = function (e){
        e.preventDefault();
        var _ol = this,
            _delay = _ol.options.delayIn;

        //clear timer in all cases
        clearTimeout(_ol.timer);

        //open with timer if needed
        if (0 === _delay) {
            _ol.change_state('visible');
        }
        else {
            _ol.timer = setTimeout(function (){
                _ol.change_state('visible');
            }, _delay);
        }
    };

    OlTooltip.prototype.trigger_toggle = function (e){
        e.preventDefault();
        var _ol = this;

        //make the good action works
        if (_ol.state === 'visible') {
            _ol.trigger_close(e);
        }
        else {
            _ol.trigger_open(e);
        }
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

                new OlTooltip($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olTooltip = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olTooltip');
            return false;
        }
    };
})(window.jQuery);
