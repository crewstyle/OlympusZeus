
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
                            <?php echo sprintf(__('<p>Please, contact <a href="mailto:teatime@takeatea.com?subject=Tea Theme Options on %s">teatime@takeatea.com</a> if you have any suggestions.</p><p>Your Teamakers will always be here for you ;)</p>', TEMPLATE_DICTIONNARY), BLOG_NAME) ?>
                            <hr/>
                            <?php _e('&copy; 2013, Take a tea. All rights reserved.', TEMPLATE_DICTIONNARY) ?>
                        </div>
                    </div>
                </div>
                <!-- /Register block -->

            </div>
        </div>

    </form>
</div>