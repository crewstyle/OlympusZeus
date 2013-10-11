<!-- Content <?php echo $type ?> <?php echo $id ?> -->
<div id="<?php echo $id ?>_text_content" class="tea_to_wrap stuffbox">
    <h3>
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
    </h3>

    <div class="inside <?php echo $type ?>">
        <input type="<?php echo $type ?>" name="<?php echo $id ?>" id="<?php echo $id ?>" value="<?php echo $val ?>" size="30" <?php echo $placeholder ?> <?php echo $maxlength ?> <?php echo $min ?> <?php echo $max ?> <?php echo $step ?> />

        <?php if ('range' == $type): ?>
            <output id="<?php echo $id ?>_output"><?php echo $val ?></output>
        <?php endif ?>

        <p><?php echo $description ?></p>
    </div>
</div>
<!-- /Content <?php echo $type ?> <?php echo $id ?> -->