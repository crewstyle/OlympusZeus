<?php

namespace Takeatea\TeaThemeOptions\Fields\Toggle;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA TOGGLE FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'toggle',
 *     'title' => 'Enable bot mode?',
 *     'id' => 'my_toggle_field_id',
 *     'default' => false, //define the default state
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Toggle
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Toggle
 * @since 2.1.0
 *
 */
class Toggle extends TeaFields
{
    /**
     * Constructor.
     *
     * @since 2.1.0
     */
    public function __construct(){}

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     * @param array $post Contains all post data
     * @param string $prefix
     *
     * @since 2.1.0
     */
    public function templatePages($content, $post = array(), $prefix = '')
    {
        //Check current post on CPTs
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Switch', TTO_I18N);
        $description = isset($content['description']) ? $content['description'] : '';
        $default = isset($content['default']) ? $content['default'] : false;

        //Default way
        if (empty($post)) {
            //Check selected
            $val = TeaThemeOptions::get_option($prefix.$id, $default);
        }
        //On CPT
        else {
            //Check selected
            $val = get_post_meta($post->ID, $post->post_type . '-' . $id, true);
        }

        //Get template
        include(TTO_PATH.'/Fields/Toggle/in_pages.tpl.php');
    }
}
