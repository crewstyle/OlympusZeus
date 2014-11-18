<!-- Content review <?php echo $id ?> -->
<div class="tea-screen-meta tea_to_wrap tea_to_review">
    <div class="tea-contextual-help-wrap">
        <div class="tea-contextual-help-back"></div>

        <div class="tea-contextual-help-columns">
            <div class="contextual-help-tabs">
                <?php if (empty($post)): ?><h2><?php echo $title ?></h2><?php endif ?>
                <ul>
                    <?php foreach ($users as $k => $author): ?>
                        <li class="<?php echo !$k ? 'active' : '' ?>">
                            <a href="#<?php echo $id ?>-author-<?php echo $author ?>">
                                <span class="details">
                                    <?php if ($auth == $author): ?>
                                        <i class="fa fa-mortar-board fa-lg"></i>
                                    <?php endif ?>
                                    <?php if ($current == $author): ?>
                                        <i class="fa fa-pencil fa-lg"></i>
                                    <?php endif ?>
                                </span>

                                <?php echo get_the_author_meta('display_name', $author) ?>
                            </a>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>

            <div class="contextual-help-tabs-wrap">
                <input type="hidden" name="<?php echo $id ?>" value="" />

                <?php
                    foreach ($users as $k => $author):
                        $note = isset($vals[$author]['note']) && !empty($vals[$author]['note']) ? $vals[$author]['note'] : 1;
                        $rate = isset($vals[$author]['rate']) ? $vals[$author]['rate'] : '';
                ?>
                    <div id="<?php echo $id ?>-author-<?php echo $author ?>" class="help-tab-content<?php echo !$k ? ' active' : '' ?>">
                        <div class="inside tea-inside radio">
                            <fieldset class="radio-rate">
                                <?php
                                    for ($i = 0; $i <= 5; $i++):
                                        $sel = $i == $note ? true : false;
                                        $for = $id . '_' . $author . '_note_' . $i;
                                    ?>
                                    <p>
                                        <label for="<?php echo $for ?>" class="<?php echo $sel ? 'selected' : '' ?>">
                                            <?php if ($current == $author || $review_all): ?>
                                                <input type="radio" name="<?php echo $id ?>[<?php echo $author ?>][note]" id="<?php echo $for ?>" value="<?php echo $i ?>" <?php echo $sel ? 'checked="checked" ' : '' ?> />
                                            <?php endif ?>

                                            <?php for ($j = 0; $j <= 5; $j++): ?>
                                                <i class="fa fa-star<?php echo $j > $i ? '-o' : '' ?> fa-lg"></i>
                                            <?php endfor ?>
                                        </label>
                                    </p>
                                <?php endfor ?>

                                <?php if ($current != $author && !$review_all): ?>
                                    <input type="hidden" name="<?php echo $id ?>[<?php echo $author ?>][note]" value="<?php echo $note ?>" />
                                <?php endif ?>
                            </fieldset>
                        </div>

                        <div class="inside tea-inside textarea">
                            <?php if ($current == $author || $review_all): ?>
                                <?php wp_editor(stripcslashes($rate), $id . '_' . $author . '_rate', array(
                                    'media_buttons' => false,
                                    'textarea_rows' => 8,
                                    'textarea_name' => $id . '[' . $author . '][rate]'
                                )) ?>
                            <?php else: ?>
                                <input type="hidden" name="<?php echo $id ?>[<?php echo $author ?>][rate]" value="<?php echo esc_html($rate) ?>" />
                                <div><?php echo stripcslashes($rate) ?></div>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
<!-- /Content review <?php echo $id ?> -->
