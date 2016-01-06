<?php

namespace crewstyle\TeaThemeOptions\Core\Field\Toggle;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Field\Field;

/**
 * TTO TOGGLE FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'toggle',
 *     'title' => 'Enable bot mode?',
 *     'id' => 'my_toggle_field_id',
 *     'default' => false, //define the default state
 *     'enable' => 'Yes, enable it!', //Optional
 *     'disable' => 'No no no no no no no', //Optional
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Toggle
 *
 * Class used to build Toggle field.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\Toggle
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Toggle extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-toggle-off';

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Toggle'),
            'description' => isset($content['description']) ? $content['description'] : '',
            'default' => isset($content['default']) ? $content['default'] : false,
            'enable' => isset($content['enable']) ? $content['enable'] : '',
            'disable' => isset($content['disable']) ? $content['disable'] : '',

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_on' => TeaThemeOptions::__('On'),
            't_off' => TeaThemeOptions::__('Off'),
        );

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id']);

        //Get template
        return $this->renderField('fields/toggle.html.twig', $template);
    }
}
