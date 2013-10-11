<?php
/**
 * Tea Theme Options Twitter network
 * 
 * @package TakeaTea
 * @subpackage Tea Networks Twitter
 * @since Tea Theme Options 1.3.0
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//---------------------------------------------------------------------------------------------------------//

//Require master Class
require_once(TTO_PATH . 'classes/class-tea-networks.php');
//Define statics
define('TTO_TWITTER', 'http://takeatea.com/twitter.php');

//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Networks Twitter
 *
 * To get its own Network
 *
 * @since Tea Theme Options 1.3.0
 *
 */
class Tea_Networks_Twitter extends Tea_Networks
{
    //Define protected vars


    //--------------------------------------------------------------------------//

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     *
     * @since Tea Theme Options 1.3.0
     */
    public function templatePages()
    {
        //Default variables
        $icon = TTO_URI . 'classes/fields/network/networks/twitter/icon.png';
        $page = $this->getCurrentPage();
        $display_form = false;

        //Check if we display form or user informations
        $token = _get_option('tea_twitter_access_token', '');

        if (false === $token || empty($token))
        {
            //Default vars
            $display_form = true;
        }
        else
        {
            //Get user Instagram info from DB
            $user_info = _get_option('tea_twitter_user_info', array());
            $user_info = false === $user_info ? array() : $user_info;

            //Get recent photos from DB
            $user_recent = _get_option('tea_twitter_user_recent', array());
            $user_recent = false === $user_recent ? array() : $user_recent;

            //Display date of update
            $update = _get_option('tea_twitter_connection_update', '');
            $update = false === $update || empty($update) ? '' : $update;
        }

        //Get template
        include('in_pages.tpl.php');
    }


    //-------------------------------------//

    /**
     * Build data from the asked network.
     *
     * @uses add_query_arg()
     * @uses admin_url()
     * @uses header()
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since Tea Theme Options 1.3.0
     */
    public function getCallback($request)
    {
        //Check if a network connection is asked
        if (!isset($request['tea_to_callback']))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition. You need to specify a callback network to update the informations.', TTO_I18N);
            return false;
        }

        //Default vars
        $page = $this->getCurrentPage();

        //Update DB with the token
        $token = array(
            'oauth_token' => $request['twitter_token'],
            'oauth_token_secret' => $request['twitter_secret']
        );
        _set_option('tea_twitter_access_token', $token);

        //Get all data
        $request['tea_to_network'] = 'twitter';
        $this->getUpdate($request);

        //Build callback
        $return = add_query_arg(array('page' => $page), admin_url('/admin.php'));

        //Redirect
        header('Location: ' . $return, false, 307);
        exit;
    }

    /**
     * Build connection to the asked network.
     *
     * @uses add_query_arg()
     * @uses admin_url()
     * @uses header()
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since Tea Theme Options 1.3.0
     */
    public function getConnection($request)
    {
        //Default vars
        $page = $this->getCurrentPage();

        //Build callback
        $return = add_query_arg(array('page' => $page), admin_url('/admin.php'));
        $uri = add_query_arg('return_uri', urlencode($return), TTO_TWITTER);

        //Redirect to network
        header('Location: ' . $uri, false, 307);
        exit;
    }

    /**
     * Build disconnection to the asked network.
     *
     * @uses add_query_arg()
     * @uses admin_url()
     * @uses header()
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since Tea Theme Options 1.3.0
     */
    public function getDisconnection($request)
    {
        //Default vars
        $page = $this->getCurrentPage();

        //Delete all data from DB
        _del_option('tea_twitter_access_token');
        _del_option('tea_twitter_user_info');
        _del_option('tea_twitter_user_recent');
        _del_option('tea_twitter_connection_update');

        //Build callback
        $return = add_query_arg(array('page' => $page), admin_url('/admin.php'));
        $uri = add_query_arg(array('return_uri' => urlencode($return), 'logout' => 'true'), TTO_TWITTER);

        //Redirect to network
        header('Location: ' . $uri, false, 307);
        exit;
    }

    /**
     * Build data from the asked network.
     *
     * @uses date_i18n()
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since Tea Theme Options 1.3.0
     */
    public function getUpdate($request = array())
    {
        //Define date of update
        $timer = date_i18n(_get_option('date_format') . ', ' . _get_option('time_format'));

        //Get includes
        $includes = $this->getIncludes();
        $request = !empty($request) ? $request : (isset($_REQUEST) ? $_REQUEST : array());

        //Define date of update
        _set_option('tea_twitter_connection_update', $timer);

        //Check if Twitter has already been included
        if (!isset($includes['twitter']))
        {
            $this->setIncludes('twitter');
            require_once(TTO_PATH . 'classes/fields/network/networks/twitter/twitteroauth/twitteroauth.php');
        }

        //Get Twitter configurations
        $conf = array(
            'consumer_key'      => 'T6K5yb4oGrS5UTZxsvDdhw',
            'consumer_secret'   => 'gpamCLVGgNZGN3jprq40A4JD5KzQ2PLqFIu5lUQyw'
        );

        //Get token from DB
        $token = _get_option('tea_twitter_access_token', '');

        //Build TwitterOAuth object
        $api = new TwitterOAuth($conf['consumer_key'], $conf['consumer_secret'], $token['oauth_token'], $token['oauth_token_secret']);

        //Get user info
        $user_info = $api->get('account/verify_credentials');
        _set_option('tea_twitter_user_info', $user_info);

        //Get recent tweets
        $user_recent = $api->get('statuses/user_timeline');
        _set_option('tea_twitter_user_recent', $user_recent);
    }
}
