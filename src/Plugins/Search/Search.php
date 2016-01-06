<?php

namespace crewstyle\TeaThemeOptions\Plugins\Search;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Plugins\Search\Action\Action;
use crewstyle\TeaThemeOptions\Plugins\Search\Engine\Engine;

/**
 * TTO SEARCH
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//
defined('SE_TEMPLATE') or define('SE_TEMPLATE', TTO_PATH . '/Resources/contents/settings-search.php');

/**
 * TTO Search
 *
 * To get its own search engine with ElasticSearch.
 *
 * @package Tea Theme Options
 * @subpackage Plugins\Search\Search
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Search
{
    /**
     * @var Action
     */
    protected $action = null;

    /**
     * @var string
     */
    protected static $index = '3rd-search';

    /**
     * @var SearchEngine
     */
    protected $search = null;

    /**
     * Constructor.
     *
     * @param boolean $hook Define if we need to call hooks
     *
     * @since 3.3.0
     */
    public function __construct($hook = true)
    {
        //Initialize search engine
        $configs = self::getConfigs();
        $this->search = new Engine($configs, true);

        //Update search content mode?
        if (TTO_IS_ADMIN && !$hook) {
            //Initialize action
            $this->action = new Action($_REQUEST, $this->search);

            return;
        }

        //Get current page
        $currentPage = isset($_REQUEST['page']) ? (string) $_REQUEST['page'] : '';

        //Hooks
        if (TTO_IS_ADMIN && preg_match('/-settings$/', $currentPage)) {
            //Hooks
            add_filter('tto_menu_settings-search_contents', function ($contents) {
                //Get Search datum
                $index = $this->getIndex();
                $enable = TeaThemeOptions::getConfigs($index);
                include(SE_TEMPLATE);

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
            'index_name' => 'ttosearch',
            'read_timeout' => 5,
            'write_timeout' => 10,

            //TTO configs
            'template' => 'no',

            //Indexes
            'posttypes' => array(),
            'terms' => array(),
            'scores' => array(),
        );

        //Return merge values
        return array_merge($defaults, TeaThemeOptions::getConfigs(self::getIndex().'-data'));
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
     * @since 3.0.0
     */
    public function searchChildren($type, $parent, $order = 'desc')
    {
        return $this->search->getEngine()->searchContents($type, $parent, $order);
    }

    /**
     * Search contents.
     *
     * @return array $search Combine of all results, total and aggregations
     *
     * @since 3.0.0
     */
    public function searchContents()
    {
        return $this->search->getEngine()->searchContents();
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
        return $this->search->getEngine()->searchContents($type, $post, $tags);
    }
}
