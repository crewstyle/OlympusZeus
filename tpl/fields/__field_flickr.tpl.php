                    <!-- Content flickr -->
                    <div id="screen-meta">
                        <div id="contextual-help-wrap">
                            <div id="contextual-help-back"></div>

                            <div id="contextual-help-columns">
                                <div class="contextual-help-tabs">
                                    <h2><?php echo $title ?></h2>
                                    <ul>
                                        <?php if ($display_form): ?>
                                            <li class="active">
                                                <a href="#tea-flickr-createuser"><?php _e('Connection') ?></a>
                                            </li>
                                        <?php else: ?>
                                            <li class="active">
                                                <a href="#tea-flickr-infouser"><b><?php echo $user_info['username'] ?></b></a>
                                            </li>
                                            <li>
                                                <a href="#tea-flickr-recentmedias"><?php _e('Recent medias') ?></a>
                                            </li>
                                            <li>
                                                <a href="#tea-flickr-helpbox"><?php _e('Help') ?></a>
                                            </li>
                                        <?php endif ?>
                                    </ul>
                                </div>

                                <div class="contextual-help-tabs-wrap">
                                    <?php if ($display_form): ?>
                                        <form action="admin.php?page=<?php echo $page ?>" method="post" id="tea-flickr-createuser">
                                            <input type="hidden" name="tea_connection" id="tea_connection" value="1" />
                                            <input type="hidden" name="tea_network" value="flickr" />

                                            <p><?php echo $description ?></p>
                                            <p>http://www.flickr.com/photos/<input type="text" name="tea_flickr_username" id="tea_flickr_username" value="" placeholder="<?php _e('Your username') ?>" />/</p>

                                            <?php submit_button(__('Connection to FlickR')) ?>
                                        </form>
                                    <?php else: ?>
                                        <form action="admin.php?page=<?php echo $page ?>" method="post" id="tea-flickr-infouser" class="help-tab-content active">
                                            <input type="hidden" name="tea_disconnection" id="tea_disconnection" value="1" />
                                            <input type="hidden" name="tea_network" value="flickr" />

                                            <img src="http://www.flickr.com/images/buddyicon.gif" alt="<?php echo $user_info['username'] ?>" class="network-profile" />

                                            <h3 class="network-fullname">
                                                <b><?php echo $user_details['realname'] ?></b>
                                                <small> &bull; <a href="<?php echo $user_details['profileurl'] ?>" class="button button-primary"><?php _e('See profile') ?></a></small>
                                            </h3>

                                            <ul class="network-counts">
                                                <li>
                                                    <?php _e('First date') ?>
                                                    <span><?php echo $user_details['photos']['firstdate'] ?></span>
                                                </li>
                                                <li>
                                                    <?php _e('First date taken') ?>
                                                    <span><?php echo $user_details['photos']['firstdatetaken'] ?></span>
                                                </li>
                                                <li>
                                                    <?php _e('Medias') ?>
                                                    <span><?php echo $user_details['photos']['count'] ?></span>
                                                </li>
                                            </ul>
                                            <div class="clearfix"></div>
                                            <?php submit_button(__('Logout')) ?>
                                        </form>
                                        <form action="admin.php?page=<?php echo $page ?>&updated=true" method="post" id="tea-flickr-recentmedias" class="help-tab-content">
                                            <input type="hidden" name="tea_update" id="tea_update" value="1" />
                                            <input type="hidden" name="tea_network" value="flickr" />

                                            <h3>
                                                <?php _e('Your recent photos') ?>
                                                <small> &bull; <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Update') ?>" /></small>
                                            </h3>

                                            <ul class="network-recent">
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
                                            </ul>
                                        </form>
                                        <div id="tea-flickr-helpbox" class="help-tab-content">
                                            <h3><?php _e('Display recent photos') ?></h3>

                                            <p><?php _e('To display your recent photos in a widget or else, simply get them from cache by the request:') ?></p>
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
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Content flickr -->