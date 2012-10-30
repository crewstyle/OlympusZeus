                    <!-- Content image <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label><?php echo $title ?></label>
                        </h3>

                        <div class="inside image image-<?php echo $type ?>">
                            <fieldset>
                                <?php foreach ($options as $option): ?>
                                    <?php
                                        $selected = is_array($val) && in_array($option, $val) ? true : ($option == $val ? true : false);
                                        $pathinfo = pathinfo($option);
                                        $for = $id . '_' . $pathinfo['filename'];
                                        $name = $multiselect ? $id . '[' . $pathinfo['filename'] . ']' : $id;
                                    ?>
                                    <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                                        <div class="image-to-show">
                                            <img src="<?php echo $option ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
                                            <span>
                                                <?php echo $pathinfo['filename'] ?>
                                            </span>
                                        </div>
                                        <input type="<?php echo $type ?>" name="<?php echo $name ?>" id="<?php echo $for ?>" value="<?php echo $option ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                                    </label>
                                <?php endforeach ?>
                            </fieldset>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content image <?php echo $id ?> -->