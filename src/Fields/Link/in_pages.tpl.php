<!-- Content Link <?php echo $id ?> -->
<div id="<?php echo $id ?>_link_content" class="tea_to_wrap">
    <h3 class="tea_title">
        <label for="<?php echo $ipt ?>"><?php echo $title ?></label>
    </h3>

    <div class="inside tea-inside link" data-id="<?php echo $id ?>">
        <?php
            $links = !$expandable ? array($vals) : $vals;
            $num = 0;

            foreach ($links as $k => $v):
                $num++;
                $ipt = !$expandable ? $id : $id.'['.$num.']';
        ?>
            <div class="link-container<?php echo $expandable ? ' link-expand' : '' ?>" data-num="<?php echo $num ?>">
                <p class="block-link">
                    <?php if ($expandable && 1 < $num): ?>
                        <a href="#" class="del_link"><i class="fa fa-times"></i></a>
                    <?php endif ?>

                    <label for="<?php echo $ipt ?>-url"><?php _e('Web address', TTO_I18N) ?></label>
                    <input type="text" name="<?php echo $ipt ?>[url]" id="<?php echo $ipt ?>-url" value="<?php echo $v['url'] ?>" />
                    <a href="<?php echo $v['url'] ?>" target="_blank"><?php _e('Go to the Website', TTO_I18N) ?></a>
                </p>
                <p>
                    <label for="<?php echo $ipt ?>-label"><?php _e('Title', TTO_I18N) ?></label>
                    <input type="text" name="<?php echo $ipt ?>[label]" id="<?php echo $ipt ?>-label" value="<?php echo htmlentities($v['label']) ?>" />
                </p>
                <p>
                    <label for="<?php echo $ipt ?>-target"><?php _e('Target', TTO_I18N) ?></label>
                    <select name="<?php echo $ipt ?>[target]" id="<?php echo $ipt ?>-target">
                        <option value="_blank" <?php echo '_blank' == $v['target'] ? 'selected="selected"' : '' ?>>
                            <?php _e('Opens the linked document in a new window or tab', TTO_I18N) ?>
                        </option>
                        <option value="_self" <?php echo '_self' == $v['target'] ? 'selected="selected"' : '' ?>>
                            <?php _e('Opens the linked document in the same frame as it was clicked', TTO_I18N) ?>
                        </option>
                        <option value="_parent" <?php echo '_parent' == $v['target'] ? 'selected="selected"' : '' ?>>
                            <?php _e('Opens the linked document in the parent frame', TTO_I18N) ?>
                        </option>
                        <option value="_top" <?php echo '_top' == $v['target'] ? 'selected="selected"' : '' ?>>
                            <?php _e('Opens the linked document in the full body of the window', TTO_I18N) ?>
                        </option>
                    </select>
                </p>
                <p class="rel">
                    <label for="<?php echo $ipt ?>-rel"><?php _e('Relationship', TTO_I18N) ?></label>
                    <input type="text" name="<?php echo $ipt ?>[rel]" id="<?php echo $ipt ?>-rel" value="<?php echo $v['rel'] ?>" />
                    <small><?php _e('You can set the <code>nofollow</code> value to avoid bots following the linked document.', TTO_I18N) ?></small>
                </p>
            </div>
        <?php endforeach ?>

        <?php if ($expandable): ?>
            <div class="hide-if-no-js">
                <a href="#" class="button add_link" title="<?php echo esc_html(__('Add link', TTO_I18N)) ?>">
                    <i class="fa fa-link fa-lg"></i> <?php _e('Add link', TTO_I18N) ?>
                </a>
            </div>
        <?php endif ?>

        <p><?php echo $description ?></p>
    </div>
</div>
