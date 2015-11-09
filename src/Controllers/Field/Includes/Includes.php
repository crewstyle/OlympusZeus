<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Includes;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO INCLUDES FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'includes',
 *     'title' => 'No more jokes...',
 *     'file' => 'my_php_file_path.php'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Includes
 *
 * Class used to build Include field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Includes
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Includes extends Field
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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Include'),
            'file' => isset($content['file']) ? $content['file'] : false,
        );

        //Get template
        return $this->renderField('fields/includes.html.twig', $template);
    }
}
