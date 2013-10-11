<?php
/**
 * Tea Theme Options Flickr network
 * 
 * @package TakeaTea
 * @subpackage Tea Networks Flickr
 * @since Tea Theme Options 1.3.0
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//---------------------------------------------------------------------------------------------------------//

//Require master Class
require_once(TTO_PATH . 'classes/class-tea-networks.php');

//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Networks Flickr
 *
 * To get its own Network
 *
 * @since Tea Theme Options 1.3.0
 *
 */
class Tea_Networks_Flickr extends Tea_Networks
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
        $icon = TTO_URI . 'classes/fields/network/networks/flickr/icon.png';
        $page = $this->getCurrentPage();
        $display_form = false;

        //Check if we display form or user informations
        $user_info = _get_option('tea_flickr_user_info', array());

        if (false === $user_info || empty($user_info))
        {
            //Default vars
            $display_form = true;
        }
        else
        {
            //Get user Flickr info from DB
            $user_details = _get_option('tea_flickr_user_details', array());
            $user_details = false === $user_details ? array() : $user_details;

            //Get recent photos from DB
            $user_recent = _get_option('tea_flickr_user_recent', array());
            $user_recent = false === $user_recent ? array() : $user_recent;

            //Display date of update
            $update = _get_option('tea_flickr_connection_update', '');
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

        //Update data
        $request['tea_flickr_install'] = true;
        $this->getUpdate($request);
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
        _del_option('tea_flickr_user_info');
        _del_option('tea_flickr_user_details');
        _del_option('tea_flickr_user_recent');
        _del_option('tea_flickr_connection_update');
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

        //Check if a username is defined
        if (isset($request['tea_flickr_install']) && (!isset($request['tea_flickr_username']) || empty($request['tea_flickr_username'])))
        {
            $this->adminmessage = __('Something went wrong in your parameters definition. You need to specify a username to get connected.', TTO_I18N);
            return false;
        }

        //Define date of update
        _set_option('tea_flickr_connection_update', $timer);

        //Check if Flickr has already been included
        if (!isset($includes['flickr']))
        {
            $this->setIncludes('flickr');
            require_once(TTO_PATH . 'classes/fields/network/networks/flickr/phpflickr/phpFlickr.php');
        }

        //Get Flickr configurations
        $defaults = array(
            'api_key'       => '202431176865b4c5f725087d26bd78af',
            'api_secret'    => '2efaf89685c295ea'
        );

        //Get Flickr instance with token
        $api = new phpFlickr($defaults['api_key']);

        //Install a new user
        if (isset($request['tea_flickr_install']))
        {
            //Get Flickr instance with token
            $user_info = $api->people_findByUsername($request['tea_flickr_username']);

            //Check if the API returns value
            if (false === $user_info || empty($user_info))
            {
                $this->adminmessage = __('Something went wrong in your parameters definition. The username specified is unknown.', TTO_I18N);
                return false;
            }

            //Update DB with the user info
            _set_option('tea_flickr_user_info', $user_info);
        }

        //Get user info
        $user_info = isset($user_info) ? $user_info : _get_option('tea_flickr_user_info', array());

        //Update DB with the user details
        $user_details = $api->people_getInfo($user_info['id']);
        _set_option('tea_flickr_user_details', $user_details);

        //Update DB with the user info
        $user_recent = $api->people_getPublicPhotos($user_info['id'], null, null, 20, 1);
        $recents = array();

        //Iterate
        foreach ($user_recent['photos']['photo'] as $item)
        {
            $recents[] = array(
                'link' => 'http://www.flickr.com/photos/' . $item['owner'] . '/' . $item['id'],
                'url' => $api->buildPhotoURL($item, 'medium_640'),
                'url_small' => $api->buildPhotoURL($item, 'square'),
                'title' => $item['title']
            );
        }

        //Update
        _set_option('tea_flickr_user_recent', $recents);
    }
}
