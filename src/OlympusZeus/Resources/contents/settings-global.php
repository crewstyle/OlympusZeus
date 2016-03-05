<?php
$return = array(
    array(
        'type' => 'heading',
        'level' => 2,
        'title' => __('Enable all features you need<br/><small style="color:#ccc;font-weight:700;">and see make your WordPress website faster</small>', OLZ_I18N),
        'style' => 'margin:60px 0;text-align:center;',
    ),



    array(
        'type' => 'checkbox',
        'title' => __('Select all <b>backend</b> features you want to enable.', OLZ_I18N),
        'id' => 'olz-configs-backendhooks',
        'options' => array(
            'emojicons' => __('<b>Disable WordPress emojicons</b><small> introduced with WP 4.2 in administration panel.</small>', OLZ_I18N),
            'versioncheck' => __('<b>Remove WordPress version check</b><small> in the administration to all users, except the administrator.</small>', OLZ_I18N),
            'baricons' => __('<b>Remove default menu items</b><small> from WordPress admin bar.</small>', OLZ_I18N),
            'menus' => __('<b>Remove WordPress customization pages</b><small> (ie. Themes, Plugins or Tools) in the administration to all users, except the administrator.</small>', OLZ_I18N),
        ),
        'description' => __('By choosing anyone of these features, you can make your website faster in your admin panel.', OLZ_I18N),
    ),
    array(
        'type' => 'checkbox',
        'title' => __('Select all <b>frontend</b> features you want to enable.', OLZ_I18N),
        'id' => 'olz-configs-frontendhooks',
        'options' => array(
            'generated' => __('<b>Remove WordPress auto-generated tags</b><small>, including feed links, RSD and Windows Live Writter supports, and more.</small>', OLZ_I18N),
            'bodyclass' => __('<b>Remove unecessary details in <code>body_class</code></b><small> making your WordPress website more secure.</small>', OLZ_I18N),
            'emojicons' => __('<b>Disable WordPress emojicons</b><small> introduced with WP 4.2 in frontend panel.</small>', OLZ_I18N),
            'version' => __('<b>Remove generated WordPress version</b><small> which indicates to hackers what WordPress version your are using.</small>', OLZ_I18N),
            'shortcodeformatter' => __('<b>Delete Wordpress auto-formatting</b><small> on shortcodes, as <code>&lt;p&gt;</code> and <code>&lt;br/&gt;</code> HTML tags.</small>', OLZ_I18N),
        ),
        'description' => __('By choosing anyone of these features, you can make your public website faster and more secure.', OLZ_I18N),
    ),
);
