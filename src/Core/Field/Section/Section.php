<?php

namespace crewstyle\TeaThemeOptions\Core\Field\Section;

use crewstyle\TeaThemeOptions\Core\Field\Field;

/**
 * TTO SECTION FIELD
 *
 * You do not have to use it!
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Section
 *
 * Class used to build Section field.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\Section\Section
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Section extends Field
{
    /**
     * @var boolean
     */
    protected $hasId = false;

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
        //Build defaults data
        $template = array(
            'color' => isset($content['color']) ? $content['color'] : 'white',
            'title' => isset($content['title']) ? $content['title'] : '',
            'action' => isset($content['action']) ? $content['action'] : array(),
            'quote' => isset($content['quote']) ? $content['quote'] : '',
        );

        //Build contents
        if (!empty($content['contents'])) {
            $template['contents'] = array();

            //Iterate
            foreach ($content['contents'] as $c) {
                $template['contents'][] = array(
                    'svg' => isset($c['svg']) ? file_get_contents($c['svg'], FILE_USE_INCLUDE_PATH) : '',
                    'text' => $c['text'],
                );
            }
        }

        //Get template
        return $this->renderField('fields/section.html.twig', $template);
    }
}
