<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Section field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Section
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-section
 *
 */

class Section extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-th-large';

    /**
     * @var boolean
     */
    protected $hasId = false;

    /**
     * @var boolean
     */
    protected $isAuthorized = false;

    /**
     * @var string
     */
    protected $template = 'fields/section.html.twig';

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
            'color' => 'white',
            'title' => '',
            'action' => [],
            'quote' => '',
            'contents' => [],
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Build contents
        if (!empty($content['contents'])) {
            foreach ($content['contents'] as $c) {
                $vars['contents'][] = [
                    'svg' => isset($c['svg']) ? file_get_contents($c['svg'], FILE_USE_INCLUDE_PATH) : '',
                    'text' => $c['text'],
                ];
            }
        }

        //Update vars
        $this->getField()->setVars($vars);
    }
}
