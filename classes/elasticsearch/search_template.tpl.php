<?php
/**
 * The Tea T.O. Elasticsearch default template.
 *
 * @package TakeaTea
 * @subpackage Tea Elasticsearch
 * @since 1.4.0
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//Get results from Tea_Theme_Options
$results = $this->searchContents();

//display header
get_header() ?>

    <?php if ($results['total']): ?>
        <aside class="filter-search">
            <ul>
                <?php foreach ($results['types'] as $type): ?>
                    <li><?php echo $type ?> (<?php echo count($results['results'][$type]) ?>)</li>
                <?php endforeach ?>
            </ul>
        </aside>

        <section class="result-search">
            <?php foreach ($results['results'] as $type => $type_results): ?>
                <div class="row">
                    <h2><?php echo sprintf(__('%s (%d)', TTO_I18N), $type, count($type_results)) ?></h2>
                </div>

                <div class="row">
                    <?php foreach ($type_results as $res): ?>
                        <?php
                            //Get datas
                            $score = $res['score'];
                            $source = $res['source'];

                            //Data shared
                            $id = $source['id'];
                            $title = $source['title'];
                            $content = $source['content'];

                            if ('category' == $type || 'post_tag' == $type) {
                                $link = get_term_link(intval($id), $type);
                                ?>
<article class="article" itemscope itemtype="http://schema.org/BlogPosting">
    <!-- Title -->
    <h3 itemprop="name"><?php echo $title ?> (<?php echo $score ?>)</h3>

    <?php if (!empty($content)): ?>
        <!-- Description -->
        <div class="media-content" itemprop="headline"><?php echo $content ?></div>
    <?php endif ?>

    <!-- Read more -->
    <a href="<?php echo $link ?>" title="<?php echo esc_html($title) ?>" itemprop="url"><?php _e('See all', TTO_I18N) ?></a>
</article>
                                <?php
                            }
                            else if ('post' == $type) {
                                $excerpt = $source['excerpt'];
                                $link = get_permalink($id);

                                //get author datas
                                $author = array(
                                    'id' => $source['author'],
                                    'name' => get_the_author_meta('display_name', $source['author']),
                                    'link' => get_author_posts_url($source['author'])
                                );

                                //get date datas
                                $date = array(
                                    'complete' => date('c', strtotime($source['date'])),
                                    'display' => date('j F Y à H:i', strtotime($source['date']))
                                );
                                ?>
<article class="article" itemscope itemtype="http://schema.org/BlogPosting">
    <!-- Categories -->
    <aside class="post-categories" itemprop="keywords">
        <ul>
            <?php get_the_term_list($id, 'category', '<li>', ',</li><li>', '</li>') ?>
        </ul>
    </aside>

    <?php if (has_post_thumbnail($id)):
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'thumbnail');
    ?>
        <!-- Thumbnail -->
        <a href="<?php echo $link ?>" title="<?php echo esc_html($title) ?>" class="thumbnail" itemprop="url">
            <img src="<?php echo $image[0] ?>" alt="" width="<?php echo $image[1] ?>" height="<?php echo $image[2] ?>" />
        </a>
    <?php endif ?>

    <!-- Title -->
    <h3 itemprop="name"><?php echo $title ?> (<?php echo $score ?>)</h3>

    <!-- Details -->
    <span class="media-time">
        <?php echo sprintf(
            __('By <a href="%s" itemprop="author">%s</a> on <time datetime="%s" itemprop="datePublished">%s</time>', TTO_I18N),
            $author['link'],
            $author['name'],
            $date['complete'],
            $date['display']
        ); ?>
    </span>

    <!-- Excerpt -->
    <div class="media-content" itemprop="headline"><?php echo $excerpt ?></div>

    <!-- Read more -->
    <a href="<?php echo $link ?>" title="<?php echo esc_html($title) ?>" itemprop="url"><?php _e('Read more', TTO_I18N) ?></a>
</article>
                                <?php
                            }
                        ?>
                    <?php endforeach ?>
                </div>
            <?php endforeach ?>
        </section>
    <?php else: ?>
        <section class="result-search">
            <div class="row">
                <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', TTO_I18N) ?></p>
            </div>
        </section>
    <?php endif ?>

<?php get_footer() ?>