<?php
/**
 * Tea Theme Options Text field
 * 
 * @package TakeaTea
 * @subpackage Tea Fields Text
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
 * Tea Fields Text
 *
 * To get its own Fields
 *
 * @since Tea Theme Options 1.3.0
 *
 */
class Tea_Fields_Text extends Tea_Fields
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
     * @param array $post Contains all post data
     *
     * @since Tea Theme Options 1.3.0
     */
    public function templatePages($content, $post = array())
    {
        //Check current post on CPTs
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Text', TTO_I18N);
        $std = isset($content['std']) ? $content['std'] : '';
        $placeholder = isset($content['placeholder']) ? 'placeholder="' . $content['placeholder'] . '"' : '';
        $maxlength = isset($content['maxlength']) ? 'maxlength="' . $content['maxlength'] . '"' : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $options = isset($content['options']) ? $content['options'] : array();

        //Special variables
        $min = $max = $step = '';
        $options['type'] = !isset($options['type']) || empty($options['type']) ? 'text' : $options['type'];

        //Check options
        if ('number' == $options['type'] || 'range' == $options['type'])
        {
            //Infos
            $type = $options['type'];

            //Special variables
            $min = isset($options['min']) ? 'min="' . $options['min'] . '"' : 'min="1"';
            $max = isset($options['max']) ? 'max="' . $options['max'] . '"' : 'max="50"';
            $step = isset($options['step']) ? 'step="' . $options['step'] . '"' : 'step="1"';
        }
        else
        {
            //Infos
            $type = $options['type'];
        }

        //Default way
        if (empty($post))
        {
            //Check selected
            $val = $this->getOption($id, $std);
            $val = stripslashes($val);
        }
        //On CPT
        else
        {
            //Check selected
            $value = get_post_custom($post->ID);
            $val = isset($value[$post->post_type . '-' . $id]) ? $value[$post->post_type . '-' . $id][0] : $std;
            $val = stripslashes($val);
        }

        //Get template
        include('in_pages.tpl.php');
    }
}
