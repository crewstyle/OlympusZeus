<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Link field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Link
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/link
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
     * @since 4.0.0
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
            'title' => isset($content['title']) ? $content['title'] : OlympusZeus::translate('Link'),
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
            't_add_link' => OlympusZeus::translate('Add link'),
            't_delete_all' => OlympusZeus::translate('Delete all links'),
            't_relationship' => OlympusZeus::translate('Relationship'),
            't_relationship_description' => OlympusZeus::translate('You can set the <code>nofollow</code> value 
                to avoid bots following the linked document.'),
            't_target' => OlympusZeus::translate('Target'),
            't_target_blank' => OlympusZeus::translate('Opens the linked document in a new window or tab'),
            't_target_self' => OlympusZeus::translate('Opens the linked document in the same frame as it was clicked'),
            't_target_parent' => OlympusZeus::translate('Opens the linked document in the parent frame'),
            't_target_top' => OlympusZeus::translate('Opens the linked document in the full body of the window'),
            't_title' => OlympusZeus::translate('Title'),
            't_website_address' => OlympusZeus::translate('Web address'),
            't_website_placeholder' => OlympusZeus::translate('http://'),
            't_goto' => OlympusZeus::translate('Go to website'),
        );

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);
        $template['val'] = isset($template['val']['url']) ? array($template['val']) : $template['val'];

        //Get template
        return $this->renderField('fields/link.html.twig', $template);
    }
}
