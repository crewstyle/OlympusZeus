<?php
/**
 * Tea TO backend functions and definitions
 * 
 * @package TakeaTea
 * @subpackage Tea Networks
 * @since Tea Theme Options 1.3.0
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Networks
 *
 * To get its own Fields
 *
 * @since Tea Theme Options 1.3.0
 *
 */
abstract class Tea_Networks
{
    //Define protected/private vars
    private $currentpage;
    private $includes = array();
    protected $adminmessage = '';

    /**
     * Constructor.
     *
     * @since Tea Theme Options 1.3.0
     */
    public function __construct()
    {
        //Adds an admin notice
        add_action('admin_notices', array(&$this, '__showAdminMessage'));
    }


    //--------------------------------------------------------------------------//

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
     * ACCESSORS
     **/

    /**
     * Retrieve the $currentpage value
     *
     * @return string $currentpage Get the current page
     *
     * @since Tea Theme Options 1.3.0
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
     * @since Tea Theme Options 1.3.0
     */
    public function setCurrentPage($currentpage = '')
    {
        $this->currentpage = $currentpage;
    }

    /**
     * Get includes.
     *
     * @return array $includes Array of all included files
     *
     * @since Tea Theme Options 1.3.0
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
     * @since Tea Theme Options 1.3.0
     */
    protected function setIncludes($context)
    {
        $includes = $this->getIncludes();
        $this->includes[$context] = true;
    }

    /**
     * Display a warning message on the admin panel.
     *
     * @since Tea Theme Options 1.3.0
     */
    public function __showAdminMessage()
    {
        //Get admin message
        $content = $this->adminmessage;

        if (!empty($content))
        {
            //Get template
            include('tpl/layouts/__layout_admin_message.tpl.php');
        }
    }
}
