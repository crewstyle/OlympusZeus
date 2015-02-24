<?php

namespace Takeatea\TeaThemeOptions\Fields\Rte;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA RTE FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'rte',
 *     'title' => 'RTE',
 *     'id' => 'simple_rte',
 *     'default' => 'Do EEEEEEEEEEEEverything you want...',
 *     'description' => 'But not so much ! :('
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields RTE
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields RTE
 * @since 2.0.0
 *
 */
class Rte extends TeaFields
{
    /**
     * Constructor.
     *
     * @since 1.3.0
     */
    public function __construct(){}

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
        $title = isset($content['title']) ? $content['title'] : __('Tea RTE', TTO_I18N);
        $default = isset($content['default']) ? $content['default'] : '';
        $description = isset($content['description']) ? $content['description'] : '';

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
        include(TTO_PATH.'/Fields/Rte/in_pages.tpl.php');
    }
}
