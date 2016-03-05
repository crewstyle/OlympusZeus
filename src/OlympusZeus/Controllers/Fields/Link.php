<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Link field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Link
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-link
 *
 */

class Link extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-link';

    /**
     * @var string
     */
    protected $template = 'fields/link.html.twig';

    /**
     * Prepare HTML component.
     *
     * @param array $content
     * @param array $details
     *
     * @since 5.0.0
     */
    protected function getVars($content, $details = [])
    {
        //Build defaults
        $defaults = [
            'id' => '',
            'title' => Translate::t('Link'),
            'default' => [],
            'description' => '',
            'expandable' => false,

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',

            //texts
            't_add_link' => Translate::t('Add link'),
            't_delete_all' => Translate::t('Delete all links'),
            't_relationship' => Translate::t('Relationship'),
            't_relationship_description' => Translate::t('You can set the <code>nofollow</code> value 
                to avoid bots following the linked document.'),
            't_target' => Translate::t('Target'),
            't_target_blank' => Translate::t('Opens the linked document in a new window or tab'),
            't_target_self' => Translate::t('Opens the linked document in the same frame as it was clicked'),
            't_target_parent' => Translate::t('Opens the linked document in the parent frame'),
            't_target_top' => Translate::t('Opens the linked document in the full body of the window'),
            't_title' => Translate::t('Title'),
            't_website_address' => Translate::t('Web address'),
            't_website_placeholder' => Translate::t('http://'),
            't_goto' => Translate::t('Go to website'),
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id'], true);
        $vars['val'] = is_array($vars['val']) ? $vars['val'] : [$template['val']];

        //Update vars
        $this->getField()->setVars($vars);
    }
}
