
                    <?php if ($submit): ?>
                        <!-- Content save -->
                        <div id="save_content">
                            <div class="inside">
                                <?php submit_button(__('Update', TTO_I18N)) ?>
                            </div>
                        </div>
                        <!-- /Content save -->
                    <?php endif ?>
                </div>
                <!-- /Content block -->

                <div class="clear"></div>
            </div>
        </div>
    <?php if ($submit): ?></form><?php endif ?>

    <footer class="teato-footer">
        <?php if (!empty($capurl)): ?>
            <p><a href="<?php echo $capurl ?>"><b><?php _e('Update capabilities', TTO_I18N) ?></b></a></p>
        <?php endif ?>
        <hr/>
        <p><?php _e('Brewed to you by Take a Tea &copy; 20xx Take a Tea. All rights reserved.', TTO_I18N) ?></p>
        <p><?php echo sprintf(__('Please, contact <a href="mailto:teatime@takeatea.com?subject=Tea Theme Options on %s - version %s"><b>teatime@takeatea.com</b></a> if you have any suggestions.', TTO_I18N), get_bloginfo('name'), $version) ?></p>
        <p><small><?php echo sprintf(__('Your Tea Theme Option is in <b>version %s</b>', TTO_I18N), $version) ?></small></p>
    </footer>
</div>

<div id="modal-elasticsearch" class="tea-modal" tabindex="-1" style="display:none;">
    <header>
        <a href="#" class="close">&times;</a>
        <h2><?php _e('Search template', TTO_I18N) ?></h2>
    </header>

    <div class="content-container">
        <div class="content">
<pre>
&lt;?php
//Get results from Tea_Theme_Options
$results = $tea->getElasticsearch()->searchContents();
?>
&lt;?php if ($results['total']): ?&gt;
    &lt;aside class="filter-search"&gt;
        &lt;ul&gt;
            &lt;?php foreach ($results['types'] as $typ): ?&gt;
                &lt;li&gt;&lt;?php echo $typ ?&gt; (&lt;?php echo count($results['results'][$typ]) ?&gt;)&lt;/li&gt;
            &lt;?php endforeach ?&gt;
        &lt;/ul&gt;
    &lt;/aside&gt;

    &lt;section class="results"&gt;
        &lt;?php foreach ($results['results'] as $type =&gt; $type_res): ?&gt;
            &lt;h2&gt;&lt;?php echo sprintf(__('%s (%d)'), $type, count($type_res)) ?&gt;&lt;/h2&gt;

            &lt;div class="row"&gt;
                &lt;?php foreach ($type_res as $res): ?&gt;
                    &lt;?php
                        //Get source and score
                        $score = $res['score'];
                        $source = $res['source'];

                        //Get datas
                        $id = $source['id'];
                        $title = $source['title'];
                        $content = $source['content'];
                        $excerpt = isset($source['excerpt']) ? $source['excerpt'] : '';
                        $date = isset($source['date']) ? date('j F Y à H:i', strtotime($source['date'])) : date('j F Y à H:i');
                        $author = isset($source['author']) ? get_author_posts_url($source['author']) : '#';
                        $link = 'category' == $type || 'post_tag' == $type ? get_term_link(intval($id), $type) : get_permalink($id);

                        //include template
                        include('content-' . $type . '.php');
                    ?&gt;
                &lt;?php endforeach ?&gt;
            &lt;/div&gt;
        &lt;?php endforeach ?&gt;
    &lt;/section&gt;
&lt;?php else: ?&gt;
    &lt;section class="results"&gt;
        &lt;div class="row"&gt;
            &lt;p&gt;&lt;?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.') ?&gt;&lt;/p&gt;
        &lt;/div&gt;
    &lt;/section&gt;
&lt;?php endif ?&gt;
</pre>
        </div>
    </div>
</div>

<div class="tea-modal-backdrop"></div>
