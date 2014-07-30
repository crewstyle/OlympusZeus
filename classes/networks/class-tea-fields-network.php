<?php
/**
 * Tea Theme Options Network field
 * 
 * @package TakeaTea
 * @subpackage Tea Fields Network
 * @since 1.3.0
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//---------------------------------------------------------------------------------------------------------//

//Require master Class
require_once(TTO_PATH . '/classes/fields/class-tea-fields.php');

//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Fields Network
 *
 * To get its own Fields
 *
 * @since 1.4.0
 *
 */
class Tea_Fields_Network extends Tea_Fields
{
    //Define protected vars
    private $currentpage;

    /**
     * Constructor.
     *
     * @since 1.3.0
     */
    public function __construct(){}


    //--------------------------------------------------------------------------//

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     * @param array $post Contains all post data
     *
     * @since 1.4.0
     */
    public function templatePages($content, $post = array())
    {
        //Default variables
        $title = isset($content['title']) ? $content['title'] : __('Tea Network', TTO_I18N);

        //Get social networks
        require_once(TTO_PATH . '/classes/fields/class-tea-fields.php');
        $socials = Tea_Fields::getDefaults('networks');

        //Check for networks
        if (empty($socials)) {
            $adminmessage = __('Something went wrong with parameters definition:
                it seems there is no network to connect to.', TTO_I18N);
            $this->setAdminMessage($adminmessage);
            return;
        }

        //Get template
        $area = 'head';
        include(TTO_PATH . '/classes/networks/in_pages.tpl.php');

        //Others vars
        $default_networks = $this->getDefaults('networks');
        $includes = $this->getIncludes();
        $page = $this->getCurrentPage();

        //Iterate on networks
        foreach ($socials as $net => $work) {
            //Check current network is valid
            if (!array_key_exists($net, $default_networks)) {
                continue;
            }

            //Check if the network has already been included
            if (!isset($includes['network_' . $net])) {
                $this->setIncludes('network_' . $net);
                require_once(TTO_PATH . '/classes/networks/' . $net . '/class-tea-networks-' . $net . '.php');
            }

            //Display it
            $class = 'Tea_Networks_' . ucfirst(str_replace('-', '', $net));
            $field = new $class();
            $field->templatePages();
        }

        //Get template
        $area = 'footer';
        include(TTO_PATH . '/classes/networks/in_pages.tpl.php');
    }

    /**
     * Build action method.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 1.4.0
     */
    public function actionNetwork($request)
    {
        //Get callback
        if (isset($request['tea_to_callback'])) {
            $this->getCallback($request);
        }
        //Get action
        else if (isset($request['tea_to_network'])) {
            $this->getAction($request);
        }
    }

    /**
     * Build action method.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 1.4.0
     */
    protected function getAction($request)
    {
        //Get network
        $net = $request['tea_to_network'];
        $includes = $this->getIncludes();
        $page = $this->getCurrentPage();

        //Check if the network has already been included
        if (!isset($includes['network_' . $net])) {
            $this->setIncludes('network_' . $net);
            require_once(TTO_PATH . '/classes/networks/' . $net . '/class-tea-fields-networks-' . $net . '.php');
        }

        //Make the magic
        $class = 'Tea_Networks_' . ucfirst($net);
        $field = new $class();
        $field->setCurrentPage($page);

        //Update connection network
        if (isset($request['tea_to_connection'])) {
            $field->getConnection($request);
        }
        //...Or update disconnection network
        else if (isset($request['tea_to_disconnection'])) {
            $field->getDisconnection($request);
        }
        //...Or update data from network
        else if (isset($request['tea_to_update'])) {
            $field->getUpdate($request);
        }
    }

    /**
     * Build callback method.
     *
     * @param array $request Contains all data sent in $_REQUEST method
     *
     * @since 1.4.0
     */
    protected function getCallback($request)
    {
        //Default variables
        $callbacks = $this->getDefaults('networks_callback');
        $includes = $this->getIncludes();
        $page = $this->getCurrentPage();

        //Iterate on them
        foreach ($callbacks as $token => $net) {
            if (isset($request[$token])) {
                //Check if the network has already been included
                if (!isset($includes['network_' . $net])) {
                    $this->setIncludes('network_' . $net);
                    require_once(TTO_PATH . '/classes/networks/' . $net . '/class-tea-fields-networks-' . $net . '.php');
                }

                //Display it
                $class = 'Tea_Networks_' . ucfirst($net);
                $field = new $class();
                $field->setCurrentPage($page);
                $field->getCallback($request);

                //Finish
                break;
            }
        }
    }

    /**
     * Update all networks data.
     *
     * @since 1.3.0
     */
    public function updateNetworks()
    {
        //Default variables
        $networks = $this->getDefaults('networks');
        $includes = $this->getIncludes();

        //Update ALL networks
        foreach($networks as $net => $work) {
            //Check if the network has already been included
            if (!isset($includes['network_' . $net])) {
                $this->setIncludes('network_' . $net);
                require_once(TTO_PATH . '/classes/networks/' . $net . '/class-tea-fields-networks-' . $net . '.php');
            }

            //Display it
            $class = 'Tea_Networks_' . ucfirst($net);
            $field = new $class();
            $field->getUpdate();
        }
    }

    /**
     * ACCESSORS
     **/

    /**
     * Retrieve the $currentpage value
     *
     * @return string $currentpage Get the current page
     *
     * @since 1.3.0
     */
    protected function getCurrentPage()
    {
        //Return value
        return $this->currentpage;
    }

    /**
     * Define the $currentpage value
     *
     * @param string $currentpage Get the current page
     *
     * @since 1.3.0
     */
    public function setCurrentPage($currentpage = '')
    {
        $this->currentpage = $currentpage;
    }
}
