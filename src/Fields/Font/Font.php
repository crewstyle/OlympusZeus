<?php

namespace Takeatea\TeaThemeOptions\Fields\Font;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA FONT FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'font',
 *     'title' => 'Choose your style',
 *     'id' => 'my_font_field_id',
 *     'default' => 'my_gorgeous_font',
 *     'description' => 'Tell us how to scribe :D',
 *     'fonts' => true,
 *     'options' => array(
 *         'PT+Sans' => array('PT Sans', '400,500')
 *     )
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Font
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Font
 * @since 2.0.0
 *
 */
class Font extends TeaFields
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
        //Do not display field on CPTs
        if (!empty($post)) {
            return;
        }

        //Check if an id is defined at least
        $this->checkId($content);

        //Default variables
        $id = $content['id'];
        $title = isset($content['title']) ? $content['title'] : __('Tea Font', TTO_I18N);
        $default = isset($content['default']) ? $content['default'] : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $fonts = isset($content['fonts']) && (true === $content['fonts'] || '1' == $content['fonts']) ? true : false;

        //Get options
        $options = isset($content['options']) ? $content['options'] : array();

        if ($fonts) {
            $fonts = $this->getDefaults('fonts');
            $options = array_merge($fonts, $options);
        }

        //Get includes
        $includes = $this->getIncludes();
        $linkstylesheet = '';
        $gfontstyle = '';

        //Check if Google Font has already been included
        if (!isset($includes['googlefonts'])) {
            $this->setIncludes('googlefonts');

            //Define our stylesheets
            foreach ($options as $option) {
                if (empty($option[0]) || 'sansserif' == $option[0]) {
                    continue;
                }

                $opt = isset($option[2]) ? ':'.$option[2] : '';
                $linkstylesheet .= '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' . $option[0] . $opt . '" />' . "\n";
                $gfontstyle .= '.gfont_' . str_replace(' ', '_', $option[1]) . ' {font-family:\'' . $option[1] . '\',sans-serif;}' . "\n";
            }
        }

        //Check selected
        $val = TeaThemeOptions::get_option($prefix.$id, $default);

        //Get template
        include(TTO_PATH.'/Fields/Font/in_pages.tpl.php');
    }
}
