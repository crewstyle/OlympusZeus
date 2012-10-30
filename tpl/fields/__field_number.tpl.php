                    <!-- Content <?php echo $type ?> <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label for="<?php echo $id ?>"><?php echo $title ?></label>
                        </h3>

                        <div class="inside text">
                            <input type="<?php echo $type ?>" name="<?php echo $id ?>" id="<?php echo $id ?>" value="<?php echo $val ?>" size="30" <?php echo $placeholder ?> <?php echo $min ?> <?php echo $max ?> <?php echo $step ?> />

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content <?php echo $type ?> <?php echo $id ?> -->