<!-- Content gallery <?php echo $id ?> -->
<div id="<?php echo $id ?>_gallery_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
        <a href="#" class="del_all_items" data-target="<?php echo $id ?>"><?php _e('Delete all galleries', TTO_I18N) ?></a>
    </h3>

    <div class="inside tea-inside gallery">
        <p><?php echo $description ?></p>
        <input type="hidden" name="<?php echo $id ?>[]" id="<?php echo $id ?>" value="" />
        <?php wp_print_styles('editor-buttons') ?>

        <div class="upload_image_result">
            <?php if (!empty($vals)): ?>
                <?php
                foreach ($vals as $key => $item):
                    if (empty($item) || 'NONE' == $item) {
                        continue;
                    }

                    $img = $item[0];
                    $iid = $item[1];
                    $val = $item[2];
                    $for = $id . '_' . $iid;
                ?>
                    <div class="item" data-id="<?php echo $iid ?>">
                        <input type="hidden" name="<?php echo $id ?>[<?php echo $iid ?>][0]" value="<?php echo $img ?>" />
                        <input type="hidden" name="<?php echo $id ?>[<?php echo $iid ?>][1]" value="<?php echo $iid ?>" />

                        <a href="#" class="del_item" data-target="<?php echo $id ?>">&times;</a>

                        <img src="<?php echo $img ?>" alt="" />

                        <div class="gallery-editor">
                            <?php wp_editor($val, $id . '_' . $iid . '_2', array(
                                'media_buttons' => false,
                                'textarea_rows' => 3,
                                'textarea_name' => $id . '[' . $iid . '][2]',
                                'teeny' => true,
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