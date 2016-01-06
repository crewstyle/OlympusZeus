<?php

namespace crewstyle\TeaThemeOptions\Core\Menu;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Menu\Engine\Engine;
use crewstyle\TeaThemeOptions\Core\PostType\PostType;
use crewstyle\TeaThemeOptions\Core\Term\Term;

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
 * @subpackage Core\Menu\Menu
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.2.0
 *
 */
class Menu
{
    /**
     * @var Engine
     */
    protected $menu = null;

    /**
     * @var PostType
     */
    protected $posttype = null;

    /**
     * @var Term
     */
    protected $term = null;

    /**
     * Constructor.
     *
     * @param string $identifier Define the main slug
     *
     * @since 3.3.0
     */
    public function __construct($identifier)
    {
        //Instanciate PostType
        $this->posttype = new PostType();

        //Instanciate Term
        $this->term = new Term();

        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Instanciate Menu engine
        $this->menu = new Engine($identifier);
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
}
