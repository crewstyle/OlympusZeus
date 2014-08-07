<?php
namespace Takeatea\TeaThemeOptions\Fields\Maps;

use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA MAPS FIELD
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
 * To add this field, simply make the same as follow: @todo
 * array(
 *     'type' => 'maps',
 *     'title' => 'Choose your final stage',
 *     'id' => 'my_maps_field_id',
 *     'std' => '',
 *     'description' => 'Do not choose the black hole World'
 * )
 *
 */

if (!defined('ABSPATH')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * Tea Fields Maps
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Maps
 * @since 1.4.0
 *
 */
class Maps extends TeaFields
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
    public function templatePages($content, $post = array(), $prefix = '')
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
        $title = isset($content['title']) ? $content['title'] : __('Tea Maps', TTO_I18N);
        $description = isset($content['description']) ? $content['description'] : '';
        $can_upload = $this->getCanUpload();
        $delete = __('Delete selection', TTO_I18N);
        $url = TTO_URI . '/src/Fields/Maps/img/marker@2x.png';

        //Default values
        $std = isset($content['std']) ? $content['std'] : array();
        $std['address'] = isset($std['address']) ? $std['address'] : '';
        $std['marker'] = isset($std['marker']) ? $std['marker'] : $url;
        $std['width'] = isset($std['width']) ? $std['width'] : '500';
        $std['height'] = isset($std['height']) ? $std['height'] : '250';
        $std['zoom'] = isset($std['zoom']) ? $std['zoom'] : '14';
        $std['type'] = isset($std['type']) ? $std['type'] : 'ROADMAP';
        $std['enable'] = isset($std['enable']) ? $std['enable'] : 'no';
        $std['json'] = isset($std['json']) ? $std['json'] : '';

        //Default options
        $std['options'] = array(
            'dragndrop' => isset($std['options']['dragndrop']) ? $std['options']['dragndrop'] : 'no',
            'mapcontrol' => isset($std['options']['mapcontrol']) ? $std['options']['mapcontrol'] : 'no',
            'pancontrol' => isset($std['options']['pancontrol']) ? $std['options']['pancontrol'] : 'no',
            'zoomcontrol' => isset($std['options']['zoomcontrol']) ? $std['options']['zoomcontrol'] : 'no',
            'scalecontrol' => isset($std['options']['scalecontrol']) ? $std['options']['scalecontrol'] : 'no',
            'scrollwheel' => isset($std['options']['scrollwheel']) ? $std['options']['scrollwheel'] : 'no',
            'streetview' => isset($std['options']['streetview']) ? $std['options']['streetview'] : 'no',
            'rotatecontrol' => isset($std['options']['rotatecontrol']) ? $std['options']['rotatecontrol'] : 'no',
            'rotatecontroloptions' => isset($std['options']['rotatecontroloptions']) ? $std['options']['rotatecontroloptions'] : 'no',
            'overviewmapcontrol' => isset($std['options']['overviewmapcontrol']) ? $std['options']['overviewmapcontrol'] : 'no',
            'overviewmapcontroloptions' => isset($std['options']['overviewmapcontroloptions']) ? $std['options']['overviewmapcontroloptions'] : 'no',
        );

        //Default way
        if (empty($post)) {
            //Check selected
            $vals = $this->getOption($prefix.$id, $std);
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }
        //On CPT
        else {
            //Check selected
            $vals = get_post_meta($post->ID, $post->post_type . '-' . $id, false);
            $vals = empty($vals) ? $std : (is_array($vals) ? $vals[0] : array($vals));
        }

        //Get template
        include(TTO_PATH . '/Fields/Maps/in_pages.tpl.php');
    }
}
