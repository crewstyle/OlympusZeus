<!-- Content social <?php echo $id ?> -->
<div id="<?php echo $id ?>_social_content" class="tea_to_wrap checkboxes">
    <h3 class="tea_title">
        <label><?php echo $title ?></label>
        <?php if (2 < $count): ?>
            <label for="checkall" class="checkall">
                <?php _e('Un/select all options', TTO_I18N) ?>
                <input type="checkbox" id="checkall" <?php echo $count == count($vals) ? 'checked="checked"' : '' ?> />
            </label>
        <?php endif ?>
    </h3>

    <div class="inside tea-inside social social-checkbox" data-id="<?php echo $id ?>">
        <fieldset>
            <?php foreach ($socials as $key => $option): ?>
                <?php
                    //Check if we need to display the social network
                    if (!array_key_exists($key, $vals)) {
                        continue;
                    }

                    //Get values
                    $display = isset($vals[$key]['display']) ? $vals[$key]['display'] : '';
                    $label = isset($vals[$key]['label']) ? $vals[$key]['label'] : '';
                    $link = isset($vals[$key]['link']) ? $vals[$key]['link'] : '';

                    //Treat contents
                    $selected = !empty($display) && '1' == $display ? true : false;
                    $for = $id . '_' . $key;
                ?>
                <div data-network="<?php echo $key ?>">
                    <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                        <input type="hidden" name="<?php echo $id ?>__checkbox[<?php echo $key ?>]" value="0" />
                        <input type="checkbox" name="<?php echo $id ?>[<?php echo $key ?>][display]" id="<?php echo $for ?>" value="1" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                        <i class="fa fa-<?php echo $key ?> fa-lg"></i>
                    </label>

                    <?php if (isset($option[0])): ?>
                        <input type="text" name="<?php echo $id ?>[<?php echo $key ?>][label]" value="<?php echo $label ?>" placeholder="<?php echo $option[0] ?>" />
                    <?php endif ?>

                    <?php if (isset($option[1])): ?>
                        <input type="text" name="<?php echo $id ?>[<?php echo $key ?>][link]" value="<?php echo $link ?>" placeholder="<?php echo $option[1] ?>" />
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        </fieldset>

        <div class="hide-if-no-js">
            <a href="#" class="button add_social" title="<?php echo esc_html(__('Add social networks', TTO_I18N)) ?>">
                <i class="fa fa-globe fa-lg"></i> <?php _e('Add social networks', TTO_I18N) ?>
            </a>
        </div>

        <p><?php echo $description ?></p>
    </div>
</div>

<?php if (!empty($socials)): ?>
    <script type="template/html" id="modal-socials">
        <div id="" class="tea-modal" tabindex="-1" style="display:none;">
            <header>
                <a href="#" class="close">&times;</a>
                <h2><?php _e('Choose the social networks you want to display', TTO_I18N) ?></h2>
            </header>

            <div class="content-container">
                <div class="content radio-image">
                    <?php
                        foreach ($socials as $key => $option):
                            $for = 'social-networks_' . $key;
                            $datas = isset($option[0]) ? ' data-label="' . esc_html($option[0]) . '"' : '';
                            $datas .= isset($option[1]) ? ' data-link="' . esc_html($option[1]) . '"' : '';
                        ?>
                        <a href="#<?php echo $key ?>" data-network="<?php echo $key ?>" class="button button-secondary"<?php echo $datas ?>>
                            <i class="fa fa-<?php echo $key ?> fa-lg"></i> <?php echo $key ?>
                        </a>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </script>
<?php endif ?>
<!-- /Content social <?php echo $id ?> -->