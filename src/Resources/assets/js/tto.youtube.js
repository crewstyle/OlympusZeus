/*!
 * tto.youtube.js v1.0.0
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin adds a Youtube integration into container.
 *
 * Example of JS:
 *      $('.yt-container').tto_youtube({
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
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOYoutube = function ($el,options){
        //vars
        var _tto = this;
        _tto.$el = $el;
        _tto.options = options;

        //get sizes
        _tto.options.width = _tto.$el.width();
        _tto.options.height = Math.ceil(_tto.options.width / _tto.options.ratio);

        //get Youtube video ID
        var _s = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/,
            _m = _tto.options.url.match(_s);

        //check the ID
        if (!_m || 11 != _m[7].length) {
            return false;
        }

        //update options
        _tto.options.video = _m[7];

        //initialize
        _tto.init();
    };

    TTOYoutube.prototype.$el = null;
    TTOYoutube.prototype.$wind = $(window);
    TTOYoutube.prototype.options = null;
    TTOYoutube.prototype.player = null;

    TTOYoutube.prototype.init = function (){
        var _tto = this;

        //youtube API
        var tag = document.createElement('script');
        tag.src = "//www.youtube.com/iframe_api";

        //include script
        var _first = document.getElementsByTagName('script')[0];
        _first.parentNode.insertBefore(tag, _first);

        //make the magic :)
        _tto.youtube();
    };

    TTOYoutube.prototype.youtube = function (){
        var _tto = this;

        //build wrapper
        var $wrap = $(document.createElement('div')).css({
            position: 'relative',
            zIndex: '3'
        }).html(_tto.$el.html());
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
        _tto.$el.html('');

        //append
        _tto.$el.prepend($wrap);
        _tto.$el.prepend($stripes);
        _tto.$el.prepend($cont);

        //bind resize event
        _tto.$wind.bind('resize', $.proxy(_tto.ytResize, _tto));

        //add triggers
        window.player;
        window.onYouTubeIframeAPIReady = function (){
            //create player
            player = new YT.Player('yt-player', {
                width: _tto.options.width,
                height: _tto.options.height,
                videoId: _tto.options.video,
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
            var _tto = this;

            //check position
            if (0 === state.data && _tto.options.repeat) {
                player.seekTo(_tto.options.start);
            }
        };

        window.ytReady = function (e){
            var _tto = this;

            //check mute
            if (_tto.options.mute) {
                e.target.mute();
            }

            //change position and autoplay
            e.target.seekTo(_tto.options.start);
            e.target.playVideo();
        };
    };

    TTOYoutube.prototype.ytResize = function (e){
        var _tto = this;

        //update sizes
        _tto.options.width = _tto.$el.width();
        _tto.options.height = Math.ceil(_tto.options.width / _tto.options.ratio);

        //affect sizes
        _tto.$el.find('.yt-container iframe')
            .width(_tto.options.width)
            .height(_tto.options.height);
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

                new TTOYoutube($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_youtube = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_youtube');
            return false;
        }
    };
})(window.jQuery);
