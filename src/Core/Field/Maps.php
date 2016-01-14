<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Maps field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Maps
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/checkbox
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
     * @since 4.0.0
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
            'title' => isset($content['title']) ? $content['title'] : OlympusZeus::translate('Maps'),
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
            't_delete_item' => OlympusZeus::translate('Delete selection'),
            't_item_globals' => OlympusZeus::translate('Globals'),
            't_item_configs' => OlympusZeus::translate('Configurations'),
            't_item_customs' => OlympusZeus::translate('Customizations'),
            't_item_preview' => OlympusZeus::translate('Preview'),

            't_address' => OlympusZeus::translate('Address'),
            't_address_description' => OlympusZeus::translate('Define your default address in which 
                the maps will be centered.'),

            't_marker' => OlympusZeus::translate('Marker'),
            't_marker_add' => OlympusZeus::translate('Add marker'),
            't_marker_cannot_upload' => OlympusZeus::translate('It seems you are not able to upload files.'),

            't_width' => OlympusZeus::translate('Width'),
            't_width_description' => OlympusZeus::translate('Define the width maps in pixels.'),

            't_height' => OlympusZeus::translate('Height'),
            't_height_description' => OlympusZeus::translate('Define the height maps in pixels.'),

            't_zoom' => OlympusZeus::translate('Zoom'),
            't_zoom_description' => OlympusZeus::translate('Define your default zoom.'),

            't_type' => OlympusZeus::translate('Type'),
            't_type_description' => OlympusZeus::translate('Define your default type.'),

            't_options' => OlympusZeus::translate('Options'),
            't_options_select' => OlympusZeus::translate('Un/select all options'),
            't_options_description' => OlympusZeus::translate('Define your default options.'),

            't_json' => OlympusZeus::translate('Enable JSON design?'),
            't_json_yes' => OlympusZeus::translate('Yes'),
            't_json_no' => OlympusZeus::translate('No'),
            't_json_description' => OlympusZeus::translate('To customize your Google map, go to 
                <a href="http://snazzymaps.com/" target="_blank"><b>SnazzyMaps</b></a> website, 
                choose a theme and copy/paste the <code>Javascript style array</code> code 
                in this textarea.'),

            't_update' => OlympusZeus::translate('Update maps'),
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
            'dragndrop' => OlympusZeus::translate('Drag\'n drop'),
            'streetview' => OlympusZeus::translate('Street View'),
            'zoomcontrol' => OlympusZeus::translate('Zoom control'),

            'mapcontrol' => OlympusZeus::translate('Map control'),
            'scalecontrol' => OlympusZeus::translate('Scale control'),
            'pancontrol' => OlympusZeus::translate('Pan control'),

            'rotatecontrol' => OlympusZeus::translate('Rotate control'),
            'rotatecontroloptions' => OlympusZeus::translate('Rotate control options'),
            'scrollwheel' => OlympusZeus::translate('Scroll Wheel'),

            'overviewmapcontrol' => OlympusZeus::translate('Overview Map control'),
            'overviewmapcontroloptions' => OlympusZeus::translate('Overview Map control options'),
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
            'ROADMAP' => OlympusZeus::translate('Roadmap'),
            'SATELLITE' => OlympusZeus::translate('Satellite'),
            'HYBRID' => OlympusZeus::translate('Hybrid'),
            'TERRAIN' => OlympusZeus::translate('Terrain'),
        );
    }

    /**
     * Return default assets url.
     *
     * @return string $url Contains url to assets folder
     *
     * @since 4.0.0
     */
    public function getMarkerAssets()
    {
        return OLZ_URI.'/assets/img/maps/';
    }
}
