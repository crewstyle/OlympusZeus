                    <!-- Content rte <?php echo $id ?> -->
                    <div id="<?php echo $id ?>_rte_content" class="<?php echo $group ? 'smallbox' : 'stuffbox' ?>">
                        <h3>
                            <label for="<?php echo $id ?>"><?php echo $title ?></label>
                        </h3>

                        <div class="inside rte">
                            <textarea name="<?php echo $id ?>" id="<?php echo $id ?>" rows="<?php echo $rows ?>" <?php echo $placeholder ?>><?php echo $val ?></textarea>

                            <p><?php echo $description ?></p>
                        </div>
                    </div>
                    <!-- /Content rte <?php echo $id ?> -->