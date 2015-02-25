<!-- Content select <?php echo $id ?> -->
<div id="<?php echo $id ?>_select_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
    </h3>

    <div class="inside tea-inside select">
        <select name="<?php echo $id ?>" id="<?php echo $id ?>">
            <?php if (!empty($options)): ?>
                <?php
                    foreach ($options as $key => $option):
                        if (empty($key)) {
                            continue;
                        }

                        $selected = $key == $val ? true : false;
                ?>
                    <option value="<?php echo '-1' == $key ? '' : $key ?>" <?php echo $selected ? 'selected="selected" ' : '' ?>><?php echo $option ?></option>
                <?php endforeach ?>
            <?php else: ?>
                <?php _e('Something went wrong in your parameters definition: no options have been defined.') ?>
            <?php endif ?>
        </select>

        <p><?php echo $description ?></p>
    </div>
</div>
