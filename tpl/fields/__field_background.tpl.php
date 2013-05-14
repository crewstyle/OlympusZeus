                    <!-- Content background <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_background_content" class="checkboxes <?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label><?php echo $title ?></label>
                        </h3>

                        <div class="inside background">
                            <p><?php echo $description ?></p>

                            <?php if ($defaults): ?>
                                <fieldset class="bg-image">
                                    <?php $backing = isset($val['image']) && !empty($val['image']) ? $val['image'] : '' ?>
                                    <?php foreach ($options as $key => $option): ?>
                                        <?php
                                            if (!$can_upload && 'CUSTOM' == $option) {
                                                continue;
                                            }

                                            $selected = $backing == $key ? true : false;
                                            $pathinfo = pathinfo($key);
                                            $for = $id . '_' . $pathinfo['filename'];
                                        ?>
                                        <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected ' : '' ?>">
                                            <div class="image-to-show">
                                                <img src="<?php echo $key ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
                                                <?php if ('CUSTOM' != $option): ?>
                                                    <span>
                                                        <?php echo $option ?>
                                                    </span>
                                                <?php endif ?>
                                            </div>
                                            <input type="radio" name="<?php echo $id ?>[image]" id="<?php echo $for ?>" value="<?php echo $key ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                                        </label>
                                    <?php endforeach ?>
                                </fieldset>

                                <hr/>
                            <?php endif ?>

                            <fieldset class="bg-upload" data-del="<?php echo $delete ?>">
                                <?php $imaging = isset($val['image_custom']) && !empty($val['image_custom']) ? $val['image_custom'] : '' ?>
                                <input type="hidden" name="<?php echo $id ?>[image_custom]" id="<?php echo $id ?>_image_custom" value="<?php echo $imaging ?>" />

                                <?php if (!empty($imaging)): ?>
                                    <div class="upload_image_result">
                                        <figure>
                                            <img src="<?php echo $imaging ?>" id="<?php echo $id ?>_image" alt="" />
                                            <a href="#" class="delete" data-target="<?php echo $id ?>_image_custom"><?php echo $delete ?></a>
                                        </figure>
                                    </div>
                                <?php endif ?>

                                <?php if ($can_upload): ?>
                                    <div id="wp-<?php echo $id ?>-editor-tools" class="wp-editor-tools customized hide-if-no-js" data-target="<?php echo $id ?>_image_custom" data-type="image" data-multiple="0">
                                        <?php do_action('media_buttons', $id) ?>
                                        <p><?php _e('Do not forget to check the "Custom" background to set yours.') ?></p>
                                    </div>
                                <?php else: ?>
                                    <?php _e('It seems you are not able to upload files.') ?>
                                <?php endif ?>
                            </fieldset>

                            <hr/>

                            <fieldset class="bg-details bg-color">
                                <?php $coloring = isset($val['color']) && !empty($val['color']) ? $val['color'] : '' ?>
                                <input type="text" name="<?php echo $id ?>[color]" id="<?php echo $id ?>_color" value="<?php echo $coloring ?>" class="color-picker" maxlength="7" />
                            </fieldset>

                            <fieldset class="bg-details bg-repeat">
                                <?php $repeating = isset($val['repeat']) && !empty($val['repeat']) ? $val['repeat'] : 'repeat' ?>
                                <?php foreach ($repeats as $key => $repeat): ?>
                                    <?php
                                        $for = $id . '_rep_' . $key;
                                        $selected = $repeating == $key ? true : false;
                                    ?>
                                    <p>
                                        <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                                            <input type="radio" name="<?php echo $id ?>[repeat]" id="<?php echo $for ?>" value="<?php echo $key ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                                            <?php echo $repeat ?>
                                        </label>
                                    </p>
                                <?php endforeach ?>
                            </fieldset>

                            <fieldset class="bg-details bg-position">
                                <?php
                                    $for = $id . '_position_x';
                                    $pos_x = isset($val['position_x']) && !empty($val['position_x']) ? $val['position_x'] : 0;
                                    $pos_xs = isset($val['position_x_pos']) && !empty($val['position_x_pos']) ? $val['position_x_pos'] : 'left';
                                ?>
                                <p>
                                    <label for="<?php echo $for ?>">
                                        <span><?php _e('Horizontal background position.') ?></span>
                                        <input type="number" name="<?php echo $id ?>[position_x]" id="<?php echo $for ?>" value="<?php echo $pos_x ?>" min="0" />
                                        <select name="<?php echo $id ?>[position_x_pos]">
                                            <optgroup label="<?php _e('Custom values') ?>">
                                                <option value="px" <?php echo 'px' == $pos_xs ? 'selected="selected" ' : '' ?>><?php _e('Custom value in px') ?></option>
                                                <option value="%" <?php echo '%' == $pos_xs ? 'selected="selected" ' : '' ?>><?php _e('Custom value in %') ?></option>
                                            </optgroup>
                                            <optgroup label="<?php _e('Authorized ') ?>">
                                                <option value="left" <?php echo 'left' == $pos_xs ? 'selected="selected" ' : '' ?>><?php _e('Left') ?></option>
                                                <option value="center" <?php echo 'center' == $pos_xs ? 'selected="selected" ' : '' ?>><?php _e('Center') ?></option>
                                                <option value="right" <?php echo 'right' == $pos_xs ? 'selected="selected" ' : '' ?>><?php _e('Right') ?></option>
                                            </optgroup>
                                        </select>
                                    </label>
                                    <div class="clearfix"></div>
                                </p>

                                <?php
                                    $for = $id . '_position_y';
                                    $pos_y = isset($val['position_y']) && !empty($val['position_y']) ? $val['position_y'] : 0;
                                    $pos_ys = isset($val['position_y_pos']) && !empty($val['position_y_pos']) ? $val['position_y_pos'] : 'top';
                                ?>
                                <p>
                                    <label for="<?php echo $for ?>">
                                        <span><?php _e('Vertical background position.') ?></span>
                                        <input type="number" name="<?php echo $id ?>[position_y]" id="<?php echo $for ?>" value="<?php echo $pos_y ?>" min="0" />
                                        <select name="<?php echo $id ?>[position_y_pos]">
                                            <optgroup label="<?php _e('Custom values') ?>">
                                                <option value="px" <?php echo 'px' == $pos_ys ? 'selected="selected" ' : '' ?>><?php _e('Custom value in px') ?></option>
                                                <option value="%" <?php echo '%' == $pos_ys ? 'selected="selected" ' : '' ?>><?php _e('Custom value in %') ?></option>
                                            </optgroup>
                                            <optgroup label="<?php _e('Authorized ') ?>">
                                                <option value="top" <?php echo 'top' == $pos_ys ? 'selected="selected" ' : '' ?>><?php _e('Top') ?></option>
                                                <option value="middle" <?php echo 'middle' == $pos_ys ? 'selected="selected" ' : '' ?>><?php _e('Middle') ?></option>
                                                <option value="bottom" <?php echo 'bottom' == $pos_ys ? 'selected="selected" ' : '' ?>><?php _e('Bottom') ?></option>
                                            </optgroup>
                                        </select>
                                    </label>
                                    <div class="clearfix"></div>
                                </p>

                                <small><?php _e('The position can be a custom number in <code>pixels</code> / <code>percent</code>, or an option authorized in the displayed selectboxes.') ?></small>
                            </fieldset>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <!-- /Content background <?php echo $id ?> -->