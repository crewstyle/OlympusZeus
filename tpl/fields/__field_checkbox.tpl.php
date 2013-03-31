                    <!-- Content checkbox <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_content" class="checkboxes <?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label><?php echo $title ?></label>
                            <?php if (2 < count($options)): ?>
                                <label for="checkall" class="checkall">
                                    <?php _e('Un/select all options') ?>
                                    <input type="checkbox" id="checkall" />
                                </label>
                            <?php endif ?>
                        </h3>

                        <div class="inside checkbox">
                            <fieldset>
                                <?php foreach ($options as $key => $option): ?>
                                    <?php
                                        $selected = is_array($vals) && in_array($key, $vals) ? true : false;
                                        $for = $id . '_' . $key;
                                    ?>
                                    <p>
                                        <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                                            <input type="hidden" name="<?php echo $id ?>__checkbox[<?php echo $key ?>]" value="0" />
                                            <input type="checkbox" name="<?php echo $id ?>[<?php echo $key ?>]" id="<?php echo $for ?>" value="<?php echo $key ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                                            <?php echo $option ?>
                                        </label>
                                    </p>
                                <?php endforeach ?>
                            </fieldset>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content checkbox <?php echo $id ?> -->