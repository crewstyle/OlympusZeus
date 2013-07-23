                    <?php if (!$style): ?>
                    <!-- Content font style -->
                        <?php foreach ($options as $key => $option): ?>
                            <?php if ('sansserif' != $key): ?>
                                <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=<?php echo $key ?>:<?php echo $option[1] ?>" />
                            <?php endif ?>
                        <?php endforeach ?>
                        <style>
                            <?php foreach ($options as $key => $option): ?>
                                <?php if ('sansserif' != $key): ?>
                                    .gfont_<?php echo str_replace(' ', '_', $option[0]) ?> {font-family: '<?php echo $option[0] ?>', sans-serif;}
                                <?php endif ?>
                            <?php endforeach ?>
                        </style>
                    <!-- /Content font style -->
                    <?php endif ?>

                    <!-- Content font <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_font_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label><?php echo $title ?></label>
                        </h3>

                        <div class="inside gfont gfont-radio">
                            <fieldset>
                                <?php foreach ($options as $key => $option): ?>
                                    <?php
                                        $selected = $key == $val ? true : false;
                                        $for = $id . '_' . str_replace(' ', '_', $option[0]);
                                    ?>
                                    <label for="<?php echo $for ?>" class="gfont_<?php echo str_replace(' ', '_', $option[0]) ?> <?php echo $selected ? 'selected' : '' ?>">
                                        <span><?php echo $option[0] ?></span>
                                        <input type="radio" name="<?php echo $id ?>" id="<?php echo $for ?>" value="<?php echo $key ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                                    </label>
                                <?php endforeach ?>
                            </fieldset>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content font <?php echo $id ?> -->