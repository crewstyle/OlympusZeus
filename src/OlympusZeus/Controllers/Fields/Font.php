<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Font field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Font
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-font
 *
 */

class Font extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-font';

    /**
     * @var string
     */
    protected $template = 'fields/font.html.twig';

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
            'title' => Translate::t('Font'),
            'default' => '',
            'description' => '',
            'fonts' => false,

            //options
            'options' => $this->getFontGoogle(),
            'styles' => '',
            'css' => '',

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id'], true);

        //Update vars
        $this->getField()->setVars($vars);
    }

    /**
     * Return all available Google fonts.
     *
     * @return array $fonts
     *
     * @since 5.0.0
     */
    protected function getFontGoogle()
    {
        return [
            'sans_serif'    => [
                'name'  => 'sansserif',
                'title' => 'Sans serif',
                'sizes' => '',
            ],
            'arvo'          => [
                'name'  => 'Arvo',
                'title' => 'Arvo',
                'sizes' => '400,700',
            ],
            'bree_serif'    => [
                'name'  => 'Bree+Serif',
                'title' => 'Bree Serif',
                'sizes' => '400',
            ],
            'cabin'         => [
                'name'  => 'Cabin',
                'title' => 'Cabin',
                'sizes' => '400,500,600,700',
            ],
            'cantarell'     => [
                'name'  => 'Cantarell',
                'title' => 'Cantarell',
                'sizes' => '400,700',
            ],
            'copse'         => [
                'name'  => 'Copse',
                'title' => 'Copse',
                'sizes' => '400',
            ],
            'cuprum'        => [
                'name'  => 'Cuprum',
                'title' => 'Cuprum',
                'sizes' => '400,700',
            ],
            'droid_sans'    => [
                'name'  => 'Droid+Sans',
                'title' => 'Droid Sans',
                'sizes' => '400,700',
            ],
            'lobster_two'   => [
                'name'  => 'Lobster+Two',
                'title' => 'Lobster Two',
                'sizes' => '400,700',
            ],
            'open_sans'     => [
                'name'  => 'Open+Sans',
                'title' => 'Open Sans',
                'sizes' => '300,400,600,700,800',
            ],
            'oswald'        => [
                'name'  => 'Oswald',
                'title' => 'Oswald',
                'sizes' => '300,400,700',
            ],
            'pacifico'      => [
                'name'  => 'Pacifico',
                'title' => 'Pacifico',
                'sizes' => '400',
            ],
            'patua_one'     => [
                'name'  => 'Patua+One',
                'title' => 'Patua One',
                'sizes' => '400',
            ],
            'pt_sans'       => [
                'name'  => 'PT+Sans',
                'title' => 'PT Sans',
                'sizes' => '400,700',
            ],
            'puritan'       => [
                'name'  => 'Puritan',
                'title' => 'Puritan',
                'sizes' => '400,700',
            ],
            'qwigley'       => [
                'name'  => 'Qwigley',
                'title' => 'Qwigley',
                'sizes' => '400',
            ],
            'titillium_web' => [
                'name'  => 'Titillium+Web',
                'title' => 'Titillium Web',
                'sizes' => '200,300,400,600,700,900',
            ],
            'vollkorn'      => [
                'name'  => 'Vollkorn',
                'title' => 'Vollkorn',
                'sizes' => '400,700',
            ],
        ];
    }
}
