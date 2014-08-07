<?php
namespace Takeatea\TeaThemeOptions\Networks\Facebook;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaNetworks;

/**
 * TEA FACEBOOK NETWORK
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Networks Facebook
 *
 * To get its own Network
 *
 * @since 1.4.0
 *
 */
class Facebook extends TeaNetworks
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
        $keys = $this->getTokens('facebook');

        //Check if we display form or user informations
        /*$token = TeaThemeOptions::get_option('tea_twitter_access_token', '');

        if (false === $token || empty($token)) {
            //Default vars
            $display_form = true;
        }
        else {
            //Get user Instagram info from DB
            $user_info = TeaThemeOptions::get_option('tea_twitter_user_info', array());
            $user_info = false === $user_info ? array() : $user_info;

            //Get recent photos from DB
            $user_recent = TeaThemeOptions::get_option('tea_twitter_user_recent', array());
            $user_recent = false === $user_recent ? array() : $user_recent;

            //Display date of update
            $update = TeaThemeOptions::get_option('tea_twitter_connection_update', '');
            $update = false === $update || empty($update) ? '' : $update;
        }*/

        //Get template
        include(TTO_PATH . '/Networks/Facebook/in_pages.tpl.php');
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
