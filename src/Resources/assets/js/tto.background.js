/*!
 * tto.background.js v1.0.0
 * https://github.com/crewstyle/TeaThemeOptions 
 *
 * This plugin changes background attributes. Note that the background image
 * is set thanks to the Upload tto.upload.js plugin.
 *
 * Example of JS:
 *      $('.background').tto_background({
 *          color: 'input[name="color"]',               //item node to set background color
 *          position: 'input[type="radio"]',            //item node to set background position
 *          preview: 'figure',                          //item node containing backgroud preview
 *          repeat: 'select[name="position"]',          //item node to set background repeat
 *      });
 * 
 * Example of HTML:
 *      <div class="background">
 *          <figure></figure>
 *          <fieldset>
 *              <label><input type="text" name="color" value="#000000" /></label>
 *              <select name="position">
 *                  <option value="left top">Left top</option>
 *                  <option value="center top">Center top</option>
 *                  <option value="right top">Right top</option>
 *              </select>
 *              <p>
 *                  <input type="radio" name="repeat" value="no-repeat"/> No repeat
 *                  <input type="radio" name="repeat" value="repeat-x"/> Repeat horizontally
 *                  <input type="radio" name="repeat" value="repeat-y"/> Repeat vertically
 *                  <input type="radio" name="repeat" value="repeat"/> Repeat all the way around
 *              </p>
 *              <p>
 *                  <input type="radio" name="size" value="auto"/> Default value
 *                  <input type="radio" name="size" value="cover"/> As large as possible
 *                  <input type="radio" name="size" value="contain"/> Width and height fit in the content area
 *              </p>
 *          </fieldset>
 *      </div>
 *
 * Copyright 2015 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var TTOBackground = function ($el,options){
        //vars
        var _tto = this;
        _tto.$el = $el;
        _tto.options = options;

        //get inputs
        _tto.$color = _tto.$el.find(_tto.options.color);
        _tto.$position = _tto.$el.find(_tto.options.position);
        _tto.$repeat = _tto.$el.find(_tto.options.repeat);
        _tto.$size = _tto.$el.find(_tto.options.size);

        //get preview
        _tto.$preview = _tto.$el.find(_tto.options.preview);

        //color picker
        _tto.$color.tto_color({
            afterchange: function (value){
                _tto.$preview.css({
                    backgroundColor: value
                });
            }
        });

        //bind the change event
        _tto.$position.on('change', $.proxy(_tto.update_background, _tto));
        _tto.$repeat.on('change', $.proxy(_tto.update_background, _tto));
        _tto.$size.on('change', $.proxy(_tto.update_background, _tto));
    };

    TTOBackground.prototype.$color = null;
    TTOBackground.prototype.$el = null;
    TTOBackground.prototype.$preview = null;
    TTOBackground.prototype.$position = null;
    TTOBackground.prototype.$repeat = null;
    TTOBackground.prototype.$size = null;
    TTOBackground.prototype.options = null;

    TTOBackground.prototype.update_background = function (e){
        e.preventDefault();
        var _tto = this;

        //check repeat
        _tto.$preview.css({
            backgroundPosition: _tto.$position.filter(':checked').val(),
            backgroundRepeat: _tto.$repeat.val(),
            backgroundSize: _tto.$size.filter(':checked').val(),
        });
    };

    var methods = {
        init: function (options){
            if (!this.length) {
                return false;
            }

            var settings = {
                color: '.bg-color input',
                position: '.bg-position input',
                preview: '.bg-preview',
                repeat: '.bg-repeat select',
                size: '.bg-size input'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new TTOBackground($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tto_background = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on tto_background');
            return false;
        }
    };
})(window.jQuery);
