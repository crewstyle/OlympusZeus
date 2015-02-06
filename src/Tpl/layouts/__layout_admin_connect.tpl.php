<div id="message" class="updated tea_to_message">
    <a href="<?php echo $dismiss ?>" title="<?php _e('Dismiss this notice.', TTO_I18N); ?>" class="dismiss-button">
        <i class="fa fa-times fa-lg"></i>
    </a>

    <img src="<?php echo TTO_URI ?>/assets/img/teato-logo.svg" alt="" class="logo" />
    <h3><?php _e('It\'s Tea time!', TTO_I18N) ?></h3>
    <p class="about"><?php _e('Your Tea Theme Options is almost ready.', TTO_I18N) ?></p>

    <div class="panel">
        <div class="column">
            <h4><?php _e('Social features') ?></h4>

            <a href="<?php echo '#'//$connect ?>" class="button button-primarys button-hero">
                <i class="fa fa-cloud fa-lg"></i>
                <?php _e('Coming soon.', TTO_I18N) //_e('Connect', TTO_I18N) ?>
            </a>

            <p><?php _e('Connect your profile to enable social features and get full of functionalities ;)', TTO_I18N) ?></p>
        </div>

        <div class="column">
            <h4><?php _e('Customize your theme', TTO_I18N) ?></h4>
            <ul>
                <li>
                    <?php _e('Ocean tea', TTO_I18N) ?><br/>
                    <img src="<?php echo TTO_URI ?>/assets/img/tea-ocean-blue.png" alt="" />
                </li>
                <li>
                    <?php _e('Earth tea', TTO_I18N) ?><br/>
                    <img src="<?php echo TTO_URI ?>/assets/img/tea-earth-green.png" alt="" />
                </li>
                <li>
                    <?php _e('Vulcan tea', TTO_I18N) ?><br/>
                    <img src="<?php echo TTO_URI ?>/assets/img/tea-vulcan-red.png" alt="" />
                </li>
            </ul>
            <p><?php echo sprintf(__('Do not forget to customize <a href="%s">your current theme</a> with the 3 new Tea Theme Options appearances.', TTO_I18N), admin_url('profile.php')) ?></p>
        </div>
        <div class="column last">
            <h4><?php _e('Boost your engine!', TTO_I18N) ?></h4>
            <ul>
                <li><?php _e('Manage your pages easily, create your own setting pages symply.', TTO_I18N) ?></li>
                <li><?php _e('Boost your search engine with Elasticsearch.', TTO_I18N) ?></li>
                <li><?php _e('Create your own Custom Post Types.', TTO_I18N) ?></li>
            </ul>
        </div>
    </div>
</div>
