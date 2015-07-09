/* =====================================================
 * tea.link.js v1.0.0
 * https://github.com/TeaThemeOptions/TeaThemeOptions
 * =====================================================
 * ~ Copyright since 2014 ~
 * Take a Tea (http://takeatea.com)
 * Tea Theme Options (http://teato.me)
 * =====================================================
 * This plugin change dynamically the href attribute
 * for the link URL.
 * =====================================================
 * Example:
 *      $('input[type="url"]').tea_link();
 * ===================================================== */

(function ($){
    "use strict";

    var Tea_link = function ($el,options){
        //vars
        var _tea = this;
        _tea.$el = $el;
        _tea.id = $el.attr('data-id');
        _tea.options = options;

        //bind click event
        _tea.$el.find('.block-link input').on('keyup', $.proxy(_tea.linketize, _tea));
        _tea.$el.find('.add_link').on('click', $.proxy(_tea.addblock, _tea));
        _tea.$el.find('.del_link').on('click', $.proxy(_tea.removeblock, _tea));
    };

    Tea_link.prototype.$el = null;
    Tea_link.prototype.id = null;
    Tea_link.prototype.options = null;

    Tea_link.prototype.linketize = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $self = $(e.target || e.currentTarget);

        //change href attribute
        $self.next('a').attr('href', $self.val());
    };

    Tea_link.prototype.addblock = function (e){
        e.preventDefault();
        var _tea = this,
            _id = '',
            _name = '';

        //vars
        var $self = $(e.target || e.currentTarget),
            _num = _tea.$el.find('.link-container').last().attr('data-num');

        //update number
        _num = parseInt(_num) + 1;
        _name = _tea.id+'['+_num+']';
        _id = _tea.id+'-'+_num;

        //create new block
        var $lctn = $(document.createElement('div'))
            .attr('data-num', _num)
            .addClass('link-container link-expand'),

            //create first block
            $block1 = $(document.createElement('p'))
                .addClass('block-link')
                .html('
<a href="#" class="del_link"><i class="fa fa-times"></i></a>
<label for="'+_id+'-url">Web address</label>
<input type="text" name="'+_name+'[url]" id="'+_id+'-url" value="">
<a href="#" target="_blank">Go to the Website</a>
                '),

            //create second block
            $block2 = $(document.createElement('p'))
                .html('
<label for="'+_id+'-label">Title</label>
<input type="text" name="'+_name+'[label]" id="'+_id+'-label" value=""/>
                '),

            //create third block
            $block3 = $(document.createElement('p'))
                .html('
<label for="'+_id+'-target">Target</label>
<select name="'+_name+'[target]" id="'+_id+'-target">
    <option value="_blank" selected="selected">Opens the linked document in a new window or tab</option>
    <option value="_self">Opens the linked document in the same frame as it was clicked</option>
    <option value="_parent">Opens the linked document in the parent frame</option>
    <option value="_top">Opens the linked document in the full body of the window</option>
</select>
                '),

            //create last block
            $block4 = $(document.createElement('p')).addClass('rel')
                .html('
<label for="'+_id+'-rel">Relationship</label>
<input type="text" name="'+_name+'[rel]" id="'+_id+'-rel" value="nofollow">
<small>You can set the <code>nofollow</code> value to avoid bots following the linked document.</small>
                ');

        //assembling
        $lctn.append($block1);
        $lctn.append($block2);
        $lctn.append($block3);
        $lctn.append($block4);
        _tea.$el.find('.hide-if-no-js').before($lctn);

        //bind events
        $block1.find('input').on('keyup', $.proxy(_tea.linketize, _tea));
        $block1.find('.del_link').on('click', $.proxy(_tea.removeblock, _tea));
    };

    Tea_link.prototype.removeblock = function (e){
        e.preventDefault();
        var _tea = this;

        //vars
        var $self = $(e.target || e.currentTarget);
        var $parent = $self.closest('.link-container');

        //deleting animation
        $parent.css('background', _tea.options.color);
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
                color: '#ffaaaa'
            };

            return this.each(function (){
                if (options) {
                    $.extend(settings, options);
                }

                new Tea_link($(this), settings);
            });
        },
        update: function (){},
        destroy: function (){}
    };

    $.fn.tea_link = function (method){
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        }
        else {
            $.error('Method ' + method + ' does not exist on tea_link');
            return false;
        }
    };
})(window.jQuery);
