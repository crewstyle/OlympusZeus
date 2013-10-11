<?php if (!empty($linkstylesheet) || !empty($gfontstyle)): ?>
<!-- Content font style -->
    <?php echo $linkstylesheet ?>
    <style>
        <?php echo $gfontstyle ?>
    </style>
<!-- /Content font style -->
<?php endif ?>

<!-- Content font <?php echo $id ?> -->
<div id="<?php echo $id ?>_font_content" class="tea_to_wrap stuffbox">
    <h3>
        <label><?php echo $title ?></label>
    </h3>

    <div class="inside gfont gfont-radio">
        <fieldset>
            <?php foreach ($options as $option): ?>
                <?php
                    if (empty($option[0]))
                    {
                        continue;
                    }

                    $selected = $val == $option[0] ? true : false;
                    $replaced = str_replace(' ', '_', $option[1]);
                    $for = $id . '_' . $replaced;
                ?>
                <label for="<?php echo $for ?>" class="gfont_<?php echo $replaced ?> <?php echo $selected ? 'selected' : '' ?>">
                    <span><?php echo $option[1] ?></span>
                    <input type="radio" name="<?php echo $id ?>" id="<?php echo $for ?>" value="<?php echo $option[0] ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                </label>
            <?php endforeach ?>
        </fieldset>

        <p><?php echo $description ?></p>
    </div>
</div>
<!-- /Content font <?php echo $id ?> -->