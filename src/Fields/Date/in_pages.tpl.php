<!-- Content Date <?php echo $id ?> -->
<div id="<?php echo $id ?>_link_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
    </h3>

    <div class="inside tea-inside date">
        <input type="text" name="<?php echo $id ?>" id="<?php echo $id ?>" value="<?php echo $val ?>" data-value="<?php echo $val ?>" placeholder="<?php echo $format ?>" data-format="<?php echo $format ?>" data-submit="<?php echo $submit ?>" class="pickadate" />
        <p><?php echo $description ?></p>
    </div>
</div>
<!-- /Content Date <?php echo $id ?> -->
