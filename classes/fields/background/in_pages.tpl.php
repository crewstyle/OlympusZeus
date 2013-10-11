<!-- Content background <?php echo $id ?> -->
<div id="<?php echo $id ?>_background_content" class="tea_to_wrap checkboxes stuffbox">
    <h3>
        <label><?php echo $title ?></label>
    </h3>

    <div class="inside background">
        <p><?php echo $description ?></p>

        <?php if ($default): ?>
            <!-- Default images -->
            <fieldset class="bg-image">
                <?php $backing = !empty($val['image']) ? $val['image'] : '' ?>
                <?php foreach ($options as $key => $option): ?>
                    <?php
                        if (!$can_upload && 'CUSTOM' == $option) {
                            continue;
                        }

                        $imgurl = $url . $key;
                        $selected = $backing == $imgurl ? true : false;
                        $pathinfo = pathinfo($key);
                        $for = $id . '_' . $pathinfo['filename'];
                    ?>
                    <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected ' : '' ?>">
                        <div class="image-to-show">
                            <img src="<?php echo $imgurl ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
                            <?php if ('CUSTOM' != $option): ?>
                                <span>
                                    <?php echo $option ?>
                                </span>
                            <?php endif ?>
                        </div>
                        <input type="radio" name="<?php echo $id ?>[image]" id="<?php echo $for ?>" value="<?php echo $imgurl ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                    </label>
                <?php endforeach ?>
            </fieldset>
            <!-- /Default images -->

            <p><?php _e('To upload your own background, check the "CUSTOM" image.', TTO_I18N) ?></p>

            <hr/>
        <?php endif ?>

        <!-- Custom image -->
        <fieldset class="bg-upload" data-del="<?php echo $delete ?>">
            <?php $imaging = isset($val['image_custom']) && !empty($val['image_custom']) ? $val['image_custom'] : '' ?>
            <input type="hidden" name="<?php echo $id ?>[image_custom]" id="<?php echo $id ?>_image_custom" value="<?php echo $imaging ?>" />

            <div class="upload_image_result">
                <figure>
                    <?php if (!empty($imaging)): ?>
                        <img src="<?php echo $imaging ?>" id="<?php echo $id ?>_image" alt="" />
                        <a href="#" class="delete" data-target="<?php echo $id ?>_image_custom"><?php echo $delete ?></a>
                    <?php endif ?>
                </figure>
            </div>

            <?php if ($can_upload): ?>
                <div id="wp-<?php echo $id ?>-editor-tools" class="wp-editor-tools customized hide-if-no-js" data-target="<?php echo $id ?>_image_custom" data-type="image" data-multiple="0">
                    <?php do_action('media_buttons', $id) ?>
                </div>
            <?php else: ?>
                <?php _e('It seems you are not able to upload files.', TTO_I18N) ?>
            <?php endif ?>
        </fieldset>
        <!-- /Custom image -->

        <hr/>

        <!-- Custom background color -->
        <fieldset class="bg-details bg-color">
            <?php $coloring = isset($val['color']) && !empty($val['color']) ? $val['color'] : '' ?>
            <input type="text" name="<?php echo $id ?>[color]" id="<?php echo $id ?>_color" value="<?php echo $coloring ?>" class="color-picker" maxlength="7" />
        </fieldset>
        <!-- /Custom background color -->

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
        <!-- /Custom background repeat -->

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
        <!-- /Custom background positions -->

        <div class="clearfix"></div>
    </div>
</div>
<!-- /Content background <?php echo $id ?> -->