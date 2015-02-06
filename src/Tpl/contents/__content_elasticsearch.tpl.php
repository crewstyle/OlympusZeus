<?php
//Build data
$titles = array(
    'title' => __('Elasticsearch', TTO_I18N),
    'name' => __('<span style="color:#3a71bb">Elasticsearch</span>', TTO_I18N),
    'slug' => '_elasticsearch',
    'submit' => false,
);
$details = array(
    array(
        'type' => 'elasticsearh',
        'title' => __('Elasticsearh: an Open Source, Distributed and RESTful Search Engine.', TTO_I18N),
        'description' => __('
            <p>Elasticsearch is a search server based on Lucene. It provides a distributed, multitenant-capable full-text search engine with a RESTful web interface and schema-free JSON documents. Elasticsearch is developed in Java and is released as open source under the terms of the Apache License.</p>
            <p>Elasticsearch can be used to search all kinds of documents. It provides scalable search, has near real-time search, and supports multitenancy. "ElasticSearch is distributed, which means that indices can be divided into shards and each shard can have zero or more replicas. Each node hosts one or more shards, and acts as a coordinator to delegate operations to the correct shard(s). Rebalancing and routing are done automatically [...]".</p>
            <p>It uses Lucene and tries to make all features of it available through the JSON and Java API. It supports facetting and percolating, which can be useful for notifying if new documents match for registered queries.</p>
            <p>Another feature is called "gateway" and handles the long term persistence of the index; for example, an index can be recovered from the gateway in a case of a server crash. Elasticsearch supports real-time GET requests, which makes it suitable as a NoSQL solution, but it lacks distributed transactions.</p>
            <p>To learn more about Elasticsearch, <a href="http://www.elasticsearch.org/" target="_blank">please see the official website</a>.', TTO_I18N),
    ),
);
