<?php
/**
 * Tea Theme Options List field
 * 
 * @package TakeaTea
 * @subpackage Tea Fields List
 * @since Tea Theme Options 1.3.0
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//---------------------------------------------------------------------------------------------------------//

//Require master Class
require_once(TTO_PATH . 'classes/class-tea-fields.php');

//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Fields List
 *
 * To get its own Fields
 *
 * @since Tea Theme Options 1.3.0
 *
 */
class Tea_Fields_List extends Tea_Fields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since Tea Theme Options 1.3.0
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
     *
     * @since Tea Theme Options 1.3.0
     */
    public function templatePages($content, $post = array())
    {
        //Default variables
        $contents = isset($content['contents']) ? $content['contents'] : array();

        //Get template
        include('in_pages.tpl.php');
    }
}
