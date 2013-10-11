<?php
/**
 * Tea Theme Options Wordpress field
 * 
 * @package TakeaTea
 * @subpackage Tea Fields Wordpress
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
 * Tea Fields Wordpress
 *
 * To get its own Fields
 *
 * @since Tea Theme Options 1.3.0
 *
 */
class Tea_Fields_Wordpress extends Tea_Fields
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
        $mode = isset($content['mode']) ? $content['mode'] : 'posts';
        $title = isset($content['title']) ? $content['title'] : __('Tea Wordpress Contents', TTO_I18N);
        $multiple = isset($content['multiple']) && (true === $content['multiple'] || '1' == $content['multiple']) ? true : false;
        $description = isset($content['description']) ? $content['description'] : '';

        //Get the categories
        $contents = $this->getWPContents($mode, $multiple);

        //Default way
        if (empty($post))
        {
            //Check selected
            $vals = $this->getOption($id, array());
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }
        //On CPT
        else
        {
            //Check selected
            $value = get_post_custom($post->ID);
            $vals = isset($value[$post->post_type . '-' . $id]) ? $value[$post->post_type . '-' . $id][0] : array();
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }

        //Get template
        include('in_pages.tpl.php');
    }
}
