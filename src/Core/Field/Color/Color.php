<?php

namespace crewstyle\TeaThemeOptions\Core\Field\Color;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Field\Field;

/**
 * TTO COLOR FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'color',
 *     'title' => 'What is your favorite Coke?',
 *     'id' => 'my_color_field_id',
 *     'default' => '#000000',
 *     'description' => 'Do not choose the Coke Zero, right? ;)'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Color
 *
 * Class used to build Color field.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\Code
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Color extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-tint';

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
        //Build details
        $post = isset($details['post']) ? $details['post'] : 0;
        $prefix = isset($details['prefix']) ? $details['prefix'] : '';
        $tpl = empty($prefix) ? 'pages' : 'terms';

        //Build defaults data
        $template = array(
            'id' => $content['id'],
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Color'),
            'default' => isset($content['default']) ? $content['default'] : '',
            'description' => isset($content['description']) ? $content['description'] : '',

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,
        );

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id']);

        //Get template
        return $this->renderField('fields/color.html.twig', $template);
    }
}
