/* =========================================================
 * shortcodes-modal.js v1.0.0
 * http://www.takeatea.com/wordpress/#shortcodes-modal
 * =========================================================
 * Copyright 2013 Take a Tea
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */

!function ($){
    "use strict"; // jshint ;_;

    /* TEAMODAL CLASS DEFINITION
     * ========================= */

    var TeaModal = function (element, options){
        this.options = options;
    }

    TeaModal.prototype = {
        constructor: TeaModal,

        builtin: function (){
            var that = this;
            this.modal = $('body').find('' + this.options.id);

            //check if modal is already appended in the body
            if (!this.modal.length)
            {
                var $tpl = $('' + this.options.tpl).html();
                $('body').append($tpl);
                this.modal = $('body').find('' + this.options.id);
            }

            this.modal.find('#tea-title').html('');
            this.modal.find('#tea-subtitle').html('');
            this.modal.find('#tea-content').html('');
            this.modal.find('#tea-submit').html('');

            this.modal.find('#tea-title').text(this.options.title || 'Modal box');
            this.modal.find('#tea-subtitle').text(this.options.subtitle || '');
            this.modal.find('#tea-submit').text(this.options.submit || 'Submit');

            //Check if the submit button is asked
            if (!this.options.submitbutton)
            {
                this.modal.find('.media-frame.wp-core-ui').addClass('hide-submit');
            }

            if (this.options.content && 'string' == typeof this.options.content)
            {
                this.modal.find('#tea-content').text(this.options.content);
            }
            else if (this.options.content && 'object' == typeof this.options.content)
            {
                var placeholder = '',
                    label = '',
                    options = '';

                $.each(this.options.content, function (i,e){
                    var then = this;
                    placeholder = this.placeholder ? 'placeholder="' + this.placeholder + '"' : '';
                    label = this.label ? '<span>' + this.label + '</span>' : '';

                    if ('text' == this.type && this.id)
                    {
                        that.modal.find('#tea-content').append('<label class="setting" for="' + this.id + '">' + label + '<input type="text" name="' + this.id + '" id="' + this.id + '" class="alignment" ' + placeholder + ' /></label>');
                    }
                    else if ('textarea' == this.type && this.id)
                    {
                        that.modal.find('#tea-content').append('<label class="setting" for="' + this.id + '">' + label + '<textarea name="' + this.id + '" id="' + this.id + '" rows="5" class="alignment" ' + placeholder + '></textarea></label>');
                    }
                    else if ('selectbox' == this.type && this.options && this.id)
                    {
                        options = '';

                        $.each(this.options, function (ind,elem){
                            options += '<option value="' + elem.value + '">' + elem.label + '</option>';
                        });

                        that.modal.find('#tea-content').append('<label class="setting" for="' + this.id + '">' + label + '<select name="' + this.id + '" id="' + this.id + '">' + options + '</select></label>');
                    }
                    else if ('html' == this.type && this.code)
                    {
                        that.modal.find('#tea-content').append('<label class="setting-html">' + label + '</label><pre>' + this.code + '</pre>');
                    }
                });

                this.modal.find('#tea-content').find('input[type="text"], textarea').focus();
            }
            else
            {
                this.modal.find('#tea-content').text('');
            }
        },

        toggle: function (){
            return this[!this.isShown ? 'show' : 'hide']();
        },

        show: function (){
            var that = this,
                e = $.Event('show');

            //this.$element.trigger(e);
            this.builtin();

            //check if modal is already shown
            if (this.isShown || e.isDefaultPrevented())
            {
                return;
            }

            this.modal.show();
            this.isShown = true;

            this.modal.find('#tea-submit').live('click', function (e){
                e.preventDefault();

                if (that.options.onsubmit && $.isFunction(that.options.onsubmit))
                {
                    that.options.onsubmit(that.modal.find('#tea-content'));
                }

                that.hide(e);
            });

            this.modal.find('.media-modal-close, .media-modal-backdrop').live('click', function (e){
                that.hide();
            });

            this.modal.find('#tea-subtitle').live('click', function (e){
                e.preventDefault();
            });
        },

        hide: function (e){
            var that = this;

            e && e.preventDefault();
            e = $.Event('hide');

            //check if is already shown
            if (!this.isShown || e.isDefaultPrevented())
            {
                return;
            }

            this.modal.hide();
            this.isShown = false;

            this.modal.find('#tea-submit').die();
            this.modal.find('.media-modal-close, .media-modal-backdrop').die();
            this.modal.find('#tea-subtitle').die();
        }
    }

    /* TEAMODAL PLUGIN DEFINITION
     * ========================== */

    var old = $.fn.teamodal;

    $.fn.teamodal = function (option){
        return this.each(function (){
            var $this = $(this),
                data = $this.data('teamodal'),
                options = $.extend({}, $.fn.teamodal.defaults, $this.data(), typeof option == 'object' && option);

            if (!data)
            {
                $this.data('teamodal', (data = new TeaModal(this, options)));
            }

            data.show();
        })
    }

    $.fn.teamodal.defaults = {
        show: true,
        submitbutton: true,
        tpl: '#__tea-template',
        id: '#__tea-shortcodes'
    }

    $.fn.teamodal.Constructor = TeaModal

    /* TEAMODAL NO CONFLICT
     * ==================== */

    $.fn.teamodal.noConflict = function (){
        $.fn.teamodal = old
        return this
    }
}(window.jQuery);
