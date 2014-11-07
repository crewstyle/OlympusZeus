<?php
namespace Takeatea\TeaThemeOptions\Fields\Heading;

use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA HEADING FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'heading',
 *     'title' => 'Take a tea, simply'
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Heading
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Heading
 * @since 1.4.0
 *
 */
class Heading extends TeaFields
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Heading', TTO_I18N);
        $level = isset($content['level']) ? $content['level'] : 'h2';

        //Get template
        include(TTO_PATH.'/Fields/Heading/in_pages.tpl.php');
    }
}
