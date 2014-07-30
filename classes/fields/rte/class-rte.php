<?php
/**
 * TEA RTE FIELD
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
 *     'type' => 'rte',
 *     'title' => 'RTE',
 *     'id' => 'simple_rte',
 *     'std' => 'Do EEEEEEEEEEEEverything you want...',
 *     'description' => 'But not so much ! :('
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
 * Tea Fields RTE
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields RTE
 * @since 1.4.0
 *
 */
class Tea_Fields_Rte extends Tea_Fields
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
        $title = isset($content['title']) ? $content['title'] : __('Tea RTE', TTO_I18N);
        $std = isset($content['std']) ? $content['std'] : '';
        $description = isset($content['description']) ? $content['description'] : '';

        //Default way
        if (empty($post)) {
            //Check selected
            $val = $this->getOption($id, $std);
            $val = stripslashes($val);
        }
        //On CPT
        else {
            //Check selected
            $val = get_post_meta($post->ID, $post->post_type . '-' . $id, true);
            $val = stripslashes($val);
        }

        //Get template
        include(TTO_PATH . '/classes/fields/rte/in_pages.tpl.php');
    }
}
