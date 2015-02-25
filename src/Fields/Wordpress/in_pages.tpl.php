<!-- Content Wordpress <?php echo $mode ?> <?php echo $id ?> -->
<div id="<?php echo $id ?>_<?php echo $mode ?>_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
    </h3>

    <div class="inside tea-inside tea-wordpress <?php echo $mode ?>">
        <?php if (!empty($contents) && 1 <= count($contents)): ?>
            <?php $squares = $multiple ? '[]' : '' ?>
            <input type="hidden" name="<?php echo $id.$squares ?>" value="" />
            <select name="<?php echo $id.$squares ?>" id="<?php echo $id ?>" <?php echo $multiple ? 'multiple="true" size="5" data-value="'.implode(',', $vals).'"' : '' ?>>
                <?php foreach ($contents as $key => $optgroup): ?>
                    <?php if (!empty($key)): ?>
                        <optgroup label="<?php echo $key ?>">
                    <?php endif ?>

                    <?php foreach ($optgroup as $k => $option): ?>
                        <?php $sel = is_array($vals) && in_array($k, $vals) ? true : ($k == $vals ? true : false) ?>
                        <option value="<?php echo $k ?>" <?php echo $sel ? 'selected="selected" ' : '' ?>><?php echo $option ?></option>
                    <?php endforeach ?>

                    <?php if (!empty($key)): ?>
                        </optgroup>
                    <?php endif ?>
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
