<?php

namespace Takeatea\TeaThemeOptions\Fields\Wordpress;

use Takeatea\TeaThemeOptions\TeaThemeOptions;
use Takeatea\TeaThemeOptions\TeaFields;

/**
 * TEA WORDPRESS FIELD
 *
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
 * Adding a `taxonomies`
 * array(
 *     'type' => 'wordpress',
 *     'mode' => 'taxonomies',
 *     'title' => 'My Wordpress taxonomies',
 *     'id' => 'my_taxonomies_field_id',
 *     'options' => array(
 *         'term' => 'my_taxonomy'
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
 * Tea Fields Wordpress
 *
 * To get its own Fields
 *
 * @package Tea Fields
 * @subpackage Tea Fields Wordpress
 * @since 1.5.2.14
 *
 */
class Wordpress extends TeaFields
{
    //Define protected vars

    /**
     * Constructor.
     *
     * @since 1.3.0
     */
    public function __construct(){}


    //------------------------------------------------------------------------//

    /**
     * MAIN FUNCTIONS
     **/

    /**
     * Build HTML component to display in all the Tea T.O. defined pages.
     *
     * @param array $content Contains all data
     * @param array $post Contains all post data
     *
     * @since 1.4.0
     */
    public function templatePages($content, $post = array(), $prefix = '')
    {
        //Check if an id is defined at least
        if (empty($post)) {
            //Check if an id is defined at least
            $this->checkId($content);
            $postid = 0;
        }
        else {
            //Modify content
            $content = $content['args']['contents'];
            $postid = $post->ID;
        }

        //Default variables
        $id = $content['id'];
        $mode = isset($content['mode']) ? $content['mode'] : 'posts';
        $title = isset($content['title']) ? $content['title'] : __('Tea Wordpress Contents', TTO_I18N);
        $multiple = isset($content['multiple']) && (true === $content['multiple'] || '1' == $content['multiple']) ? true : false;
        $description = isset($content['description']) ? $content['description'] : '';
        $options = isset($content['options']) ? $content['options'] : array();

        //Get the categories
        $contents = $this->getWPContents($mode, $multiple, $options, $postid);

        //Default way
        if (empty($post)) {
            //Check selected
            $vals = TeaThemeOptions::get_option($prefix.$id, array());
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }
        //On CPT
        else {
            //Check selected
            $vals = get_post_meta($post->ID, $post->post_type . '-' . $id, $multiple);
            $vals = empty($vals) ? array(0) : (is_array($vals) ? $vals : array($vals));
        }

        //Get template
        include(TTO_PATH.'/Fields/Wordpress/in_pages.tpl.php');
    }
}
