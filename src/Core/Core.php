<?php

namespace crewstyle\TeaThemeOptions\Core;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Action\Action;
use crewstyle\TeaThemeOptions\Core\Hook\Hook;
use crewstyle\TeaThemeOptions\Core\Menu\Menu;
use crewstyle\TeaThemeOptions\Plugins\Plugins;

/**
 * TTO CORE
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Core
 *
 * To get all core methods.
 *
 * @package Tea Theme Options
 * @subpackage Core\Core
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Core
{
    /**
     * @var Action
     */
    protected $action = null;

    /**
     * @var Hook
     */
    protected $hook = null;

    /**
     * @var Menu
     */
    protected $menu = null;

    /**
     * @var Plugins
     */
    protected $plugins = null;

    /**
     * Constructor.
     *
     * @todo Networks!
     *
     * @since 3.3.0
     */
    public function __construct()
    {
        //Build identifier
        $identifier = 'tea-theme-options';

        /**
         * Update TTO identifier.
         *
         * @param string $identifier
         * @return string $identifier
         *
         * @since 3.3.0
         */
        $identifier = apply_filters('tto_core_identifier', $identifier);

        //Instanciate Action
        $this->action = new Action($identifier);

        //Instanciate Hook
        $this->hook = new Hook();

        //Instanciate Menu
        $this->menu = new Menu($identifier);

        //Instanciate Plugins
        $this->plugins = new Plugins($identifier);
    }

    /**
     * Get core menu.
     *
     * @return Menu $menu
     *
     * @since 3.3.0
     */
    public function getCoreMenu()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        return $this->menu;
    }

    /**
     * Return Search engine.
     *
     * @return Search $search
     *
     * @since 3.3.0
     */
    public function getSearch()
    {
        return $this->plugins->getSearch();
    }
}
