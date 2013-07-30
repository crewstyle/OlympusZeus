                    <!-- Content instagram -->
                    <div id="screen-meta">
                        <div id="contextual-help-wrap">
                            <div id="contextual-help-back"></div>

                            <div id="contextual-help-columns">
                                <div class="contextual-help-tabs">
                                    <h2><?php echo $title ?></h2>
                                    <ul>
                                        <?php if ($display_form): ?>
                                            <li class="active">
                                                <a href="#tea-instagram-createuser"><?php _e('Connection') ?></a>
                                            </li>
                                        <?php else: ?>
                                            <li class="active">
                                                <a href="#tea-instagram-infouser"><b><?php echo $user_info->username ?></b></a>
                                            </li>
                                            <li>
                                                <a href="#tea-instagram-recentmedias"><?php _e('Recent medias') ?></a>
                                            </li>
                                            <li>
                                                <a href="#tea-instagram-helpbox"><?php _e('Help') ?></a>
                                            </li>
                                        <?php endif ?>
                                    </ul>
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
                                        <form action="admin.php?page=<?php echo $page ?>" method="post" id="tea-instagram-infouser" class="help-tab-content active">
                                            <input type="hidden" name="tea_disconnection" id="tea_disconnection" value="1" />
                                            <input type="hidden" name="tea_network" value="instagram" />

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
                                            <div class="clearfix"></div>
                                            <?php submit_button(__('Logout')) ?>
                                        </form>
                                        <form action="admin.php?page=<?php echo $page ?>&updated=true" method="post" id="tea-instagram-recentmedias" class="help-tab-content">
                                            <input type="hidden" name="tea_update" id="tea_update" value="1" />
                                            <input type="hidden" name="tea_network" value="instagram" />

                                            <h3>
                                                <?php _e('Your recent photos') ?>
                                                <small> &bull; <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Update') ?>" /></small>
                                            </h3>

                                            <ul class="network-recent">
                                                <?php
                                                    foreach ($user_recent as $item):
                                                        $link = $item->link;
                                                        $url = $item->images->thumbnail->url;
                                                        $alt = empty($item->caption->text) ? __('Untitled') : $item->caption->text;
                                                        $width = $item->images->thumbnail->width;
                                                        $height = $item->images->thumbnail->height;
                                                        $likes = $item->likes->count;
                                                        $comments = $item->comments->count;
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
                                            </ul>
                                        </form>
                                        <div id="tea-instagram-helpbox" class="help-tab-content">
                                            <h3><?php _e('Display recent photos') ?></h3>

                                            <p><?php _e('To display your recent photos in a widget or else, simply get them from cache by the request:') ?></p>
<pre>&lt;php
$recent = _get_option('tea_instagram_user_recent');
if (false !== $recent &amp;&amp; !empty($recent))
{
    foreach ($recent as $item)
    {
        $link = $item-&gt;link;
        $url = $item-&gt;images-&gt;thumbnail-&gt;url;
        $alt = empty($item-&gt;caption-&gt;text) ? __('Untitled') : $item-&gt;caption-&gt;text;
        $width = $item-&gt;images-&gt;thumbnail-&gt;width;
        $height = $item-&gt;images-&gt;thumbnail-&gt;height;
        $likes = $item-&gt;likes-&gt;count;
        $comments = $item-&gt;comments-&gt;count;

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