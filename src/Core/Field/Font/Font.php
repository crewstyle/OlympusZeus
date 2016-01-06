<?php

namespace crewstyle\TeaThemeOptions\Core\Field\Font;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Field\Field;

/**
 * TTO FONT FIELD
 *
 * To add this field, simply make the same as follow:
 * array(
 *     'type' => 'font',
 *     'title' => 'Choose your style',
 *     'id' => 'my_font_field_id',
 *     'default' => 'my_gorgeous_font',
 *     'description' => 'Tell us how to scribe :D',
 *     'fonts' => true,
 *     'options' => array(
 *         array(
 *             'name' => 'PT+Sans'
 *             'title' => 'PT Sans',
 *             'style' => 'pt_sans',
 *             'sizes' => '400,500',
 *         )
 *     )
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}

//----------------------------------------------------------------------------//

/**
 * TTO Field Font
 *
 * Class used to build Font field.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\Font
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Font extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-font';

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
            'title' => isset($content['title']) ? $content['title'] : TeaThemeOptions::__('Tea Font'),
            'default' => isset($content['default']) ? $content['default'] : '',
            'description' => isset($content['description']) ? $content['description'] : '',
            'fonts' => isset($content['fonts']) && $content['fonts'] ? true : false,
            'options' => array(),

            'styles' => '',
            'css' => '',

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,
        );

        //Get options
        if ($template['fonts']) {
            $template['options'] = $this->getFontGoogle();
        }

        //Retrieve field value
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id']);

        //Get template
        return $this->renderField('fields/font.html.twig', $template);
    }

    /**
     * Return all available Google fonts.
     *
     * @return array $array Contains all Google fonts
     *
     * @since 3.0.0
     */
    public function getFontGoogle()
    {
        return array(
            'sans_serif'    => array(
                'name'  => 'sansserif',
                'title' => 'Sans serif',
                'sizes' => '',
            ),
            'arvo'          => array(
                'name'  => 'Arvo',
                'title' => 'Arvo',
                'sizes' => '400,700',
            ),
            'bree_serif'    => array(
                'name'  => 'Bree+Serif',
                'title' => 'Bree Serif',
                'sizes' => '400',
            ),
            'cabin'         => array(
                'name'  => 'Cabin',
                'title' => 'Cabin',
                'sizes' => '400,500,600,700',
            ),
            'cantarell'     => array(
                'name'  => 'Cantarell',
                'title' => 'Cantarell',
                'sizes' => '400,700',
            ),
            'copse'         => array(
                'name'  => 'Copse',
                'title' => 'Copse',
                'sizes' => '400',
            ),
            'cuprum'        => array(
                'name'  => 'Cuprum',
                'title' => 'Cuprum',
                'sizes' => '400,700',
            ),
            'droid_sans'    => array(
                'name'  => 'Droid+Sans',
                'title' => 'Droid Sans',
                'sizes' => '400,700',
            ),
            'lobster_two'   => array(
                'name'  => 'Lobster+Two',
                'title' => 'Lobster Two',
                'sizes' => '400,700',
            ),
            'open_sans'     => array(
                'name'  => 'Open+Sans',
                'title' => 'Open Sans',
                'sizes' => '300,400,600,700,800',
            ),
            'oswald'        => array(
                'name'  => 'Oswald',
                'title' => 'Oswald',
                'sizes' => '300,400,700',
            ),
            'pacifico'      => array(
                'name'  => 'Pacifico',
                'title' => 'Pacifico',
                'sizes' => '400',
            ),
            'patua_one'     => array(
                'name'  => 'Patua+One',
                'title' => 'Patua One',
                'sizes' => '400',
            ),
            'pt_sans'       => array(
                'name'  => 'PT+Sans',
                'title' => 'PT Sans',
                'sizes' => '400,700',
            ),
            'puritan'       => array(
                'name'  => 'Puritan',
                'title' => 'Puritan',
                'sizes' => '400,700',
            ),
            'qwigley'       => array(
                'name'  => 'Qwigley',
                'title' => 'Qwigley',
                'sizes' => '400',
            ),
            'titillium_web' => array(
                'name'  => 'Titillium+Web',
                'title' => 'Titillium Web',
                'sizes' => '200,300,400,600,700,900',
            ),
            'vollkorn'      => array(
                'name'  => 'Vollkorn',
                'title' => 'Vollkorn',
                'sizes' => '400,700',
            ),
        );
    }
}
