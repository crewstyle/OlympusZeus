<?php
namespace Takeatea\TeaThemeOptions\Networks\Twitter;

use Takeatea\TeaThemeOptions\TeaNetworks;

/**
 * TEA TWITTER NETWORK
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Networks Twitter
 *
 * To get its own Network
 *
 * @since 1.4.0
 *
 */
class Twitter extends TeaNetworks
{
    //Define protected vars


    //------------------------------------------------------------------------//

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @since 1.4.0
     */
    public function templatePages()
    {
        //Default variables
        $keys = $this->getTokens('twitter');

        //Get template
        include(TTO_PATH . '/Networks/Twitter/in_pages.tpl.php');
    }


    //------------------------------------------------------------------------//

    /**
     * Build data from the asked network.
     *
     * @uses add_query_arg()
     * @uses admin_url()
     * @uses header()
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 1.4.0
     */
    public function getCallback($request)
    {
    }

    /**
     * Build connection to the asked network.
     *
     * @uses add_query_arg()
     * @uses admin_url()
     * @uses header()
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 1.4.0
     */
    public function getConnection($request)
    {
    }

    /**
     * Build disconnection to the asked network.
     *
     * @uses add_query_arg()
     * @uses admin_url()
     * @uses header()
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 1.4.0
     */
    public function getDisconnection($request)
    {
    }

    /**
     * Build data from the asked network.
     *
     * @uses date_i18n()
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 1.4.0
     */
    public function getUpdate($request = array())
    {
    }
}
