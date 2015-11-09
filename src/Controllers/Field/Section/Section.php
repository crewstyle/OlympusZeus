<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Section;

use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO SECTION FIELD
 *
 * You do not have to use it!
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Section
 *
 * Class used to build Section field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Section\Section
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Section extends Field
{
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
            'color' => isset($content['color']) ? $content['color'] : 'white',
            'identifier' => isset($content['identifier']) ? $content['identifier'] : '',
            'image' => isset($content['image']) ? $content['image'] : '',
            'svg' => isset($content['svg']) ? file_get_contents($content['svg'], FILE_USE_INCLUDE_PATH) : '',
            'content' => isset($content['content']) ? $content['content'] : '',
        );

        //Get template
        return $this->renderField('fields/section.html.twig', $template);
    }
}
