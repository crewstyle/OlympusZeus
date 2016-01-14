<?php

namespace crewstyle\OlympusZeus\Core\Field;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;

/**
 * Builds Hidden field.
 *
 * @package Olympus Zeus
 * @subpackage Core\Field\Hidden
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 * @see https://olympus.readme.io/docs/hidden
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
            OlympusZeus::translate('Hidden field <code><b>%s</b></code> with value stored: <code>%s</code>'),
            $content['id'],
            $template['val']
        );

        //Get template
        return $this->renderField('fields/hidden.html.twig', $template);
    }
}
