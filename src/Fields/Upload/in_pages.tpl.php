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

        <div class="upload_image_result">
            <?php if ($multiple): ?>
                <ul>
                    <?php
                        foreach ($vals as $item):
                            if (!isset($item['id']) || empty($item['id'])) {
                                continue;
                            }

                            $ids = $item['id'];
                            $url = isset($item['url']) ? $item['url'] : '';
                            $name = isset($item['name']) ? $item['name'] : '';

                            $url = 'image' == $library ? $url : $wplink;
                            $cls = 'image' == $library ? ' class="image"' : '';
                            $det = 'image' == $library ? '' : '<br/><small>' . $name . '</small>';
                    ?>
                        <li id="<?php echo $id ?>__<?php echo $item['id'] ?>">
                            <input type="hidden" name="<?php echo $id ?>[<?php echo $ids ?>][url]" value="<?php echo $url ?>" />
                            <input type="hidden" name="<?php echo $id ?>[<?php echo $ids ?>][id]" value="<?php echo $ids ?>" />
                            <input type="hidden" name="<?php echo $id ?>[<?php echo $ids ?>][name]" value="<?php echo $name ?>" />

                            <?php if ('video' == $library): ?>
                                <video src="<?php echo $url ?>" controls></video>
                            <?php else: ?>
                                <img src="<?php echo $url ?>" alt=""<?php echo $cls ?> />
                            <?php endif ?>

                            <?php echo $det ?>
                            <a href="#" class="del_image">&times;</a>
                        </li>
                    <?php endforeach ?>

                    <?php if ($can_upload): ?>
                        <li class="upload-time hide-if-no-js" data-target="<?php echo $id ?>">
                            <input type="hidden" name="<?php echo $id ?>[0][url]" value="" />
                            <input type="hidden" name="<?php echo $id ?>[0][id]" value="" />
                            <input type="hidden" name="<?php echo $id ?>[0][name]" value="" />

                            <a href="#" class="add_image" title="<?php echo esc_html(__('Add medias', TTO_I18N)) ?>">
                                <i class="fa fa-cloud-upload fa-lg"></i> <?php _e('Add medias', TTO_I18N) ?>
                            </a>
                        </li>
                    <?php endif ?>
                </ul>
            <?php else: ?>
                <?php
                    if (!empty($vals)):
                        $ids = isset($vals['id']) ? $vals['id'] : '';
                        $url = isset($vals['url']) ? $vals['url'] : '';
                        $name = isset($vals['name']) ? $vals['name'] : '';
                ?>
                    <figure>
                        <input type="hidden" name="<?php echo $id ?>[url]" value="<?php echo $url ?>" />
                        <input type="hidden" name="<?php echo $id ?>[id]" value="<?php echo $ids ?>" />
                        <input type="hidden" name="<?php echo $id ?>[name]" value="<?php echo $name ?>" />

                        <?php if ('image' == $library): ?>
                            <img src="<?php echo $url ?>" alt="" class="image" />
                        <?php elseif ('application' == $library): ?>
                            <img src="<?php echo $wplink ?>" alt="" />
                            <br/><small><?php echo $name ?></small>
                        <?php elseif ('video' == $library): ?>
                            <video src="<?php echo $url ?>" controls></video>
                            <br/><small><?php echo $name ?></small>
                        <?php endif ?>

                        <a href="#" class="del_image">&times;</a>
                    </figure>
                <?php endif ?>

                <div class="upload-time hide-if-no-js <?php echo !empty($vals) ? 'item-added' : '' ?>" data-target="<?php echo $id ?>">
                    <input type="hidden" name="<?php echo $id ?>[url]" value="" />
                    <input type="hidden" name="<?php echo $id ?>[id]" value="" />
                    <input type="hidden" name="<?php echo $id ?>[name]" value="" />

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
