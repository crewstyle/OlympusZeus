<?php

namespace crewstyle\TeaThemeOptions\Plugins\Search\Action;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Plugins\Search\Search;

/**
 * TTO SEARCH HOOK
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Search Action
 *
 * Class used to work with Search Action.
 *
 * @package Tea Theme Options
 * @subpackage Search\Action\Action
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Action
{
    /**
     * @var Search
     */
    protected $search = null;

    /**
     * Constructor.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     * @param Search $search Contains search engine
     *
     * @since 3.3.0
     */
    public function __construct($request, $search)
    {
        $this->initialize($request, $search);
    }

    /**
     * Initialize actions.
     *
     * @since 3.3.0
     */
    public function initialize($request, $searchengine)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Update search
        $this->search = $searchengine;

        //Get the kind of action asked
        $section = isset($request['section']) ? (string) $request['section'] : '';

        //Check action to make
        if ('search' !== $section) {
            return;
        }

        //Get action to make
        $make = isset($request['make']) ? $request['make'] : '';

        //Create index
        if ('create' == $make) {
            $this->makeCreate($request);
        }
        //Index datas
        else if ('index' == $make) {
            $this->makeIndex();
        }

        //Redirect to Tea TO search configuration page
        wp_safe_redirect(admin_url('admin.php?page='.$request['page'].'&section=search'));
    }

    /**
     * Create the index for all datas.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 3.0.0
     */
    public function makeCreate($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get index
        $index = Search::getIndex();

        //Get enable
        $enable = TeaThemeOptions::getConfigs($index, false);

        //Check if this action was properly called
        if (!$enable) {
            return;
        }

        //Get contents
        $status = TeaThemeOptions::getConfigs($index.'-status', 0);

        //Check status
        if (200 === $status) {
            //We do not need to create the Index
            return;
        }

        //Create new occurrence
        $this->search->getEngine()->makeSearch();

        //Get Connection status
        $status = $this->search->getEngine()->connection();

        //Define data in DB
        TeaThemeOptions::setConfigs($index.'-status', $status);
    }

    /**
     * Index all search datas.
     *
     * @since 3.0.0
     */
    public function makeIndex()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Index contents
        $count = $this->search->getEngine()->indexContents();

        //Update counter
        $index = Search::getIndex();
        TeaThemeOptions::setConfigs($index.'-count', $count);
    }
}
