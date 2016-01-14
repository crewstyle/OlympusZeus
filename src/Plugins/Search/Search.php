<?php

namespace crewstyle\OlympusZeus\Plugins\Search;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Plugins\Search\SearchAction;
use crewstyle\OlympusZeus\Plugins\Search\SearchEngine;

/**
 * Gets its own search engine with ElasticSearch.
 *
 * @package Olympus Zeus
 * @subpackage Plugins\Search\Search
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class Search
{
    const SE_TEMPLATE = '/Resources/contents/settings-search.php';

    /**
     * @var string
     */
    protected static $index = '3rd-search';

    /**
     * @var SearchAction
     */
    protected $searchAction = null;

    /**
     * @var SearchEngine
     */
    protected $searchEngine = null;

    /**
     * Constructor.
     *
     * @param boolean $hook Define if we need to call hooks
     *
     * @since 4.0.0
     */
    public function __construct($hook = true)
    {
        //Initialize search engine
        $configs = self::getConfigs();
        $this->searchEngine = new SearchEngine($configs, true);

        //Update search content mode?
        if (OLZ_ISADMIN && !$hook) {
            //Initialize action
            $this->searchAction = new SearchAction($_REQUEST, $this->searchEngine);

            return;
        }

        //Get current page
        $currentPage = isset($_REQUEST['page']) ? (string) $_REQUEST['page'] : '';

        //Hooks
        if (OLZ_ISADMIN && preg_match('/-settings$/', $currentPage)) {
            //Hooks
            add_filter('olz_menu_settings-search_contents', function ($contents) {
                //Get Search datum
                $index = $this->getIndex();
                $enable = OlympusZeus::getConfigs($index);
                include(OLZ_PATH.self::SE_TEMPLATE);

                return $return;
            }, 10, 1);
        }
    }

    /**
     * Get config values.
     *
     * @return array $configs Contains all config values
     *
     * @since 3.3.0
     */
    public static function getConfigs()
    {
        //Build defaults
        $defaults = array(
            //ES configs
            'status' => 0,
            'server_host' => 'localhost',
            'server_port' => '9200',
            'index_name' => 'olzsearch',
            'read_timeout' => 5,
            'write_timeout' => 10,

            //Library configs
            'template' => 'no',

            //Indexes
            'posttypes' => array(),
            'terms' => array(),
            'scores' => array(),
        );

        //Return merge values
        return array_merge($defaults, OlympusZeus::getConfigs(self::getIndex().'-data'));
    }

    /**
     * Get index.
     *
     * @return string $name Search index name
     *
     * @since 3.0.0
     */
    public static function getIndex()
    {
        return (string) self::$index;
    }

    /**
     * Search children.
     *
     * @param string $type Post type
     * @param int $parent Parent ID to get all children
     * @param string $order Order way
     * @return array $search Combine of all results, total and aggregations
     *
     * @since 4.0.0
     */
    public function searchChildren($type, $parent, $order = 'desc')
    {
        return $this->searchEngine->searchContents($type, $parent, $order);
    }

    /**
     * Search contents.
     *
     * @return array $search Combine of all results, total and aggregations
     *
     * @since 4.0.0
     */
    public function searchContents()
    {
        return $this->searchEngine->searchContents();
    }

    /**
     * Search suggest.
     *
     * @param string $type Post type
     * @param int $post Post ID to get all suggestions
     * @param array $tags Array contains all post tags
     * @return array $search Combine of all results, total and aggregations
     *
     * @since 4.0.0
     */
    public function searchSuggest($type, $post, $tags)
    {
        return $this->searchEngine->searchContents($type, $post, $tags);
    }
}
