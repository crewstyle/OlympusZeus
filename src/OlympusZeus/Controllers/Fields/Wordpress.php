<?php

namespace crewstyle\OlympusZeus\Controllers\Fields;

use crewstyle\OlympusZeus\Models\Field as FieldModel;
use crewstyle\OlympusZeus\Controllers\Field;
use crewstyle\OlympusZeus\Controllers\Translate;

/**
 * Builds Wordpress field.
 *
 * @package Olympus Zeus
 * @subpackage Controllers\Fields\Wordpress
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 5.0.0
 *
 * @see https://olympus.readme.io/docs/field-wordpress
 *
 */

class Wordpress extends Field
{
    /**
     * @var string
     */
    protected $faIcon = 'fa-wordpress';

    /**
     * @var string
     */
    protected $template = 'fields/wordpress.html.twig';

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
            'title' => Translate::t('Wordpress Contents'),
            'default' => [],
            'description' => '',
            'mode' => 'posts',
            'multiple' => false,
            'options' => [],

            //details
            'post' => 0,
            'prefix' => '',
            'template' => 'pages',
        ];

        //Build defaults data
        $vars = array_merge($defaults, $content);

        //Check if an id is defined at least
        $postid = empty($vars['post']) ? 0 : $vars['post']->ID;

        //Retrieve field value
        $vars['val'] = $this->getValue($details, $vars['default'], $content['id'], true);

        //Get the categories
        $vars['contents'] = $this->getWPContents(
            $vars['mode'],
            $vars['multiple'],
            $vars['options'],
            $postid
        );

        //Field description
        if (!empty($vars['contents']) && 1 <= count($vars['contents'])) {
            $description = $vars['multiple'] 
                ? Translate::t('Press the <code>CTRL</code> or <code>CMD</code> button 
                    to select more than one option.') . '<br/>' 
                : '';
        }
        else if ($vars['multiple']) {
            $description = sprintf(Translate::t('There are no %s found.'), $vars['mode']) . '<br/>';
        }
        else {
            $description = sprintf(Translate::t('There is no %s found.'), $vars['mode']) . '<br/>';
        }

        //Update description
        $vars['description'] = $description . $vars['description'];

        //Update vars
        $this->getField()->setVars($vars);
    }

    /**
     * Get Wordpress contents already registered.
     *
     * @param string $type Wordpress content type to return
     * @param bool $multiple Define if there is multiselect or not
     * @param array $options Define options if needed
     * @param int $post Define the post ID for meta boxes
     * @return array $wpcontents Array of Wordpress content type registered
     *
     * @since 5.0.0
     */
    protected function getWPContents($type = 'posts', $multiple = false, $options = [], $post = 0)
    {
        //Access WordPress contents
        $wpcontents = [];

        //Set the first item
        if (!$multiple) {
            $wpcontents[0][-1] = '';
        }

        //Exclude current item
        if (isset($options['exclude']) && 'current' === $options['exclude']) {
            $options['exclude'] = $post;
        }

        //Get asked contents
        $authorized = [
            'categories', 'category',
            'menus', 'menu',
            'pages', 'page',
            'posts', 'post',
            'posttypes', 'posttype',
            'tags', 'tag',
            'terms', 'term'
        ];

        //Data retrieed
        if (in_array($type, $authorized)) {
            if (in_array($type, ['categories', 'category'])) {
                $wptype = 'Categories';
            }
            else if (in_array($type, ['menus', 'menu'])) {
                $wptype = 'Menus';
            }
            else if (in_array($type, ['pages', 'page'])) {
                $wptype = 'Pages';
            }
            else if (in_array($type, ['posts', 'post'])) {
                $wptype = 'Posts';
            }
            else if (in_array($type, ['posttypes', 'posttype'])) {
                $wptype = 'Posttypes';
            }
            else if (in_array($type, ['tags', 'tag'])) {
                $wptype = 'Tags';
            }
            else {
                $wptype = 'Terms';
            }

            $function = 'getWP'.$wptype;
            $wpcontents = array_merge($wpcontents, $this->$function($options));
        }

        //Return value
        return $wpcontents;
    }

    /**
     * Get WordPress Categories registered.
     *
     * @uses get_categories()
     *
     * @param array $options Define options if needed
     * @return array $wpcontents Array of WordPress items
     *
     * @since 5.0.0
     */
    protected function getWPCategories($options = [])
    {
        //Build contents
        $contents = [];

        //Build options
        $request = [
            'hide_empty' => 0
        ];
        $args = array_merge($request, $options);

        //Build request
        $categories_obj = get_categories($args);

        //Iterate on categories
        if (!empty($categories_obj)) {
            foreach ($categories_obj as $cat) {
                //For Wordpress version < 3.0
                if (empty($cat->cat_ID)) {
                    continue;
                }

                //Get the id and the name
                $contents[$cat->cat_ID] = $cat->cat_name;
            }
        }

        //Return all values in a well formatted way
        return $contents;
    }

    /**
     * Get WordPress Menus registered.
     *
     * @uses wp_get_nav_menus()
     *
     * @param array $options Define options if needed
     * @return array $wpcontents Array of WordPress items
     *
     * @since 5.0.0
     */
    protected function getWPMenus($options = [])
    {
        //Build contents
        $contents = [];

        //Build options
        $request = [
            'hide_empty' => false,
            'orderby' => 'none'
        ];
        $args = array_merge($request, $options);

        //Build request
        $menus_obj = wp_get_nav_menus($args);

        //Iterate on menus
        if (!empty($menus_obj)) {
            foreach ($menus_obj as $menu) {
                //For Wordpress version < 3.0
                if (empty($menu->term_id)) {
                    continue;
                }

                //Get the id and the name
                $contents[0][$menu->term_id] = $menu->name;
            }
        }

        //Return all values in a well formatted way
        return $contents;
    }

    /**
     * Get WordPress Pages registered.
     *
     * @uses get_pages()
     *
     * @param array $options Define options if needed
     * @return array $wpcontents Array of WordPress items
     *
     * @since 5.0.0
     */
    protected function getWPPages($options = [])
    {
        //Build contents
        $contents = [];

        //Build options
        $request = [
            'sort_column' => 'post_parent,menu_order'
        ];
        $args = array_merge($request, $options);

        //Build request
        $pages_obj = get_pages($args);

        //Iterate on pages
        if (!empty($pages_obj)) {
            foreach ($pages_obj as $pag) {
                //For Wordpress version < 3.0
                if (empty($pag->ID)) {
                    continue;
                }

                //Get the id and the name
                $contents[0][$pag->ID] = $pag->post_title;
            }
        }

        //Return all values in a well formatted way
        return $contents;
    }

    /**
     * Get WordPress Posts registered.
     *
     * @uses wp_get_recent_posts()
     *
     * @param array $options Define options if needed
     * @return array $wpcontents Array of WordPress items
     *
     * @since 5.0.0
     */
    protected function getWPPosts($options = [])
    {
        //Build contents
        $contents = [];

        //Build options
        $request = [
            'post_type' => isset($options['post_type']) ? $options['post_type'] : 'post',
            'post_status' => 'publish'
        ];
        $args = array_merge($request, $options);

        //Build request
        $posts_obj = wp_get_recent_posts($args, OBJECT);

        //Iterate on posts
        if (!empty($posts_obj)) {
            foreach ($posts_obj as $pos) {
                //For Wordpress version < 3.0
                if (empty($pos->ID)) {
                    continue;
                }

                //Get the id and the name
                $contents[$pos->post_type][$pos->ID] = $pos->post_title;
            }
        }

        //Return all values in a well formatted way
        return $contents;
    }

    /**
     * Get WordPress Post Types registered.
     *
     * @uses get_post_types()
     *
     * @param array $options Define options if needed
     * @return array $wpcontents Array of WordPress items
     *
     * @since 5.0.0
     */
    protected function getWPPosttypes($options = [])
    {
        //Build contents
        $contents = [];

        //Build options
        $request = [];
        $args = array_merge($request, $options);

        //Build request
        $types_obj = get_post_types($args, 'object');

        //Iterate on posttypes
        if (!empty($types_obj)) {
            foreach ($types_obj as $typ) {
                //Get the the name
                $contents[0][$typ->name] = $typ->labels->name.' ('.$typ->name.')';
            }
        }

        //Return all values in a well formatted way
        return $contents;
    }

    /**
     * Get WordPress Tags registered.
     *
     * @uses get_the_tags()
     *
     * @param array $options Define options if needed
     * @return array $wpcontents Array of WordPress items
     *
     * @since 5.0.0
     */
    protected function getWPTags($options = [])
    {
        //Build contents
        $contents = [];

        //Build options
        $request = [];
        $args = array_merge($request, $options);

        //Build request
        $tags_obj = get_the_tags();

        //Iterate on tags
        if (!empty($tags_obj)) {
            foreach ($tags_obj as $tag) {
                //Get the id and the name
                $contents[0][$tag->term_id] = $tag->name;
            }
        }

        //Return all values in a well formatted way
        return $contents;
    }

    /**
     * Get WordPress Terms registered.
     *
     * @uses get_terms()
     *
     * @param array $options Define options if needed
     * @return array $wpcontents Array of WordPress items
     *
     * @since 5.0.0
     */
    protected function getWPTerms($options = [])
    {
        //Build contents
        $contents = [];

        //Build options
        $request = [
            'public' => 1
        ];
        $args = array_merge($request, $options);

        //Build request
        $taxs_obj = get_taxonomies($args);

        //Iterate on tags
        if (!empty($taxs_obj)) {
            foreach ($taxs_obj as $tax) {
                //Get the id and the name
                $taxo = get_taxonomy($tax);
                $contents[0][$tax] = $taxo->labels->name.' ('.$taxo->name.')';
            }
        }

        //Return all values in a well formatted way
        return $contents;
    }
}
