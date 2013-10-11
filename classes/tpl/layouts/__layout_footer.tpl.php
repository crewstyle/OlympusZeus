
                    <?php if ($submit): ?>
                        <!-- Content save -->
                        <div id="save_content">
                            <div class="inside">
                                <?php submit_button(__('Update', TTO_I18N)) ?>
                            </div>
                        </div>
                        <!-- /Content save -->
                    <?php endif ?>
                </div>
                <!-- /Content block -->

                <!-- Register block -->
                <div id="postbox-container-1" class="postbox-container">
                    <div id="linksubmitdiv" class="postbox">
                        <h3 class="hndle">
                            <span><?php _e('Informations', TTO_I18N) ?></span>
                        </h3>

                        <div class="inside">
                            <p><?php echo sprintf(__('Your Tea Theme Option is in <b>version %s</b>', TTO_I18N), $version) ?></p>
                            <p><?php echo sprintf(__('Please, contact <a href="mailto:teatime@takeatea.com?subject=Tea Theme Options on %s - version %s"><b>teatime@takeatea.com</b></a> if you have any suggestions.', TTO_I18N), get_bloginfo('name'), $version) ?></p>
                        </div>
                    </div>
                    <?php _e('&copy; 2013, Take a tea. All rights reserved.', TTO_I18N) ?>
                </div>
                <!-- /Register block -->

            </div>
        </div>
    <?php if ($submit): ?></form><?php endif ?>
</div>

<script type="template/html" id="__tea-template">
    <div id="__tea-shortcodes" class="tea-shortcodes" tabindex="0" style="display:none;">
        <div class="media-modal wp-core-ui">
            <a class="media-modal-close" href="#" title="Fermer"><span class="media-modal-icon"></span></a>

            <div class="media-modal-content">
                <div class="media-frame wp-core-ui">
                    <div class="media-frame-title">
                        <h1 id="tea-title"></h1>
                    </div>
                    <div class="media-frame-router">
                        <div class="media-router">
                            <a href="#" id="tea-subtitle" class="media-menu-item active"></a>
                        </div>
                    </div>
                    <div class="media-frame-content">
                        <div class="media-embed">
                            <ul class="attachments ui-sortable ui-sortable-disabled">
                                <li id="tea-content"></li>
                            </ul>
                        </div>
                    </div>
                    <div class="media-frame-toolbar">
                        <div class="media-toolbar">
                            <div class="media-toolbar-primary">
                                <a href="#" id="tea-submit" class="button media-button button-primary button-large media-button-insert"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="media-modal-backdrop"></div>
    </div>
</script>