<?php
//Build data
$return = array(
    array(
        'type' => 'heading',
        'level' => 2,
        'title' => __('ElasticSearch: an Open Source,<br/>Distributed and RESTful Search Engine.<br/><small style="color:#ccc;font-weight:700;">The Olympus Zeus Search Engine</small>', OLZ_I18N),
        'style' => 'margin:60px 0;text-align:center;',
    ),



    array(
        'type' => 'search',
    ),
    array(
        'type' => 'heading',
        'level' => 3,
        'title' => __('ElasticSearch is a search server based on Lucene. It provides a distributed, multitenant-capable full-text search engine with a RESTful web interface and schema-free JSON documents. ElasticSearch is developed in Java and is released as open source under the terms of the Apache License.', OLZ_I18N),
        'style' => 'font-size:18px;line-height:30px;margin:0 0 40px;text-align:center;',
    ),



    array(
        'type' => 'heading',
        'level' => 2,
        'title' => __('Configuration', OLZ_I18N),
    ),
    array(
        'type' => 'text',
        'title' => __('Server host.', OLZ_I18N),
        'id' => 'olz-configs-'.$index.'-data[server_host]',
        'default' => 'localhost',
        'placeholder' => 'localhost',
        'description' => __('If your search provider has given you a connection URL, use that instead of filling out server information.<br/>http://<code>localhost</code>:9200 is a common value.', OLZ_I18N),
    ),
    array(
        'type' => 'text',
        'title' => __('Server port.', OLZ_I18N),
        'id' => 'olz-configs-'.$index.'-data[server_port]',
        'default' => '9200',
        'placeholder' => '9200',
        'description' => __('If your search provider has given you a connection URL, use that instead of filling out server information.<br/>http://localhost:<code>9200</code> is a common value.', OLZ_I18N),
    ),
    array(
        'type' => 'text',
        'title' => __('Index name.', OLZ_I18N),
        'id' => 'olz-configs-'.$index.'-data[index_name]',
        'default' => 'olzsearch',
        'placeholder' => 'olzsearch',
        'description' => __('Use a uniq name in lowercase with no special character.<br/><code>olzsearch</code> is a good value.', OLZ_I18N),
    ),
    array(
        'type' => 'select',
        'title' => __('Results template.', OLZ_I18N),
        'id' => 'olz-configs-'.$index.'-data[template]',
        'default' => 'no',
        'options' => array(
            'no' => __('Use the Olympus Zeus Search default template.', OLZ_I18N),
            'yes' => __('Use your own Search theme template.', OLZ_I18N),
        ),
        'description' => __('You can <a href="https://olympus.readme.io/docs/use-search-engine" target="_blank">click here</a> to see how you can integrate Olympus Zeus Search Engine results in your template.', OLZ_I18N),
    ),



    array(
        'type' => 'heading',
        'level' => 2,
        'title' => __('Indexation', OLZ_I18N),
    ),
    array(
        'type' => 'wordpress',
        'title' => __('Post types', OLZ_I18N),
        'id' => 'olz-configs-'.$index.'-data[posttypes]',
        'mode' => 'posttypes',
        'multiple' => true,
        'description' => __('Choose which post types you want to index in your Search Engine.', OLZ_I18N),
    ),
    array(
        'type' => 'wordpress',
        'title' => __('Terms', OLZ_I18N),
        'id' => 'olz-configs-'.$index.'-data[terms]',
        'mode' => 'terms',
        'multiple' => true,
        'description' => __('Choose which terms you want to index in your Search Engine.', OLZ_I18N),
    ),
    array(
        'type' => 'heading',
        'level' => 3,
        'title' => __('To learn more about ElasticSearch, please see the official <a href="http://www.elasticsearch.org/" target="_blank">website</a>.', OLZ_I18N),
        'style' => 'font-size:18px;line-height:30px;margin:0 0 40px;text-align:center;',
    ),
);
