<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Textarea field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Textarea
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-textarea
 *
 */

class Textarea extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-text-height';

    /**
     * @var string
     */
    protected $template = 'fields/textarea.html.twig';

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
            'title' => Translate::t('Textarea'),
            'default' => '',
            'description' => '',
            'placeholder' => '',
            'rows' => 8,

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id']);

        //Update vars
        $this->getField()->setVars($vars);
    }
}
