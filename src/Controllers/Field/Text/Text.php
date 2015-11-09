<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Text;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO TEXT FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'text',
 *     'title' => 'What do you like?',
 *     'id' => 'my_text_field_id',
 *     'default' => "Penguins, I am sure they're gonna dominate the World!",
 *     'placeholder' => "McDonald's as well",
 *     'description' => 'Put in here everything you want.',
 *     'maxlength' => 120
 * )
 *
 * Or if you need to add somes details...
 * array(
 *     'type' => 'text',
 *     'title' => 'How much do you like Penguins?',
 *     'id' => 'my_text_field_id',
 *     'default' => 100,
 *     'placeholder' => '50',
 *     'description' => 'Tell us how much do like Penguins to have a chance to get into our private Penguins community ;)',
 *     'options' => array(
 *         'type' => 'number',
 *         'min' => 10,
 *         'max' => 100,
 *         'step' => 1
 *     )
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Text
 *
 * Class used to build Text field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Text
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Text extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-text-width';

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Text'),
            'default' => isset($content['default']) ? $content['default'] : '',
            'description' => isset($content['description']) ? $content['description'] : '',
            'options' => isset($content['options']) ? $content['options'] : array(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,
        );

        //Special variables
        $template['type'] = 'text';
        $template['min'] = '';
        $template['max'] = '';
        $template['step'] = '';

        //Attributes
        $template['attributes'] = 'size="30"';
        $template['attributes'] .= isset($content['placeholder']) ? ' placeholder="'.$content['placeholder'].'"' : '';
        $template['attributes'] .= isset($content['maxlength']) ? ' maxlength="'.$content['maxlength'].'"' : '';

        //Check type
        $type = isset($template['options']['type']) ? $template['options']['type'] : 'text';

        //Check options
        if ('number' == $type || 'range' == $type) {
            $template['type'] = $type;

            //Special variables
            $template['attributes'] .= isset($template['options']['min']) 
                ? ' min="'.$template['options']['min'].'"' 
                : '';
            $template['attributes'] .= isset($template['options']['max']) 
                ? ' max="'.$template['options']['max'].'"' 
                : '';
            $template['attributes'] .= isset($template['options']['step']) 
                ? ' step="'.$template['options']['step'].'"' 
                : ' step="1"';
        }

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id']);

        //Get template
        return $this->renderField('fields/text.html.twig', $template);
    }
}
