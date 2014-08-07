<?php
namespace Takeatea\TeaThemeOptions\Fields\P;

use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA P FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'p',
 *     'content' => 'Hello and welcome to the "Tea Test Academy"'
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields P
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields P
 * @since 1.4.0
 *
 */
class P extends TeaFields
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
        //Default variables
        $content = isset($content['content']) ? $content['content'] : '';

        //Get template
        include(TTO_PATH . '/Fields/P/in_pages.tpl.php');
    }
}
