<!-- Content upload <?php echo $id ?> -->
<div id="<?php echo $id ?>_upload_content" class="tea_to_wrap stuffbox">
    <h3>
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
        <?php if ($multiple): ?>
            <a href="#" class="delall" data-target="<?php echo $id ?>"><?php _e('Delete all medias', TTO_I18N) ?></a>
        <?php endif ?>
    </h3>

    <div class="inside upload" data-del="<?php echo $delete ?>">
        <p><?php echo $description ?></p>
        <input type="hidden" name="<?php echo $id ?>" id="<?php echo $id ?>" value="<?php echo $val ?>" />

        <?php if (!empty($val)): ?>
            <div class="upload_image_result">
                <?php
                if ($multiple):
                    $vals = explode('||', $val);
                ?>
                    <ul>
                        <?php
                        foreach ($vals as $item):
                            if (empty($item)) {
                                continue;
                            }

                            $it = explode(';', $item);
                        ?>
                        <li>
                            <img src="<?php echo $it[1] ?>" alt="" id="<?php echo $it[0] ?>" />
                            <a href="#" class="delete" data-target="<?php echo $id ?>"><?php echo $delete ?></a>
                        </li>
                        <?php endforeach ?>
                    </ul>
                <?php else: ?>
                    <figure>
                        <img src="<?php echo $val ?>" id="<?php echo $id ?>" alt="" />
                        <a href="#" class="delete" data-target="<?php echo $id ?>"><?php echo $delete ?></a>
                    </figure>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if ($can_upload): ?>
            <div id="wp-<?php echo $id ?>-editor-tools" class="wp-editor-tools customized hide-if-no-js" data-target="<?php echo $id ?>" data-type="<?php echo $library ?>" data-multiple="<?php echo $multiple ?>">
                <?php do_action('media_buttons', $id) ?>
            </div>
        <?php else: ?>
            <?php _e('It seems you are not able to upload files.', TTO_I18N) ?>
        <?php endif ?>

    </div>
</div>
<!-- /Content upload <?php echo $id ?> -->