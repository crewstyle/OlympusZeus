<?php
namespace Takeatea\TeaThemeOptions\Fields\Textarea;

use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA TEXTAREA FIELD
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
 *     'type' => 'textarea',
 *     'title' => 'How do Penguins drink their cola?',
 *     'id' => 'my_textarea_field_id',
 *     'std' => 'On the rocks.',
 *     'placeholder' => 'Tell us how?',
 *     'description' => 'A simple question to know if you will be able to survive to the Penguin domination.'
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Textarea
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Textarea
 * @since 1.4.0
 *
 */
class Textarea extends TeaFields
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Textarea', TTO_I18N);
        $std = isset($content['std']) ? $content['std'] : '';
        $placeholder = isset($content['placeholder']) ? 'placeholder="' . $content['placeholder'] . '"' : '';
        $description = isset($content['description']) ? $content['description'] : '';
        $rows = isset($content['rows']) ? $content['rows'] : '8';

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
        include('in_pages.tpl.php');
    }
}
