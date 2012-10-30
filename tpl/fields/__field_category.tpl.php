                    <!-- Content category <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label for="<?php echo $id ?>"><?php echo $title ?></label>
                        </h3>

                        <div class="inside category">
                            <select name="<?php echo $id ?>" id="<?php echo $id ?>" <?php echo $multiselect ? 'multiple size="5"' : '' ?>>
                                <?php foreach ($categories as $key => $cat): ?>
                                    <?php $selected = is_array($vals) && in_array($key, $vals) ? true : ($key == $vals ? true : false) ?>
                                    <option value="<?php echo $key ?>" <?php echo $selected ? 'selected="selected" ' : '' ?>><?php echo $cat ?></option>
                                <?php endforeach ?>
                            </select>

                            <p>
                                <?php echo $multiselect ? __('Press the <code>CTRL</code> or <code>CMD</code> button to select more than one option.') . '<br/>' : '' ?>
                                <?php echo $description ?>
                            </p>
                        </div>
                    </div>
                    <!-- /Content category <?php echo $id ?> -->