<!-- Content rte <?php echo $id ?> -->
<div id="<?php echo $id ?>_rte_content" class="tea_to_wrap inside-rte">
    <h3 class="tea_title">
        <label for="<?php echo $id ?>">
            <?php echo $title ?>
            <small><?php echo $description ?></small>
        </label>
    </h3>

    <?php wp_editor($val, $id, array('textarea_rows' => '10')) ?>
</div>
<!-- /Content rte <?php echo $id ?> -->