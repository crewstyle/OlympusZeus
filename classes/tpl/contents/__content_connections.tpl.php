<?php
//Build data
$titles = array(
    'title' => __('Connections', TTO_I18N),
    'name' => __('Connections', TTO_I18N),
    'slug' => '_connections',
    'submit' => false
);
$details = array(
    array(
        'type' => 'network',
        'title' => __('Simple, use these boxes to fully integrate your Tea Theme Options for Wordpress with your favorites social networks.', TTO_I18N),
        'std' => array(
            'twitter', 'instagram', 'flickr'
        )
    )
);