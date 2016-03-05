<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Hidden field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Hidden
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-hidden
 *
 */

class Hidden extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-eye-slash';

    /**
     * @var string
     */
    protected $template = 'fields/hidden.html.twig';

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
            'default' => '',
            'description' => '',

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id'], true);

        //Get description
        $vars['description'] = sprintf(
            Translate::t('Hidden field <code><b>%s</b></code> with value stored: <code>%s</code>'),
            $content['id'],
            $vars['val']
        );

        //Update vars
        $this->getField()->setVars($vars);
    }
}
