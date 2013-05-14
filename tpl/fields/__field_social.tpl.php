                    <!-- Content social <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_social_content" class="checkboxes <?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label><?php echo $title ?></label>
                            <?php if (2 < count($options)): ?>
                                <label for="checkall" class="checkall">
                                    <?php _e('Un/select all options') ?>
                                    <input type="checkbox" id="checkall" />
                                </label>
                            <?php endif ?>
                        </h3>

                        <div class="inside social social-checkbox">
                            <fieldset>
                                <?php foreach ($options as $key => $option): ?>
                                    <?php
                                        $selected = array_key_exists($key, $val) && isset($val[$key]['display']) && $key == $val[$key]['display'] ? true : false;
                                        $for = $id . '_' . $key;
                                    ?>
                                    <div>
                                        <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                                            <input type="hidden" name="<?php echo $id ?>__checkbox[<?php echo $key ?>]" value="0" />
                                            <input type="checkbox" name="<?php echo $id ?>[<?php echo $key ?>][display]" id="<?php echo $for ?>" value="<?php echo $key ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                                            <img src="<?php echo $url . $key ?>.png" alt="" />
                                        </label>

                                        <?php if (isset($option[0])): ?>
                                            <input type="text" name="<?php echo $id ?>[<?php echo $key ?>][label]" value="<?php echo $val[$key]['label'] ?>" placeholder="<?php echo $option[0] ?>" />
                                        <?php endif ?>

                                        <?php if (isset($option[1])): ?>
                                            <input type="text" name="<?php echo $id ?>[<?php echo $key ?>][link]" value="<?php echo $val[$key]['link'] ?>" placeholder="<?php echo $option[1] ?>" />
                                        <?php endif ?>
                                    </div>
                                <?php endforeach ?>
                            </fieldset>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content social <?php echo $id ?> -->