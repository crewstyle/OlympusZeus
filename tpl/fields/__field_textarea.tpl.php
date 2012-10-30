                    <!-- Content textarea <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label for="<?php echo $id ?>"><?php echo $title ?></label>
                        </h3>

                        <div class="inside">
                            <textarea name="<?php echo $id ?>" id="<?php echo $id ?>" rows="8" <?php echo $placeholder ?>><?php echo $val ?></textarea>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content textarea <?php echo $id ?> -->