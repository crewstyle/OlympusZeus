<?php

namespace Takeatea\TeaThemeOptions\Fields\Date;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA DATE FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'date',
 *     'title' => 'Once uppon a time...',
 *     'id' => 'my_date_field_id',
 *     'description' => 'A white rabbit was running...',
 *     'format' => 'dd.mm.YYYY', //optional, "YYYY/mm/dd" by default
 *     'std' => '27.04.1984'
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Date
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Date
 * @since 1.5.0
 *
 */
class Date extends TeaFields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since 1.5.0
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
     * @param string $prefix Contains meta post prefix
     *
     * @since 1.5.0
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Date', TTO_I18N);
        $std = isset($content['std']) ? $content['std'] : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $format = isset($content['format']) ? $content['format'] : 'd mmmm, yyyy';
        $submit = isset($content['submit']) ? $content['submit'] : 'yyyy.mm.dd';

        //Default way
        if (empty($post)) {
            //Check selected
            $val = TeaThemeOptions::get_option($prefix.$id, $std);
            $val = stripslashes($val);
        }
        //On CPT
        else {
            //Check selected
            $val = get_post_meta($post->ID, $post->post_type . '-' . $id, true);
            $val = stripslashes($val);
        }

        //Get template
        include(TTO_PATH.'/Fields/Date/in_pages.tpl.php');
    }
}
