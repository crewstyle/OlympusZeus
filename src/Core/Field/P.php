<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Paragraph field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\P
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/p
 *
 */

class P extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-paragraph';

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
     * @since 3.0.0
     */
    public function prepareField($content, $details = array())
    {
        //Build defaults data
        $template = array(
            'content' => isset($content['content']) ? $content['content'] : ''
        );

        //Get template
        return $this->renderField('fields/p.html.twig', $template);
    }
}
