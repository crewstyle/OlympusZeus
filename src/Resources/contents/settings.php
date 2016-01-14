<?php
$configs = array(
    'title' => __('Settings', OLZ_I18N),
    'name' => __('<span style="color:#27a3ee">Settings</span>', OLZ_I18N),
    'slug' => 'settings',
    'submit' => false,
    'sections' => array(
        'global' => array(
            'title' => __('Globals', OLZ_I18N),
        ),
        'modules' => array(
            'title' => __('Modules', OLZ_I18N),
        ),
    ),
);

if ($thirdsearch) {
    $configs['sections']['search'] = array(
        'title' => __('<i class="fa fa-search fa-lg"></i> Search Engine', OLZ_I18N),
    );
}
