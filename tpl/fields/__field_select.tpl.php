                    <!-- Content select <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_select_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label for="<?php echo $id ?>"><?php echo $title ?></label>
                        </h3>

                        <div class="inside select">
                            <select name="<?php echo $id ?>" id="<?php echo $id ?>">
                                <?php foreach ($options as $key => $option): ?>
                                    <?php $selected = $key == $val ? true : false ?>
                                    <option value="<?php echo $key ?>" <?php echo $selected ? 'selected="selected" ' : '' ?>><?php echo $option ?></option>
                                <?php endforeach ?>
                            </select>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content select <?php echo $id ?> -->