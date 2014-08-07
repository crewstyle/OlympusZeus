<?php
namespace Takeatea\TeaThemeOptions\Fields\Includes;

use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA INCLUDES FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'includes',
 *     'title' => 'No more jokes...',
 *     'file' => 'my_php_file_path.php'
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Includes
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Includes
 * @since 1.4.0
 *
 */
class Includes extends TeaFields
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Include', TTO_I18N);
        $file = isset($content['file']) ? $content['file'] : false;

        //Get template
        include(TTO_PATH . '/Fields/Includes/in_pages.tpl.php');
    }
}
