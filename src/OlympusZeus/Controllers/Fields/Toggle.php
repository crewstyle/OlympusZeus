<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Toggle field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Toggle
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-toggle
 *
 */

class Toggle extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-toggle-off';

    /**
     * @var string
     */
    protected $template = 'fields/toggle.html.twig';

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
            'title' => Translate::t('Toggle'),
            'default' => false,
            'description' => '',
            'enable' => '',
            'disable' => '',

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',

            //texts
            't_on' => Translate::t('On'),
            't_off' => Translate::t('Off'),
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id']);

        //Update vars
        $this->getField()->setVars($vars);
    }
}
