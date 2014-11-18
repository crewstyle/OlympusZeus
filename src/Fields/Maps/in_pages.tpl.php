<!-- Content maps <?php echo $id ?> -->
<div class="tea-screen-meta tea_to_wrap">
    <div class="tea-contextual-help-wrap">
        <div class="tea-contextual-help-back"></div>

        <div class="tea-contextual-help-columns">
            <div class="contextual-help-tabs">
                <h2><?php echo $title ?></h2>
                <ul>
                    <li class="active">
                        <a href="#tea-maps-<?php echo $id ?>-globals"><?php _e('Globals', TTO_I18N) ?></a>
                    </li>
                    <li>
                        <a href="#tea-maps-<?php echo $id ?>-configs"><?php _e('Configurations', TTO_I18N) ?></a>
                    </li>
                    <li>
                        <a href="#tea-maps-<?php echo $id ?>-customs"><?php _e('Customizations', TTO_I18N) ?></a>
                    </li>
                </ul>
            </div>

            <div class="contextual-help-tabs-wrap">
                <!-- Globals -->
                <div id="tea-maps-<?php echo $id ?>-globals" class="help-tab-content active">
                    <p><?php echo $description ?></p>

                    <!-- Address -->
                    <h3><label for="<?php echo $id ?>-address"><?php _e('Address', TTO_I18N) ?></label></h3>
                    <div class="inside tea-inside text">
                        <input type="text" name="<?php echo $id ?>[address]" id="<?php echo $id ?>-address" value="<?php echo stripcslashes($vals['address']) ?>" size="30" />

                        <p><?php _e('Define your default address in which the maps will be centered.', TTO_I18N) ?></p>
                    </div>

                    <hr/>

                    <!-- Marker -->
                    <div style="float:left;margin:0 2% 0 0;width:49%;">
                        <h3><label for="<?php echo $id ?>-marker"><?php _e('Marker', TTO_I18N) ?></label></h3>
                        <div class="inside tea-inside upload" data-type="image">
                            <input type="hidden" name="<?php echo $id ?>[marker]" id="<?php echo $id ?>-marker" value="<?php echo $vals['marker'] ?>" />

                            <div class="upload_image_result">
                                <?php if (!empty($vals['marker'])): ?>
                                    <figure>
                                        <img src="<?php echo $vals['marker'] ?>" alt="" />
                                        <a href="#" class="del_image" data-target="<?php echo $id ?>-marker">&times;</i></a>
                                    </figure>
                                <?php endif ?>

                                <?php if ($can_upload): ?>
                                    <div class="upload-time hide-if-no-js <?php echo !empty($vals['marker']) ? 'item-added' : '' ?>" data-target="<?php echo $id ?>-marker">
                                        <a href="#" class="add_image" title="<?php echo esc_html(__('Add marker', TTO_I18N)) ?>">
                                            <i class="fa fa-map-marker fa-lg"></i> <?php _e('Add marker', TTO_I18N) ?>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <?php _e('It seems you are not able to upload files.', TTO_I18N) ?>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>

                    <div style="float:left;width:49%;">
                        <!-- Width -->
                        <h3><label for="<?php echo $id ?>-width"><?php _e('Width', TTO_I18N) ?></label></h3>
                        <div class="inside tea-inside number">
                            <input type="number" name="<?php echo $id ?>[width]" id="<?php echo $id ?>-width" value="<?php echo $vals['width'] ?>" size="30" step="1" />

                            <p><?php _e('Define the width maps in pixels.', TTO_I18N) ?></p>
                        </div>

                        <!-- Height -->
                        <h3><label for="<?php echo $id ?>-height"><?php _e('Height', TTO_I18N) ?></label></h3>
                        <div class="inside tea-inside number">
                            <input type="number" name="<?php echo $id ?>[height]" id="<?php echo $id ?>-height" value="<?php echo $vals['height'] ?>" size="30" step="1" />

                            <p><?php _e('Define the height maps in pixels.', TTO_I18N) ?></p>
                        </div>
                    </div>

                    <br class="clear"/>
                </div>

                <!-- Configurations -->
                <div id="tea-maps-<?php echo $id ?>-configs" class="help-tab-content">
                    <!-- Zoom -->
                    <div style="float:left;margin:0 2% 0 0;width:49%;">
                        <h3><label for="<?php echo $id ?>-zoom"><?php _e('Zoom', TTO_I18N) ?></label></h3>
                        <div class="inside tea-inside number">
                            <input type="number" name="<?php echo $id ?>[zoom]" id="<?php echo $id ?>-zoom" value="<?php echo $vals['zoom'] ?>" size="30" min="0" max="21" step="1" />

                            <p><?php _e('Define your default zoom.', TTO_I18N) ?></p>
                        </div>
                    </div>

                    <!-- Type -->
                    <div style="float:left;width:49%;">
                        <h3><label for="<?php echo $id ?>-type"><?php _e('Type', TTO_I18N) ?></label></h3>
                        <div class="inside tea-inside select">
                            <select name="<?php echo $id ?>[type]" id="<?php echo $id ?>-type">
                                <?php
                                    $opt_types = array(
                                        'ROADMAP' => __('Roadmap', TTO_I18N),
                                        'SATELLITE' => __('Satellite', TTO_I18N),
                                        'HYBRID' => __('Hybrid', TTO_I18N),
                                        'TERRAIN' => __('Terrain', TTO_I18N)
                                    );

                                    foreach ($opt_types as $key => $option):
                                        $selected = $key == $vals['type'] ? true : false;
                                ?>
                                    <option value="<?php echo '-1' == $key ? '' : $key ?>" <?php echo $selected ? 'selected="selected" ' : '' ?>><?php echo $option ?></option>
                                <?php endforeach ?>
                            </select>

                            <p><?php _e('Define your default type.', TTO_I18N) ?></p>
                        </div>
                    </div>

                    <hr class="clear"/>

                    <!-- Options -->
                    <h3>
                        <?php
                            $opt_details = array(
                                'dragndrop' => __('Drag\'n drop', TTO_I18N),
                                'streetview' => __('Street View', TTO_I18N),
                                'zoomcontrol' => __('Zoom control', TTO_I18N),

                                'mapcontrol' => __('Map control', TTO_I18N),
                                'scalecontrol' => __('Scale control', TTO_I18N),
                                'pancontrol' => __('Pan control', TTO_I18N),

                                'rotatecontrol' => __('Rotate control', TTO_I18N),
                                'rotatecontroloptions' => __('Rotate control options', TTO_I18N),
                                'scrollwheel' => __('Scroll Wheel', TTO_I18N),

                                'overviewmapcontrol' => __('Overview Map control', TTO_I18N),
                                'overviewmapcontroloptions' => __('Overview Map control options', TTO_I18N)
                            );
                        ?>
                        <label><?php _e('Options', TTO_I18N) ?></label>
                        <label for="checkall" class="checkall">
                            <?php _e('Un/select all options') ?>
                            <input type="checkbox" id="checkall" data-target="#<?php echo $id ?>-options input[type='checkbox']" <?php echo count($opt_details) == count($vals) ? 'checked="checked"' : '' ?> />
                        </label>
                    </h3>
                    <div id="<?php echo $id ?>-options" class="inside tea-inside checkbox">
                        <fieldset>
                            <?php
                                foreach ($opt_details as $key => $option):
                                    $sel = isset($vals['options'][$key]) && 'yes' == $vals['options'][$key] ? ' checked="checked"' : '';
                                    $for = $id . '-params_' . $key;
                                ?>
                                <p class="item">
                                    <label for="<?php echo $for ?>" class="<?php echo $sel ? 'selected' : '' ?>">
                                        <input type="hidden" name="<?php echo $id ?>__checkbox[<?php echo $key ?>]" value="0" />
                                        <input type="checkbox" name="<?php echo $id ?>[options][<?php echo $key ?>]" id="<?php echo $for ?>" value="yes" <?php echo $sel ?> />
                                        <?php echo $option ?>
                                    </label>
                                </p>
                            <?php endforeach ?>
                        </fieldset>

                        <p><?php _e('Define your default options.', TTO_I18N) ?></p>
                    </div>
                </div>

                <!-- Customizations -->
                <div id="tea-maps-<?php echo $id ?>-customs" class="help-tab-content">
                    <!-- Zoom -->
                    <h3>
                        <label for="<?php echo $id ?>-enable">
                            <?php _e('Enable JSON design?', TTO_I18N) ?>
                            <select name="<?php echo $id ?>[enable]" id="<?php echo $id ?>-enable" style="display:inline;width:100px;">
                                <option value="yes" <?php echo 'yes' == $vals['enable'] ? 'selected="selected" ' : '' ?>><?php _e('Yes', TTO_I18N) ?></option>
                                <option value="no" <?php echo 'no' == $vals['enable'] ? 'selected="selected" ' : '' ?>><?php _e('No', TTO_I18N) ?></option>
                            </select>
                        </label>
                    </h3>
                    <div class="inside tea-inside textarea">
                        <textarea name="<?php echo $id ?>[json]" id="<?php echo $id ?>-json" rows="16" class="textarea" placeholder="[{'featureType':'administrative', 'stylers':[{'visibility':'off'}] }]"><?php echo stripslashes($vals['json']) ?></textarea>

                        <p><?php _e('To customize your Google map, go to <a href="http://snazzymaps.com/" target="_blank"><b>SnazzyMaps</b></a> website, choose a theme and copy/paste the <code>Javascript style array</code> code in this textarea.', TTO_I18N) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Content maps <?php echo $id ?> -->
