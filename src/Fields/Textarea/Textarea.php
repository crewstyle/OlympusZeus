<?php

namespace Takeatea\TeaThemeOptions\Fields\Textarea;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA TEXTAREA FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'textarea',
 *     'title' => 'How do Penguins drink their cola?',
 *     'id' => 'my_textarea_field_id',
 *     'default' => 'On the rocks.',
 *     'placeholder' => 'Tell us how?',
 *     'description' => 'A simple question to know if you will be able to survive to the Penguin domination.'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Textarea
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Textarea
 * @since 2.0.0
 *
 */
class Textarea extends TeaFields
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
     * @param string $prefix
     *
     * @since 2.0.0
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Textarea', TTO_I18N);
        $default = isset($content['default']) ? $content['default'] : '';
        $placeholder = isset($content['placeholder']) ? 'placeholder="' . $content['placeholder'] . '"' : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $rows = isset($content['rows']) ? $content['rows'] : '8';

        //Default way
        if (empty($post)) {
            //Check selected
            $val = TeaThemeOptions::get_option($prefix.$id, $default);
            $val = stripslashes($val);
        }
        //On CPT
        else {
            //Check selected
            $val = get_post_meta($post->ID, $post->post_type . '-' . $id, true);
            $val = stripslashes($val);
        }

        //Get template
        include(TTO_PATH.'/Fields/Textarea/in_pages.tpl.php');
    }
}
