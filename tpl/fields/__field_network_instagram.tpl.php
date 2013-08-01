                    <!-- Content instagram -->
                    <div id="screen-meta">
                        <div id="contextual-help-wrap">
                            <div id="contextual-help-back"></div>

                            <div id="contextual-help-columns">
                                <div class="contextual-help-tabs">
                                    <h2>
                                        <img src="<?php echo $icon ?>" alt="" />
                                        <?php echo $title ?>
                                    </h2>
                                    <ul>
                                        <?php if ($display_form): ?>
                                            <li class="active">
                                                <a href="#tea-instagram-createuser"><?php _e('Connection') ?></a>
                                            </li>
                                        <?php else: ?>
                                            <li class="active">
                                                <a href="#tea-instagram-infouser"><b><?php echo $user_info->full_name ?></b></a>
                                            </li>
                                            <li>
                                                <a href="#tea-instagram-recentmedias"><?php _e('Medias') ?></a>
                                            </li>
                                            <li>
                                                <a href="#tea-instagram-helpbox"><?php _e('Help') ?></a>
                                            </li>
                                        <?php endif ?>
                                    </ul>
                                    <?php if (!$display_form): ?>
                                        <form action="admin.php?page=<?php echo $page ?>" method="post">
                                            <input type="hidden" name="tea_disconnection" id="tea_disconnection" value="1" />
                                            <input type="hidden" name="tea_network" value="instagram" />
                                            <input type="submit" name="submit" value="<?php _e('Logout') ?>" class="submitdelete" />
                                        </form>
                                    <?php endif ?>
                                </div>

                                <div class="contextual-help-tabs-wrap">
                                    <?php if ($display_form): ?>
                                        <form action="admin.php?page=<?php echo $page ?>" method="post" id="tea-instagram-createuser">
                                            <input type="hidden" name="tea_connection" id="tea_connection" value="1" />
                                            <input type="hidden" name="tea_network" value="instagram" />

                                            <p><?php echo $description ?></p>
                                            <?php submit_button(__('Connection to Instagram')) ?>
                                        </form>
                                    <?php else: ?>
                                        <div id="tea-instagram-infouser" class="help-tab-content active">
                                            <img src="<?php echo $user_info->profile_picture ?>" alt="<?php echo $user_info->username ?>" class="network-profile" />

                                            <h3 class="network-fullname">
                                                <b><?php echo $user_info->full_name ?></b>
                                                <small> &bull; <a href="http://instagram.com/<?php echo $user_info->username ?>" class="button button-primary"><?php _e('See profile') ?></a></small>
                                            </h3>

                                            <ul class="network-counts">
                                                <li>
                                                    <?php _e('Medias') ?>
                                                    <span><?php echo $user_info->counts->media ?></span>
                                                </li>
                                                <li>
                                                    <?php _e('Followers') ?>
                                                    <span><?php echo $user_info->counts->followed_by ?></span>
                                                </li>
                                                <li>
                                                    <?php _e('Following') ?>
                                                    <span><?php echo $user_info->counts->follows ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                        <form action="admin.php?page=<?php echo $page ?>&updated=true" method="post" id="tea-instagram-recentmedias" class="help-tab-content">
                                            <input type="hidden" name="tea_update" id="tea_update" value="1" />
                                            <input type="hidden" name="tea_network" value="instagram" />

                                            <h3>
                                                <?php _e('Medias') ?>
                                                <small> &bull; <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Update') ?>" /></small>
                                            </h3>

                                            <ul class="network-recent">
                                                <?php if (empty($user_recent)): ?>
                                                    <li><?php _e('You have no medias in your Instagram account.') ?></li>
                                                <?php else: ?>
                                                    <?php
                                                        foreach ($user_recent as $item):
                                                            $link = $item['link'];
                                                            $url = $item['url'];
                                                            $title = $item['title'];
                                                            $width = $item['width'];
                                                            $height = $item['height'];
                                                            $likes = $item['likes'];
                                                            $comments = $item['comments'];
                                                    ?>
                                                        <li>
                                                            <a href="<?php echo $link ?>" title="<?php echo $alt ?>">
                                                                <img src="<?php echo $url ?>" alt="<?php echo $alt ?>" width="<?php echo $width ?>" height="<?php echo $height ?>" />
                                                            </a>
                                                            <div class="details">
                                                                <span class="likes"><?php echo $likes ?></span>
                                                                <span class="comments"><?php echo $comments ?></span>
                                                            </div>
                                                        </li>
                                                    <?php endforeach ?>
                                                <?php endif ?>
                                            </ul>
                                        </form>
                                        <div id="tea-instagram-helpbox" class="help-tab-content">
                                            <?php /*<h3><?php _e('Update data') ?></h3>

                                            <p><?php _e('You can manually update your data from Instagram by clicking on the "Update" button from the "Recent medias" menu. There are two ways to update automatically the informations :') ?></p>
                                            <ul>
                                                <li><b><?php _e('Use the Wordpress default Cron') ?></b> - <?php _e('Used by default.') ?></li>
                                                <li><b><?php _e('Use your server Cron') ?></b> - <?php _e('You can simply use your own server Cron if you are able to. Follow the next instructions to make the magic happens.') ?></li>
                                            </ul>

                                            <h4><?php _e('Instructions to use your own server Cron') ?></h4>
                                            <p><?php _e('First of all, disable the Cron call') ?></p>
                                            <code>wget -q -O - http://VOTRESITE.COM/wp-cron.php > /dev/null 2>&1</code>

                                            <hr class="network-sep" />*/ ?>

                                            <h3><?php _e('Medias') ?></h3>

                                            <p><?php _e('To display your recent medias in a widget or else, simply get them from cache by the request:') ?></p>
<pre>&lt;php
$recent = _get_option('tea_instagram_user_recent');
if (false !== $recent &amp;&amp; !empty($recent))
{
    foreach ($recent as $item)
    {
        $link = $item['link'];
        $url = $item['url'];
        $title = $item['title'];
        $width = $item['width'];
        $height = $item['height'];
        $likes = $item['likes'];
        $comments = $item['comments'];

        echo '&lt;img src="' . $url . '" alt="' . $alt . '" width="' . $width . '" height="' . $height . '" /&gt;';
    }
}
?&gt;</pre>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Content instagram -->