<?php
//Build data
$titles = array(
    'title' => __('Connections'),
    'name' => __('Connections'),
    'slug' => '_connections',
    'submit' => false
);
$details = array(
    array(
        'type' => 'heading',
        'title' => __('Simple, use these boxes to fully integrate your Tea Theme Options for Wordpress with your favorites social networks.')
    ),
    array(
        'type' => 'twitter',
        'title' => __('Twitter.'),
        'description' => __('Login to your Twitter profile to get all your photos.')
    ),
    array(
        'type' => 'instagram',
        'title' => __('Instagram.'),
        'description' => __('Login to your Instagram profile to get all your photos.')
    ),
    array(
        'type' => 'flickr',
        'title' => __('FlickR.'),
        'description' => __('Login to your FlickR profile to get all your photos.')
    )
);