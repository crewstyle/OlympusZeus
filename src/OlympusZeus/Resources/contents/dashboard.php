<?php
$configs = array(
    'title' => __('Olympus', OLZ_I18N),
    'name' => __('<span style="color:#75cd45">Olympus</span>', OLZ_I18N),
    'slug' => '',
    'submit' => false,
    'contents' => array(
        array(
            'type' => 'section',
            'color' => 'green',
            'title' => __('Your simple, easy to use<br/>and fully integrated<br/><strong>theme options for WordPress</strong>', OLZ_I18N),
            'action' => array(
                'label' => __('Settings <i class="fa fa-long-arrow-right"></i>', OLZ_I18N),
                'href' => $settingslink,
                'target' => '_self',
                'class' => 'button button-primary',
            ),
        ),
        array(
            'type' => 'section',
            'contents' => array(
                array(
                    'svg' => OLZ_PATH . '/../assets/img/zeus-logo.svg',
                    'text' => __('
<h2>The <a href="https://github.com/crewstyle/OlympusZeus" title="Olympus Zeus" target="_blank">Olympus Zeus</a> library allows you to easily add professional looking theme options panels to your WordPress website.</h2>
<p>Easier for administrators <b>and</b> developers, the interface is thought to be the most user-friendly. The <b>Olympus Zeus</b> adds some extra interface customisations to make your life easier. Developers can now create new admin panels with just a couple lines of codes. All is made to allow non-developer profiles to easily create the settings they need to customise their templates.</p>
                    ', OLZ_I18N),
                ),
                array(
                    'svg' => OLZ_PATH . '/../assets/img/zeus-cogs.svg',
                    'text' => __('
<h2>The <b>Olympus Zeus</b> is built with ♥ for <a href="http://wordpress.org" target="_blank">WordPress</a> v3.x/4.x and uses the WordPress built-in pages.</h2>
<p>Custom post types, custom taxonomies, here is the simpliest way to create custom content types to your WordPress website. A new experience with dashicons, complete customisable backend panels, custom fields and datas saved in DB as the good way. The <b>Olympus Zeus</b> uses the WordPress Media Manager too: a beautiful interface where you can create galleries faster with drag-and-drop reordering, inline caption editing, and simplified controls.</p>
                    ', OLZ_I18N),
                ),
                array(
                    'svg' => OLZ_PATH . '/../assets/img/zeus-wp.svg',
                    'text' => __('
<h2>A better search engine, real related contents, highly smart algorithms. The <b>Olympus Zeus</b> offers you full of functionalities.</h2>
<p>The <b>Olympus Zeus</b> works has deeply integrated ElasticSearch search engine features to creates scaleable, real-time search for your website by indexing all your datas millions of times a day. Related contents works by the same way. The <b>Olympus Zeus</b> is upgraded every day with new features you can use as you need.</p>
                    ', OLZ_I18N),
                ),
            ),
        ),
    ),
);
