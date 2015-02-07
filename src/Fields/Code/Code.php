<?php

namespace Takeatea\TeaThemeOptions\Fields\Code;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA CODE FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'Code',
 *     'title' => 'How do Penguins code their icebergs?',
 *     'id' => 'my_code_field_id',
 *     'std' => 'With a frozen bug.',
 *     'rows' => 4,
 *     'description' => 'A simple question to know if you know how to seduce a penguin.'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Code
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Code
 * @since 1.5.2.14
 *
 */
class Code extends TeaFields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since 1.5.1-6
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
     * @since 1.5.1-7
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Code', TTO_I18N);
        $description = isset($content['description']) ? $content['description'] : '';
        $rows = isset($content['rows']) ? $content['rows'] : 4;


        //Default values
        $std = isset($content['std']) ? $content['std'] : array();
        $std = array(
            'mode' => isset($std['mode']) ? $std['mode'] : 'application/json',
            'code' => isset($std['code']) ? $std['code'] : ''
        );

        //Default way
        if (empty($post)) {
            //Check selected
            $vals = TeaThemeOptions::get_option($prefix.$id, $std);
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }
        //On CPT
        else {
            //Check selected
            $vals = get_post_meta($post->ID, $post->post_type . '-' . $id, true);
            $vals = empty($vals) ? $std : (is_array($vals) ? $vals[0] : array($vals));
        }

        //Get template
        include(TTO_PATH.'/Fields/Code/in_pages.tpl.php');
    }
}
