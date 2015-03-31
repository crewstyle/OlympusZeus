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
 *     'id' => 'my_gallery_field_id',
 *     'content' => true, //default "true"
 *     'description' => 'Give me this map and let me make us rich! MOUAHAHAHA'
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
 * @since 2.2.0
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
     * @since 2.2.0
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
        $description = isset($content['description']) ? $content['description'] : '';
        $content = isset($content['content']) ? $content['content'] : true;
        $can_upload = $this->getCanUpload();
        $delete = __('Delete selection', TTO_I18N);

        //Default way
        if (empty($post)) {
            //Check selected
            $vals = TeaThemeOptions::get_option($prefix.$id, array());
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }
        //On CPT
        else {
            //Check selected
            $vals = get_post_meta($post->ID, $post->post_type . '-' . $id, true);
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }

        //Get template
        include(TTO_PATH.'/Fields/Gallery/in_pages.tpl.php');
    }
}
