<?php
/**
 * Tea Theme Options Instagram network
 * 
 * @package TakeaTea
 * @subpackage Tea Networks Instagram
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
define('TTO_INSTAGRAM', 'http://takeatea.com/instagram.php');

//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Networks Instagram
 *
 * To get its own Network
 *
 * @since Tea Theme Options 1.3.0
 *
 */
class Tea_Networks_Instagram extends Tea_Networks
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
        $icon = TTO_URI . 'classes/fields/network/networks/instagram/icon.png';
        $page = $this->getCurrentPage();
        $display_form = false;

        //Check if we display form or user informations
        $token = _get_option('tea_instagram_access_token', '');

        if (false === $token || empty($token))
        {
            //Default vars
            $display_form = true;
        }
        else
        {
            //Get user Instagram info from DB
            $user_info = _get_option('tea_instagram_user_info', array());
            $user_info = false === $user_info ? array() : $user_info;

            //Get recent photos from DB
            $user_recent = _get_option('tea_instagram_user_recent', array());
            $user_recent = false === $user_recent ? array() : $user_recent;

            //Display date of update
            $update = _get_option('tea_instagram_connection_update', '');
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
        $token = $request['instagram_token'];
        _set_option('tea_instagram_access_token', $token);

        //Get all data
        $request['tea_to_network'] = 'instagram';
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
        $uri = add_query_arg('return_uri', urlencode($return), TTO_INSTAGRAM);

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
        _del_option('tea_instagram_access_token');
        _del_option('tea_instagram_user_info');
        _del_option('tea_instagram_user_recent');
        _del_option('tea_instagram_connection_update');

        //Build callback
        $return = add_query_arg(array('page' => $page), admin_url('/admin.php'));
        $uri = add_query_arg(array('return_uri' => urlencode($return), 'logout' => 'true'), TTO_INSTAGRAM);

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
        _set_option('tea_instagram_connection_update', $timer);

        //Check if Google Font has already been included
        if (!isset($includes['instagram']))
        {
            $this->setIncludes('instagram');
            require_once(TTO_PATH . 'classes/fields/network/networks/instagram/instaphp/instaphp.php');
        }

        //Get token from DB
        $token = _get_option('tea_instagram_access_token', '');

        //Get user info
        $api = Instaphp\Instaphp::Instance($token);
        $user_info = $api->Users->Info();
        $user_recent = $api->Users->Recent('self');

        //Uodate DB with the user info
        _set_option('tea_instagram_user_info', $user_info->data);

        //Update DB with the user info
        $recents = array();

        //Iterate
        foreach ($user_recent->data as $item)
        {
            $recents[] = array(
                'link' => $item->link,
                'url' => $item->images->thumbnail->url,
                'title' => empty($item->caption->text) ? __('Untitled', TTO_I18N) : $item->caption->text,
                'width' => $item->images->thumbnail->width,
                'height' => $item->images->thumbnail->height,
                'likes' => $item->likes->count,
                'comments' => $item->comments->count
            );
        }

        //Update
        _set_option('tea_instagram_user_recent', $recents);
    }
}
