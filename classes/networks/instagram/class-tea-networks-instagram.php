<?php
/**
 * Tea Theme Options Instagram network
 * 
 * @package TakeaTea
 * @subpackage Tea Networks Instagram
 * @since 1.4.0
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

//Require master Class
require_once(TTO_PATH . '/classes/networks/class-tea-networks.php');

//----------------------------------------------------------------------------//

/**
 * Tea Networks Instagram
 *
 * To get its own Network
 *
 * @since 1.4.0
 *
 */
class Tea_Networks_Instagram extends Tea_Networks
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
        $keys = $this->getTokens('instagram');

        //Get template
        include(TTO_PATH . '/classes/networks/instagram/in_pages.tpl.php');
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
