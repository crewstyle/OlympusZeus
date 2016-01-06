<?php

namespace crewstyle\TeaThemeOptions\Core\Render;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use Behat\Transliterator\Transliterator;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFunction;

/**
 * TTO RENDER
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Render
 *
 * Class used to render HTML entities.
 *
 * @package Tea Theme Options
 * @subpackage Core\Render\Render
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.2.4
 *
 */
class Render
{
    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct(){}

    /**
     * Slugify string.
     *
     * @param string $text Text string to slugify
     * @param string $separator Character used to separate each word
     * @return string $slugified Slugified string
     *
     * @since 3.1.0
     */
    public static function urlize($text, $separator = '-')
    {
        return Transliterator::urlize($text, $separator);
    }

    /**
     * Render TWIG component.
     *
     * @param string $template Twig template to display
     * @param array $vars Contains all field options
     *
     * @since 3.2.4
     */
    public static function render($template, $vars)
    {
        //Define Twig loaders
        $loader = new Twig_Loader_Filesystem(array(
            TTO_PATH.'/Resources/views'
        ));

        //Build Twig renderer
        $twig = new Twig_Environment($loader, array(
            'cache' => TTO_PATH.'/../_cache',
        ));

        //Get footer and header from WordPress
        $twig->addFunction(new Twig_SimpleFunction('getFooter', function ($file = ''){
            get_footer($file);
        }));
        $twig->addFunction(new Twig_SimpleFunction('getHeader', function ($file = ''){
            get_header($file);
        }));

        //Get permalink from WordPress
        $twig->addFunction(new Twig_SimpleFunction('get_permalink', function ($id){
            get_permalink($id);
        }));
        $twig->addFunction(new Twig_SimpleFunction('get_term_link', function ($id, $type){
            get_term_link($id, $type);
        }));

        //Get terms
        $twig->addFunction(new Twig_SimpleFunction('get_the_term_list', function ($id, $type, $before, $inside, $after){
            get_the_term_list($id, $type, $before, $inside, $after);
        }));

        //Get author from WordPress
        $twig->addFunction(new Twig_SimpleFunction('get_the_author_meta', function ($display, $id){
            get_the_author_meta($display, $id);
        }));
        $twig->addFunction(new Twig_SimpleFunction('get_author_posts_url', function ($id){
            get_author_posts_url($id);
        }));

        //Image
        $twig->addFunction(new Twig_SimpleFunction('has_post_thumbnail', function ($id){
            has_post_thumbnail($id);
        }));
        $twig->addFunction(new Twig_SimpleFunction('get_post_thumbnail_id', function ($id){
            get_post_thumbnail_id($id);
        }));
        $twig->addFunction(new Twig_SimpleFunction('wp_get_attachment_image_src', function ($id, $format){
            wp_get_attachment_image_src($id, $format);
        }));

        //Make Include function
        $twig->addFunction(new Twig_SimpleFunction('includeFile', function ($file){
            include($file);
        }));

        //Make wpEditor function
        $twig->addFunction(new Twig_SimpleFunction('wpEditor', function ($content, $editor_id, $settings = array()){
            wp_editor($content, $editor_id, $settings);
        }));

        //Display template
        echo $twig->render($template, $vars);
    }
}
