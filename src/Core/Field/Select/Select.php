<?php

namespace crewstyle\TeaThemeOptions\Core\Field\Select;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Field\Field;

/**
 * TTO SELECT FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'select',
 *     'title' => 'Prove it: what do they mean by "Bapouet"?',
 *     'id' => 'my_select_field_id',
 *     'default' => '',
 *     'description' => "Don't cheat: the movie is NOT the solution :)",
 *     'options' => array(
 *         'toy' => 'A simple toy',
 *         'milk' => 'Just milk',
 *         'unicorn-toy' => 'A unicorn toy... Very stupid :p',
 *         'tails' => 'A red fox with a tiny cute fire tail with his blue faster hedgedog friend'
 *     )
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Select
 *
 * Class used to build Select field.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\Select
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Select extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-list';

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Select'),
            'description' => isset($content['description']) ? $content['description'] : '',
            'default' => isset($content['default']) ? $content['default'] : '',
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
        return $this->renderField('fields/select.html.twig', $template);
    }
}
