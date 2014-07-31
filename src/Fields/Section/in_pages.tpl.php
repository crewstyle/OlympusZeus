<div class="inside tea-inside inside-section <?php echo $color ?>">
    <?php if (!empty($image)): ?>
        <div class="<?php echo $position ?> imaged">
            <img src="<?php echo $image ?>" alt="" />
        </div>
    <?php endif ?>

    <?php if (!empty($image)): ?>
        <div class="<?php echo 'left' == $position ? 'right' : 'left' ?>">
            <?php echo $content ?>
        </div>
    <?php else: ?>
        <?php echo $content ?>
    <?php endif ?>

    <br class="clear"/>
</div>