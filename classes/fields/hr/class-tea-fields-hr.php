<?php
/**
 * Tea Theme Options Hr field
 * 
 * @package TakeaTea
 * @subpackage Tea Fields Hr
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
 * Tea Fields Hr
 *
 * To get its own Fields
 *
 * @since Tea Theme Options 1.3.0
 *
 */
class Tea_Fields_Hr extends Tea_Fields
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
        //Get template
        include('in_pages.tpl.php');
    }
}