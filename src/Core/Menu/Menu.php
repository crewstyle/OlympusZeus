<?php

namespace crewstyle\OlympusZeus\Core\Menu;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Menu\MenuEngine;
use crewstyle\OlympusZeus\Core\Posttype\Posttype;
use crewstyle\OlympusZeus\Core\Term\Term;

/**
 * Gets its own menu.
 *
 * @package Olympus Zeus
 * @subpackage Core\Menu\Menu
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class Menu
{
    /**
     * @var MenuEngine
     */
    protected $menuEngine = null;

    /**
     * @var Posttype
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
     * @since 4.0.0
     */
    public function __construct($identifier)
    {
        //Instanciate PostType
        $this->posttype = new Posttype();

        //Instanciate Term
        $this->term = new Term();

        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Instanciate Menu engine
        $this->menuEngine = new MenuEngine($identifier);
    }

    /**
     * Return Menu engine.
     *
     * @return MenuEngine $menuEngine
     *
     * @since 4.0.0
     */
    public function getMenuEngine()
    {
        return $this->menuEngine;
    }

    /**
     * Return all pages.
     *
     * @return array $pages
     *
     * @since 4.0.0
     */
    public function getPages()
    {
        return $this->getMenuEngine()->getPages();
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
