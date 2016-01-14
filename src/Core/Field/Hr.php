<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Hr field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Hr
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/hr
 *
 */

class Hr extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-minus';

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
        //Get template
        return $this->renderField('fields/hr.html.twig', array());
    }
}
