<!-- Content Link <?php echo $id ?> -->
<div id="<?php echo $id ?>_link_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label for="<?php echo $id ?>"><?php echo $title ?></label>
    </h3>

    <div class="inside tea-inside link">
        <p class="block-link">
            <label for="<?php echo $id ?>-url"><?php _e('Web address', TTO_I18N) ?></label>
            <input type="text" name="<?php echo $id ?>[url]" id="<?php echo $id ?>-url" value="<?php echo $vals['url'] ?>" />
            <a href="<?php echo $vals['url'] ?>" target="_blank"><?php _e('Go to the Website', TTO_I18N) ?></a>
        </p>
        <p>
            <label for="<?php echo $id ?>-label"><?php _e('Title', TTO_I18N) ?></label>
            <input type="text" name="<?php echo $id ?>[label]" id="<?php echo $id ?>-label" value="<?php echo $vals['label'] ?>" />
        </p>
        <p>
            <label for="<?php echo $id ?>-target"><?php _e('Target', TTO_I18N) ?></label>
            <select name="<?php echo $id ?>[target]" id="<?php echo $id ?>-target">
                <option value="_blank" <?php echo '_blank' == $vals['target'] ? 'selected="selected"' : '' ?>>
                    <?php _e('Opens the linked document in a new window or tab', TTO_I18N) ?>
                </option>
                <option value="_self" <?php echo '_self' == $vals['target'] ? 'selected="selected"' : '' ?>>
                    <?php _e('Opens the linked document in the same frame as it was clicked', TTO_I18N) ?>
                </option>
                <option value="_parent" <?php echo '_parent' == $vals['target'] ? 'selected="selected"' : '' ?>>
                    <?php _e('Opens the linked document in the parent frame', TTO_I18N) ?>
                </option>
                <option value="_top" <?php echo '_top' == $vals['target'] ? 'selected="selected"' : '' ?>>
                    <?php _e('Opens the linked document in the full body of the window', TTO_I18N) ?>
                </option>
            </select>
        </p>
        <p class="rel">
            <label for="<?php echo $id ?>-rel"><?php _e('Relationship', TTO_I18N) ?></label>
            <input type="text" name="<?php echo $id ?>[rel]" id="<?php echo $id ?>-rel" value="<?php echo $vals['rel'] ?>" />
            <small><?php _e('You can set the <code>nofollow</code> value to avoid bots following the linked document.', TTO_I18N) ?></small>
        </p>

        <p><?php echo $description ?></p>
    </div>
</div>
