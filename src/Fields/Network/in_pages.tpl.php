<?php if ('head' == $area): ?>
    <h2><?php echo $title ?></h2>

    <div class="tea-connect">
        <label for="checkall" class="checkall">
            <?php _e('Un/select all options') ?>
            <input type="checkbox" id="checkall" />
        </label>

        <br class="clear" />
<?php else: ?>
    </div>
<?php endif ?>
