<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Heading;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO HEADING FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'heading',
 *     'title' => 'Take a tea, simply',
 *     'level' => 2,
 *     'style' => 'text-align:center'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Heading
 *
 * Class used to build Heading field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Heading
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
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
    protected $hasId = false;

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Heading'),
            'level' => isset($content['level']) ? $content['level'] : 2,
            'style' => isset($content['style']) ? ' style="' . $content['style'] . '"' : '',
        );

        //Get template
        return $this->renderField('fields/heading.html.twig', $template);
    }
}
