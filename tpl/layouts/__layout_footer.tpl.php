
                    <?php if ($submit): ?>
                        <!-- Content save -->
                        <div id="save_content">
                            <div class="inside">
                                <?php submit_button(__('Update')) ?>
                            </div>
                        </div>
                        <!-- /Content save -->
                    <?php endif ?>
                </div>
                <!-- /Content block -->

                <!-- Register block -->
                <div id="postbox-container-1" class="postbox-container">
                    <div id="linksubmitdiv" class="postbox ">
                        <h3 class="hndle">
                            <span><?php _e('Informations') ?></span>
                        </h3>

                        <div class="inside">
                            <p><?php echo sprintf(__('Your Tea Theme Option is in <b>version %s</b>', TEMPLATE_DICTIONNARY), $version) ?></p>
                            <p><?php echo sprintf(__('Please, contact <a href="mailto:teatime@takeatea.com?subject=Tea Theme Options on %s - version %s"><b>teatime@takeatea.com</b></a> if you have any suggestions.', TEMPLATE_DICTIONNARY), BLOG_NAME, $version) ?></p>
                        </div>
                    </div>
                    <?php _e('&copy; 2013, Take a tea. All rights reserved.', TEMPLATE_DICTIONNARY) ?>
                </div>
                <!-- /Register block -->

            </div>
        </div>
    <?php if ($submit): ?></form><?php endif ?>
</div>