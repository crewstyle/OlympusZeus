<?php

namespace Takeatea\TeaThemeOptions\Fields\Maps;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA MAPS FIELD
 *
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'maps',
 *     'title' => 'Choose your final stage',
 *     'id' => 'my_maps_field_id',
 *     'description' => 'Do not choose the black hole World',
 *     'std' => array( //every attribute is optional
 *         'address' => '3 avenue Secretan, Paris, France',
 *         'marker' => array(
 *             'url' => get_template_directory_uri() . 'img/my_marker.png',
 *         ),
 *         'width' => 500,
 *         'height' => 200,
 *         'zoom' => 14,
 *         'type' => 'ROADMAP',
 *         'enable' => 'yes',
 *         'json' => '[{"stylers":[{"saturation":-100},{"gamma":0.8},{"lightness":4},{"visibility":"on"}]},{"featureType":"landscape.natural","stylers":[{"visibility":"on"},{"color":"#5dff00"},{"gamma":4.97},{"lightness":-5},{"saturation":100}]}]',
 *         'options' => array(
 *             'dragndrop' => 'no',
 *             'mapcontrol' => 'no',
 *             'pancontrol' => 'no',
 *             'zoomcontrol' => 'no',
 *             'scalecontrol' => 'no',
 *             'scrollwheel' => 'no',
 *             'streetview' => 'no',
 *             'rotatecontrol' => 'no',
 *             'rotatecontroloptions' => 'no',
 *             'overviewmapcontrol' => 'no',
 *             'overviewmapcontroloptions' => 'no'
 *         ),
 *     )
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
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
 * @since 1.5.2.14
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
     * DEFAULTS
     **/

    /**
     * Get default values.
     *
     * @param string $url Define the default marker URL
     * @return array $default Contains all default values
     *
     * @since 1.5.2.14
     */
    public function getValues($url)
    {
        return array(
            'address' => '',
            'marker' => array('url' => $url),
            'width' => '500',
            'height' => '250',
            'zoom' => '14',
            'type' => 'ROADMAP',
            'enable' => 'no',
            'json' => '',
            'options' => array(
                'dragndrop' => 'no',
                'mapcontrol' => 'no',
                'pancontrol' => 'no',
                'zoomcontrol' => 'no',
                'scalecontrol' => 'no',
                'scrollwheel' => 'no',
                'streetview' => 'no',
                'rotatecontrol' => 'no',
                'rotatecontroloptions' => 'no',
                'overviewmapcontrol' => 'no',
                'overviewmapcontroloptions' => 'no',
            )
        );
    }

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     * @param array $post Contains all post data
     *
     * @since 1.5.2.5
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
        $def = $this->getValues($url);
        $ctn = isset($content['std']) ? $content['std'] : array();

        //Merge values
        $std = array_merge($def, $ctn);

        //Default way
        if (empty($post)) {
            //Check selected
            $vals = TeaThemeOptions::get_option($prefix.$id, $std);
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }
        //On CPT
        else {
            //Check selected
            $vals = get_post_meta($post->ID, $post->post_type . '-' . $id, false);
            $vals = empty($vals) ? $std : (is_array($vals) ? $vals[0] : array($vals));
        }

        //Get template
        include(TTO_PATH.'/Fields/Maps/in_pages.tpl.php');
    }
}
