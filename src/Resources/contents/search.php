<?php
//Build data
$titles = array(
    'title' => __('Search engine', TTO_I18N),
    'name' => __('<span style="color:#3a71bb">Search engine</span>', TTO_I18N),
    'slug' => $slug,
    'submit' => false,
);
$details = array(
    array(
        'type' => 'heading',
        'level' => 2,
        'title' => __('ElasticSearch: an Open Source,<br/>Distributed and RESTful Search Engine.<br/><small style="color:#ccc;font-weight:700;">The Tea T.O. Search Engine</small>', TTO_I18N),
        'style' => 'margin:60px 0;text-align:center;',
    ),
    array(
        'type' => 'search',
        'mode' => 'toggle',
    ),
    array(
        'type' => 'p',
        'content' => __('ElasticSearch is a search server based on Lucene. It provides a distributed, multitenant-capable full-text search engine with a RESTful web interface and schema-free JSON documents. ElasticSearch is developed in Java and is released as open source under the terms of the Apache License.', TTO_I18N),
    ),
);

//Check toggle status
if ($toggle) {
    //Vars
    $help_title = __('Get results from Tea_Theme_Options, instance of `$tea` variable.', TTO_I18N);
    $help_sources = __('Get source and score.', TTO_I18N);
    $help_datas = __('Get datas.', TTO_I18N);
    $help_include = __('Include template.', TTO_I18N);
    $help_apologies = __('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', TTO_I18N);

    //Build
    $details[] = array(
        'type' => 'search',
        'mode' => 'configurations',
    );
    $details[] = array(
        'type' => 'p',
        'content' => __('ElasticSearch can be used to search all kinds of documents. It provides scalable search, has near real-time search, and supports multitenancy. "ElasticSearch is distributed, which means that indices can be divided into shards and each shard can have zero or more replicas. Each node hosts one or more shards, and acts as a coordinator to delegate operations to the correct shard(s). Rebalancing and routing are done automatically [...]".', TTO_I18N),
    );
    $details[] = array(
        'type' => 'search',
        'mode' => 'index',
    );
    $details[] = array(
        'type' => 'p',
        'content' => __('It uses Lucene and tries to make all features of it available through the JSON and Java API. It supports facetting and percolating, which can be useful for notifying if new documents match for registered queries.', TTO_I18N),
    );
    $details[] = array(
        'type' => 'heading',
        'level' => 3,
        'title' => __('Example of PHP code integration', TTO_I18N),
    );
    $details[] = array(
        'type' => 'code',
        'id' => 'es_code_example',
        'change' => false,
        'readonly' => true,
        'default' => array(
            'mode' => 'php',
            'code' => '<?php
    /* ' . $help_title . ' */
    $results = $tea->search()->searchContents();
?>
<?php if ($results[\'total\']): ?>
    <aside class="filter-search">
        <ul>
            <?php foreach ($results[\'types\'] as $typ): ?>
                <li><?php echo $typ ?> (<?php echo count($results[\'results\'][$typ]) ?>)</li>
            <?php endforeach ?>
        </ul>
    </aside>

    <section class="results">
        <?php foreach ($results[\'results\'] as $type => $type_res): ?>
            <h2><?php echo sprintf(__(\'%s (%d)\'), $type, count($type_res)) ?></h2>

            <div class="row">
                <?php foreach ($type_res as $res): ?>
                    <?php
                        //' . $help_sources . '
                        $score = $res[\'score\'];
                        $source = $res[\'source\'];

                        //' . $help_datas . '
                        $id = $source[\'id\'];
                        $title = $source[\'title\'];
                        $content = $source[\'content\'];

                        //' . $help_include . '
                        if (\'category\' == $type || \'post_tag\' == $type) {
                            $link = get_term_link(intval($id), $type);
                    ?>

<article class="article" itemscope itemtype="http://schema.org/BlogPosting">
    <h3 itemprop="name"><?php echo $title ?> (<?php echo $score ?>)</h3>

    <?php if (!empty($content)): ?>
        <div class="media-content" itemprop="headline"><?php echo $content ?></div>
    <?php endif ?>

    <a href="<?php echo $link ?>" title="<?php echo esc_html($title) ?>" itemprop="url"><?php _e(\'See all\') ?></a>
</article>

                    <?php
                        }
                        else if (\'post\' == $type) {
                            $date = isset($source[\'date\']) ? date(\'j F Y à H:i\', strtotime($source[\'date\'])) : date(\'j F Y à H:i\');
                            $excerpt = isset($source[\'excerpt\']) ? $source[\'excerpt\'] : \'\';
                            $link = get_permalink($id);

                            //get author datas
                            $author = array(
                                \'id\' => $source[\'author\'],
                                \'name\' => get_the_author_meta(\'display_name\', $source[\'author\']),
                                \'link\' => get_author_posts_url($source[\'author\'])
                            );

                            //get date datas
                            $date = array(
                                \'complete\' => date(\'c\', strtotime($source[\'date\'])),
                                \'display\' => date(\'j F Y à H:i\', strtotime($source[\'date\']))
                            );
                    ?>

<article class="article" itemscope itemtype="http://schema.org/BlogPosting">
    <aside class="post-categories" itemprop="keywords">
        <ul>
            <?php get_the_term_list($id, \'category\', \'<li>\', \',</li><li>\', \'</li>\') ?>
        </ul>
    </aside>

    <?php if (has_post_thumbnail($id)):
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($id), \'thumbnail\');
    ?>
        <a href="<?php echo $link ?>" title="<?php echo esc_html($title) ?>" class="thumbnail" itemprop="url">
            <img src="<?php echo $image[0] ?>" alt="" width="<?php echo $image[1] ?>" height="<?php echo $image[2] ?>" />
        </a>
    <?php endif ?>

    <h3 itemprop="name"><?php echo $title ?> (<?php echo $score ?>)</h3>

    <span class="media-time">
        <?php echo sprintf(
            __(\'By <a href="%s" itemprop="author">%s</a> on <time datetime="%s" itemprop="datePublished">%s</time>\'),
            $author[\'link\'],
            $author[\'name\'],
            $date[\'complete\'],
            $date[\'display\']
        ); ?>
    </span>

    <div class="media-content" itemprop="headline"><?php echo $excerpt ?></div>

    <a href="<?php echo $link ?>" title="<?php echo esc_html($title) ?>" itemprop="url"><?php _e(\'Read more\') ?></a>
</article>

                    <?php
                        }
                    ?>
                <?php endforeach ?>
            </div>
        <?php endforeach ?>
    </section>
<?php else: ?>
    <section class="results">
        <div class="row">
            <p><?php _e(\'' . $help_apologies . '\') ?></p>
        </div>
    </section>
<?php endif ?>'
        ),
    );
}

$details[] = array(
    'type' => 'p',
    'content' => __('To learn more about ElasticSearch, please see the official <a href="http://www.elasticsearch.org/" target="_blank">website</a>.', TTO_I18N),
);
