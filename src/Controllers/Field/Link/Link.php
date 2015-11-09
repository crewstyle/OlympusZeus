<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Link;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO LINK FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'link',
 *     'title' => 'Never gonna give you up!',
 *     'id' => 'my_link_field_id',
 *     'description' => 'Never gonna give you up!',
 *     'expandable' => false,
 *     'default' => array(
 *         'url' => "http://www.youtube.com/watch?v=BROWqjuTM0g",
 *         'label' => 'Never gonna get you down!', //optional, display link instead
 *         'target' => '_blank', //optional, "_self" by default
 *         'rel' => 'nofollow', //optional, "external" by default
 *     ),
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Link
 *
 * Class used to build Link field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Link
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Link extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-link';

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Link'),
            'description' => isset($content['description']) ? $content['description'] : '',
            'default' => isset($content['default']) ? $content['default'] : array(),
            'expandable' => isset($content['expandable']) && is_bool($content['expandable']) 
                ? $content['expandable'] 
                : false,

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_add_link' => TeaThemeOptions::__('Add link'),
            't_delete_all' => TeaThemeOptions::__('Delete all links'),
            't_relationship' => TeaThemeOptions::__('Relationship'),
            't_relationship_description' => TeaThemeOptions::__('You can set the <code>nofollow</code> value 
                to avoid bots following the linked document.'),
            't_target' => TeaThemeOptions::__('Target'),
            't_target_blank' => TeaThemeOptions::__('Opens the linked document in a new window or tab'),
            't_target_self' => TeaThemeOptions::__('Opens the linked document in the same frame as it was clicked'),
            't_target_parent' => TeaThemeOptions::__('Opens the linked document in the parent frame'),
            't_target_top' => TeaThemeOptions::__('Opens the linked document in the full body of the window'),
            't_title' => TeaThemeOptions::__('Title'),
            't_website_address' => TeaThemeOptions::__('Web address'),
            't_website_placeholder' => TeaThemeOptions::__('http://'),
            't_goto' => TeaThemeOptions::__('Go to website'),
        );

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);
        $template['val'] = isset($template['val']['url']) ? array($template['val']) : $template['val'];

        //Get template
        return $this->renderField('fields/link.html.twig', $template);
    }
}
