<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Textarea;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO TEXTAREA FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'textarea',
 *     'title' => 'How do Penguins drink their cola?',
 *     'id' => 'my_textarea_field_id',
 *     'default' => 'On the rocks.',
 *     'placeholder' => 'Tell us how?',
 *     'description' => 'A simple question to know if you will be able to survive to the Penguin domination.'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Textarea
 *
 * Class used to build Textarea field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Textarea
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Textarea extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-text-height';

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Textarea'),
            'default' => isset($content['default']) ? $content['default'] : '',
            'placeholder' => isset($content['placeholder']) ? ' placeholder="' . $content['placeholder'] . '"' : '',
            'description' => isset($content['description']) ? $content['description'] : '',
            'rows' => isset($content['rows']) ? $content['rows'] : '8',

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,
        );

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id']);

        //Get template
        return $this->renderField('fields/textarea.html.twig', $template);
    }
}
