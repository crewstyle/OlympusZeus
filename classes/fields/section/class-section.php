<?php
/**
 * TEA SECTION FIELD
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
 *     'type' => 'section',
 *     'image' => 'my_image_url',
 *     'color' => 'white', //or "green"
 *     'content' => 'Hello and welcome to the "Tea Test Academy"'
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

//Require master Class
require_once(TTO_PATH . '/classes/fields/class-tea-fields.php');

//----------------------------------------------------------------------------//

/**
 * Tea Fields Section
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields P
 * @since 1.4.0
 *
 */
class Tea_Fields_Section extends Tea_Fields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since 1.4.0
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
        //Default variables
        $color = isset($content['color']) ? $content['color'] : 'white';
        $image = isset($content['image']) ? $content['image'] : '';
        $position = isset($content['position']) ? $content['position'] : 'left';
        $content = isset($content['content']) ? $content['content'] : '';

        //Get template
        include(TTO_PATH . '/classes/fields/section/in_pages.tpl.php');
    }
}
