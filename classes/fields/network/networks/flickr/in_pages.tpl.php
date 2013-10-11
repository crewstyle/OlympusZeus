<!-- Content flickr -->
<div class="tea-screen-meta">
    <div class="tea-contextual-help-wrap">
        <div class="tea-contextual-help-back"></div>

        <div class="tea-contextual-help-columns">
            <div class="contextual-help-tabs">
                <h2>
                    <img src="<?php echo $icon ?>" alt="" />
                    <?php _e('FlickR', TTO_I18N) ?>
                </h2>
                <ul>
                    <?php if ($display_form): ?>
                        <li class="active">
                            <a href="#tea-flickr-createuser"><?php _e('Connection', TTO_I18N) ?></a>
                        </li>
                    <?php else: ?>
                        <li class="active">
                            <a href="#tea-flickr-infouser"><b><?php echo $user_details['realname'] ?></b></a>
                        </li>
                        <li>
                            <a href="#tea-flickr-recentmedias"><?php _e('Medias', TTO_I18N) ?></a>
                        </li>
                        <li>
                            <a href="#tea-flickr-helpbox"><?php _e('Help', TTO_I18N) ?></a>
                        </li>
                    <?php endif ?>
                </ul>
                <?php if (!$display_form): ?>
                    <form action="admin.php?page=<?php echo $page ?>" method="post">
                        <input type="hidden" name="tea_to_disconnection" id="tea_to_disconnection" value="1" />
                        <input type="hidden" name="tea_to_network" value="flickr" />
                        <input type="submit" name="submit" value="<?php _e('Logout', TTO_I18N) ?>" class="submitdelete" />
                    </form>
                <?php endif ?>
            </div>

            <div class="contextual-help-tabs-wrap">
                <?php if ($display_form): ?>
                    <form action="admin.php?page=<?php echo $page ?>" method="post" id="tea-flickr-createuser">
                        <input type="hidden" name="tea_to_connection" id="tea_to_connection" value="1" />
                        <input type="hidden" name="tea_to_network" value="flickr" />

                        <p><?php _e('FlickR', TTO_I18N) ?></p>
                        <p>
                            <label for="tea_flickr_username" style="display:block">
                                <?php _e('Enter your FlickR username', TTO_I18N) ?>
                            </label>
                            <input type="text" name="tea_flickr_username" id="tea_flickr_username" value="" placeholder="<?php _e('Your username', TTO_I18N) ?>" />
                        </p>

                        <?php submit_button(__('Connection to FlickR', TTO_I18N)) ?>
                    </form>
                <?php else: ?>
                    <div id="tea-flickr-infouser" class="help-tab-content active">
                        <img src="http://www.flickr.com/images/buddyicon.gif" alt="<?php echo $user_info['username'] ?>" class="network-profile" />

                        <h3 class="network-fullname">
                            <b><?php echo $user_details['realname'] ?></b>
                            <small> &bull; <a href="<?php echo $user_details['profileurl'] ?>" class="button button-primary"><?php _e('See profile', TTO_I18N) ?></a></small>
                        </h3>

                        <ul class="network-counts">
                            <!--li>
                                <?php //_e('First date', TTO_I18N) ?>
                                <span><?php //echo $user_details['photos']['firstdate'] ?></span>
                            </li-->
                            <li>
                                <?php _e('First date taken', TTO_I18N) ?>
                                <span><?php echo $user_details['photos']['firstdatetaken'] ?></span>
                            </li>
                            <li>
                                <?php _e('Medias', TTO_I18N) ?>
                                <span><?php echo $user_details['photos']['count'] ?></span>
                            </li>
                        </ul>
                    </div>
                    <form action="admin.php?page=<?php echo $page ?>&updated=true" method="post" id="tea-flickr-recentmedias" class="help-tab-content">
                        <input type="hidden" name="tea_to_update" id="tea_to_update" value="1" />
                        <input type="hidden" name="tea_to_network" value="flickr" />

                        <h3>
                            <?php _e('Medias', TTO_I18N) ?>
                            <small> &bull; <input type="submit" name="submit" class="button button-primary" value="<?php _e('Update', TTO_I18N) ?>" /></small>
                        </h3>

                        <ul class="network-recent">
                            <?php if (empty($user_recent)): ?>
                                <li><?php _e('You have no medias in your FlickR account.', TTO_I18N) ?></li>
                            <?php else: ?>
                                <?php
                                    foreach ($user_recent as $item):
                                        $link = $item['link'];
                                        $url = $item['url_small'];
                                        $title = $item['title'];
                                ?>
                                    <li>
                                        <a href="<?php echo $link ?>" title="<?php echo $title ?>">
                                            <img src="<?php echo $url ?>" alt="<?php echo $title ?>" />
                                        </a>
                                    </li>
                                <?php endforeach ?>
                            <?php endif ?>
                        </ul>
                    </form>
                    <div id="tea-flickr-helpbox" class="help-tab-content">
                        <h3><?php _e('Help', TTO_I18N) ?></h3>

                        <p><?php _e('To display your recent medias in a widget or else, simply get them from cache by the request:', TTO_I18N) ?></p>
<pre>&lt;php
$recent = _get_option('tea_flickr_user_recent');
if (false !== $recent &amp;&amp; !empty($recent))
{
    foreach ($recent as $item)
    {
        $link = $item['link'];
        $url_small = $item['url_small'];
        $url = $item['url'];
        $title = $item['title'];

        echo '&lt;a href="' . $link . '"&gt;&lt;img src="' . $url . '" alt="' . $title . '" /&gt;&lt;/a&gt;';
    }
}
?&gt;</pre>

                        <?php if (!empty($update)): ?>
                            <p><small><?php _e('Medias updated on:', TTO_I18N) ?> <?php echo $update ?></small></p>
                        <?php endif ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<!-- /Content flickr -->