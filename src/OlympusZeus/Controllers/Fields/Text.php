<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Text field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Text
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-text
 *
 */

class Text extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-text-width';

    /**
     * @var string
     */
    protected $template = 'fields/text.html.twig';

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
            'title' => Translate::t('Text'),
            'default' => '',
            'description' => '',
            'options' => [
                'type' => 'text',
                'min' => '',
                'max' => '',
                'step' => '',
            ],

            //options
            'attrs' => '',
            'placeholder' => '',
            'maxlength' => '',

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id']);

        //Attributes
        $vars['attrs'] = 'size="30"';
        $vars['attrs'] .= !empty($vars['placeholder']) ? ' placeholder="'.$vars['placeholder'].'"' : '';
        $vars['attrs'] .= !empty($vars['maxlength']) ? ' maxlength="'.$vars['maxlength'].'"' : '';

        //Check type
        $type = 'text' !== $vars['options']['type'] ? $vars['options']['type'] : 'text';

        //Check options
        if ('number' == $type || 'range' == $type) {
            $vars['type'] = $type;

            //Special variables
            $vars['attrs'] .= !empty($vars['options']['min']) 
                ? ' min="'.$vars['options']['min'].'"' 
                : '';
            $vars['attrs'] .= !empty($vars['options']['max']) 
                ? ' max="'.$vars['options']['max'].'"' 
                : '';
            $vars['attrs'] .= !empty($vars['options']['step']) 
                ? ' step="'.$vars['options']['step'].'"' 
                : ' step="1"';
        }

        //Update vars
        $this->getField()->setVars($vars);
    }
}
