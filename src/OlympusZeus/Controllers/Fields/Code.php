<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Code field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Code
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-code
 *
 */

class Code extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-code';

    /**
     * @var string
     */
    protected $template = 'fields/code.html.twig';

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
            'title' => Translate::t('Code'),
            'description' => '',
            'change' => true,
            'readonly' => false,
            'rows' => 4,
            'default' => [],
            'modes' => $this->getCodeModes(),

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',

            //defaults
            'default' => [
                'mode' => 'application/json',
                'code' => ''
            ],
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);
        $vars['default']['mode'] = $this->retrieveMode($vars['default']['mode']);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id'], true);

        //Update vars
        $this->getField()->setVars($vars);
    }

    /**
     * Return all available modes.
     *
     * @return array $array
     *
     * @since 5.0.0
     */
    protected function getCodeModes()
    {
        //List all modes
        $modes = $this->getModes();
        $codemodes = [];

        //Build select
        foreach ($modes as $mode) {
            $codemodes[$mode['mode'][0]] = $mode['title'];
        }

        return $codemodes;
    }

    /**
     * Return all available modes.
     *
     * @return array $array
     *
     * @since 5.0.0
     */
    protected function getModes()
    {
        return [
            [
                'title' => Translate::t('CSS'),
                'mode' => ['text/css', 'css'],
            ],
            [
                'title' => Translate::t('Diff'),
                'mode' => ['text/x-diff','x-diff','diff'],
            ],
            [
                'title' => Translate::t('HTML Mixed'),
                'mode' => ['text/html','html'],
            ],
            [
                'title' => Translate::t('JavaScript'),
                'mode' => ['text/javascript','javascript','js'],
            ],
            [
                'title' => Translate::t('JSON'),
                'mode' => ['application/json','json'],
            ],
            [
                'title' => Translate::t('Markdown'),
                'mode' => ['text/x-markdown','markdown','md'],
            ],
            [
                'title' => Translate::t('PHP'),
                'mode' => ['application/x-httpd-php','x-httpd-php','php'],
            ],
            [
                'title' => Translate::t('Python'),
                'mode' => ['text/x-python','x-python','python'],
            ],
            [
                'title' => Translate::t('Ruby'),
                'mode' => ['text/x-ruby','x-ruby','ruby'],
            ],
            [
                'title' => Translate::t('Shell'),
                'mode' => ['text/x-sh','x-sh','sh'],
            ],
            [
                'title' => Translate::t('MySQL'),
                'mode' => ['text/x-mysql','x-mysql','mysql'],
            ],
            [
                'title' => Translate::t('MariaDB'),
                'mode' => ['text/x-mariadb','x-mariadb','mariadb'],
            ],
            [
                'title' => Translate::t('XML'),
                'mode' => ['application/xml','xml'],
            ],
            [
                'title' => Translate::t('YAML'),
                'mode' => ['text/x-yaml','x-yaml','yaml'],
            ],
        ];
    }

    /**
     * Return all available modes.
     *
     * @param string $search Mode we are looking for
     * @return string $good Real mode name
     *
     * @since 5.0.0
     */
    protected function retrieveMode($search)
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
