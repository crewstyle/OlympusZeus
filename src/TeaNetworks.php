<?php
namespace Takeatea\TeaThemeOptions;

use Takeatea\TeaThemeOptions\TeaThemeOptions;

/**
 * TEA NETWORKS
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * Tea Networks
 *
 * To get its own Fields
 *
 * @since 1.4.0
 *
 */
abstract class TeaNetworks
{
    //Define protected/private vars
    private $includes = array();
    protected $adminmessage = '';

    /**
     * Constructor.
     *
     * @since 1.3.0
     */
    public function __construct()
    {
        //Adds an admin notice
        add_action('admin_notices', array(&$this, '__showAdminMessage'));
    }


    //------------------------------------------------------------------------//

    /**
     * FUNCTIONS
     **/

    /**
     * MAIN FUNCTIONS
     **/

    abstract protected function templatePages();
    abstract public function getCallback($request);
    abstract public function getConnection($request);
    abstract public function getDisconnection($request);
    abstract public function getUpdate();

    /**
     * Get includes.
     *
     * @param string $network Contains the network name to check
     * @return boolean $tks Answear to the question.
     *
     * @since 1.4.0
     */
    protected function getTokens($network)
    {
        $tks = TeaThemeOptions::getConfigs('networks');

        if (empty($tks)) {
            return false;
        }

        return array_key_exists($network, $tks);
    }

    /**
     * ACCESSORS
     **/

    /**
     * Get includes.
     *
     * @return array $includes Array of all included files
     *
     * @since 1.3.0
     */
    protected function getIncludes()
    {
        //Return value
        return $this->includes;
    }

    /**
     * Set includes.
     *
     * @param string $context Name of the included file's context
     *
     * @since 1.4.0
     */
    protected function setIncludes($context)
    {
        $this->includes[$context] = true;
    }

    /**
     * Display a warning message on the admin panel.
     *
     * @since 1.4.0
     */
    public function __showAdminMessage()
    {
        //Get admin message
        $content = $this->adminmessage;

        if (!empty($content)) {
            //Get template
            include(TTO_PATH . '/Tpl/layouts/__layout_admin_message.tpl.php');
        }
    }
}
