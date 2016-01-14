<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Date field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Date
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/date
 *
 */
class Date extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-clock-o';

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
            'title' => isset($content['title']) ? $content['title'] : OlympusZeus::translate('Date'),
            'default' => isset($content['default']) ? $content['default'] : '',
            'description' => isset($content['description']) ? $content['description'] : '',
            'format' => isset($content['format']) ? $content['format'] : 'mm.dd.yyyy',

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,
        );

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id']);

        //Get template
        return $this->renderField('fields/date.html.twig', $template);
    }
}
