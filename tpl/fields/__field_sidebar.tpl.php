                    <!-- Content sidebar <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_sidebar_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label><?php echo $title ?></label>
                        </h3>

                        <div class="inside sidebar">
                            <p>
                                <?php if (is_active_sidebar($id)): ?>
                                    <?php _e('This sidebar has activated widgets.') ?>
                                <?php else: ?>
                                    <?php _e('This sidebar has no activated widgets.') ?>
                                <?php endif ?>

                                <?php echo sprintf(__('<a href="widgets.php">See all widgets</a> to customize it.')) ?>
                            </p>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content sidebar <?php echo $id ?> -->