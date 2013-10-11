<!-- Content rte <?php echo $id ?> -->
<div id="<?php echo $id ?>_rte_content" class="tea_to_wrap inside-rte">
    <h3>
        <label for="<?php echo $id ?>">
            <?php echo $title ?>
            <small><?php echo $description ?></small>
        </label>
    </h3>

    <div id="poststuff">
        <?php wp_editor($val, $id) ?>
    </div>
</div>
<!-- /Content rte <?php echo $id ?> -->