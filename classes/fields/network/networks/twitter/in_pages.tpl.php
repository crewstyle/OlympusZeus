<!-- Content twitter -->
<div class="tea-screen-meta">
    <div class="tea-contextual-help-wrap">
        <div class="tea-contextual-help-back"></div>

        <div class="tea-contextual-help-columns">
            <div class="contextual-help-tabs">
                <h2>
                    <img src="<?php echo $icon ?>" alt="" />
                    <?php _e('Twitter', TTO_I18N) ?>
                </h2>
                <ul>
                    <?php if ($display_form): ?>
                        <li class="active">
                            <a href="#tea-twitter-createuser"><?php _e('Connection', TTO_I18N) ?></a>
                        </li>
                    <?php else: ?>
                        <li class="active">
                            <a href="#tea-twitter-infouser"><b><?php echo $user_info->name ?></b></a>
                        </li>
                        <li>
                            <a href="#tea-twitter-recenttweets"><?php _e('Tweets', TTO_I18N) ?></a>
                        </li>
                        <li>
                            <a href="#tea-twitter-helpbox"><?php _e('Help', TTO_I18N) ?></a>
                        </li>
                    <?php endif ?>
                </ul>
                <?php if (!$display_form): ?>
                    <form action="admin.php?page=<?php echo $page ?>" method="post">
                        <input type="hidden" name="tea_to_disconnection" id="tea_to_disconnection" value="1" />
                        <input type="hidden" name="tea_to_network" value="twitter" />
                        <input type="submit" name="submit" value="<?php _e('Logout', TTO_I18N) ?>" class="submitdelete" />
                    </form>
                <?php endif ?>
            </div>

            <div class="contextual-help-tabs-wrap">
                <?php if ($display_form): ?>
                    <form action="admin.php?page=<?php echo $page ?>" method="post" id="tea-twitter-createuser">
                        <input type="hidden" name="tea_to_connection" id="tea_to_connection" value="1" />
                        <input type="hidden" name="tea_to_network" value="twitter" />

                        <p><?php _e('Twitter', TTO_I18N) ?></p>
                        <?php submit_button(__('Connection to Twitter', TTO_I18N)) ?>
                    </form>
                <?php else: ?>
                    <div id="tea-twitter-infouser" class="help-tab-content active">
                        <img src="<?php echo $user_info->profile_image_url ?>" alt="<?php echo $user_info->name ?>" class="network-profile" />

                        <h3 class="network-fullname">
                            <b><?php echo $user_info->name ?></b>
                            <small> &bull; <a href="http://twitter.com/<?php echo $user_info->screen_name ?>" class="button button-primary"><?php _e('See profile', TTO_I18N) ?></a></small>
                        </h3>

                        <ul class="network-counts">
                            <li>
                                <?php _e('Tweets', TTO_I18N) ?>
                                <span><?php echo $user_info->statuses_count ?></span>
                            </li>
                            <li>
                                <?php _e('Followers', TTO_I18N) ?>
                                <span><?php echo $user_info->followers_count ?></span>
                            </li>
                            <li>
                                <?php _e('Following', TTO_I18N) ?>
                                <span><?php echo $user_info->friends_count ?></span>
                            </li>
                        </ul>
                    </div>
                    <form action="admin.php?page=<?php echo $page ?>&updated=true" method="post" id="tea-twitter-recenttweets" class="help-tab-content">
                        <input type="hidden" name="tea_to_update" id="tea_to_update" value="1" />
                        <input type="hidden" name="tea_to_network" value="twitter" />

                        <h3>
                            <?php _e('Tweets', TTO_I18N) ?>
                            <small> &bull; <input type="submit" name="submit" class="button button-primary" value="<?php _e('Update', TTO_I18N) ?>" /></small>
                        </h3>

                        <ul class="network-recent twitter-text">
                            <?php if (empty($user_recent)): ?>
                                <li><?php _e('You have no tweets in your Twitter account.', TTO_I18N) ?></li>
                            <?php else: ?>
                                <?php
                                    foreach ($user_recent as $item):
                                        $text = $item->text;
                                        $text = preg_replace("/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/", "<a href=\"$1\">$1</a>", $text);
                                        $text = preg_replace("/@(\w+)/i", "<a href=\"http://twitter.com/$1\">$0</a>", $text);
                                        $text = preg_replace("/#(\w+)/i", "<a href=\"https://twitter.com/search?q=%23$1&src=hash\">$0</a>", $text);
                                ?>
                                    <li>
                                        <?php echo $text ?>
                                    </li>
                                <?php endforeach ?>
                            <?php endif ?>
                        </ul>
                    </form>
                    <div id="tea-twitter-helpbox" class="help-tab-content">
                        <h3><?php _e('Help', TTO_I18N) ?></h3>

                        <p><?php _e('To display your recent tweets in a widget or else, simply get them from cache by the request:', TTO_I18N) ?></p>
<pre>&lt;php
$recent = _get_option('tea_twitter_user_recent');
if (false !== $recent &amp;&amp; !empty($recent))
{
    foreach ($recent as $item)
    {
        $text = $item->text;
        $text = preg_replace("/([\w]+\:\/\/[\w-?&amp;;#~=\.\/\@]+[\w\/])/", "&lt;a href=\"$1\"&gt;$1&lt;/a&gt;", $text);
        $text = preg_replace("/@(\w+)/i", "&lt;a href=\"http://twitter.com/$1\"&gt;$0&lt;/a&gt;", $text);
        $text = preg_replace("/#(\w+)/i", "&lt;a href=\"https://twitter.com/search?q=%23$1&amp;src=hash\"&gt;$0&lt;/a&gt;", $text);

        echo $text;
    }
}
?&gt;</pre>

                        <?php if (!empty($update)): ?>
                            <p><small><?php _e('Tweets updated on:', TTO_I18N) ?> <?php echo $update ?></small></p>
                        <?php endif ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<!-- /Content twitter -->