<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Multiselect;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO MULTISELECT FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'multiselect',
 *     'title' => 'Select the Minions that you may know',
 *     'id' => 'my_multiselect_field_id',
 *     'default' => '',
 *     'description' => 'Pay attention to this question ;)',
 *     'options' => array(
 *         'henry' => 'Henry',
 *         'jacques' => 'Jacques',
 *         'kevin' => 'Kevin',
 *         'tom' => 'Tom'
 *     )
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Multiselect
 *
 * Class used to build Multiselect field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Multiselect
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Multiselect extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-th-list';

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Multiselect'),
            'description' => isset($content['description']) ? $content['description'] : '',
            'default' => isset($content['default']) ? $content['default'] : array(),
            'options' => isset($content['options']) ? $content['options'] : array(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_keyboard' => TeaThemeOptions::__('Press the <kbd>CTRL</kbd> or <kbd>CMD</kbd> button 
                to select more than one option.'),
            't_no_options' => TeaThemeOptions::__('Something went wrong in your parameters definition: 
                no options have been defined.'),
        );

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);

        //Get template
        return $this->renderField('fields/multiselect.html.twig', $template);
    }
}
