                    <!-- Content upload <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label for="<?php echo $id ?>"><?php echo $title ?></label>
                        </h3>

                        <div class="inside upload">
                            <div class="upload_image_via_wp">
                                <input type="text" name="<?php echo $id ?>" id="<?php echo $id ?>" value="<?php echo $val ?>" readonly="readonly" />
                                <?php echo do_action('media_buttons') ?>
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