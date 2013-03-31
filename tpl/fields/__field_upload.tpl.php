                    <!-- Content upload <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label for="<?php echo $id ?>"><?php echo $title ?></label>
                        </h3>

                        <div class="inside upload">
                            <div class="upload_image_via_wp">
                                <input type="text" name="<?php echo $id ?>" id="<?php echo $id ?>" value="<?php echo $val ?>" readonly="readonly" />
                                <?php if (current_user_can('upload_files')) : ?>
                                <div id="wp-<?php echo $id ?>-editor-tools" class="wp-editor-tools customized hide-if-no-js">
                                    <?php do_action('media_buttons', $id) ?>
                                </div>
                                <?php else: ?>
                                    <?php _e('It seems you are not able to upload files.') ?>
                                <?php endif ?>
                            </div>

                            <?php if (!empty($val)): ?>
                                <br class="clear"/>

                                <div class="upload_image_result">
                                    <a href="<?php echo $val ?>" target="_blank">
                                        <img src="<?php echo $val ?>" alt="" />
                                    </a>
                                    <a href="<?php echo $val ?>" target="_blank">
                                        <?php _e('See image in its real sizes.') ?>
                                    </a>
                                </div>
                            <?php endif ?>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content upload <?php echo $id ?> -->