                    <!-- Content menu <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_menu_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label><?php echo $title ?></label>
                        </h3>

                        <div class="inside">
                            <p>
                                <?php if (has_nav_menu($id)): ?>
                                    <?php wp_nav_menu(array('theme_location' => $id)) ?>
                                    <hr/>
                                <?php else: ?>
                                    <?php _e('This menu does not exist.') ?>
                                <?php endif ?>
                            </p>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content menu <?php echo $id ?> -->