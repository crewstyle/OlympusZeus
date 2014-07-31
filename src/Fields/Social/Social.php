<?php
namespace Takeatea\TeaThemeOptions\Fields\Social;

use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA SOCIAL FIELD
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
 *     'type' => 'social',
 *     'title' => 'Big Brother is watching you...',
 *     'id' => 'my_social_field_id',
 *     'description' => '...Or not!',
 *     'std' => array(
 *          'facebook' => array(
 *              'display' => 'yes',
 *              'label' => __('Become a fan', TEMPLATE_DICTIONARY),
 *              'link' => __('http://www.facebook.com/takeatea', TEMPLATE_DICTIONARY)
 *          ),
 *          'twitter' => array(
 *              'display' => 'yes',
 *              'label' => __('Follow us', TEMPLATE_DICTIONARY),
 *              'link' => __('https://twitter.com/takeatea', TEMPLATE_DICTIONARY)
 *          ),
 *          'instagram' => array(
 *              'display' => 'no',
 *              'label' => __('Take a shot', TEMPLATE_DICTIONARY),
 *              'link' => __('http://instagram.com/takeatea', TEMPLATE_DICTIONARY)
 *          ),
 *          'rss' => array(
 *              'label' => __('Subscribe to our feed', TEMPLATE_DICTIONARY)
 *          )
 *      )
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Social
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Social
 * @since 1.4.0
 *
 */
class Social extends TeaFields
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Social', TTO_I18N);
        $std = isset($content['std']) ? $content['std'] : array();
        $description = isset($content['description']) ? $content['description'] : '';

        //Get the social networks
        $socials = $this->getDefaults('social');

        //Count options
        $count = count($std);

        //Get includes
        $includes = $this->getIncludes();
        $socials = array();

        //Check if Google Font has already been included
        if (!isset($includes['socialtemplate'])) {
            $this->setIncludes('socialtemplate');

            //Define our stylesheets
            $socials = $this->getDefaults('social');
        }

        //Check selected
        $vals = $this->getOption($id, $std);

        //Get template
        include('in_pages.tpl.php');
    }
}
