<?php

namespace Takeatea\TeaThemeOptions\Fields\Multiselect;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA MULTISELECT FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'multiselect',
 *     'title' => 'Select the Minions that you may know',
 *     'id' => 'my_multiselect_field_id',
 *     'std' => '',
 *     'description' => 'Pay attention to this question ;)',
 *     'options' => array(
 *         'henry' => 'Henry',
 *         'jacques' => 'Jacques',
 *         'kevin' => 'Kevin',
 *         'tom' => 'Tom'
 *     )
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Multiselect
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Multiselect
 * @since 1.5.2.14
 *
 */
class Multiselect extends TeaFields
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
        if (empty($post)) {
            //Check if an id is defined at least
            $this->checkId($content);
        }
        else {
            //Modify content
            $content = $content['args']['contents'];
        }

        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Multiselect', TTO_I18N);
        $description = isset($content['description']) ? $content['description'] : '';
        $std = isset($content['std']) ? $content['std'] : array();
        $options = isset($content['options']) ? $content['options'] : array();

        //Default way
        if (empty($post)) {
            //Check selected
            $vals = TeaThemeOptions::get_option($prefix.$id, $std);
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }
        //On CPT
        else {
            //Check selected
            $vals = get_post_meta($post->ID, $post->post_type . '-' . $id, false);
            $vals = empty($vals) ? $std : (is_array($vals) ? $vals[0] : array($vals));
        }

        //Get template
        include(TTO_PATH.'/Fields/Multiselect/in_pages.tpl.php');
    }
}
