<?php

namespace crewstyle\TeaThemeOptions\Core\Field\Maps;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Field\Field;

/**
 * TTO MAPS FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'maps',
 *     'title' => 'Choose your final stage',
 *     'id' => 'my_maps_field_id',
 *     'description' => 'Do not choose the black hole World',
 *     'default' => array( //every attribute is optional
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
 * TTO Field Maps
 *
 * Class used to build Maps field.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\Maps
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Maps extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-map';

    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct(){}

    /**
     * Display HTML component.
     *
     * @param array $content Contains all field data
     * @param array $details Contains all field options
     *
     * @since 3.0.0
     */
    public function prepareField($content, $details = array())
    {
        //Build details
        $post = isset($details['post']) ? $details['post'] : 0;
        $prefix = isset($details['prefix']) ? $details['prefix'] : '';
        $tpl = empty($prefix) ? 'pages' : 'terms';

        //Build defaults data
        $template = array(
            'id' => $content['id'],
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Maps'),
            'description' => isset($content['description']) ? $content['description'] : '',
            'can_upload' => $this->getCanUpload(),
            'url' => $this->getMarkerAssets(),

            'options' => $this->getMapsOptions(),
            'types' => $this->getMapsTypes(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_delete_item' => TeaThemeOptions::__('Delete selection'),
            't_item_globals' => TeaThemeOptions::__('Globals'),
            't_item_configs' => TeaThemeOptions::__('Configurations'),
            't_item_customs' => TeaThemeOptions::__('Customizations'),
            't_item_preview' => TeaThemeOptions::__('Preview'),

            't_address' => TeaThemeOptions::__('Address'),
            't_address_description' => TeaThemeOptions::__('Define your default address in which 
                the maps will be centered.'),

            't_marker' => TeaThemeOptions::__('Marker'),
            't_marker_add' => TeaThemeOptions::__('Add marker'),
            't_marker_cannot_upload' => TeaThemeOptions::__('It seems you are not able to upload files.'),

            't_width' => TeaThemeOptions::__('Width'),
            't_width_description' => TeaThemeOptions::__('Define the width maps in pixels.'),

            't_height' => TeaThemeOptions::__('Height'),
            't_height_description' => TeaThemeOptions::__('Define the height maps in pixels.'),

            't_zoom' => TeaThemeOptions::__('Zoom'),
            't_zoom_description' => TeaThemeOptions::__('Define your default zoom.'),

            't_type' => TeaThemeOptions::__('Type'),
            't_type_description' => TeaThemeOptions::__('Define your default type.'),

            't_options' => TeaThemeOptions::__('Options'),
            't_options_select' => TeaThemeOptions::__('Un/select all options'),
            't_options_description' => TeaThemeOptions::__('Define your default options.'),

            't_json' => TeaThemeOptions::__('Enable JSON design?'),
            't_json_yes' => TeaThemeOptions::__('Yes'),
            't_json_no' => TeaThemeOptions::__('No'),
            't_json_description' => TeaThemeOptions::__('To customize your Google map, go to 
                <a href="http://snazzymaps.com/" target="_blank"><b>SnazzyMaps</b></a> website, 
                choose a theme and copy/paste the <code>Javascript style array</code> code 
                in this textarea.'),

            't_update' => TeaThemeOptions::__('Update maps'),
        );

        //Default values
        $def = $this->getMapsDefault($template['url']);
        $ctn = isset($content['default']) ? $content['default'] : array();

        //Merge values
        $template['default'] = array_merge($def, $ctn);

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);

        //Get template
        return $this->renderField('fields/maps.html.twig', $template);
    }

    /**
     * Return all available maps details.
     *
     * @return array $array Contains all maps details
     *
     * @since 3.3.0
     */
    public function getMapsDefault()
    {
        return array(
            'address' => '',
            'marker' => array('url' => $this->getMarkerAssets().'marker.png'),
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
     * Return all available maps options.
     *
     * @return array $array Contains all maps options
     *
     * @since 3.0.0
     */
    public function getMapsOptions()
    {
        return array(
            'dragndrop' => TeaThemeOptions::__('Drag\'n drop'),
            'streetview' => TeaThemeOptions::__('Street View'),
            'zoomcontrol' => TeaThemeOptions::__('Zoom control'),

            'mapcontrol' => TeaThemeOptions::__('Map control'),
            'scalecontrol' => TeaThemeOptions::__('Scale control'),
            'pancontrol' => TeaThemeOptions::__('Pan control'),

            'rotatecontrol' => TeaThemeOptions::__('Rotate control'),
            'rotatecontroloptions' => TeaThemeOptions::__('Rotate control options'),
            'scrollwheel' => TeaThemeOptions::__('Scroll Wheel'),

            'overviewmapcontrol' => TeaThemeOptions::__('Overview Map control'),
            'overviewmapcontroloptions' => TeaThemeOptions::__('Overview Map control options'),
        );
    }

    /**
     * Return all available maps types.
     *
     * @return array $array Contains all maps types
     *
     * @since 3.0.0
     */
    public function getMapsTypes()
    {
        return array(
            'ROADMAP' => TeaThemeOptions::__('Roadmap'),
            'SATELLITE' => TeaThemeOptions::__('Satellite'),
            'HYBRID' => TeaThemeOptions::__('Hybrid'),
            'TERRAIN' => TeaThemeOptions::__('Terrain'),
        );
    }

    /**
     * Return default assets url.
     *
     * @return string $url Contains url to assets folder
     *
     * @since 3.0.0
     */
    public function getMarkerAssets()
    {
        return TTO_URI . '/assets/img/maps/';
    }
}
