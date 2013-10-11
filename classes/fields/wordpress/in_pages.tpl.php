<!-- Content Wordpress <?php echo $mode ?> <?php echo $id ?> -->
<div id="<?php echo $id ?>_<?php echo $mode ?>_content" class="tea_to_wrap stuffbox">
    <h3>
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
    </h3>

    <div class="inside <?php echo $mode ?>">
        <?php if (!empty($contents) && 2 <= count($contents)): ?>
            <?php $squares = $multiple ? '[]' : '' ?>
            <select name="<?php echo $id.$squares ?>" id="<?php echo $id ?>" <?php echo $multiple ? 'multiple="true" size="5"' : '' ?>>
                <?php foreach ($contents as $key => $option): ?>
                    <?php $selected = is_array($vals) && in_array($key, $vals) ? true : ($key == $vals ? true : false) ?>
                    <option value="<?php echo $key ?>" <?php echo $selected ? 'selected="selected" ' : '' ?>><?php echo $option ?></option>
                <?php endforeach ?>
            </select>

            <p>
                <?php echo $multiple ? __('Press the <code>CTRL</code> or <code>CMD</code> button to select more than one option.', TTO_I18N) . '<br/>' : '' ?>
                <?php echo $description ?>
            </p>
        <?php else: ?>
            <?php $multiple ? printf(__('There are no %s found.', TTO_I18N), $mode) : printf(__('There is no %s found.', TTO_I18N), $mode) ?>
        <?php endif ?>
    </div>
</div>
<!-- /Content Wordpress <?php echo $mode ?> <?php echo $id ?> -->