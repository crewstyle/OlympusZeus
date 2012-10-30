                    <!-- Content color <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label for="<?php echo $id ?>"><?php echo $title ?></label>
                        </h3>

                        <div class="inside color">
                            <input type="text" name="<?php echo $id ?>" id="<?php echo $id ?>" value="<?php echo $val ?>" class="color-picker" maxlength="7" readonly="readonly" style="color:<?php echo $val ?>" />

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content color <?php echo $id ?> -->