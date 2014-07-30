<!-- Content textarea <?php echo $id ?> -->
<div id="<?php echo $id ?>_textarea_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
    </h3>

    <div class="inside tea-inside textarea">
        <textarea name="<?php echo $id ?>" id="<?php echo $id ?>" rows="<?php echo $rows ?>" <?php echo $placeholder ?>><?php echo $val ?></textarea>

        <p><?php echo $description ?></p>
    </div>
</div>
<!-- /Content textarea <?php echo $id ?> -->