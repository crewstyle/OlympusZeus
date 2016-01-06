<?php
$return = array(
    array(
        'type' => 'heading',
        'level' => 2,
        'title' => __('Modules included or 3rd-party<br/><small style="color:#ccc;font-weight:700;">enable what you need</small>', TTO_I18N),
        'style' => 'margin:60px 0;text-align:center;',
    ),



    array(
        'type' => 'heading',
        'level' => 2,
        'title' => __('Modules included', TTO_I18N),
    ),
    array(
        'type' => 'checkbox',
        'title' => __('Select all modules you want to enable.', TTO_I18N),
        'id' => 'tto-configs-modules',
        'options' => array(
            'database' => __('Close WordPress database connections?', TTO_I18N),
            'customlogin' => __('Enable Tea T.O. custom login page?', TTO_I18N),
            'sanitizedfilename' => __('Sanitize media filenames? <small>Sanitizing media filenames uploaded throught the WordPress Media Manager. It permits to make them readable by all browsers, including Safari which not accepts special characters.</small>', TTO_I18N),
            //'infinitescroll' => __('<i class="fa fa-spinner fa-lg"></i> <b>Infinite scroll</b><small>, ...</small>', TTO_I18N),
            //'markdown' => __('<b>#</b> <b>Markdown</b><small>, a text-to-HTML conversion tool for instead of the TinyMCE editor. It allows you to write using an easy-to-read, easy-to-write plain text format, then convert it to structurally valid XHTML (or HTML).</small>', TTO_I18N),
        ),
    ),



    array(
        'type' => 'heading',
        'level' => 2,
        'title' => __('3rd-party modules', TTO_I18N),
    ),
    array(
        'type' => 'toggle',
        'title' => __('<i class="fa fa-search fa-lg"></i> <b>Search Engine</b><small>, a full integration with ElasticSearch, an open source, distributed and RESTful search engine.</small>', TTO_I18N),
        'id' => 'tto-configs-3rd-search',
    ),
    /*array(
        'type' => 'toggle',
        'title' => __('<i class="fa fa-sitemap fa-lg"></i> <b>Related Contents</b><small>, scans and analyses all of your posts to show contextual posts to your visitors.</small>', TTO_I18N),
        'id' => 'tto-configs-3rd-related',
    ),
    array(
        'type' => 'toggle',
        'title' => __('<i class="fa fa-comments fa-lg"></i> <b>Comment Systems</b><small>, the best way to keep your website alive with this new comment system.</small>', TTO_I18N),
        'id' => 'tto-configs-3rd-comments',
    ),
    array(
        'type' => 'toggle',
        'title' => __('<i class="fa fa-globe fa-lg"></i> <b>Social Networks</b><small>, connect all your social profiles with your WordPress website to create a dual integrity between them.</small>', TTO_I18N),
        'id' => 'tto-configs-3rd-networks',
    ),*/
);
