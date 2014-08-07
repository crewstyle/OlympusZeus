<?php
namespace Takeatea\TeaThemeOptions\Fields\Section;

use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA SECTION FIELD
 * You do not have to use it
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Section
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Section
 * @since 1.4.0
 *
 */
class Section extends TeaFields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since 1.4.0
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
        $color = isset($content['color']) ? $content['color'] : 'white';
        $image = isset($content['image']) ? $content['image'] : '';
        $position = isset($content['position']) ? $content['position'] : 'left';
        $content = isset($content['content']) ? $content['content'] : '';

        //Get template
        include(TTO_PATH . '/Fields/Section/in_pages.tpl.php');
    }
}
