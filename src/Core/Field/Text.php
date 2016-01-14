<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Text field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Text
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/text
 *
 */

class Text extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-text-width';

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
            'title' => isset($content['title']) ? $content['title'] : OlympusZeus::translate('Text'),
            'default' => isset($content['default']) ? $content['default'] : '',
            'description' => isset($content['description']) ? $content['description'] : '',
            'options' => isset($content['options']) ? $content['options'] : array(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,
        );

        //Special variables
        $template['type'] = 'text';
        $template['min'] = '';
        $template['max'] = '';
        $template['step'] = '';

        //Attributes
        $template['attributes'] = 'size="30"';
        $template['attributes'] .= isset($content['placeholder']) ? ' placeholder="'.$content['placeholder'].'"' : '';
        $template['attributes'] .= isset($content['maxlength']) ? ' maxlength="'.$content['maxlength'].'"' : '';

        //Check type
        $type = isset($template['options']['type']) ? $template['options']['type'] : 'text';

        //Check options
        if ('number' == $type || 'range' == $type) {
            $template['type'] = $type;

            //Special variables
            $template['attributes'] .= isset($template['options']['min']) 
                ? ' min="'.$template['options']['min'].'"' 
                : '';
            $template['attributes'] .= isset($template['options']['max']) 
                ? ' max="'.$template['options']['max'].'"' 
                : '';
            $template['attributes'] .= isset($template['options']['step']) 
                ? ' step="'.$template['options']['step'].'"' 
                : ' step="1"';
        }

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id']);

        //Get template
        return $this->renderField('fields/text.html.twig', $template);
    }
}
