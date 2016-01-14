<?php

namespace crewstyle\OlympusZeus\Plugins\Action;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Plugins\Search\Search;

/**
 * Makes actions from GET param.
 *
 * @package Olympus Zeus
 * @subpackage Plugins\Action\Action
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class Action
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * Constructor.
     *
     * @param string $identifier Define the main slug
     *
     * @since 4.0.0
     */
    public function __construct($identifier)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Get identifier and current page
        $this->identifier = $identifier;

        //Make good action
        $this->initialize();
    }

    /**
     * Initialize actions.
     *
     * @since 4.0.0
     */
    public function initialize()
    {
        //Get current user action
        $action = isset($_REQUEST['do']) ? (string) $_REQUEST['do'] : '';

        if ('olz-action' !== $action) {
            return;
        }

        //Get the kind of action asked
        $from = isset($_REQUEST['from']) ? (string) $_REQUEST['from'] : '';

        if ('plugin' !== $from) {
            return;
        }

        //Get the kind of action asked
        $section = isset($_REQUEST['section']) ? (string) $_REQUEST['section'] : '';

        //Update network data...
        if ('network' === $section || 'callback' === $section) {
            $this->updateNetwork($_REQUEST);
        }
        //...Or update search data...
        elseif ('search' === $section) {
            $this->updateSearch($_REQUEST);
        }
    }

    /**
     * Update Networks datum.
     *
     * @param array $request Contains all data in $_REQUEST
     * @todo Networks!
     *
     * @since 4.0.0
     */
    protected function updateNetwork($request)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        /*//Make actions
        $network = new Network();
        $network->makeActions($request);

        *//*//Get action
        $for = isset($request['for']) ? $request['for'] : '';

        //Check if a network connection is asked
        if ('callback' != $for && 'network' != $for) {
            OlympusZeus::notify('error',
                OlympusZeus::__('Something went wrong in your parameters
                definition. You need to specify a network to make the
                connection happens.')
            );
        }

        //Defaults variables
        $page = empty($this->current) ? $this->identifier : $this->current;

        //Make the magic
        //$field = new Network();
        //$field->setCurrentPage($page);
        //$field->actionNetwork($request);*/
    }

    /**
     * Update Search datum.
     *
     * @param array $request Contains all data in $_REQUEST
     *
     * @since 4.0.0
     */
    protected function updateSearch($request)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Make actions
        $search = new Search(false);
    }
}
