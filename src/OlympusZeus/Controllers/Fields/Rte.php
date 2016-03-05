<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Rte field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Rte
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-rte
 *
 */

class Rte extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-clipboard';

    /**
     * @var string
     */
    protected $template = 'fields/rte.html.twig';

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
            'title' => Translate::t('Rich Text Editor'),
            'default' => '',
            'description' => '',
            'settings' => [
                'teeny' => false,
                'textarea_rows' => 8,
            ],

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
