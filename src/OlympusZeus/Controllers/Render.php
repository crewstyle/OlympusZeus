<?php

namespace crewstyle\OlympusZeus\Controllers;

use Behat\Transliterator\Transliterator;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFunction;

/**
 * Render HTML entities.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Render
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
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
     * @since 5.0.0
     */
    public static function view($template, $vars)
    {
        //Define Twig loaders
        $loader = new Twig_Loader_Filesystem([OLZ_TWIG_VIEWS]);

        //Build Twig renderer
        $twig = new Twig_Environment($loader, ['cache' => OLZ_CACHE]);

        //Get footer and header from WordPress
        $twig->addFunction(new Twig_SimpleFunction('get_footer', function ($file = '') {
            get_footer($file);
        }));
        $twig->addFunction(new Twig_SimpleFunction('get_header', function ($file = '') {
            get_header($file);
        }));

        //Get permalink from WordPress
        $twig->addFunction(new Twig_SimpleFunction('get_permalink', function ($id) {
            get_permalink($id);
        }));
        $twig->addFunction(new Twig_SimpleFunction('get_term_link', function ($id, $type) {
            get_term_link($id, $type);
        }));

        //Get terms
        $twig->addFunction(new Twig_SimpleFunction('get_the_term_list', function ($id, $type, $before, $inside, $after) {
            get_the_term_list($id, $type, $before, $inside, $after);
        }));

        //Get author from WordPress
        $twig->addFunction(new Twig_SimpleFunction('get_the_author_meta', function ($display, $id) {
            get_the_author_meta($display, $id);
        }));
        $twig->addFunction(new Twig_SimpleFunction('get_author_posts_url', function ($id) {
            get_author_posts_url($id);
        }));

        //Image
        $twig->addFunction(new Twig_SimpleFunction('has_post_thumbnail', function ($id) {
            has_post_thumbnail($id);
        }));
        $twig->addFunction(new Twig_SimpleFunction('get_post_thumbnail_id', function ($id) {
            get_post_thumbnail_id($id);
        }));
        $twig->addFunction(new Twig_SimpleFunction('wp_get_attachment_image_src', function ($id, $format) {
            wp_get_attachment_image_src($id, $format);
        }));

        //Make Include function
        $twig->addFunction(new Twig_SimpleFunction('include_file', function ($file) {
            include($file);
        }));

        //Make wpEditor function
        $twig->addFunction(new Twig_SimpleFunction('wp_editor', function ($content, $editor_id, $settings = []) {
            wp_editor($content, $editor_id, $settings);
        }));

        //Display template
        echo $twig->render($template, $vars);
    }
}
