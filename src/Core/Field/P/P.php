<?php

namespace crewstyle\TeaThemeOptions\Core\Field\P;

use crewstyle\TeaThemeOptions\Core\Field\Field;

/**
 * TTO P FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'p',
 *     'content' => 'No more jokes please...'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Paragraph
 *
 * Class used to build Paragraph field.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\P
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
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
            'content' => isset($content['content']) ? $content['content'] : ''
        );

        //Get template
        return $this->renderField('fields/p.html.twig', $template);
    }
}
