<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds P field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\P
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-paragraph
 *
 */

class P extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-paragraph';

    /**
     * @var boolean
     */
    protected $hasId = false;

    /**
     * @var string
     */
    protected $template = 'fields/paragraph.html.twig';

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
            'content' => '',
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Update vars
        $this->getField()->setVars($vars);
    }
}
