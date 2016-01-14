<?php

namespace crewstyle\OlympusZeus\Plugins;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Plugins\Action\Action;
use crewstyle\OlympusZeus\Plugins\Search\Search;

/**
 * Gets all plugins methods.
 *
 * @package Olympus Zeus
 * @subpackage Plugins\Plugins
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class Plugins
{
    /**
     * @var Network
     */
    protected $network = null;

    /**
     * @var Search
     */
    protected $search = null;

    /**
     * Constructor.
     *
     * @param string $identifier Define the main slug
     * @todo Networks!
     *
     * @since 3.3.0
     */
    public function __construct($identifier)
    {
        //Instanciate Action
        $this->action = new Action($identifier);

        //Instanciate Network
        //$this->network = new NetworkPlugin();
        $this->network = null;

        //Instanciate Search
        $this->search = new Search();
    }

    /**
     * Return Network plugin.
     *
     * @return Network $network
     *
     * @since 3.3.0
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * Return Search plugin.
     *
     * @return Search $search
     *
     * @since 3.3.0
     */
    public function getSearch()
    {
        return $this->search;
    }
}
