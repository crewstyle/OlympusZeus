<?php

namespace crewstyle\TeaThemeOptions\Menu;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Menu\Engine\Engine as MenuEngine;
//use crewstyle\TeaThemeOptions\Network\Network as NetworkEngine;
use crewstyle\TeaThemeOptions\PostType\PostType as PostTypeEngine;
use crewstyle\TeaThemeOptions\Search\Search as SearchEngine;
use crewstyle\TeaThemeOptions\Term\Term as TermEngine;

/**
 * TTO MENU
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Menu
 *
 * To get its own menu.
 *
 * @package Tea Theme Options
 * @subpackage Menu\Menu
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Menu
{
    /**
     * @var MenuEngine
     */
    protected $menu = null;

    /**
     * @var PostTypeEngine
     */
    protected $posttype = null;

    /**
     * @var SearchEngine
     */
    protected $search = null;

    /**
     * @var TermEngine
     */
    protected $term = null;

    /**
     * Constructor.
     *
     * @param string $identifier Define the main slug
     * @param array $options Define if we can display special pages
     * @todo Networks!
     *
     * @since 3.0.0
     */
    public function __construct($identifier, $options)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Build options
        $opts = array_merge(array(
            'networks' => true,
            'posttypes' => true,
            'search' => true,
            'terms' => true,
            'notifications' => true,
        ), $options);

        //Instanciate Network
        //$this->network = new Network($opts['networks']);

        //Instanciate PostType
        $this->posttype = new PostTypeEngine($opts['posttypes']);

        //Instanciate Search
        $this->search = new SearchEngine($opts['search']);

        //Instanciate Term
        $this->term = new TermEngine($opts['terms']);

        //Instanciate Menu engine
        $this->menu = new MenuEngine($identifier, $opts);
    }

    /**
     * Return Menu engine.
     *
     * @return Menu $menu
     *
     * @since 3.0.0
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Return all pages.
     *
     * @return array $pages
     *
     * @since 3.0.0
     */
    public function getPages()
    {
        return $this->getMenu()->getPages();
    }

    /**
     * Return Post Type engine.
     *
     * @return PostType $posttype
     *
     * @since 3.0.0
     */
    public function getPosttype()
    {
        return $this->posttype;
    }

    /**
     * Return Term engine.
     *
     * @return Term $term
     *
     * @since 3.0.0
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Return Search engine.
     *
     * @return Search $search
     *
     * @since 3.0.0
     */
    public function getSearch()
    {
        return $this->search;
    }
}
