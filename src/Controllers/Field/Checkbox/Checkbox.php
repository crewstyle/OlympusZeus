<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Checkbox;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO CHECKBOX FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'checkbox',
 *     'title' => 'What are your preferred personas?',
 *     'id' => 'my_checkbox_field_id',
 *     'default' => array('minions', 'lapinscretins'), //define the default choice(s)
 *     'mode' => 'image', //if image mode selected, the options must be URLs
 *     'description' => '',
 *     
 *     //define your options
 *     'options' => array(
 *         'minions' => 'The Minions', //value => label
 *         'lapinscretins' => 'The Lapins Crétins',
 *         'marvel' => 'All Marvel Superheroes',
 *         'franklin' => 'Franklin (everything is possible)',
 *         'spongebob' => 'Spongebob (nothing to say... Love it!)'
 *     )
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Checkbox
 *
 * Class used to build Checkbox field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Checkbox
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Checkbox extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-check-square';

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Checkbox'),
            'description' => isset($content['description']) ? $content['description'] : '',
            'default' => isset($content['default']) ? $content['default'] : array(),
            'mode' => isset($content['mode']) && 'image' == $content['mode'] ? 'image' : '',
            'options' => isset($content['options']) ? $content['options'] : array(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_check_all' => TeaThemeOptions::__('Un/select all options'),
            't_no_options' => TeaThemeOptions::__('Something went wrong in your parameters definition: 
                no options have been defined.'),
        );

        //Options
        $template['count'] = count($template['options']);

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);

        //Get template
        return $this->renderField('fields/checkbox.html.twig', $template);
    }
}
