<!-- Content upload <?php echo $id ?> -->
<div id="<?php echo $id ?>_upload_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
        <?php if ($multiple): ?>
            <a href="#" class="del_all_images" data-target="<?php echo $id ?>"><?php _e('Delete all medias', TTO_I18N) ?></a>
        <?php endif ?>
    </h3>

    <div class="inside tea-inside upload" <?php echo $multiple ? 'data-multiple="1"' : '' ?> data-type="<?php echo $library ?>">
        <p><?php echo $description ?></p>
        <input type="hidden" name="<?php echo $id ?>" id="<?php echo $id ?>" value="<?php echo !empty($val) ? $val : '' ?>" />

        <div class="upload_image_result">
            <?php
            if ($multiple):
                $vals = !empty($val) ? explode('||', $val) : array();
            ?>
                <ul>
                    <?php
                    foreach ($vals as $item):
                        if (empty($item) || 'NONE' == $item) {
                            continue;
                        }

                        $it = explode(';', $item);
                    ?>
                    <li data-id="<?php echo $it[0] ?>">
                        <img src="<?php echo $it[1] ?>" alt="" id="<?php echo $it[0] ?>" />
                        <a href="#" class="del_image" data-target="<?php echo $id ?>">&times;</a>
                    </li>
                    <?php endforeach ?>

                    <?php if ($can_upload): ?>
                        <li class="upload-time hide-if-no-js" data-target="<?php echo $id ?>">
                            <a href="#" class="add_image" title="<?php echo esc_html(__('Add medias', TTO_I18N)) ?>">
                                <i class="fa fa-cloud-upload fa-lg"></i> <?php _e('Add medias', TTO_I18N) ?>
                            </a>
                        </li>
                    <?php endif ?>
                </ul>
            <?php else: ?>
                <?php if (!empty($val)): ?>
                    <figure>
                        <img src="<?php echo $val ?>" id="<?php echo $id ?>" alt="" />
                        <a href="#" class="del_image" data-target="<?php echo $id ?>">&times;</a>
                    </figure>
                <?php endif ?>

                <div class="upload-time hide-if-no-js <?php echo !empty($val) ? 'item-added' : '' ?>" data-target="<?php echo $id ?>">
                    <a href="#" class="add_image" title="<?php echo esc_html(__('Add media', TTO_I18N)) ?>">
                        <i class="fa fa-cloud-upload fa-lg"></i> <?php _e('Add media', TTO_I18N) ?>
                    </a>
                </div>
            <?php endif ?>
        </div>

        <?php if (!$can_upload): ?>
            <?php _e('It seems you are not able to upload files.', TTO_I18N) ?>
        <?php endif ?>
    </div>
</div>
<!-- /Content upload <?php echo $id ?> -->