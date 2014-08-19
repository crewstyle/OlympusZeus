<?php
//Build data
$titles = array(
    'title' => __('Tea T.O.', TTO_I18N),
    'name' => __('<span style="color:#55bb3a">Welcome</span>', TTO_I18N),
    'slug' => '',
    'submit' => false
);
$details = array(
    array(
        'type' => 'section',
        'content' => __('<h1 style="text-align:center">Your simple, easy to use<br/>and fully integrated<br/><strong>theme options for Wordpress</strong></h1>', TTO_I18N)
    ),
    array(
        'type' => 'section',
        'color' => 'green',
        'image' => TTO_URI . '/assets/img/teato-logo.svg',
        'position' => 'left',
        'content' => __('<h2>The <a href="https://github.com/Takeatea/tea_theme_options" title="Tea Theme Options" class="openit">Tea Theme Options</a> (or <b>Tea T.O.</b>) allows you to easily add professional looking theme options panels to your WordPress theme.</h2>
            <ul>
                <li><b>Option API</b> - A simple and standardized way of storing data in the database.</li>
                <li><b>Transient API</b> - Very similar to the Options API but with the added feature of an expiration time, which simplifies the process of using the wp_options database table to temporarily store cached information.</li>
                <li><b>Easier for administrators</b> - The interface is thought to be the most userfriendly. The Tea TO core adds some extra interface customisations to make your life easier.</li>
                <li><b>Easier for developers</b> - Create a new admin panel easily with the new dashboard. The Tea TO core is made to allow non-developer profiles to easily create the settings they need to customise their templates.</li>
                <li><b>Elasticsearch API</b> - Elasticsearch creates scaleable, real-time search for your website by indexing all your datas millions of times a day.</li>
            </ul>', TTO_I18N)
    ),
    array(
        'type' => 'section',
        'image' => TTO_URI . '/assets/img/teato-cogs.svg',
        'position' => 'right',
        'content' => __('<h2>The <b>Tea T.O.</b> is built for <a href="http://wordpress.org" target="_blank">Wordpress</a> v3.x and uses the Wordpress built-in pages.</h2>
            <ul>
                <li><b>Custom Post Types</b> - Here is the simpliest way to create Wordpress Custom Post Types! A new experience with dashicons, complete customisable backend panels, custom fields and datas saved in DB as the good way.</li>
                <li><b>Custom Taxonomies</b> - Since the 1.4.2 version, you are now able to create easily Wordpress Custom Taxonomies with a couple of lines code. The Tea TO permit to add custom fields as you want.</li>
                <li><b>Wordpress Media Manager</b> - Beautiful interface: A streamlined, all-new experience where you can create galleries faster with drag-and-drop reordering, inline caption editing, and simplified controls.</li>
                <li><b>Full of Options</b> - 3 kind of options used to display information, store fields values or get data from your Wordpress database. The options are made to build your Wordpress pages easily.</li>
            </ul>', TTO_I18N)
    ),
    array(
        'type' => 'section',
        'color' => 'green',
        'image' => TTO_URI . '/assets/img/teato-wp.svg',
        'position' => 'left',
        'content' => __('<h2>How to get values in your Wordpress template?</h2>
            <p>The <b>Tea T.O.</b> allows you to easily get back values and use them into your WordPress theme.<br/>There are 2 ways to get them back.</p>
            <ul>
                <li><b>Wordpress way</b> - By using the <code>get_option</code> method.</li>
                <li><b>Tea T.O. way</b> - Very similar to the Wordpress solution, simply use the <code>_get_option</code> method (pay attention to the first underscore) to get values from Transient API.</li>
            </ul>', TTO_I18N)
    ),
);