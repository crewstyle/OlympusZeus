<?php

namespace crewstyle\OlympusZeus\Plugins\Search;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Plugins\Search\Search;

/**
 * Works with Search.
 *
 * @package Olympus Zeus
 * @subpackage Search\SearchAction
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class SearchAction
{
    /**
     * @var Search
     */
    protected $searchEngine = null;

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
        if (!OLZ_ISADMIN) {
            return;
        }

        //Update search
        $this->searchEngine = $searchengine;

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

        //Redirect to Search plugin configuration page
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
        if (!OLZ_ISADMIN) {
            return;
        }

        //Get index
        $index = Search::getIndex();

        //Get enable
        $enable = OlympusZeus::getConfigs($index, false);

        //Check if this action was properly called
        if (!$enable) {
            return;
        }

        //Get contents
        $status = OlympusZeus::getConfigs($index.'-status', 0);

        //Check status
        if (200 === $status) {
            //We do not need to create the Index
            return;
        }

        //Create new occurrence
        $this->searchEngine->makeSearch();

        //Get Connection status
        $status = $this->searchEngine->connection();

        //Define data in DB
        OlympusZeus::setConfigs($index.'-status', $status);
    }

    /**
     * Index all search datas.
     *
     * @since 3.0.0
     */
    public function makeIndex()
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
            return;
        }

        //Index contents
        $count = $this->searchEngine->indexContents();

        //Update counter
        $index = Search::getIndex();
        OlympusZeus::setConfigs($index.'-count', $count);
    }
}
