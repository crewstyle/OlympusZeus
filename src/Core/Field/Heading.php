<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Heading field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Heading
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/heading
 *
 */

class Heading extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-header';

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
            'title' => isset($content['title']) ? $content['title'] : OlympusZeus::translate('Heading'),
            'level' => isset($content['level']) ? $content['level'] : 2,
            'style' => isset($content['style']) ? ' style="' . $content['style'] . '"' : '',
        );

        //Get template
        return $this->renderField('fields/heading.html.twig', $template);
    }
}
