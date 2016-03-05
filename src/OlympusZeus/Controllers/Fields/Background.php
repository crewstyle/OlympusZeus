<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Background field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Background
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-background
 *
 */

class Background extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-picture-o';

    /**
     * @var string
     */
    protected $template = 'fields/background.html.twig';

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
            'title' => Translate::t('Background'),
            'height' => '60',
            'width' => '150',
            'description' => '',
            'backgrounds' => false,
            'can_upload' => OLZ_CAN_UPLOAD,
            'delete' => Translate::t('Delete selection'),
            'details' => $this->getBackgroundDetails(),

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',

            //defaults
            'default' => [
                'image' => '',
                'color' => '',
                'position' => '0 0',
                'repeat' => 'repeat',
                'size' => 'auto',
            ],

            //texts
            't_add_background' => Translate::t('Add background'),
            't_cannot_upload' => Translate::t('It seems you are not able to upload files.'),
            't_remove' => Translate::t('Remove background'),

            't_choose_bg' => Translate::t('Define a background image'),
            't_choose_color' => Translate::t('Define a background color'),
            't_choose_position_size' => Translate::t('Define a background'),
            't_choose_position' => Translate::t('position'),
            't_choose_size' => Translate::t('size'),
            't_choose_repeat' => Translate::t('Define a background repeat'),
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id'], true);

        //Update vars
        $this->getField()->setVars($vars);
    }

    /**
     * Return all available background details.
     *
     * @return array $array Contains all background details
     *
     * @since 4.0.0
     */
    protected function getBackgroundDetails()
    {
        return [
            'position'  => [
                '0 0'           => Translate::t('Left top'),
                '50% 0'         => Translate::t('Center top'),
                '100% 0'        => Translate::t('Right top'),
                '0 50%'         => Translate::t('Left middle'),
                '50% 50%'       => Translate::t('Center middle'),
                '100% 50%'      => Translate::t('Right middle'),
                '0 100%'        => Translate::t('Left bottom'),
                '50% 100%'      => Translate::t('Center bottom'),
                '100% 100%'     => Translate::t('Right bottom'),
            ],
            'repeat'    => [
                'no-repeat'     => Translate::t('Displayed only once.'),
                'repeat-x'      => Translate::t('Repeated horizontally only.'),
                'repeat-y'      => Translate::t('Repeated vertically only.'),
                'repeat'        => Translate::t('Repeated all the way.'),
            ],
            'size'      => [
                'auto'          => [
                    'times',
                    Translate::t('Normal state')
                ],
                'cover'         => [
                    'arrows-h',
                    Translate::t('As large as possible')
                ],
                'contain'       => [
                    'arrows',
                    Translate::t('Width and height can fit inside the content area')
                ],
            ],
        ];
    }
}
