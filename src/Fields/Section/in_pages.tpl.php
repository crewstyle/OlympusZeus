<div<?php echo !empty($identifier) ? ' id="'.$identifier.'"' : ''?> class="inside tea-inside inside-section <?php echo $color ?>">
    <?php if (!empty($image) || !empty($svg)): ?>
        <div class="<?php echo $position ?> imaged">
            <?php if (!empty($image)): ?>
                <img src="<?php echo $image ?>" alt="" />
            <?php elseif (!empty($svg)): ?>
                <object><?php echo file_get_contents($svg, FILE_USE_INCLUDE_PATH) ?></object>
            <?php endif ?>
        </div>
    <?php endif ?>

    <?php if (!empty($image) || !empty($svg)): ?>
        <div class="<?php echo 'left' == $position ? 'right' : 'left' ?>">
            <?php echo $content ?>
        </div>
    <?php else: ?>
        <?php echo $content ?>
    <?php endif ?>

    <br class="clear"/>
</div>
