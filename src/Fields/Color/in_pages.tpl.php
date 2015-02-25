<!-- Content color <?php echo $id ?> -->
<div id="<?php echo $id ?>_color_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
    </h3>

    <div class="inside tea-inside color">
        <input type="text" name="<?php echo $id ?>" id="<?php echo $id ?>" value="<?php echo $val ?>" class="color-picker" maxlength="7" />

        <p><?php echo $description ?></p>
    </div>
</div>
