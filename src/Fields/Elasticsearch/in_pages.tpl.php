<!-- Content elasticsearch -->
<h2><?php echo $title ?></h2>

<?php if ('yes' == $vals['enable'] && 200 != $vals['status']): ?>
    <div class="alert alert-warning">
        <?php if (404 == $vals['status']): ?>
            <p>
                <?php _e('The connection seems good but there is no "Index name" as you have set. Click on the "Create index" button.', TTO_I18N) ?>
            </p>
        <?php else: ?>
            <p>
                <?php _e('It seems there is a problem with your connection parameters. Please, check the "Configurations" tab.', TTO_I18N) ?>
            </p>
        <?php endif ?>
    </div>
<?php elseif ('yes' == $vals['enable'] && empty($index)): ?>
    <div class="alert alert-success">
        <p>
            <?php _e('Your parameters are well configured. You can now use Elasticsearch in your website. Here is the last step: click on the "Index contents" button to index your old posts.', TTO_I18N) ?>
        </p>
    </div>
<?php endif ?>

<div class="tea-screen-meta tea_to_wrap elasticsearch">
    <div class="tea-contextual-help-wrap">
        <div class="tea-contextual-help-back"></div>

        <div class="tea-contextual-help-columns">
            <div class="contextual-help-tabs">
                <h3><?php _e('Elasticsearch', TTO_I18N) ?></h3>
                <ul>
                    <li class="active">
                        <a href="#<?php echo $id ?>-globals"><?php _e('Globals', TTO_I18N) ?></a>
                    </li>
                    <?php if ('yes' == $vals['enable']): ?>
                        <li>
                            <a href="#<?php echo $id ?>-configs"><?php _e('Configurations', TTO_I18N) ?></a>
                        </li>
                        <li>
                            <a href="#<?php echo $id ?>-indexing"><?php _e('Indexing contents', TTO_I18N) ?></a>
                        </li>
                    <?php endif ?>

                    <li class="hr">
                        <hr/>
                    </li>

                    <?php /*if ('yes' == $vals['enable'] && !empty($index)): ?>
                        <li>
                            <a href="#<?php echo $id ?>-stats"><?php _e('Statistics', TTO_I18N) ?> (<?php _e('Coming soon', TTO_I18N) ?>)</a>
                        </li>
                        <li class="hr">
                            <hr/>
                        </li>
                    <?php endif*/ ?>
                </ul>

                <div class="forms">
                    <?php if ('yes' == $vals['enable'] && (!empty($vals['index_post']) || !empty($vals['index_tax']))): ?>
                        <?php if (404 == $vals['status']): ?>
                            <form action="admin.php?page=<?php echo $page ?>&action=tea_action&for=elasticsearch" method="post">
                                <input type="hidden" name="tea_elastic_create" value="1" />
                                <button type="submit" class="button button-create"><?php _e('Create index', TTO_I18N) ?></button>
                            </form>
                        <?php elseif (200 == $vals['status']): ?>
                            <form action="admin.php?page=<?php echo $page ?>&action=tea_action&for=elasticsearch" method="post">
                                <input type="hidden" name="tea_elastic_index" value="1" />
                                <button type="submit" class="button button-index"><?php _e('Index contents', TTO_I18N) ?></button>
                            </form>
                        <?php endif ?>
                    <?php endif ?>

                    <form action="admin.php?page=<?php echo $page ?>&action=tea_action&for=elasticsearch" method="post">
                        <input type="hidden" name="tea_elastic_enable" value="1" />

                        <?php if ('no' == $vals['enable']): ?>
                            <input type="hidden" name="<?php echo $id ?>[enable]" value="yes" />
                            <button type="submit" class="button button-primary"><?php _e('Enable Elasticsearch', TTO_I18N) ?></button>
                        <?php else: ?>
                            <input type="hidden" name="<?php echo $id ?>[enable]" value="no" />
                            <button type="submit" class="submitdelete"><?php _e('Disable Elasticsearch', TTO_I18N) ?></button>
                        <?php endif ?>
                    </form>
                </div>
            </div>

            <form action="admin.php?page=<?php echo $page ?>&action=tea_action&for=elasticsearch" method="post" class="contextual-help-tabs-wrap">
                <input type="hidden" name="<?php echo $id ?>[enable]" value="yes" />

                <!-- Globals -->
                <div id="<?php echo $id ?>-globals" class="help-tab-content active">
                    <h3><?php _e('Elasticsearch is an Open Source Distributed Real Time Search &amp; Analytics', TTO_I18N) ?></h3>
                    <?php echo $description ?>

                    <br class="clear"/>
                </div>

                <?php if ('yes' == $vals['enable']): ?>
                    <!-- Configurations -->
                    <div id="<?php echo $id ?>-configs" class="help-tab-content">
                        <!-- Server URL -->
                        <h3><label for="<?php echo $id ?>_server_url"><?php _e('Server URL', TTO_I18N) ?></label></h3>
                        <div class="inside tea-inside text">
                            http://
                            <input type="text" name="<?php echo $id ?>[server_host]" id="<?php echo $id ?>_server_host" value="<?php echo $vals['server_host'] ?>" placeholder="<?php echo $std['server_host'] ?>" style="width:120px" />
                            :
                            <input type="text" name="<?php echo $id ?>[server_port]" id="<?php echo $id ?>_server_port" value="<?php echo $vals['server_port'] ?>" maxlength="4" placeholder="<?php echo $std['server_port'] ?>" style="width:50px" />
                            /

                            <p><?php echo sprintf(__('If your search provider has given you a connection URL, use that instead of filling out server information..<br/>http://<code>%s</code>:<code>%d</code> are good values.', TTO_I18N), $std['server_host'], $std['server_port']) ?></p>
                        </div>

                        <br class="clear"/>

                        <!-- Index name -->
                        <h3><label for="<?php echo $id ?>_index_name"><?php _e('Index name', TTO_I18N) ?></label></h3>
                        <div class="inside tea-inside text">
                            <input type="text" name="<?php echo $id ?>[index_name]" id="<?php echo $id ?>_index_name" value="<?php echo $vals['index_name'] ?>" />

                            <p><?php echo sprintf(__('Use a uniq name in lowercase with no special character.<br/><code>%s</code> is a good value.', TTO_I18N), $std['index_name']) ?></p>
                        </div>

                        <br class="clear"/>

                        <!-- Read timeout -->
                        <h3><label for="<?php echo $id ?>_read_timeout"><?php _e('Read timeout', TTO_I18N) ?></label></h3>
                        <div class="inside tea-inside number">
                            <input type="number" name="<?php echo $id ?>[read_timeout]" id="<?php echo $id ?>_read_timeout" value="<?php echo $vals['read_timeout'] ?>" size="30" min="1" max="30" step="1" />

                            <p><?php echo sprintf(__('The maximum time (in seconds) that read requests should wait for server response. If the call times out, wordpress will fallback to standard search.<br/><code>%s</code> is a good value.', TTO_I18N), $std['read_timeout']) ?></p>
                        </div>

                        <br class="clear"/>

                        <!-- Write timeout -->
                        <h3><label for="<?php echo $id ?>_write_timeout"><?php _e('Write timeout', TTO_I18N) ?></label></h3>
                        <div class="inside tea-inside number">
                            <input type="number" name="<?php echo $id ?>[write_timeout]" id="<?php echo $id ?>_write_timeout" value="<?php echo $vals['write_timeout'] ?>" size="30" min="1" max="30" step="1" />

                            <p><?php echo sprintf(__('The maximum time (in seconds) that write requests should wait for server response. This should be set long enough to index your entire site.<br/><code>%s</code> is a good value.', TTO_I18N), $std['write_timeout']) ?></p>
                        </div>

                        <br class="clear"/>

                        <h3><label for="<?php echo $id ?>_template"><?php _e('Search template?', TTO_I18N) ?></label></h3>
                        <div class="inside tea-inside select">
                            <select name="<?php echo $id ?>[template]" id="<?php echo $id ?>_template">
                                <option value="no" <?php echo 'no' == $vals['template'] || !isset($vals['template']) ? 'selected="selected" ' : '' ?>><?php _e('Use the Tea T.O. Search default template.', TTO_I18N) ?></option>
                                <option value="yes" <?php echo 'yes' == $vals['template'] ? 'selected="selected" ' : '' ?>><?php _e('Use your own Search theme template.', TTO_I18N) ?></option>
                            </select>
                            <p><?php _e('You can <a href="#" class="elastica-template">click here</a> to see how you can integrate Tea T.O. Elasticsearch results in your template.', TTO_I18N) ?></p>
                        </div>

                        <br class="clear"/>

                        <button type="submit" class="button button-primary"><?php _e('Submit', TTO_I18N) ?></button>
                    </div>

                    <!-- Indexing -->
                    <div id="<?php echo $id ?>-indexing" class="help-tab-content">
                        <h3><?php _e('Indexing contents', TTO_I18N) ?></h3>

                        <p>
                            <?php _e('Choose in this section which contents you want to index. This page list all post types and taxonomies defined in your template.<br/>', TTO_I18N) ?>
                            <?php if (!empty($index)): ?>
                                <b><i class="fa fa-angle-right fa-lg"></i> <?php _e('Some of your posts have already been indexed. Gorgeous!', TTO_I18N) ?></b>
                            <?php endif ?>
                        </p>

                        <hr class="clear" />

                        <?php
                            //Get all post types
                            $post_types = get_post_types(array('public' => 1));
                            $count = isset($vals['index_post']) ? count($vals['index_post']) : 0;
                        ?>
                        <h3>
                            <?php _e('Post types', TTO_I18N) ?>

                            <label for="checkall-post" class="checkall">
                                <?php _e('Un/select all options') ?>
                                <input type="checkbox" id="checkall-post" data-target="#<?php echo $id ?>-index-post input[type='checkbox']" <?php echo count($post_types) == $count ? 'checked="checked"' : '' ?> />
                            </label>
                        </h3>
                        <div id="<?php echo $id ?>-index-post" class="inside tea-inside checkbox">
                            <fieldset>
                                <?php
                                    foreach ($post_types as $key):
                                        $post = get_post_type_object($key);
                                        $selected = isset($vals['index_post'][$key]) ? ' checked="checked"' : '';
                                        $for = $id . '_indexpost_' . $key;
                                    ?>
                                    <p class="item">
                                        <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                                            <input type="checkbox" name="<?php echo $id ?>[index_post][<?php echo $key ?>]" id="<?php echo $for ?>" value="<?php echo $key ?>" <?php echo $selected ?> />
                                            <?php echo $post->label ?>
                                        </label>
                                    </p>
                                <?php endforeach ?>
                            </fieldset>
                        </div>

                        <hr class="clear" />

                        <?php
                            //Get all taxonomies
                            $taxonomies = get_taxonomies(array('public' => 1));
                            $count = isset($vals['index_tax']) ? count($vals['index_tax']) : 0;
                        ?>
                        <h3>
                            <?php _e('Taxonomies', TTO_I18N) ?>

                            <label for="checkall-tax" class="checkall">
                                <?php _e('Un/select all options') ?>
                                <input type="checkbox" id="checkall-tax" data-target="#<?php echo $id ?>-index-tax input[type='checkbox']" <?php echo count($taxonomies) == $count ? 'checked="checked"' : '' ?> />
                            </label>
                        </h3>
                        <div id="<?php echo $id ?>-index-tax" class="inside tea-inside checkbox">
                            <fieldset>
                                <?php
                                    foreach ($taxonomies as $key):
                                        $name = get_taxonomy($key);
                                        $selected = isset($vals['index_tax'][$key]) ? ' checked="checked"' : '';
                                        $for = $id . '_indextax_' . $key;
                                    ?>
                                    <p class="item">
                                        <label for="<?php echo $for ?>" class="<?php echo $selected ? 'selected' : '' ?>">
                                            <input type="checkbox" name="<?php echo $id ?>[index_tax][<?php echo $key ?>]" id="<?php echo $for ?>" value="<?php echo $key ?>" <?php echo $selected ?> />
                                            <?php echo $name->labels->name ?> (<code><?php echo $name->name ?></code>)
                                        </label>
                                    </p>
                                <?php endforeach ?>
                            </fieldset>
                        </div>

                        <br class="clear"/>

                        <button type="submit" class="button button-primary"><?php _e('Submit', TTO_I18N) ?></button>
                    </div>
                <?php endif ?>
                <?php /*if ('yes' == $vals['enable'] && !empty($index)): ?>
                    <!-- Statistics -->
                    <div id="<?php echo $id ?>-stats" class="help-tab-content">
                        <h3><?php _e('Statistics', TTO_I18N) ?></h3>
                        <p><?php _e('Retrieve here all your stats.', TTO_I18N) ?></p>
                        <p><?php _e('Coming soon', TTO_I18N) ?></p>

                        <br class="clear"/>
                    </div>
                <?php endif*/ ?>
            </form>
        </div>
    </div>
</div>
<!-- /Content elasticsearch -->