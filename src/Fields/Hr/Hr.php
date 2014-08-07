<?php
namespace Takeatea\TeaThemeOptions\Fields\Hr;

use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA HR FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'hr'
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Hr
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Hr
 * @since 1.4.0
 *
 */
class Hr extends TeaFields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since 1.3.0
     */
    public function __construct(){}


    //------------------------------------------------------------------------//

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
    public function templatePages($content, $post = array(), $prefix = '')
    {
        //Get template
        include(TTO_PATH . '/Fields/Hr/in_pages.tpl.php');
    }
}
