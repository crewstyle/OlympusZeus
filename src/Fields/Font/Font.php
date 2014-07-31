<?php
namespace Takeatea\TeaThemeOptions\Fields\Font;

use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA FONT FIELD
 * 
 * Copyright (C) 2014, Achraf Chouk - ach@takeatea.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'font',
 *     'title' => 'Choose your style',
 *     'id' => 'my_font_field_id',
 *     'std' => 'my_gorgeous_font',
 *     'description' => 'Tell us how to scribe :D',
 *     'default' => true,
 *     'options' => array(
 *         'PT+Sans' => array('PT Sans', '400,500')
 *     )
 * )
 *
 */

if (!defined('ABSPATH')) {
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
 * @since 1.4.0
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
     * @since 1.4.0
     */
    public function templatePages($content, $post = array())
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
        $std = isset($content['std']) ? $content['std'] : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $default = isset($content['default']) && (true === $content['default'] || '1' == $content['default']) ? true : false;

        //Get options
        $options = isset($content['options']) ? $content['options'] : array();

        if ($default) {
            $default = $this->getDefaults('fonts');
            $options = array_merge($default, $options);
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
        $val = $this->getOption($id, $std);

        //Get template
        include(TTO_PATH . '/Fields/Font/in_pages.tpl.php');
    }
}
