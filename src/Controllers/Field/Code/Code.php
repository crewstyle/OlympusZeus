<?php

namespace crewstyle\TeaThemeOptions\Controllers\Field\Code;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;

/**
 * TTO CODE FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'Code',
 *     'title' => 'How do Penguins code their icebergs?',
 *     'id' => 'my_code_field_id',
 *     'change' => true,
 *     'readonly' => false,
 *     'default' => array(
 *         'mode' => 'text/css',
 *         'code' => 'With.a.frozen.bug',
 *     ),
 *     'rows' => 4,
 *     'description' => 'A simple question to know if you know how to seduce a penguin.'
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Code
 *
 * Class used to build Code field.
 *
 * @package Tea Theme Options
 * @subpackage Controllers\Field\Code
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.0.0
 *
 */
class Code extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-code';

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Code'),
            'description' => isset($content['description']) ? $content['description'] : '',
            'change' => isset($content['change']) && !$content['change'] ? false : true,
            'readonly' => isset($content['readonly']) && $content['readonly'] ? true : false,
            'rows' => isset($content['rows']) ? $content['rows'] : 4,
            'default' => isset($content['default']) ? $content['default'] : array(),
            'modes' => $this->getCodeModes(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,
        );

        $template['default'] = array(
            'mode' => isset($template['default']['mode']) ? $this->retrieveMode($template['default']['mode']) : 'application/json',
            'code' => isset($template['default']['code']) ? $template['default']['code'] : ''
        );

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);

        //Get template
        return $this->renderField('fields/code.html.twig', $template);
    }

    /**
     * Return all available modes.
     *
     * @return array $array Contains all modes
     *
     * @since 3.0.0
     */
    public function getCodeModes()
    {
        //List all modes
        $modes = $this->getModes();
        $codemodes = array();

        //Build select
        foreach ($modes as $mode) {
            $codemodes[$mode['mode'][0]] = $mode['title'];
        }

        return $codemodes;
    }

    /**
     * Return all available modes.
     *
     * @return array $array Contains all modes
     *
     * @since 3.0.0
     */
    public function getModes()
    {
        return array(
            array(
                'title' => TeaThemeOptions::__('CSS'),
                'mode' => array('text/css', 'css'),
            ),
            array(
                'title' => TeaThemeOptions::__('Diff'),
                'mode' => array('text/x-diff','x-diff','diff'),
            ),
            array(
                'title' => TeaThemeOptions::__('HTML Mixed'),
                'mode' => array('text/html','html'),
            ),
            array(
                'title' => TeaThemeOptions::__('JavaScript'),
                'mode' => array('text/javascript','javascript','js'),
            ),
            array(
                'title' => TeaThemeOptions::__('JSON'),
                'mode' => array('application/json','json'),
            ),
            array(
                'title' => TeaThemeOptions::__('Markdown'),
                'mode' => array('text/x-markdown','markdown','md'),
            ),
            array(
                'title' => TeaThemeOptions::__('PHP'),
                'mode' => array('application/x-httpd-php','x-httpd-php','php'),
            ),
            array(
                'title' => TeaThemeOptions::__('Python'),
                'mode' => array('text/x-python','x-python','python'),
            ),
            array(
                'title' => TeaThemeOptions::__('Ruby'),
                'mode' => array('text/x-ruby','x-ruby','ruby'),
            ),
            array(
                'title' => TeaThemeOptions::__('Shell'),
                'mode' => array('text/x-sh','x-sh','sh'),
            ),
            array(
                'title' => TeaThemeOptions::__('MySQL'),
                'mode' => array('text/x-mysql','x-mysql','mysql'),
            ),
            array(
                'title' => TeaThemeOptions::__('MariaDB'),
                'mode' => array('text/x-mariadb','x-mariadb','mariadb'),
            ),
            array(
                'title' => TeaThemeOptions::__('XML'),
                'mode' => array('application/xml','xml'),
            ),
            array(
                'title' => TeaThemeOptions::__('YAML'),
                'mode' => array('text/x-yaml','x-yaml','yaml'),
            ),
        );
    }

    /**
     * Return all available modes.
     *
     * @param string $search Mode we are looking for
     * @return string $good Real mode name
     *
     * @since 3.0.0
     */
    public function retrieveMode($search)
    {
        //List all modes
        $modes = $this->getModes();

        //Build select
        foreach ($modes as $mode) {
            if (in_array($search, $mode['mode'])) {
                return $mode['mode'][0];
            }
        }

        return 'application/json';
    }
}
