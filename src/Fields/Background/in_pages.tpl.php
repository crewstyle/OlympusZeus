<!-- Content background <?php echo $id ?> -->
<div class="tea-screen-meta tea_to_wrap tea-background-container" data-id="<?php echo $id ?>">
    <div class="tea-contextual-help-wrap">
        <div class="tea-contextual-help-back"></div>

        <div class="tea-contextual-help-columns">
            <div class="contextual-help-tabs">
                <h2><?php echo $title ?></h2>
                <ul>
                    <li class="active">
                        <a href="#tea-background-<?php echo $id ?>-bgs"><?php _e('Backgrounds', TTO_I18N) ?></a>
                    </li>
                    <li>
                        <a href="#tea-background-<?php echo $id ?>-configs"><?php _e('Configurations', TTO_I18N) ?></a>
                    </li>
                </ul>
            </div>

            <div class="contextual-help-tabs-wrap">
                <!-- Globals -->
                <div id="tea-background-<?php echo $id ?>-bgs" class="help-tab-content tea-conf active">
                    <p><?php echo $description ?></p>

                    <?php if ($backgrounds): ?>
                        <!-- Default images -->
                        <fieldset class="bg-image">
                            <?php $backing = !empty($val['image']) ? $val['image'] : '' ?>
                            <?php foreach ($options as $key => $option): ?>
                                <?php
                                    $imgurl = $url . $key;
                                    $selected = $backing == $imgurl ? true : false;
                                    $pathinfo = pathinfo($key);
                                    $for = $id . '_' . $pathinfo['filename'];
                                ?>
                                <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected ' : '' ?>">
                                    <div class="image-to-show">
                                        <img src="<?php echo $imgurl ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
                                        <span><?php echo $option ?></span>
                                    </div>
                                    <input type="radio" name="<?php echo $id ?>[image]" id="<?php echo $for ?>" value="<?php echo $imgurl ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                                </label>
                            <?php endforeach ?>
                        </fieldset>

                        <p><?php _e('Or get your own.', TTO_I18N) ?></p>
                    <?php endif ?>

                    <!-- Custom image -->
                    <fieldset class="bg-upload" data-type="image">
                        <?php $imaging = isset($val['image_custom']) && !empty($val['image_custom']) ? $val['image_custom'] : '' ?>
                        <input type="hidden" name="<?php echo $id ?>[image_custom]" id="<?php echo $id ?>_image_custom" value="<?php echo $imaging ?>" />

                        <div class="upload_image_result">
                            <?php if (!empty($imaging)): ?>
                                <figure>
                                    <img src="<?php echo $imaging ?>" id="<?php echo $id ?>_image" alt="" />
                                    <a href="#" class="del_image" data-target="<?php echo $id ?>_image_custom">&times;</a>
                                </figure>
                            <?php endif ?>

                            <?php if ($can_upload): ?>
                                <div class="upload-time hide-if-no-js <?php echo !empty($imaging) ? 'item-added' : '' ?>" data-target="<?php echo $id ?>_image_custom">
                                    <a href="#" class="add_image" title="<?php echo esc_html(__('Add background', TTO_I18N)) ?>">
                                        <i class="fa fa-cloud-upload fa-lg"></i> <?php _e('Add background', TTO_I18N) ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <?php _e('It seems you are not able to upload files.', TTO_I18N) ?>
                            <?php endif ?>
                        </div>
                    </fieldset>

                    <br class="clear"/>
                </div>

                <!-- Configurations -->
                <div id="tea-background-<?php echo $id ?>-configs" class="help-tab-content tea-conf">
                    <!-- Custom background color -->
                    <fieldset class="bg-details bg-color">
                        <?php $coloring = isset($val['color']) && !empty($val['color']) ? $val['color'] : '' ?>
                        <input type="text" name="<?php echo $id ?>[color]" id="<?php echo $id ?>_color" value="<?php echo $coloring ?>" class="color-picker" maxlength="7" />
                    </fieldset>

                    <!-- Custom background repeat -->
                    <fieldset class="bg-details bg-repeat">
                        <select name="<?php echo $id ?>[repeat]">
                            <?php $repeating = isset($val['repeat']) && !empty($val['repeat']) ? $val['repeat'] : 'repeat' ?>
                            <?php foreach ($details['repeat'] as $key => $repeat): ?>
                                <?php $selected = $repeating == $key ? 'selected="selected"' : '' ?>
                                <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $repeat ?></option>
                            <?php endforeach ?>
                        </select>
                    </fieldset>

                    <!-- Custom background positions -->
                    <fieldset class="bg-details bg-position">
                        <select name="<?php echo $id ?>[position_x]" >
                            <?php $pxs = isset($val['position_x']) && !empty($val['position_x']) ? $val['position_x'] : 'left' ?>
                            <?php foreach ($details['position']['x'] as $key => $posx): ?>
                                <?php $selected = $pxs == $key ? 'selected="selected"' : '' ?>
                                <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $posx ?></option>
                            <?php endforeach ?>
                        </select>

                        <select name="<?php echo $id ?>[position_y]" >
                            <?php $pys = isset($val['position_y']) && !empty($val['position_y']) ? $val['position_y'] : 'top' ?>
                            <?php foreach ($details['position']['y'] as $key => $posy): ?>
                                <?php $selected = $pys == $key ? 'selected="selected"' : '' ?>
                                <option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $posy ?></option>
                            <?php endforeach ?>
                        </select>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
