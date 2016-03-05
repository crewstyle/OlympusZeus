<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Checkbox field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Checkbox
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-checkbox
 *
 */

class Checkbox extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-check-square';

    /**
     * @var string
     */
    protected $template = 'fields/checkbox.html.twig';

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
            'title' => Translate::t('Checkbox'),
            'description' => '',
            'default' => [],
            'mode' => '',
            'options' => [],

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',

            //texts
            't_check_all' => Translate::t('Un/select all options'),
            't_no_options' => Translate::t('Something went wrong in your parameters definition: 
                no options have been defined.'),
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);
        $vars['count'] = count($vars['options']);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id'], true);

        //Update vars
        $this->getField()->setVars($vars);
    }
}
