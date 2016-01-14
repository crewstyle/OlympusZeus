<?php

namespace crewstyle\OlympusZeus\Core\Hook;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Hook\HookBackend;
use crewstyle\OlympusZeus\Core\Hook\HookFrontend;

/**
 * Gets its own hooks.
 *
 * @package Olympus Zeus
 * @subpackage Core\Hook\Hook
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class Hook
{
    /**
     * Constructor.
     *
     * @since 4.0.0
     */
    public function __construct()
    {
        //Admin panel
        if (OLZ_ISADMIN) {
            //Build backend hooks
            $hooks = new HookBackend();
            $hooks->makeHooks();
        }
        else {
            //Build frontend hooks
            $hooks = new HookFrontend();
            $hooks->makeHooks();
        }

        //Build common hooks
        $this->commonHooks();
    }

    /**
     * Build admin hooks.
     *
     * @since 4.0.0
     */
    public function commonHooks()
    {
        //Get configs
        $modules = OlympusZeus::getConfigs('modules');

        if (isset($modules['database']) && $modules['database']) {
            add_action('shutdown',      array(&$this, 'hookCloseDbLink'), 99);
        }

        if (isset($modules['customlogin']) && $modules['customlogin']) {
            add_action('login_head',    array(&$this, 'hookLoginPage'));
        }
    }

    /**
     * Close DB connection simply.
     *
     * @uses mysql_close() To close all DB connections.
     *
     * @since 3.3.0
     */
    public function hookCloseDbLink()
    {
        global $wpdb;
        unset($wpdb);
    }

    /**
     * Display CSS form login page.
     *
     * @since 4.0.0
     */
    public function hookLoginPage()
    {
        echo '<link href="'.OLZ_URI.'/assets/css/olz.login.css?ver=v'.OLZ_VERSION_NUM
            .'" rel="stylesheet" type="text/css" />';
    }
}
