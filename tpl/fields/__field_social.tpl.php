                    <!-- Content social <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label><?php echo $title ?></label>
                        </h3>

                        <div class="inside image image-checkbox">
                            <fieldset>
                                <?php foreach ($options as $option): ?>
                                    <?php
                                        $selected = is_array($val) && in_array($option, $val) ? true : false;
                                        $for = $id . '_' . $option;
                                    ?>
                                    <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                                        <img src="<?php echo $url . $option ?>.png" alt="" />
                                        <input type="hidden" name="<?php echo $id ?>__checkbox[<?php echo $option ?>]" value="0" />
                                        <input type="checkbox" name="<?php echo $id ?>[<?php echo $option ?>]" id="<?php echo $for ?>" value="<?php echo $option ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                                    </label>
                                <?php endforeach ?>
                            </fieldset>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content social <?php echo $id ?> -->