<?php

namespace crewstyle\TeaThemeOptions\Search\Elastica;

use Elastica\Client;
use Elastica\Document;
use Elastica\Exception\NotFoundException;
use Elastica\Filter\Bool;
use Elastica\Filter\Term;
use Elastica\Query;
use Elastica\Query\QueryString;
use Elastica\Suggest;
use Elastica\Suggest\Term as SuggestTerm;
use Elastica\Type\Mapping;
use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\PostType\Engine\Engine as PostTypeEngine;
use crewstyle\TeaThemeOptions\Search\Search;

/**
 * TTO SEARCH ELASTICA
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Search Elastica
 *
 * Class used to work with Search Elastica.
 *
 * @package Tea Theme Options
 * @subpackage Search\Elastica\Elastica
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Elastica
{
    /**
     * @var object
     */
    protected $client = null;

    /**
     * @var array
     */
    protected $configs = array();

    /**
     * @var object
     */
    protected $index = null;

    /**
     * Constructor.
     *
     * @param array $configs Contains all Search Engine configurations
     *
     * @since 3.0.0
     */
    public function __construct($configs)
    {
        $this->setConfig($configs);
    }

    /**
     * Get Elastica Client object.
     *
     * @return object $client Object of the Elastica Client datas
     *
     * @since 3.0.0
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * Set Elastica Client object.
     *
     * @param object $client Object of the Elastica Client datas
     *
     * @since 3.0.0
     */
    protected function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * Get configs.
     *
     * @return array $search Array of all search datas
     *
     * @since 3.0.0
     */
    protected function getConfig()
    {
        $default = Search::getDefaults();
        return array_merge($default, $this->config);
    }

    /**
     * Set configs.
     *
     * @param array $config Array of all new config datas
     *
     * @since 3.0.0
     */
    protected function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Get Index object.
     *
     * @return object $index Object of the Elastica index
     *
     * @since 3.0.0
     */
    protected function getIndex()
    {
        if (null === $this->index) {
            //Get configs
            $ctn = $this->getConfig();

            //Set client
            $client = new Client(array(
                'host' => $ctn['server_host'],
                'port' => $ctn['server_port'],
            ));

            $index = $client->getIndex($ctn['index_name']);
            $this->setIndex($index);
        }

        return $this->index;
    }

    /**
     * Set index.
     *
     * @param object $index
     *
     * @since 3.0.0
     */
    protected function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * Create Elastica Document for special post.
     *
     * @param object $post Wordpress post object
     * @return array $document Elastica Document indexed with post ID
     *
     * @since 3.0.0
     */
    protected function addDocumentPost($post)
    {
        global $blog_id;

        //Create document
        $doc = array(
            'blog_id' => $blog_id
        );

        //Check field 'ID'
        if (isset($post->ID)) {
            $doc['id'] = $post->ID;

            //Get tags
            $tags = get_the_term_list($post->ID, 'post_tag', '', ',', '');
            $tags = strip_tags($tags);

            //Check tags
            if (!empty($tags)) {
                $doc['tags'] = $tags;
            }
        }

        //Check field 'post_parent'
        if (isset($post->post_parent)) {
            $doc['parent'] = $post->post_parent;
        }

        //Check field 'post_title'
        if (isset($post->post_title)) {
            $doc['title'] = $post->post_title;
        }

        //Check field 'post_content'
        if (isset($post->post_content)) {
            $doc['content'] = strip_tags(stripcslashes($post->post_content));
        }

        //Check field 'post_excerpt'
        if (isset($post->post_excerpt)) {
            $doc['excerpt'] = strip_tags(stripcslashes($post->post_excerpt));
        }

        //Check field 'post_author'
        if (isset($post->post_author)) {
            $doc['author'] = $post->post_author;
        }

        //Check field 'post_date'
        if (isset($post->post_date)) {
            $doc['date'] = date('c', strtotime($post->post_date));
        }

        //Return document
        return $doc;
    }

    /**
     * Create Elastica Document for special term.
     *
     * @param object $term Wordpress term object
     * @return array $document Elastica Document indexed with term ID
     *
     * @since 3.0.0
     */
    protected function addDocumentTerm($term)
    {
        global $blog_id;

        //Create document
        $doc = array(
            'blog_id' => $blog_id
        );

        //Check field 'term_id'
        if (isset($term->term_id)) {
            $doc['id'] = $term->term_id;
        }

        //Check field 'name'
        if (isset($term->name)) {
            $doc['title'] = $term->name;
        }

        //Check field 'description'
        if (isset($term->description)) {
            $doc['content'] = strip_tags(stripcslashes($term->description));
        }

        //Return document
        return $doc;
    }

    /**
     * Create Elastica Analysis.
     *
     * @param object $index Elastica Index
     * @param array $posttypes Array containing all post types
     * @param array $terms Array containing all terms
     * @return object $index Elastica Index
     *
     * @since 3.0.0
     */
    protected function analysis($index, $posttypes, $terms)
    {
        //Check integrity
        if (empty($index)) {
            return null;
        }

        //Check integrity
        if (empty($posttypes) && empty($terms)) {
            return null;
        }

        //Define properties
        $props = array(
            'id' => array(
                'type' => 'integer',
                'include_in_all' => false,
            ),
            'tags' => array(
                'type' => 'string',
                'index' => 'analyzed',
            ),
            'parent' => array(
                'type' => 'integer',
                'index' => 'analyzed',
            ),
            'title' => array(
                'type' => 'string',
                'index' => 'analyzed',
            ),
            'content' => array(
                'type' => 'string',
                'index' => 'analyzed',
            ),
            'excerpt' => array(
                'type' => 'string',
                'index' => 'analyzed',
            ),
            'author' => array(
                'type' => 'integer',
                'index' => 'analyzed',
            ),
            'date' => array(
                'type' => 'date',
                'format' => 'date_time_no_millis',
            ),
            'tags_suggest' => array(
                'type' => 'completion',
                'index_analyzer' => 'simple',
                'search_analyzer' => 'simple',
                'payloads' => false,
            ),
            '_boost' => array(
                'type' => 'float',
                'include_in_all' => false,
            ),
        );

        //Set analysis
        if (isset($posttypes) && !empty($posttypes)) {
            foreach ($posttypes as $k) {
                $index->create(array(
                    'number_of_shards' => 4,
                    'number_of_replicas' => 1,
                    'analysis' => array(
                        'analyzer' => array(
                            'indexAnalyzer' => array(
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'filter' => array('lowercase', 'asciifolding', 'filter_' . $k),
                            ),
                            'searchAnalyzer' => array(
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'filter' => array('standard', 'lowercase', 'asciifolding', 'filter_' . $k),
                            )
                        ),
                        'filter' => array(
                            'filter_' . $k => array(
                                'type' => 'standard',
                                'language' => TTO_LOCAL,
                                'ignoreCase' => true,
                            )
                        ),
                    ),
                ), true);

                //Define new Type
                $type = $index->getType($k);

                //Define a new Elastica Mapper
                $mapping = new Mapping();
                $mapping->setType($type);
                $mapping->setParam('index_analyzer', 'indexAnalyzer');
                $mapping->setParam('search_analyzer', 'searchAnalyzer');

                //Define boost field
                $mapping->setParam('_boost', array(
                    'name' => '_boost',
                    'null_value' => 1.0
                ));

                //Set mapping
                $mapping->setProperties($props);

                // Send mapping to type
                $mapping->send();
            }
        }

        //Set analysis
        if (isset($terms) && !empty($terms)) {
            foreach ($terms as $t) {
                $index->create(array(
                    'number_of_shards' => 4,
                    'number_of_replicas' => 1,
                    'analysis' => array(
                        'analyzer' => array(
                            'indexAnalyzer' => array(
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'filter' => array('lowercase', 'asciifolding', 'filter_' . $t),
                            ),
                            'searchAnalyzer' => array(
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'filter' => array('standard', 'lowercase', 'asciifolding', 'filter_' . $t),
                            )
                        ),
                        'filter' => array(
                            'filter_' . $t => array(
                                'type' => 'standard',
                                'language' => TTO_LOCAL,
                                'ignoreCase' => true,
                            )
                        ),
                    ),
                ), true);

                //Define new Type
                $type = $index->getType($t);

                //Define a new Elastica Mapper
                $mapping = new Mapping();
                $mapping->setType($type);
                $mapping->setParam('index_analyzer', 'indexAnalyzer');
                $mapping->setParam('search_analyzer', 'searchAnalyzer');

                //Define boost field
                $mapping->setParam('_boost', array(
                    'name' => '_boost',
                    'null_value' => 1.0
                ));

                //Set mapping
                $mapping->setProperties($props);

                // Send mapping to type
                $mapping->send();
            }
        }

        //Return index
        return $index;
    }

    /**
     * Check Elastica Connection.
     *
     * @param array $ctn Contains all stored datas
     * @return int $status HTTP header status curl code
     *
     * @since 3.0.0
     */
    public function connection($ctn)
    {
        //Check if we are in admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Do we have to check connection?
        if (!isset($ctn['toggle']) || !$ctn['toggle']) {
            return 0;
        }

        //Defaults
        $defaults = Search::getDefaults();

        //Build url
        $url = 'http://';
        $url .= isset($ctn['server_host']) ? $ctn['server_host'] : $defaults['server_host'];
        $url .= ':' . (isset($ctn['server_port']) ? $ctn['server_port'] : $defaults['server_port']) . '/';
        $url .= isset($ctn['index_name']) ? $ctn['index_name'] . '/' : '';
        $url .= '_status?ignore_unavailable=true';

        //Make curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'XGET');
        $head = curl_exec($ch);
        $status = (string) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        //Get JSON head
        $json = json_decode($head, true);

        //Check errors
        if ($status && (200 == $status || 404 == $status)) {
            //Everything is good, everything is CocaCola!
            return 200 == $status ? 200 : 404;
        }
        else if (null !== $json) {
            //Okay, only the brave, by Diesel
            $error = 'IndexMissingException[['.$ctn['index_name'].'] missing]';
            return isset($json->error) && $error == $json->error ? 404 : 0;
        }

        //Hum... Nothing is good over here.
        return 0;
    }

    /**
     * Index contents.
     *
     * @param boolean $idxctn Define it we have to index contents or just create index
     * @return int $count Get number of items indexed
     *
     * @since 3.0.0
     */
    public function indexContents($idxctn = true)
    {
        //Check page
        if (!TTO_IS_ADMIN) {
            return 0;
        }

        //Do we have to index contents
        if (!$idxctn) {
            return 0;
        }

        //Get search datas
        $ctn = $this->getConfig();
        $indexpt = PostTypeEngine::getIndex();

        //Check if we can index some post types
        if (!isset($ctn[$indexpt]) || empty($ctn[$indexpt])) {
            return 0;
        }

        //Get index
        $index = $this->getIndex();
        $idp = $ctn[$indexpt];
        $idt = isset($ctn['index_terms']) && !empty($ctn['index_terms'])
            ? $ctn['index_terms']
            : array();

        //Check index
        if (null === $index || empty($index)) {
            return 0;
        }

        //Get all wanted posts
        $count = 0;
        $posts = get_posts(array(
            'posts_per_page' => -1,
            'numberposts' => -1,
            'post_type' => $idp,
            'post_status' => 'publish',
            'orderby' => 'post_date',
            'order' => 'DESC',
        ));

        //Iterate on all posts to create documents
        foreach ($posts as $post) {
            //Check post type
            if (!array_key_exists($post->post_type, $idp)) {
                continue;
            }

            //Update document
            $this->postUpdate($post, $index);

            //Update counter
            $count++;
        }

        //Check taxonomies
        if (!empty($idt)) {
            //Get all wanted taxonomies
            $terms = get_terms($idt, array(
                'orderby' => 'slug',
                'hide_empty' => false,
            ));

            //Iterate on all posts to create documents
            foreach ($terms as $term) {
                //Check post type
                if (!array_key_exists($term->taxonomy, $idt)) {
                    continue;
                }

                //Update document
                $this->termUpdate($term, $index);

                //Update counter
                $count++;
            }
        }

        //Refresh index
        $index->refresh();

        //Set and return count
        return $count;
    }

    /**
     * Create Elastica Client.
     *
     * @param boolean $write Define if we are writing transactions or reading them
     * @return object $client Elastica Client
     *
     * @since 3.0.0
     */
    protected function makeClient($write = false)
    {
        //Get Elastica Client
        $client = $this->getClient();

        //Check integrity
        if (!empty($client)) {
            return $client;
        }

        //Get search datas
        $ctn = $this->getConfig();

        //Intensiate new object with server URL
        $client = new Client(array(
            'host' => $ctn['server_host'],
            'port' => $ctn['server_port'],
            'timeout' => $write ? $ctn['write_timeout'] : $ctn['read_timeout']
        ));

        //Define the new Client
        $this->setClient($client);

        //Return the created client
        return $client;
    }

    /**
     * Create Elastica Index.
     *
     * @param object $client Elastica Client
     * @return object $index Elastica Index
     *
     * @since 3.0.0
     */
    protected function makeIndex($client)
    {
        //Check integrity
        if (empty($client) || null === $client) {
            return null;
        }

        //Get Elastica Index
        $index = $this->getIndex();

        //Check integrity
        if (isset($index) && !empty($index) && null !== $index) {
            return $index;
        }

        //Get search datas
        $ctn = $this->getConfig();

        //Update index
        $index = $client->getIndex($ctn['index_name']);

        //Update Index var
        $this->setIndex($index);

        //Return the created client
        return $index;
    }

    /**
     * Index contents.
     *
     * @since 3.0.0
     */
    public function makeSearch()
    {
        //Check page
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get client and index
        $client = $this->getClient();
        $index = $this->getIndex();

        //Check integrity
        if (empty($client)) {
            $client = $this->makeClient(true);
        }

        //Check integrity
        if (empty($index)) {
            $index = $this->makeIndex($client);
        }

        //Get search datas
        $ctn = $this->getConfig();
        $indexpt = PostTypeEngine::getIndex();

        //Get datas for mapping
        $idp = isset($ctn[$indexpt]) ? $ctn[$indexpt] : array();
        $idt = isset($ctn['index_terms']) ? $ctn['index_terms'] : array();

        //Create analysers and mappers for Posts
        $index = $this->analysis($index, $idp, $idt);

        //Update index
        $this->setIndex($index);
    }

    /**
     * Delete post from Elastica Client.
     *
     * @param object $post Post to delete
     *
     * @since 3.0.0
     */
    public function postDelete($post)
    {
        //Get configs
        $ctn = $this->getConfig();
        $indexpt = PostTypeEngine::getIndex();

        //Check post integrity
        if (null == $post || !array_key_exists($post->post_type, $ctn[$indexpt])) {
            return;
        }

        //Get index
        $index = $this->getIndex();

        //Get type
        $type = $index->getType($post->post_type);

        //Try to delete post
        try {
            //Delete post by its ID
            $type->deleteById($post->ID);

            //Update counter
            $countname = Search::getCountName();
            $count = TeaThemeOptions::getConfigs($countname);
            $count = empty($count) ? 0 : $count[0] - 1;

            //Save in DB
            TeaThemeOptions::setConfigs($countname, $count);
        } catch (NotFoundException $ex){}
    }

    /**
     * Update or Add a post into Elastica Client.
     *
     * @param object $post Post to update or add
     * @param object $index Elastica Index
     *
     * @since 3.0.0
     */
    public function postUpdate($post, $index = null)
    {
        //Get configs
        $ctn = $this->getConfig();
        $indexpt = PostTypeEngine::getIndex();

        //Check post integrity
        if (null == $post || !array_key_exists($post->post_type, $ctn[$indexpt])) {
            return;
        }

        //Get index
        $index = null !== $index ? $index : $this->getIndex();

        //Check index
        if (null === $index) {
            return;
        }

        //Try to update or add post
        try {
            $doc = $this->addDocumentPost($post);
            $type = $index->getType($post->post_type);
            $type->addDocument(new Document($post->ID, $doc));
        } catch (NotFoundException $ex){}
    }

    /**
     * Update or Add a taxonomy into Elastica Client.
     *
     * @param object $term Term to update or add
     * @param object $index Elastica Index
     *
     * @since 3.0.0
     */
    public function termUpdate($term, $index = null)
    {
        //Get index
        $index = null != $index ? $index : $this->getIndex();

        //Check index
        if (null === $index) {
            return;
        }

        //Try to update or add post
        try {
            //Make the magic
            $doc = $this->addDocumentTerm($term);
            $type = $index->getType($term->taxonomy);
            $type->addDocument(new Document($term->term_id, $doc));
        } catch (NotFoundException $ex){}
    }

    /**
     * Search children.
     *
     * @param string $type Post type
     * @param int $parent Parent ID to get all children
     * @param string $order Order way
     * @return array $search Combine of all results, total and aggregations
     *
     * @since 3.0.0
     */
    public function searchChildren($type, $parent, $order = 'desc')
    {
        //Check page
        if (is_search()) {
            return;
        }

        //Return array
        $return = array(
            'parent' => $parent,
            'total' => 0,
            'results' => array()
        );

        //Check request
        if (empty($parent)) {
            return $return;
        }

        //Get query vars
        $results = array();
        $types = array();

        //Get Elasticsearch datas
        $index = $this->getIndex();

        //Check index
        if (null === $index || empty($index)) {
            return $return;
        }

        //Create the actual search object with some data.
        $es_query = new Query();

        //Define term
        $es_term = new Term();
        $es_term->setTerm($type . '.parent', $parent);

        //Filter 'And'
        $es_filter = new Bool();
        $es_filter->addMust($es_term);

        //Add filter to the search object
        $es_query->setFilter($es_filter);

        //Add sort
        $es_query->setSort(array(
            $type . '.date' => array('order' => $order)
        ));

        //Search!
        $es_resultset = $index->search($es_query);

        //Retrieve data
        $es_results = $es_resultset->getResults();

        //Check results
        if (null == $es_results || empty($es_results)) {
            return $return;
        }

        //Iterate to retrieve all IDs
        foreach ($es_results as $res) {
            $typ = $res->getType();

            //Save type
            $types[$typ] = $typ;

            //Save datas
            $results[$typ][] = array(
                'id' => $res->getId(),
                'score' => $res->getScore(),
                'source' => $res->getSource(),
            );
        }

        //Get total
        $total = $es_resultset->getTotalHits();

        //Return everything
        $return = array(
            'parent' => $parent,
            'total' => $total,
            'results' => $results
        );

        return $return;
    }

    /**
     * Search contents.
     *
     * @return array $elasticsearches Combine of all results, total and aggregations
     *
     * @since 1.5.0
     */
    public function searchContents()
    {
        //Return array
        $return = array(
            'query' => array(
                'search' => '',
                'type' => '',
                'paged' => 0,
                'perpage' => 0
            ),
            'total' => 0,
            'types' => array(),
            'results' => array()
        );

        //Check page
        if (!is_search()) {
            return $return;
        }

        //Get query vars
        $request = isset($_REQUEST) ? $_REQUEST : array();
        $results = array();
        $types = array();

        //Check request
        if (empty($request)) {
            return $return;
        }

        //Get Elasticsearch datas
        $index = $this->getIndex();

        //Check index
        if (null === $index || empty($index)) {
            return $return;
        }

        //Get search datas
        $search = isset($request['s'])
            ? str_replace('\"', '"', $request['s'])
            : '';

        //Return everything
        if (empty($search)) {
            return $return;
        }

        //Get search datas
        $type = isset($request['type']) ? $request['type'] : '';
        $paged = isset($request['paged']) && !empty($request['paged'])
            ? $request['paged'] - 1
            : 0;
        $perpage = isset($request['perpage'])
            ? $request['perpage']
            : TeaThemeOptions::getOption('posts_per_page', 10);

        //Build query string
        $es_querystring = new QueryString();

        //'And' or 'Or' default: 'Or'
        $es_querystring->setDefaultOperator('OR');
        $es_querystring->setQuery($search);

        //Create the actual search object with some data.
        $es_query = new Query();
        $es_query->setQuery($es_querystring);

        //Define options
        $es_query->setFrom($paged);     //Start
        $es_query->setLimit($perpage);  //How many

        //Search!
        $es_resultset = $index->search($es_query);

        //Retrieve data
        $es_results = $es_resultset->getResults();

        //Check results
        if (null == $es_results || empty($es_results)) {
            $return['query']['search'] = str_replace(' ', '+', $search);
            return $return;
        }

        //Iterate to retrieve all IDs
        foreach ($es_results as $res) {
            $typ = $res->getType();

            //Save type
            $types[$typ] = $typ;

            //Save datas
            $results[$typ][] = array(
                'id' => $res->getId(),
                'score' => $res->getScore(),
                'source' => $res->getSource(),
            );
        }

        //Get total
        $total = $es_resultset->getTotalHits();

        //Return everything
        $return = array(
            'query' => array(
                'search' => str_replace(' ', '+', $search),
                'type' => $type,
                'paged' => $paged,
                'perpage' => $perpage
            ),
            'total' => $total,
            'types' => $types,
            'results' => $results
        );

        return $return;
    }

    /**
     * Search suggest.
     *
     * @param string $type Post type
     * @param int $post Post ID to get all suggestions
     * @param array $tags Array contains all post tags
     * @return array $search Combine of all results, total and aggregations
     *
     * @since 3.0.0
     */
    public function searchSuggest($type, $post, $tags)
    {
        //Check page
        if (!is_search()) {
            return;
        }

        //Return array
        $return = array(
            'post' => $post,
            'tags' => $tags,
            'total' => 0,
            'results' => array()
        );

        //Check request
        if (empty($post)) {
            return $return;
        }

        //Get query vars
        $results = array();

        //Get Elasticsearch datas
        $index = $this->getIndex();

        //Check index
        if (null === $index || empty($index)) {
            return $return;
        }

        //Create suggestion
        $es_suggest = new Suggest();

        //Iterate on all tags
        foreach ($tags as $k => $tag) {
            //CReate Term with options
            $es_term = new SuggestTerm('tags_suggest_' . $k, '_all');
            $es_term->setText($tag);
            $es_term->setSize(5);
            $es_term->setAnalyzer('simple');

            //Add Term to current suggestion
            $es_suggest->addSuggestion($es_term);
        }

        //Search!
        $es_resultset = $index->search($es_suggest);

        //Retrieve data
        $es_results = $es_resultset->getSuggests();

        //Check results
        if (null == $es_results || empty($es_results)) {
            return $return;
        }

        //Iterate to retrieve all IDs
        foreach ($es_results as $res) {
            //Check suggestions
            if (empty($res[0]['options'])) {
                continue;
            }

            //Iterate on all options
            foreach ($res[0]['options'] as $opt) {
                //Save datas
                $results[$opt['text']] = array(
                    'score' => $opt['score'],
                    'freq' => $opt['freq'],
                );
            }
        }

        //Get total
        $total = $es_resultset->getTotalHits();

        //Return everything
        $return = array(
            'post' => $post,
            'tags' => $tags,
            'total' => $total,
            'results' => $results
        );

        return $return;
    }
}
