<?php

namespace crewstyle\TeaThemeOptions\Core\Hook;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Hook\BackendHook\BackendHook;
use crewstyle\TeaThemeOptions\Core\Hook\FrontendHook\FrontendHook;
use crewstyle\TeaThemeOptions\Core\Hook\CaptainHook\CaptainHook;

/**
 * TTO HOOK
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Hook
 *
 * To get its own hooks.
 *
 * @package Tea Theme Options
 * @subpackage Core\Hook\Hook
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Hook
{
    /**
     * Constructor.
     *
     * @since 3.3.0
     */
    public function __construct()
    {
        //Admin panel
        if (TTO_IS_ADMIN) {
            //Build backend hooks
            $hooks = new BackendHook();
            $hooks->makeHooks();
        }
        else {
            //Build frontend hooks
            $hooks = new FrontendHook();
            $hooks->makeHooks();
        }

        //Build common hooks
        $this->commonHooks();
    }

    /**
     * Build admin hooks.
     *
     * @since 3.3.0
     */
    public function commonHooks()
    {
        //Get configs
        $modules = TeaThemeOptions::getConfigs('modules');

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
     * @since 3.3.0
     */
    public function hookLoginPage()
    {
        echo '<link href="'.TTO_URI.'/assets/css/teato.login.css?ver=v'.TTO_VERSION_NUM
            .'" rel="stylesheet" type="text/css" />';
    }
}
