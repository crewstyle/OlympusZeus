<?php

namespace crewstyle\TeaThemeOptions\Search;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Search\Action\Action;
use crewstyle\TeaThemeOptions\Search\Engine\Engine;

/**
 * TTO SEARCH
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//
defined('SE_TEMPLATE') or define('SE_TEMPLATE', TTO_PATH . '/Resources/contents/search.php');

/**
 * TTO Search
 *
 * To get its own search engine with ElasticSearch.
 *
 * @package Tea Theme Options
 * @subpackage Search\Search
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.2.4
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
    protected static $countname = 'search-count';

    /**
     * @var string
     */
    protected static $id = 'search-configs';

    /**
     * @var string
     */
    protected static $index = 'search';

    /**
     * @var SearchEngine
     */
    protected $search = null;

    /**
     * @var string
     */
    protected static $slug = '_search';

    /**
     * @var string
     */
    protected static $template = SE_TEMPLATE;

    /**
     * Constructor.
     *
     * @param boolean $enable Define if search engine is enabled
     * @param boolean $hook Define if we need to call hooks
     * @param boolean $filters Define if we need to call filters
     *
     * @since 3.0.0
     */
    public function __construct($enable = true, $hook = true, $filters = true)
    {
        //Check search engine
        if (!$enable) {
            return;
        }

        //Get custom data
        $id = self::getId();
        $ctn = TeaThemeOptions::getConfigs($id);

        //Initialize action and search
        $this->action = new Action();
        $this->search = new Engine($ctn, $hook);

        //Hooks
        if ($filters) {
            add_filter('tea_to_menu_check', array(&$this, 'hookMenuCheck'), 10, 2);
            add_filter('tea_to_menu_init', array(&$this, 'hookMenuInit'), 10, 3);
            add_filter('tea_to_template_special', array(&$this, 'hookTemplateSpecial'), 10, 2);
        }
    }

    /**
     * Get count name.
     *
     * @return string $countname Search count name
     *
     * @since 3.0.0
     */
    public static function getCountName()
    {
        return (string) self::$countname;
    }

    /**
     * Get default values.
     *
     * @return array $default Contains all default values
     *
     * @since 3.0.0
     */
    public static function getDefaults()
    {
        return array(
            'toggle' => false,
            'status' => 0,
            'server_host' => 'localhost',
            'server_port' => '9200',
            'index_name' => 'elasticsearch',
            'read_timeout' => 5,
            'write_timeout' => 10,
            'template' => 'no',
            'scores' => array(),
            'index_posttypes' => array(),
            'index_terms' => array()
        );
    }

    /**
     * Get id.
     *
     * @return string $id Search id
     *
     * @since 3.0.0
     */
    public static function getId()
    {
        return (string) self::$id;
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
     * Get slug.
     *
     * @return string $slug Search slug
     *
     * @since 3.0.0
     */
    public static function getSlug()
    {
        return (string) self::$slug;
    }

    /**
     * Get template.
     *
     * @return string $template Search template
     *
     * @since 3.0.0
     */
    public static function getTemplate()
    {
        return (string) self::$template;
    }

    /**
     * Hook special filter
     *
     * @return array $tocheck
     *
     * @since 3.0.0
     */
    public function hookMenuCheck($tocheck, $identifier) {
        return array_merge($tocheck, array(
            $identifier . self::getSlug()
        ));
    }

    /**
     * Hook special filter
     *
     * @return array $contents
     *
     * @since 3.0.0
     */
    public function hookMenuInit($contents, $options, $canuser) {
        // Get post types engine page contents
        if (!$options['search'] || !$canuser) {
            return $contents;
        }

        $slug = self::getSlug();

        //Get Search status
        $id = self::getId();
        $val = TeaThemeOptions::getConfigs($id);
        $toggle = !empty($val) && isset($val['toggle']) && $val['toggle'] ? true : false;

        include(self::getTemplate());

        $contents[] = array(
            'titles' => $titles,
            'details' => $details,
        );
        unset($titles, $details);

        return $contents;
    }

    /**
     * Hook special filter
     *
     * @return array $enabled
     *
     * @since 3.2.4
     */
    public function hookTemplateSpecial($enabled, $identifier) {
        $enabled[] = $identifier.self::getSlug();
        return $enabled;
    }

    /**
     * Actions to execute after updating or using the Search Engine.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 3.0.0
     */
    public function makeActions($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Initialize actions
        $this->action->initialize($request, $this->search);
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
