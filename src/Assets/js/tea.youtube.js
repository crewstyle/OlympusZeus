/* =====================================================
 * tea.youtube.js v1.0.0
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * =====================================================
 * This plugin adds a Youtube integration into container
 * =====================================================
 * Example:
 *      $('.container').tea_youtube({
 *          url: 'https://www.youtube.com/watch?v=vNy344PbYyE',                 //item node to un/check
 *          ratio: 16/9,                                                        //closest node to item to add the selected class
 *          mute: true,                                                         //make it mute
 *          repeat: true,                                                       //repetable
 *          start: 0                                                            //position in seconds before start video
 *      });
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_youtube = function ($el,options){
        //vars
        var _tea = this;
        _tea.$el = $el;
        _tea.options = options;

        //get sizes
        _tea.options.width = _tea.$el.width();
        _tea.options.height = Math.ceil(_tea.options.width / _tea.options.ratio);

        //get Youtube video ID
        var _s = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/,
            _m = _tea.options.url.match(_s);

        //check the ID
        if (!_m || 11 != _m[7].length) {
            return false;
        }

        //update options
        _tea.options.video = _m[7];

        //initialize
        _tea.init();
    };

    Tea_youtube.prototype.$el = null;
    Tea_youtube.prototype.$wind = $(window);
    Tea_youtube.prototype.options = null;
    Tea_youtube.prototype.player = null;

    Tea_youtube.prototype.init = function (){
        var _tea = this;

        //Youtube API
        var tag = document.createElement('script');
        tag.src = "//www.youtube.com/iframe_api";

        //include script
        var _first = document.getElementsByTagName('script')[0];
        _first.parentNode.insertBefore(tag, _first);

        //make the magic :)
        _tea.youtube();
    };

    Tea_youtube.prototype.youtube = function (){
        var _tea = this;

        //build wrapper
        var $wrap = $(document.createElement('div')).css({
            position: 'relative',
            zIndex: '3'
        }).html(_tea.$el.html());
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
        _tea.$el.html('');

        //append
        _tea.$el.prepend($wrap);
        _tea.$el.prepend($stripes);
        _tea.$el.prepend($cont);

        //bind resize event
        _tea.$wind.bind('resize', $.proxy(_tea.ytResize, _tea));

        //add triggers
        window.player;
        window.onYouTubeIframeAPIReady = function (){
            //create player
            player = new YT.Player('yt-player', {
                width: _tea.options.width,
                height: _tea.options.height,
                videoId: _tea.options.video,
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
            var _tea = this;

            //check position
            if (0 === state.data && _tea.options.repeat) {
                player.seekTo(_tea.options.start);
            }
        };

        window.ytReady = function (e){
            var _tea = this;

            //check mute
            if (_tea.options.mute) {
                e.target.mute();
            }

            //change position and autoplay
            e.target.seekTo(_tea.options.start);
            e.target.playVideo();
        };
    };

    Tea_youtube.prototype.ytResize = function (e){
        var _tea = this;

        //update sizes
        _tea.options.width = _tea.$el.width();
        _tea.options.height = Math.ceil(_tea.options.width / _tea.options.ratio);

        //affect sizes
        _tea.$el.find('.yt-container iframe')
            .width(_tea.options.width)
            .height(_tea.options.height);
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

                new Tea_youtube($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_youtube = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_youtube');
            return false;
        }
    };
})(window.jQuery);
