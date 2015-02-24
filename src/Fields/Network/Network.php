<?php

namespace Takeatea\TeaThemeOptions\Fields\Network;

use Takeatea\TeaThemeOptions\TeaFields;
use Takeatea\TeaThemeOptions\TeaNetworks;

/**
 * TEA NETWORK FIELD
 * You do not have to use it
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Fields Network
 *
 * To get its own Fields
 *
 * @since 2.0.0
 *
 */
class Network extends TeaFields
{
    /**
     * @var string
     */
    protected $currentpage;

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     * @param array $post Contains all post data
     * @param string $prefix
     *
     * @since 2.0.0
     */
    public function templatePages($content, $post = array(), $prefix = '')
    {
        //Default variables
        $title = isset($content['title']) ? $content['title'] : __('Tea Network', TTO_I18N);

        //Get header template
        include(TTO_PATH.'/Fields/Network/in_pages_head.tpl.php');

        //Get template
        include(TTO_PATH.'/Fields/Network/in_pages.tpl.php');

        //Get footer template
        include(TTO_PATH.'/Fields/Network/in_pages_foot.tpl.php');
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

        //Check if the network has already been included
        if (!isset($this->includes['network_' . $net])) {
            $this->includes['network_' . $net] = true;
        }

        $field = $this->generateNetworkObject($net);

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

        //Iterate on them
        foreach ($callbacks as $token => $net) {
            if (isset($request[$token])) {
                //Check if the network has already been included
                if (!isset($this->includes['network_' . $net])) {
                    $this->includes['network_' . $net] = true;
                }

                $field = $this->generateNetworkObject($net);
//                $field->getCallback($request);

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

        foreach($networks as $net => $work) {
            //Check if the network has already been included
            if (!isset($this->includes['network_' . $net])) {
                $this->includes['network_' . $net] = true;
            }

//            $field = $this->generateNetworkObject($net);
//            $field->getUpdate();
        }
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

    /**
     * @param string $networkName
     *
     * @return TeaNetworks
     */
    protected function generateNetworkObject($networkName)
    {
        $class = ucfirst($networkName);
        $class = "\Takeatea\TeaThemeOptions\Networks\\$class\\$class";

        return new $class();
    }
}
