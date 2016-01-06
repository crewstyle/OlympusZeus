<?php

namespace crewstyle\TeaThemeOptions\Plugins\Action;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
//use crewstyle\TeaThemeOptions\Plugins\Network\Network;
use crewstyle\TeaThemeOptions\Plugins\Search\Search;

/**
 * TTO PLUGINS ACTION
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Plugins Action
 *
 * Class used to make actions from GET param.
 *
 * @package Tea Theme Options
 * @subpackage Plugins\Action\Action
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
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
     * @since 3.3.0
     */
    public function __construct($identifier)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
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
     * @since 3.3.0
     */
    public function initialize()
    {
        //Get current user action
        $action = isset($_REQUEST['do']) ? (string) $_REQUEST['do'] : '';

        if ('tto-action' !== $action) {
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
     * @since 3.3.0
     */
    protected function updateNetwork($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        /*//Make actions
        $network = new Network();
        $network->makeActions($request);

        *//*//Get action
        $for = isset($request['for']) ? $request['for'] : '';

        //Check if a network connection is asked
        if ('callback' != $for && 'network' != $for) {
            TeaThemeOptions::notify('error',
                TeaThemeOptions::__('Something went wrong in your parameters
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
     * @since 3.3.0
     */
    protected function updateSearch($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Make actions
        $search = new Search(false);
    }
}
