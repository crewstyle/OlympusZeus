<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Select field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Select
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-select
 *
 */

class Select extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-list';

    /**
     * @var string
     */
    protected $template = 'fields/select.html.twig';

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
            'title' => Translate::t('Select'),
            'default' => '',
            'description' => '',
            'options' => [],

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',

            //texts
            't_no_options' => Translate::t('Something went wrong in your parameters definition: 
                no options have been defined.'),
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id']);

        //Update vars
        $this->getField()->setVars($vars);
    }
}
