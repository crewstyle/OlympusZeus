<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Map field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Map
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-map
 *
 */

class Map extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-map';

    /**
     * @var string
     */
    protected $template = 'fields/map.html.twig';

    /**
     * Prepare HTML component.
     *
     * @param array $content
     * @param array $details
     *
     * @since 5.0.0
     */
    protected function getVars($content, $details = [])
    {
        //Build defaults
        $defaults = [
            'id' => '',
            'title' => Translate::t('Map'),
            'default' => '',
            'description' => '',
            'can_upload' => OLZ_CAN_UPLOAD,
            'url' => $this->getMarkerAssets(),
            'options' => $this->getMapsOptions(),
            'types' => $this->getMapsTypes(),

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',

            //texts
            't_delete_item' => Translate::('Delete selection'),
            't_item_globals' => Translate::('Globals'),
            't_item_configs' => Translate::('Configurations'),
            't_item_customs' => Translate::('Customizations'),
            't_item_preview' => Translate::('Preview'),

            't_address' => Translate::('Address'),
            't_address_description' => Translate::('Define your default address in which 
                the maps will be centered.'),

            't_marker' => Translate::('Marker'),
            't_marker_add' => Translate::('Add marker'),
            't_marker_cannot_upload' => Translate::('It seems you are not able to upload files.'),

            't_width' => Translate::('Width'),
            't_width_description' => Translate::('Define the width maps in pixels.'),

            't_height' => Translate::('Height'),
            't_height_description' => Translate::('Define the height maps in pixels.'),

            't_zoom' => Translate::('Zoom'),
            't_zoom_description' => Translate::('Define your default zoom.'),

            't_type' => Translate::('Type'),
            't_type_description' => Translate::('Define your default type.'),

            't_options' => Translate::('Options'),
            't_options_select' => Translate::('Un/select all options'),
            't_options_description' => Translate::('Define your default options.'),

            't_json' => Translate::('Enable JSON design?'),
            't_json_yes' => Translate::('Yes'),
            't_json_no' => Translate::('No'),
            't_json_description' => Translate::('To customize your Google map, go to 
                <a href="http://snazzymaps.com/" target="_blank"><b>SnazzyMaps</b></a> website, 
                choose a theme and copy/paste the <code>Javascript style array</code> code 
                in this textarea.'),

            't_update' => Translate::('Update maps'),
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id'], true);

        //Update vars
        $this->getField()->setVars($vars);
    }

    /**
     * Return all available maps details.
     *
     * @return array $details
     *
     * @since 5.0.0
     */
    protected function getMapsDefault()
    {
        return [
            'address' => '',
            'marker' => ['url' => $this->getMarkerAssets().'marker.png'],
            'width' => '500',
            'height' => '250',
            'zoom' => '14',
            'type' => 'ROADMAP',
            'enable' => 'no',
            'json' => '',
            'options' => [
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
            ]
        ];
    }

    /**
     * Return all available maps options.
     *
     * @return array $options
     *
     * @since 5.0.0
     */
    protected function getMapsOptions()
    {
        return [
            'dragndrop' => Translate::t('Drag\'n drop'),
            'streetview' => Translate::t('Street View'),
            'zoomcontrol' => Translate::t('Zoom control'),

            'mapcontrol' => Translate::t('Map control'),
            'scalecontrol' => Translate::t('Scale control'),
            'pancontrol' => Translate::t('Pan control'),

            'rotatecontrol' => Translate::t('Rotate control'),
            'rotatecontroloptions' => Translate::t('Rotate control options'),
            'scrollwheel' => Translate::t('Scroll Wheel'),

            'overviewmapcontrol' => Translate::t('Overview Map control'),
            'overviewmapcontroloptions' => Translate::t('Overview Map control options'),
        ];
    }

    /**
     * Return all available maps types.
     *
     * @return array $types
     *
     * @since 5.0.0
     */
    protected function getMapsTypes()
    {
        return [
            'ROADMAP' => Translate::t('Roadmap'),
            'SATELLITE' => Translate::t('Satellite'),
            'HYBRID' => Translate::t('Hybrid'),
            'TERRAIN' => Translate::t('Terrain'),
        ];
    }

    /**
     * Return default assets url.
     *
     * @return string $url
     *
     * @since 5.0.0
     */
    protected function getMarkerAssets()
    {
        return OLZ_URI.'/assets/img/maps/';
    }
}
