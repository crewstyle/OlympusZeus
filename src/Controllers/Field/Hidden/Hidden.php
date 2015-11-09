<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Hidden;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO HIDDEN FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'hidden',
 *     'id' => 'my_hidden_field_id',
 *     'default' => 'Haha I will dominate the World!!! MOUAHAHAHAHAHA - Crazy Penguin'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Hidden
 *
 * Class used to build Hidden field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Hidden
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Hidden extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-eye-slash';

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
            'default' => isset($content['default']) ? $content['default'] : '',

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,
        );

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id']);

        //Get description
        $template['description'] = sprintf(
            TeaThemeOptions::__('Hidden field <code><b>%s</b></code> with value stored: <code>%s</code>'),
            $content['id'],
            $template['val']
        );

        //Get template
        return $this->renderField('fields/hidden.html.twig', $template);
    }
}
