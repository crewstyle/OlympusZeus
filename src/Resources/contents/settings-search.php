<?php
//Build data
$return = array(
    array(
        'type' => 'heading',
        'level' => 2,
        'title' => __('ElasticSearch: an Open Source,<br/>Distributed and RESTful Search Engine.<br/><small style="color:#ccc;font-weight:700;">The Tea T.O. Search Engine</small>', TTO_I18N),
        'style' => 'margin:60px 0;text-align:center;',
    ),



    array(
        'type' => 'search',
    ),
    array(
        'type' => 'heading',
        'level' => 3,
        'title' => __('ElasticSearch is a search server based on Lucene. It provides a distributed, multitenant-capable full-text search engine with a RESTful web interface and schema-free JSON documents. ElasticSearch is developed in Java and is released as open source under the terms of the Apache License.', TTO_I18N),
        'style' => 'font-size:18px;line-height:30px;margin:0 0 40px;text-align:center;',
    ),



    array(
        'type' => 'heading',
        'level' => 2,
        'title' => __('Configuration', TTO_I18N),
    ),
    array(
        'type' => 'text',
        'title' => __('Server host.', TTO_I18N),
        'id' => 'tto-configs-'.$index.'-data[server_host]',
        'default' => 'localhost',
        'placeholder' => 'localhost',
        'description' => __('If your search provider has given you a connection URL, use that instead of filling out server information.<br/>http://<code>localhost</code>:9200 is a common value.', TTO_I18N),
    ),
    array(
        'type' => 'text',
        'title' => __('Server port.', TTO_I18N),
        'id' => 'tto-configs-'.$index.'-data[server_port]',
        'default' => '9200',
        'placeholder' => '9200',
        'description' => __('If your search provider has given you a connection URL, use that instead of filling out server information.<br/>http://localhost:<code>9200</code> is a common value.', TTO_I18N),
    ),
    array(
        'type' => 'text',
        'title' => __('Index name.', TTO_I18N),
        'id' => 'tto-configs-'.$index.'-data[index_name]',
        'default' => 'ttosearch',
        'placeholder' => 'ttosearch',
        'description' => __('Use a uniq name in lowercase with no special character.<br/><code>ttosearch</code> is a good value.', TTO_I18N),
    ),
    array(
        'type' => 'select',
        'title' => __('Results template.', TTO_I18N),
        'id' => 'tto-configs-'.$index.'-data[template]',
        'default' => 'no',
        'options' => array(
            'no' => __('Use the Tea T.O. Search default template.', TTO_I18N),
            'yes' => __('Use your own Search theme template.', TTO_I18N),
        ),
        'description' => __('You can <a href="https://tea-theme-options.readme.io/docs/use-search-engine" target="_blank">click here</a> to see how you can integrate Tea T.O. Search Engine results in your template.', TTO_I18N),
    ),



    array(
        'type' => 'heading',
        'level' => 2,
        'title' => __('Indexation', TTO_I18N),
    ),
    array(
        'type' => 'wordpress',
        'title' => __('Post types', TTO_I18N),
        'id' => 'tto-configs-'.$index.'-data[posttypes]',
        'mode' => 'posttypes',
        'multiple' => true,
        'description' => __('Choose which post types you want to index in your Search Engine.', TTO_I18N),
    ),
    array(
        'type' => 'wordpress',
        'title' => __('Terms', TTO_I18N),
        'id' => 'tto-configs-'.$index.'-data[terms]',
        'mode' => 'terms',
        'multiple' => true,
        'description' => __('Choose which terms you want to index in your Search Engine.', TTO_I18N),
    ),
    array(
        'type' => 'heading',
        'level' => 3,
        'title' => __('To learn more about ElasticSearch, please see the official <a href="http://www.elasticsearch.org/" target="_blank">website</a>.', TTO_I18N),
        'style' => 'font-size:18px;line-height:30px;margin:0 0 40px;text-align:center;',
    ),
);
