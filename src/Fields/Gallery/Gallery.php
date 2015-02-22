<?php

namespace Takeatea\TeaThemeOptions\Fields\Gallery;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA GALLERY FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'gallery',
 *     'title' => 'Show me your treasures...',
 *     'id' => 'my_gallery_field_id'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Gallery
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Gallery
 * @since 2.0.0
 *
 */
class Gallery extends TeaFields
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Gallery', TTO_I18N);
        $default = isset($content['default']) ? $content['default'] : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $can_upload = $this->getCanUpload();
        $delete = __('Delete selection', TTO_I18N);

        //Default way
        if (empty($post)) {
            //Check selected
            $vals = TeaThemeOptions::get_option($prefix.$id, $default);
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }
        //On CPT
        else {
            //Check selected
            $value = get_post_custom($post->ID);
            $vals = isset($value[$post->post_type . '-' . $id]) ? unserialize($value[$post->post_type . '-' . $id][0]) : $default;
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }

        //Get template
        include(TTO_PATH.'/Fields/Gallery/in_pages.tpl.php');
    }
}
