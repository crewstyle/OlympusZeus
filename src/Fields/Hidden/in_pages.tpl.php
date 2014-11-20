<!-- Content Hidden <?php echo $id ?> -->
<div id="<?php echo $id ?>_hidden_content" class="tea_to_wrap hiddenfield">
    <input type="hidden" name="<?php echo $id ?>" id="<?php echo $id ?>" value="<?php echo esc_html($val) ?>" />
    <p><?php echo sprintf(__('Hidden field <code><b>%s</b></code> with value stored: <code>%s</code>'), $id, $val) ?></p>
</div>
<!-- Content Hidden <?php echo $id ?> -->
