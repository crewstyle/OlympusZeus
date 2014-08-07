<?php
namespace Takeatea\TeaThemeOptions\Fields\Hidden;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA HIDDEN FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'hidden',
 *     'id' => 'my_hidden_field_id',
 *     'std' => 'Haha I will dominate the World!!! MOUAHAHAHAHAHA - Crazy Penguin'
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Hidden
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Hidden
 * @since 1.4.0
 *
 */
class Hidden extends TeaFields
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
        //Check if an id is defined at least
        $this->checkId($content);

        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : '';

        //Check selected
        $val = TeaThemeOptions::get_option($prefix.$id, $title);

        //Get template
        include(TTO_PATH . '/Fields/Hidden/in_pages.tpl.php');
    }
}
