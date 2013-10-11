<!-- Content textarea <?php echo $id ?> -->
<div id="<?php echo $id ?>_textarea_content" class="tea_to_wrap stuffbox">
    <h3>
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
    </h3>

    <div class="inside textarea">
        <textarea name="<?php echo $id ?>" id="<?php echo $id ?>" rows="<?php echo $rows ?>" <?php echo $placeholder ?>><?php echo $val ?></textarea>

        <p><?php echo $description ?></p>
    </div>
</div>
<!-- /Content textarea <?php echo $id ?> -->