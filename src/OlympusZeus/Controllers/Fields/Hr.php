<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;

/**
 * Builds Hr field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Hr
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-hr
 *
 */

class Hr extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-minus';

    /**
     * @var boolean
     */
    protected $hasId = false;

    /**
     * @var string
     */
    protected $template = 'fields/hr.html.twig';

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

        //Update vars
        $this->getField()->setVars([]);
    }
}
