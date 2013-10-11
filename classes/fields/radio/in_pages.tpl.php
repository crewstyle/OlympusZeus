<!-- Content radio <?php echo $id ?> -->
<div id="<?php echo $id ?>_radio_content" class="tea_to_wrap stuffbox">
    <h3>
        <label><?php echo $title ?></label>
    </h3>

    <div class="inside radio">
        <fieldset>
            <?php
                foreach ($options as $key => $option):
                    if (empty($key))
                    {
                        continue;
                    }

                    $selected = $key == $val ? true : false;
                    $for = $id . '_' . $key;
                ?>
                <p>
                    <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                        <input type="radio" name="<?php echo $id ?>" id="<?php echo $for ?>" value="<?php echo $key ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                        <?php echo $option ?>
                    </label>
                </p>
            <?php endforeach ?>
        </fieldset>

        <p><?php echo $description ?></p>
    </div>
</div>
<!-- /Content radio <?php echo $id ?> -->