<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Include field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Includes
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/includes
 *
 */

class Includes extends Field
{
    /**
     * @var boolean
     */
    protected static $hasId = false;

    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct(){}

    /**
     * Display HTML component.
     *
     * @param array $content Contains all field data
     * @param array $details Contains all field options
     *
     * @since 4.0.0
     */
    public function prepareField($content, $details = array())
    {
        //Build defaults data
        $template = array(
            'title' => isset($content['title']) ? $content['title'] : OlympusZeus::translate('Include'),
            'file' => isset($content['file']) ? $content['file'] : false,
        );

        //Get template
        return $this->renderField('fields/includes.html.twig', $template);
    }
}
