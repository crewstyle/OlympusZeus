<!-- Content radio <?php echo $id ?> -->
<div id="<?php echo $id ?>_radio_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label><?php echo $title ?></label>
    </h3>

    <div class="inside tea-inside radio">
        <fieldset<?php echo 'image' == $mode ? ' class="radio-image"' : '' ?>>
            <?php
                foreach ($options as $key => $option):
                    if (empty($key)) {
                        continue;
                    }

                    $selected = $key == $val ? true : false;
                    $for = $id . '_' . $key;
                ?>
                <p>
                    <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                        <input type="radio" name="<?php echo $id ?>" id="<?php echo $for ?>" value="<?php echo $key ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />

                        <?php if ('image' == $mode): ?>
                            <img src="<?php echo $option ?>" alt="" />
                        <?php else: ?>
                            <?php echo $option ?>
                        <?php endif ?>
                    </label>
                </p>
            <?php endforeach ?>
        </fieldset>

        <p><?php echo $description ?></p>
    </div>
</div>
<!-- /Content radio <?php echo $id ?> -->