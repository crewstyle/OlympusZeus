<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Checkbox field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Checkbox
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/checkbox
 *
 */

class Checkbox extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-check-square';

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
            'title' => isset($content['title']) ? $content['title'] : OlympusZeus::translate('Checkbox'),
            'description' => isset($content['description']) ? $content['description'] : '',
            'default' => isset($content['default']) ? $content['default'] : array(),
            'mode' => isset($content['mode']) && 'image' == $content['mode'] ? 'image' : '',
            'options' => isset($content['options']) ? $content['options'] : array(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,

            //texts
            't_check_all' => OlympusZeus::translate('Un/select all options'),
            't_no_options' => OlympusZeus::translate('Something went wrong in your parameters definition: 
                no options have been defined.'),
        );

        //Options
        $template['count'] = count($template['options']);

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);

        //Get template
        return $this->renderField('fields/checkbox.html.twig', $template);
    }
}
