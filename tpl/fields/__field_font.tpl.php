                    <!-- Content font <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_font_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label><?php echo $title ?></label>
                        </h3>

                        <div class="inside image image-radio">
                            <fieldset>
                                <?php foreach ($options as $key => $option): ?>
                                    <?php
                                        $selected = $key == $val ? true : false;
                                        $for = $id . '_' . $key;
                                    ?>
                                    <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                                        <img src="<?php echo $option ?>" alt="" width="140" height="25" />
                                        <input type="radio" name="<?php echo $id ?>" id="<?php echo $for ?>" value="<?php echo $key ?>" <?php echo $selected ? 'checked="checked" ' : '' ?> />
                                    </label>
                                <?php endforeach ?>
                            </fieldset>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content font <?php echo $id ?> -->