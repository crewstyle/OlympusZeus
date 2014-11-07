<!-- Content gallery <?php echo $id ?> -->
<div id="<?php echo $id ?>_gallery_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
        <a href="#" class="del_all_items" data-target="<?php echo $id ?>"><?php _e('Delete all galleries', TTO_I18N) ?></a>
    </h3>

    <div class="inside tea-inside gallery" data-title="<?php echo $title ?>">
        <p><?php echo $description ?></p>
        <input type="hidden" name="<?php echo $id ?>[]" id="<?php echo $id ?>" value="" />
        <?php wp_print_styles('editor-buttons') ?>

        <div class="upload_image_result">
            <?php if (!empty($vals)): ?>
                <ul class="upload-listing">
                    <?php
                    foreach ($vals as $key => $item):
                        if (empty($item)) {
                            continue;
                        }
                    ?>
                        <li class="list" data-id="<?php echo $item['id'] ?>">
                            <a href="#" class="del_item" data-target="<?php echo $item['id'] ?>">&times;</a>
                            <img src="<?php echo $item['image'] ?>" alt="" />
                        </li>
                    <?php endforeach ?>
                </ul>

                <?php
                foreach ($vals as $key => $item):
                    if (empty($item)) {
                        continue;
                    }
                ?>
                    <div class="item" data-id="<?php echo $item['id'] ?>">
                        <input type="hidden" name="<?php echo $id ?>[<?php echo $item['id'] ?>][image]" value="<?php echo $item['image'] ?>" />
                        <input type="hidden" name="<?php echo $id ?>[<?php echo $item['id'] ?>][id]" value="<?php echo $item['id'] ?>" />

                        <div class="gallery-editor">
                            <?php wp_editor($item['content'], $id . '_' . $item['id'] . '_content', array(
                                'media_buttons' => false,
                                'textarea_rows' => 3,
                                'textarea_name' => $id . '[' . $item['id'] . '][content]',
                                'teeny' => true,
                                'skin' => 'wordpress',
                                'tinymce' => array(
                                    'theme_advanced_buttons1' => 'bold,italic,strikethrough,bullist,numlist,blockquote,justifyleft,justifycenter,justifyright,link,unlink,close,|,youtube',
                                    'theme_advanced_buttons2' => 'formatselect,underline,justifyfull,forecolor',
                                    'theme_advanced_blockformats' => 'p,h2,h3,h4,h5,h6,pre'
                                )
                            )) ?>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
            <?php if ($can_upload): ?>
                <div class="upload-time hide-if-no-js" data-target="<?php echo $id ?>" data-edit="<?php _e('Edit', TTO_I18N) ?>">
                    <a href="#" class="add_item" title="<?php echo esc_html(__('Add item', TTO_I18N)) ?>">
                        <i class="fa fa-plus-circle fa-lg"></i> <?php _e('Add item', TTO_I18N) ?>
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
