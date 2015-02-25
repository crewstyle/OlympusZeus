<!-- Content toggle <?php echo $id ?> -->
<div id="<?php echo $id ?>_toggle_content" class="tea_to_wrap toggle">
    <h3 class="tea_title">
        <label><?php echo $title ?></label>
    </h3>

    <div class="inside tea-inside toggle">
        <fieldset class="<?php echo $val ? 'is-on' : 'is-off' ?>">
            <label for="<?php echo $id ?>-on" class="on">
                <input type="radio" name="<?php echo $id ?>" id="<?php echo $id ?>-on" value="1"<?php echo $val ? ' checked="checked"' : '' ?> />
                <?php _e('On', TTO_I18N) ?>
            </label>

            <label for="<?php echo $id ?>-off" class="off">
                <input type="radio" name="<?php echo $id ?>" id="<?php echo $id ?>-off" value="0"<?php echo !$val ? ' checked="checked"' : '' ?> />
                <?php _e('Off', TTO_I18N) ?>
            </label>
        </fieldset>

        <p><?php echo $description ?></p>
    </div>
</div>
