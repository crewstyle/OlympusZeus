/*!
 * youtube.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin adds a Youtube integration into container.
 *
 * Example of JS:
 *      $('.yt-container').olYoutube({
 *          url: 'https://www.youtube.com/watch?v=vNy344PbYyE',                 //item node to un/check
 *          ratio: 16/9,                                                        //closest node to item to add the selected class
 *          mute: true,                                                         //make it mute
 *          repeat: true,                                                       //repetable
 *          start: 0                                                            //position in seconds before start video
 *      });
 *
 * Example of HTML:
 *      <div class="yt-container"></div>
 *
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlYoutube = function ($el,options){
        //vars
        var _ol = this;
        _ol.$el = $el;
        _ol.options = options;

        //get sizes
        _ol.options.width = _ol.$el.width();
        _ol.options.height = Math.ceil(_ol.options.width / _ol.options.ratio);

        //get Youtube video ID
        var _s = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/,
            _m = _ol.options.url.match(_s);

        //check the ID
        if (!_m || 11 != _m[7].length) {
            return false;
        }

        //update options
        _ol.options.video = _m[7];

        //initialize
        _ol.init();
    };

    OlYoutube.prototype.$el = null;
    OlYoutube.prototype.$wind = $(window);
    OlYoutube.prototype.options = null;

    OlYoutube.prototype.init = function (){
        var _ol = this;

        //youtube API
        var tag = document.createElement('script');
        tag.src = "//www.youtube.com/iframe_api";

        //include script
        var _first = document.getElementsByTagName('script')[0];
        _first.parentNode.insertBefore(tag, _first);

        //make the magic :)
        _ol.youtube();
    };

    OlYoutube.prototype.youtube = function (){
        var _ol = this;

        //build wrapper
        var $wrap = $(document.createElement('div')).css({
            position: 'relative',
            zIndex: '3'
        }).html(_ol.$el.html());
        //build container
        var $cont = $(document.createElement('div')).addClass('yt-container').css({
            bottom: '0',
            left: '0',
            overflow: 'hidden',
            position: 'fixed',
            right: '0',
            top: '0',
            zIndex: '1'
        }).html('<div id="yt-player"></div>');
        //build stripes
        var $stripes = $(document.createElement('div')).addClass('yt-stripes').css({
            bottom: '0',
            left: '0',
            overflow: 'hidden',
            position: 'fixed',
            right: '0',
            top: '0',
            zIndex: '2'
        });

        //remove contents
        _ol.$el.html('');

        //append
        _ol.$el.prepend($wrap);
        _ol.$el.prepend($stripes);
        _ol.$el.prepend($cont);

        //bind resize event
        _ol.$wind.bind('resize', $.proxy(_ol.ytResize, _ol));

        //add triggers
        window.onYouTubeIframeAPIReady = function (){
            //create player
            window.player = new YT.Player('yt-player', {
                width: _ol.options.width,
                height: _ol.options.height,
                videoId: _ol.options.video,
                playerVars: {
                    controls: 0,
                    showinfo: 0,
                    modestbranding: 1,
                    wmode: 'transparent'
                },
                events: {
                    'onReady': ytReady,
                    'onStateChange': ytChange
                }
            });
        };
        window.ytChange = function (state){
            var _ol = this;

            //check position
            if (0 === state.data && _ol.options.repeat) {
                window.player.seekTo(_ol.options.start);
            }
        };

        window.ytReady = function (e){
            var _ol = this;

            //check mute
            if (_ol.options.mute) {
                e.target.mute();
            }

            //change position and autoplay
            e.target.seekTo(_ol.options.start);
            e.target.playVideo();
        };
    };

    OlYoutube.prototype.ytResize = function (e){
        var _ol = this;

        //update sizes
        _ol.options.width = _ol.$el.width();
        _ol.options.height = Math.ceil(_ol.options.width / _ol.options.ratio);

        //affect sizes
        _ol.$el.find('.yt-container iframe')
            .width(_ol.options.width)
            .height(_ol.options.height);
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                url: 'https://www.youtube.com/watch?v=vNy344PbYyE',
                ratio: 16/9,
                mute: true,
                repeat: true,
                start: 0
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new OlYoutube($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olYoutube = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olYoutube');
            return false;
        }
    };
})(window.jQuery);
