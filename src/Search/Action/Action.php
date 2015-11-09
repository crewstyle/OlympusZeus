<?php

namespace crewstyle\TeaThemeOptions\Search\Action;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Search\Engine\Engine;
use crewstyle\TeaThemeOptions\Search\Search;

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
     * @since 3.0.0
     */
    public function __construct(){}

    /**
     * Initialize actions.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     * @param Search $search Contains search engine
     *
     * @since 3.0.0
     */
    public function initialize($request, $search)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get current user action
        $action = isset($request['action']) ? (string) $request['action'] : '';

        if ('tea-to-action' != $action) {
            return;
        }

        //Update search
        $this->search = $search;

        //Get the kind of action asked
        $for = isset($request['for']) ? (string) $request['for'] : '';

        //Check action to make
        if ('search' != $for) {
            return;
        }

        //Get action to make
        $make = isset($request['make']) ? $request['make'] : '';

        //Create index
        if ('create' == $make) {
            $this->makeCreate();
        }
        //Index datas
        else if ('index' == $make) {
            $this->makeIndex();
        }
        //Toggle search engine status
        else if ('toggle' == $make) {
            $this->makeToggle($request);
        }
        //Update datas
        else if ('update' == $make) {
            $this->makeUpdate($request);
        }
    }

    /**
     * Create the index for all datas.
     *
     * @since 3.0.0
     */
    public function makeCreate()
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get id
        $id = Search::getId();
        $index = Search::getIndex();

        //Get datas
        $ctn = TeaThemeOptions::getConfigs($id, array());

        //Check if this action was properly called
        if (!isset($ctn['toggle']) || !$ctn['toggle']) {
            return;
        }

        //Check status
        if (!isset($ctn['status']) || 404 != $ctn['status']) {
            //We do not need to create the Index
            return;
        }

        //Create new occurrence
        $this->search->getEngine()->makeSearch();

        //Get Connection status
        $ctn['status'] = $this->search->getEngine()->connection($ctn);

        //Define data in DB
        TeaThemeOptions::setConfigs($id, $ctn);
        TeaThemeOptions::setConfigs($index, 0);
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
        $countname = Search::getCountName();
        TeaThemeOptions::setConfigs($countname, $count);
    }

    /**
     * Toggle search engine status.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 3.0.0
     */
    public function makeToggle($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get id
        $id = Search::getId();
        $index = Search::getIndex();

        //Check if this action was properly called
        if (!isset($request[$id], $request[$id]['toggle'])) {
            return;
        }

        //Get datas
        $ctn = TeaThemeOptions::getConfigs($id, array());

        //Enable or disable Elasticsearch
        $ctn['toggle'] = $request[$id]['toggle'];
        $ctn['status'] = 0;

        //Update datas
        TeaThemeOptions::setConfigs($id, $ctn);
        TeaThemeOptions::setConfigs($index, 0);
    }

    /**
     * Update all search datas.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 3.0.0
     */
    public function makeUpdate($request)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Get id
        $id = Search::getId();
        $index = Search::getIndex();

        //Check if this action was properly called
        if (!isset($request[$id])) {
            return;
        }

        //Get datas
        $ctn = array_merge(
            Search::getDefaults(),
            TeaThemeOptions::getConfigs($id, array())
        );

        $shost = $request[$id]['server_host'] != $ctn['server_host'] ? true : false;
        $sport = $request[$id]['server_port'] != $ctn['server_port'] ? true : false;
        $sindex = $request[$id]['index_name'] != $ctn['index_name'] ? true : false;

        //Check old values
        if ($shost || $sport || $sindex) {
            TeaThemeOptions::setConfigs($index, 0);
            $ctn['status'] = 0;
        }

        //Update all datas
        $new = array_merge($ctn, $request[$id]);

        //Get Connection status
        if (0 == $new['status']) {
            $new['status'] = $this->search->getEngine()->connection($new);
        }

        //Define data in DB
        TeaThemeOptions::setConfigs($id, $new);
    }
}
