<?php

namespace crewstyle\TeaThemeOptions\Core\Field\Wordpress;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Field\Field;

/**
 * TTO WORDPRESS FIELD
 *
 * To add this field, simply make the same as follow:
 * Adding a `categories`
 * array(
 *     'type' => 'wordpress',
 *     'mode' => 'categories',
 *     'title' => 'My Wordpress categories',
 *     'mode' => 'categories',
 *     'id' => 'my_categories_field_id',
 *     'multiple' => true //Optional: to "false" by default
 * )
 *
 * Adding a `menus`
 * array(
 *     'type' => 'wordpress',
 *     'mode' => 'menus',
 *     'title' => 'My Wordpress menus',
 *     'id' => 'my_menus_field_id',
 *     'multiple' => true //Optional: to "false" by default
 * )
 *
 * Adding a `pages`
 * array(
 *     'type' => 'wordpress',
 *     'mode' => 'pages',
 *     'title' => 'My Wordpress pages',
 *     'id' => 'my_pages_field_id',
 *     'multiple' => true //Optional: to "false" by default
 * )
 *
 * Adding a `posts`
 * array(
 *     'type' => 'wordpress',
 *     'mode' => 'posts',
 *     'title' => 'My Wordpress posts',
 *     'id' => 'my_posts_field_id',
 *     'options' => array(
 *         'post_type' => array('post', 'my_cpt_1', 'my_cpt_2'),
 *         'numberposts' => 10
 *     ),
 *     'multiple' => true //Optional: to "false" by default
 * )
 *
 * Adding a `post types`
 * array(
 *     'type' => 'wordpress',
 *     'mode' => 'posttypes',
 *     'title' => 'My Wordpress post types',
 *     'id' => 'my_posttypes_field_id',
 *     'multiple' => true //Optional: to "false" by default
 * )
 *
 * Adding a `tags`
 * array(
 *     'type' => 'wordpress',
 *     'mode' => 'tags',
 *     'title' => 'My Wordpress tags',
 *     'id' => 'my_tags_field_id',
 *     'multiple' => true //Optional: to "false" by default
 * )
 *
 * Adding a `terms`
 * array(
 *     'type' => 'wordpress',
 *     'mode' => 'terms',
 *     'title' => 'My WordPress terms',
 *     'id' => 'my_terms_field_id',
 *     'options' => array(
 *         'term' => 'my_term'
 *     ),
 *     'multiple' => true //Optional: to "false" by default
 * )
 *
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO WordPress Field
 *
 * Class used to build Wordpress fields.
 *
 * @package Tea Theme Options
 * @subpackage Core\Field\Wordpress
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Wordpress extends Field
{
    /**
     * @var string
     */
    public static $faicon = 'fa-wordpress';

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

        //Check if an id is defined at least
        $postid = empty($post) ? 0 : $post->ID;

        //Build defaults data
        $template = array(
            'id' => $content['id'],
            'mode' => isset($content['mode']) ? $content['mode'] : 'posts',
            'title' => isset($content['title']) 
                ? $content['title'] : 
                TeaThemeOptions::__('Tea Wordpress Contents'),
            'description' => isset($content['description']) ? $content['description'] : '',
            'multiple' => isset($content['multiple']) ? $content['multiple'] : false,
            'options' => isset($content['options']) ? $content['options'] : array(),
            'default' => array(),

            //details
            'post' => $post,
            'prefix' => $prefix,
            'template' => $tpl,
        );

        //Get the categories
        $template['contents'] = $this->getWPContents(
            $template['mode'],
            $template['multiple'],
            $template['options'],
            $postid
        );

        //Field description
        if (!empty($template['contents']) && 1 <= count($template['contents'])) {
            $description = $template['multiple'] 
                ? TeaThemeOptions::__('Press the <code>CTRL</code> or <code>CMD</code> button 
                    to select more than one option.') . '<br/>' 
                : '';
        }
        else if ($template['multiple']) {
            $description = sprintf(TeaThemeOptions::__('There are no %s found.'), $template['mode']) . '<br/>';
        }
        else {
            $description = sprintf(TeaThemeOptions::__('There is no %s found.'), $template['mode']) . '<br/>';
        }

        //Update description
        $template['description'] = $description . $template['description'];

        //Get values
        $template['val'] = $this->getFieldValue($details, $template['default'], $content['id'], true);

        //Get template
        return $this->renderField('fields/wordpress.html.twig', $template);
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
     * @since 3.0.0
     */
    protected function getWPContents($type = 'posts', $multiple = false, $options = array(), $post = 0)
    {
        //Access the WordPress Categories via an Array
        $wpcontents = array();

        //Set the first item
        if (!$multiple) {
            $wpcontents[0][-1] = '';
        }

        //Exclude current item
        if (isset($options['exclude']) && 'current' == $options['exclude']) {
            $options['exclude'] = $post;
        }

        //Get asked contents
        $authorized = array('categories', 'menus', 'pages', 'posts', 'posttypes', 'tags', 'terms');

        //Data retrieed
        if (in_array($type, $authorized)) {
            $function = 'getWP'.ucfirst($type);
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
     * @since 3.0.0
     */
    protected function getWPCategories($options = array())
    {
        //Build contents
        $contents = array();

        //Build options
        $request = array(
            'hide_empty' => 0
        );
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
     * @since 3.0.0
     */
    protected function getWPMenus($options = array())
    {
        //Build contents
        $contents = array();

        //Build options
        $request = array(
            'hide_empty' => false,
            'orderby' => 'none'
        );
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
     * @since 3.0.0
     */
    protected function getWPPages($options = array())
    {
        //Build contents
        $contents = array();

        //Build options
        $request = array(
            'sort_column' => 'post_parent,menu_order'
        );
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
     * @since 3.0.0
     */
    protected function getWPPosts($options = array())
    {
        //Build contents
        $contents = array();

        //Build options
        $request = array(
            'post_type' => isset($options['post_type']) ? $options['post_type'] : 'post',
            'post_status' => 'publish'
        );
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
     * @since 3.3.0
     */
    protected function getWPPosttypes($options = array())
    {
        //Build contents
        $contents = array();

        //Build options
        $request = array();
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
     * @since 3.0.0
     */
    protected function getWPTags($options = array())
    {
        //Build contents
        $contents = array();

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
     * @since 3.3.0
     */
    protected function getWPTerms($options = array())
    {
        //Build contents
        $contents = array();

        //Build request
        $taxs_obj = get_taxonomies(array('public' => 1));

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
