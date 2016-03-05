<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Include field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Include
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-include
 *
 */

class Include extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-file-code-o';

    /**
     * @var boolean
     */
    protected $hasId = false;

    /**
     * @var string
     */
    protected $template = 'fields/include.html.twig';

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
            'title' => Translate::t('Include'),
            'file' => false,
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Update vars
        $this->getField()->setVars($vars);
    }
}
