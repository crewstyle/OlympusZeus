<!-- Content instagram -->
<div class="tea-screen-meta">
    <div class="tea-contextual-help-wrap">
        <div class="tea-contextual-help-back"></div>

        <div class="tea-contextual-help-columns">
            <div class="contextual-help-tabs">
                <h2>
                    <img src="<?php echo $icon ?>" alt="" />
                    <?php _e('Instagram', TTO_I18N) ?>
                </h2>
                <ul>
                    <?php if ($display_form): ?>
                        <li class="active">
                            <a href="#tea-instagram-createuser"><?php _e('Connection', TTO_I18N) ?></a>
                        </li>
                    <?php else: ?>
                        <li class="active">
                            <a href="#tea-instagram-infouser"><b><?php echo $user_info->full_name ?></b></a>
                        </li>
                        <li>
                            <a href="#tea-instagram-recentmedias"><?php _e('Medias', TTO_I18N) ?></a>
                        </li>
                        <li>
                            <a href="#tea-instagram-helpbox"><?php _e('Help', TTO_I18N) ?></a>
                        </li>
                    <?php endif ?>
                </ul>
                <?php if (!$display_form): ?>
                    <form action="admin.php?page=<?php echo $page ?>" method="post">
                        <input type="hidden" name="tea_to_disconnection" id="tea_to_disconnection" value="1" />
                        <input type="hidden" name="tea_to_network" value="instagram" />
                        <input type="submit" name="submit" value="<?php _e('Logout', TTO_I18N) ?>" class="submitdelete" />
                    </form>
                <?php endif ?>
            </div>

            <div class="contextual-help-tabs-wrap">
                <?php if ($display_form): ?>
                    <form action="admin.php?page=<?php echo $page ?>" method="post" id="tea-instagram-createuser">
                        <input type="hidden" name="tea_to_connection" id="tea_to_connection" value="1" />
                        <input type="hidden" name="tea_to_network" value="instagram" />

                        <p><?php _e('Instagram', TTO_I18N) ?></p>
                        <?php submit_button(__('Connection to Instagram', TTO_I18N)) ?>
                    </form>
                <?php else: ?>
                    <div id="tea-instagram-infouser" class="help-tab-content active">
                        <img src="<?php echo $user_info->profile_picture ?>" alt="<?php echo $user_info->username ?>" class="network-profile" />

                        <h3 class="network-fullname">
                            <b><?php echo $user_info->full_name ?></b>
                            <small> &bull; <a href="http://instagram.com/<?php echo $user_info->username ?>" class="button button-primary"><?php _e('See profile', TTO_I18N) ?></a></small>
                        </h3>

                        <ul class="network-counts">
                            <li>
                                <?php _e('Medias', TTO_I18N) ?>
                                <span><?php echo $user_info->counts->media ?></span>
                            </li>
                            <li>
                                <?php _e('Followers', TTO_I18N) ?>
                                <span><?php echo $user_info->counts->followed_by ?></span>
                            </li>
                            <li>
                                <?php _e('Following', TTO_I18N) ?>
                                <span><?php echo $user_info->counts->follows ?></span>
                            </li>
                        </ul>
                    </div>
                    <form action="admin.php?page=<?php echo $page ?>&updated=true" method="post" id="tea-instagram-recentmedias" class="help-tab-content">
                        <input type="hidden" name="tea_to_update" id="tea_to_update" value="1" />
                        <input type="hidden" name="tea_to_network" value="instagram" />

                        <h3>
                            <?php _e('Medias', TTO_I18N) ?>
                            <small> &bull; <input type="submit" name="submit" class="button button-primary" value="<?php _e('Update', TTO_I18N) ?>" /></small>
                        </h3>

                        <ul class="network-recent">
                            <?php if (empty($user_recent)): ?>
                                <li><?php _e('You have no medias in your Instagram account.', TTO_I18N) ?></li>
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
                        <h3><?php _e('Help', TTO_I18N) ?></h3>

                        <p><?php _e('To display your recent medias in a widget or else, simply get them from cache by the request:', TTO_I18N) ?></p>
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

                        <?php if (!empty($update)): ?>
                            <p><small><?php _e('Medias updated on:', TTO_I18N) ?> <?php echo $update ?></small></p>
                        <?php endif ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<!-- /Content instagram -->