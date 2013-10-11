<?php
/**
 * Tea Theme Options Background field
 * 
 * @package TakeaTea
 * @subpackage Tea Fields Background
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
 * Tea Fields Background
 *
 * To get its own Fields
 *
 * @since Tea Theme Options 1.3.0
 *
 */
class Tea_Fields_Background extends Tea_Fields
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Background', TTO_I18N);
        $height = isset($content['height']) ? $content['height'] : '60';
        $width = isset($content['width']) ? $content['width'] : '150';
        $description = isset($content['description']) ? $content['description'] : '';
        $default = isset($content['default']) && (true === $content['default'] || '1' == $content['default']) ? true : false;
        $can_upload = $this->getCanUpload();
        $delete = __('Delete selection', TTO_I18N);

        //Default values
        $std = isset($content['std']) ? $content['std'] : array();
        $std['image'] = isset($content['std']['image']) ? $content['std']['image'] : '';
        $std['image_custom'] = isset($content['std']['image_custom']) ? $content['std']['image_custom'] : '';
        $std['color'] = isset($content['std']['color']) ? $content['std']['color'] : '';
        $std['position'] = isset($content['std']['position']) ? $content['std']['position'] : array();
        $std['position']['x'] = isset($content['std']['position']['x']) ? $content['std']['position']['x'] : 'left';
        $std['position']['y'] = isset($content['std']['position']['y']) ? $content['std']['position']['y'] : 'top';
        $std['repeat'] = isset($content['std']['repeat']) ? $content['std']['repeat'] : 'repeat';

        //Get options
        $options = isset($content['options']) ? $content['options'] : array();

        if ($default)
        {
            $default = $this->getDefaults('images');
            $options = array_merge($default, $options);
        }

        //Positions
        $details = $this->getDefaults('background-details');
        $url = TTO_URI . 'classes/fields/background/img/';

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
            $val = isset($value[$post->post_type . '-' . $id]) ? unserialize($value[$post->post_type . '-' . $id][0]) : $std;
        }

        //Get template
        include('in_pages.tpl.php');
    }
}
