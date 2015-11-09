<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Rte;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO RTE FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'rte',
 *     'title' => 'RTE',
 *     'id' => 'simple_rte',
 *     'default' => 'Do EEEEEEEEEEEEverything you want...',
 *     'description' => 'But not so much ! :('
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Rte
 *
 * Class used to build Rte field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Rte
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Rte extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-clipboard';

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea RTE'),
            'default' => isset($content['default']) ? $content['default'] : '',
            'description' => isset($content['description']) ? $content['description'] : '',
            'settings' => array(
                'teeny' => false,
                'textarea_rows' => 8,
            ),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,
        );

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id']);

        //Get template
        return $this->renderField('fields/rte.html.twig', $template);
    }
}
