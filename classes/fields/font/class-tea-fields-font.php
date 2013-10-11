<?php
/**
 * Tea Theme Options Font field
 * 
 * @package TakeaTea
 * @subpackage Tea Fields Font
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
 * Tea Fields Font
 *
 * To get its own Fields
 *
 * @since Tea Theme Options 1.3.0
 *
 */
class Tea_Fields_Font extends Tea_Fields
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Font', TTO_I18N);
        $std = isset($content['std']) ? $content['std'] : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $default = isset($content['default']) && (true === $content['default'] || '1' == $content['default']) ? true : false;

        //Get options
        $options = isset($content['options']) ? $content['options'] : array();

        if ($default)
        {
            $default = $this->getDefaults('fonts');
            $options = array_merge($default, $options);
        }

        //Get includes
        $includes = $this->getIncludes();
        $linkstylesheet = '';
        $gfontstyle = '';

        //Check if Google Font has already been included
        if (!isset($includes['googlefonts']))
        {
            $this->setIncludes('googlefonts');

            //Define our stylesheets
            foreach ($options as $option)
            {
                if (empty($option[0]) || 'sansserif' == $option[0])
                {
                    continue;
                }

                $linkstylesheet .= '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' . $option[0] . ':' . $option[2] . '" />' . "\n";
                $gfontstyle .= '.gfont_' . str_replace(' ', '_', $option[1]) . ' {font-family:\'' . $option[1] . '\',sans-serif;}' . "\n";
            }
        }

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
