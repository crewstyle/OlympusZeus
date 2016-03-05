/*!
 * background.js v2.0.0
 * https://github.com/crewstyle/OlympusZeus
 *
 * This plugin changes background attributes. Note that the background image
 * is set thanks to the Upload tto.upload.js plugin.
 *
 * Example of JS:
 *      $('.background').olBackground({
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
 * Copyright 2016 Achraf Chouk
 * Achraf Chouk (https://github.com/crewstyle)
 */

(function ($){
    "use strict";

    var OlBackground = function ($el,options){
        //vars
        var _ol = this;
        _ol.$el = $el;
        _ol.options = options;

        //get inputs
        _ol.$color = _ol.$el.find(_ol.options.color);
        _ol.$position = _ol.$el.find(_ol.options.position);
        _ol.$repeat = _ol.$el.find(_ol.options.repeat);
        _ol.$size = _ol.$el.find(_ol.options.size);

        //get preview
        _ol.$preview = _ol.$el.find(_ol.options.preview);

        //color picker
        _ol.$color.olColor({
            afterchange: function (value){
                _ol.$preview.css({
                    backgroundColor: value
                });
            }
        });

        //bind the change event
        _ol.$position.on('change', $.proxy(_ol.update_background, _ol));
        _ol.$repeat.on('change', $.proxy(_ol.update_background, _ol));
        _ol.$size.on('change', $.proxy(_ol.update_background, _ol));
    };

    OlBackground.prototype.$color = null;
    OlBackground.prototype.$el = null;
    OlBackground.prototype.$preview = null;
    OlBackground.prototype.$position = null;
    OlBackground.prototype.$repeat = null;
    OlBackground.prototype.$size = null;
    OlBackground.prototype.options = null;

    OlBackground.prototype.update_background = function (e){
        e.preventDefault();
        var _ol = this;

        //check repeat
        _ol.$preview.css({
            backgroundPosition: _ol.$position.filter(':checked').val(),
            backgroundRepeat: _ol.$repeat.val(),
            backgroundSize: _ol.$size.filter(':checked').val(),
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

                new OlBackground($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.olBackground = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method '+method+' does not exist on olBackground');
            return false;
        }
    };
})(window.jQuery);
