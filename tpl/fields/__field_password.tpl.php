                    <!-- Content password <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label for="<?php echo $id ?>"><?php echo $title ?></label>
                        </h3>

                        <div class="inside password">
                            <input type="password" name="<?php echo $id ?>" id="<?php echo $id ?>" value="<?php echo $val ?>" size="30" <?php echo $placeholder ?> />

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content password <?php echo $id ?> -->