<?php
namespace Takeatea\TeaThemeOptions\Fields\Text;

use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA TEXT FIELD
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
 *     'type' => 'text',
 *     'title' => 'What do you like?',
 *     'id' => 'my_text_field_id',
 *     'std' => "Penguins, I am sure they're gonna dominate the World!",
 *     'placeholder' => "McDonald's as well",
 *     'description' => 'Put in here everything you want.',
 *     'maxlength' => 120
 * )
 *
 * Or if you need to add somes details...
 * array(
 *     'type' => 'text',
 *     'title' => 'How much do you like Penguins?',
 *     'id' => 'my_text_field_id',
 *     'std' => 100,
 *     'placeholder' => '50',
 *     'description' => 'Tell us how much do like Penguins to have a chance to get into our private Penguins community ;)',
 *     'options' => array(
 *         'type' => 'number',
 *         'min' => 10,
 *         'max' => 100,
 *         'step' => 1
 *     )
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Text
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Text
 * @since 1.4.0
 *
 */
class Text extends TeaFields
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
    public function templatePages($content, $post = array(), $prefix = '')
    {
        //Check current post on CPTs
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
        if ('number' == $options['type'] || 'range' == $options['type']) {
            //Infos
            $type = $options['type'];

            //Special variables
            $min = isset($options['min']) ? 'min="' . $options['min'] . '"' : 'min="1"';
            $max = isset($options['max']) ? 'max="' . $options['max'] . '"' : 'max="50"';
            $step = isset($options['step']) ? 'step="' . $options['step'] . '"' : 'step="1"';
        }
        else {
            //Infos
            $type = $options['type'];
        }

        //Default way
        if (empty($post)) {
            //Check selected
            $val = $this->getOption($prefix.$id, $std);
            $val = stripslashes($val);
        }
        //On CPT
        else {
            //Check selected
            $val = get_post_meta($post->ID, $post->post_type . '-' . $id, true);
            $val = stripslashes($val);
        }

        //Get template
        include(TTO_PATH . '/Fields/Text/in_pages.tpl.php');
    }
}
