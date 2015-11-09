<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Radio;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO RADIO FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'radio',
 *     'title' => 'Ok ok... But what is your favorite?',
 *     'id' => 'my_radio_field_id',
 *     'default' => 'minions',
 *     'mode' => 'image', //if image mode selected, the options must be URLs
 *     'description' => '- "Bapouet?" - "Na na na, baapouet!" - "AAAAAAAAAA Bapoueeeeettttt!!!!"',
 *     
 *     //define your options
 *     'options' => array(
 *         'minions' => 'The Minions',
 *         'lapinscretins' => 'The Lapins Crétins'
 *     )
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Radio
 *
 * Class used to build Radio field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Radio
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Radio extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-dot-circle-o';

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Radio'),
            'description' => isset($content['description']) ? $content['description'] : '',
            'default' => isset($content['default']) ? $content['default'] : '',
            'mode' => isset($content['mode']) && 'image' == $content['mode'] ? 'image' : '',
            'options' => isset($content['options']) ? $content['options'] : array(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_no_options' => TeaThemeOptions::__('Something went wrong in your parameters definition: 
                no options have been defined.'),
        );

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id']);

        //Get template
        return $this->renderField('fields/radio.html.twig', $template);
    }
}
