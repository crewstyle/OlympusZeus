<?php
/**
 * Tea Theme Options Upload field
 * 
 * @package TakeaTea
 * @subpackage Tea Fields Upload
 * @since Tea Theme Options 1.3.0
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//---------------------------------------------------------------------------------------------------------//

//Require master Class
require_once(TTO_PATH . 'classes/class-tea-fields.php');

//---------------------------------------------------------------------------------------------------------//

/**
 * Tea Fields Upload
 *
 * To get its own Fields
 *
 * @since Tea Theme Options 1.3.0
 *
 */
class Tea_Fields_Upload extends Tea_Fields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since Tea Theme Options 1.3.0
     */
    public function __construct(){}


    //--------------------------------------------------------------------------//

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     *
     * @since Tea Theme Options 1.3.0
     */
    public function templatePages($content, $post = array())
    {
        //Check if an id is defined at least
        if (empty($post))
        {
            //Check if an id is defined at least
            $this->checkId($content);
        }
        else
        {
            //Modify content
            $content = $content['args']['contents'];
        }

        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Upload', TTO_I18N);
        $std = isset($content['std']) ? $content['std'] : '';
        $library = isset($content['library']) ? $content['library'] : 'image';
        $description = isset($content['description']) ? $content['description'] : '';
        $multiple = isset($content['multiple']) && (true === $content['multiple'] || '1' == $content['multiple']) ? '1' : '0';
        $can_upload = $this->getCanUpload();
        $delete = __('Delete selection', TTO_I18N);

        //Default way
        if (empty($post))
        {
            //Check selected
            $val = $this->getOption($id, $std);
        }
        //On CPT
        else
        {
            //Check selected
            $value = get_post_custom($post->ID);
            $val = isset($value[$post->post_type . '-' . $id]) ? $value[$post->post_type . '-' . $id][0] : $std;
        }

        //Get template
        include('in_pages.tpl.php');
    }
}
