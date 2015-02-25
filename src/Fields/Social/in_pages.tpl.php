<!-- Content social <?php echo $id ?> -->
<div id="<?php echo $id ?>_social_content" class="tea_to_wrap checkboxes">
    <h3 class="tea_title">
        <label><?php echo $title ?></label>
        <?php if (2 < $count): ?>
            <label for="checkall-<?php echo $id ?>" class="checkall">
                <?php _e('Un/select all options', TTO_I18N) ?>
                <input type="checkbox" id="checkall-<?php echo $id ?>" <?php echo $count == count($vals) ? 'checked="checked"' : '' ?> />
            </label>
        <?php endif ?>
    </h3>

    <div class="inside tea-inside social social-checkbox" data-id="<?php echo $id ?>">
        <fieldset>
            <?php if (!empty($vals)): ?>
                <?php foreach ($vals as $k => $opt): ?>
                    <?php
                        //Get values
                        $display = isset($opt['display']) ? $opt['display'] : '';
                        $label = isset($opt['label']) ? $opt['label'] : '';
                        $link = isset($opt['link']) ? $opt['link'] : '';

                        //Treat contents
                        $selected = !empty($display) && '1' == $display ? true : false;
                        $for = $id . '_' . $k;

                        //Special cases
                        $keyc = 'vimeo' == $k ? 'vimeo-square' : (isset($diff[$k]) ? 'circle-thin' : $k);
                    ?>
                    <div class="movendrop" data-network="<?php echo $k ?>">
                        <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                            <input type="hidden" name="<?php echo $id ?>[<?php echo $k ?>][display]" value="" />
                            <input type="checkbox" name="<?php echo $id ?>[<?php echo $k ?>][display]" id="<?php echo $for ?>" value="1" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                            <i class="fa fa-<?php echo $keyc ?> fa-lg"></i>
                        </label>

                        <input type="text" name="<?php echo $id ?>[<?php echo $k ?>][label]" value="<?php echo $label ?>" placeholder="<?php echo isset($socials[$k][0]) ? $socials[$k][0] : (isset($diff[$k]['label']) ? $diff[$k]['label'] : $label) ?>" />

                        <?php if ('rss' != $k): ?>
                            <input type="text" name="<?php echo $id ?>[<?php echo $k ?>][link]" value="<?php echo $link ?>" placeholder="<?php echo isset($socials[$k][1]) ? $socials[$k][1] : (isset($diff[$k]['link']) ? $diff[$k]['link'] : $link) ?>" />
                        <?php endif ?>

                        <?php if (isset($diff[$k])): ?><?php echo $k ?><?php endif ?>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
            <?php if (!empty($news)): ?>
                <?php foreach ($news as $k => $opt): ?>
                    <?php
                        //Get values
                        $display = isset($opt['display']) ? $opt['display'] : '';
                        $label = isset($opt['label']) ? $opt['label'] : '';
                        $link = isset($opt['link']) ? $opt['link'] : '';

                        //Treat contents
                        $selected = !empty($display) && '1' == $display ? true : false;
                        $for = $id . '_' . $k;
                    ?>
                    <div class="movendrop" data-network="<?php echo $k ?>">
                        <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                            <input type="hidden" name="<?php echo $id ?>[<?php echo $k ?>][display]" value="" />
                            <input type="checkbox" name="<?php echo $id ?>[<?php echo $k ?>][display]" id="<?php echo $for ?>" value="1" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                            <i class="fa fa-<?php echo $k ?> fa-lg"></i>
                        </label>

                        <input type="text" name="<?php echo $id ?>[<?php echo $k ?>][label]" value="<?php echo $opt['label'] ?>" placeholder="<?php echo $opt['label'] ?>" />

                        <input type="text" name="<?php echo $id ?>[<?php echo $k ?>][link]" value="<?php echo $opt['link'] ?>" placeholder="<?php echo $opt['link'] ?>" />
                    </div>
                <?php endforeach ?>
            <?php endif ?>
        </fieldset>

        <?php if ($expandable): ?>
            <div class="hide-if-no-js">
                <a href="#" class="button add_social" title="<?php echo esc_html(__('Add social networks', TTO_I18N)) ?>">
                    <i class="fa fa-globe fa-lg"></i> <?php _e('Add social networks', TTO_I18N) ?>
                </a>
            </div>
        <?php endif ?>

        <p><?php echo $description ?></p>
    </div>
</div>

<?php if (!empty($socials) && $expandable): ?>
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

                            //Special cases
                            $keyc = 'vimeo' == $key ? 'vimeo-square' : $key;
                        ?>
                        <a href="#<?php echo $key ?>" data-network="<?php echo $key ?>" class="button button-secondary"<?php echo $datas ?>>
                            <i class="fa fa-<?php echo $keyc ?> fa-lg"></i> <?php echo $key ?>
                        </a>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </script>
<?php endif ?>
