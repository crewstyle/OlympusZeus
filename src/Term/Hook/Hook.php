<?php

namespace crewstyle\TeaThemeOptions\Term\Hook;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Controllers\Field\Field;
use crewstyle\TeaThemeOptions\Term\Engine\Engine;

/**
 * TTO TERM HOOK
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO Term Hook
 *
 * Class used to work with Term Hook.
 *
 * @package Tea Theme Options
 * @subpackage Term\Hook\Hook
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.1.0
 *
 */
class Hook
{
    /**
     * @var array
     */
    protected $contents = array();

    /**
     * @var array
     */
    protected static $existingTerms = array('attachment', 'attachment_id', 'author', 'author_name', 'calendar', 'cat', 'category', 'category__and', 'category__in', 'category__not_in', 'category_name', 'comments_per_page', 'comments_popup', 'customize_messenger_channel', 'customized', 'cpage', 'day', 'debug', 'error', 'exact', 'feed', 'hour', 'link_category', 'm', 'minute', 'monthnum', 'more', 'name', 'nav_menu', 'nonce', 'nopaging', 'offset', 'order', 'orderby', 'p', 'page', 'page_id', 'paged', 'pagename', 'pb', 'perm', 'post', 'post__in', 'post__not_in', 'post_format', 'post_mime_type', 'post_status', 'post_tag', 'post_type', 'posts', 'posts_per_archive_page', 'posts_per_page', 'preview', 'robots', 's', 'search', 'second', 'sentence', 'showposts', 'static', 'subpost', 'subpost_id', 'tag', 'tag__and', 'tag__in', 'tag__not_in', 'tag_id', 'tag_slug__and', 'tag_slug__in', 'taxonomy', 'tb', 'term', 'theme', 'type', 'w', 'withcomments', 'withoutcomments', 'year');

    /**
     * @var array
     */
    protected $terms = array();

    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct(){}

    /**
     * Build args.
     *
     * @param string $slug Contains slug term
     * @param boolean $hierarchical Define if term is hierarchical or not (like categories)
     * @return array $args Contains all args
     *
     * @since 3.0.0
     */
    public static function defineArgs($slug, $hierarchical = false)
    {
        return array(
            'hierarchical' => $hierarchical,
            'query_var' => true,
            'rewrite' => array('slug' => $slug),
            'show_admin_column' => true,
            'show_ui' => true,
        );
    }

    /**
     * Build lables.
     *
     * @param array $ctn Contains all term contents
     * @param boolean $hierarchical Define if term is hierarchical or not (like categories)
     * @return array $labels Contains all labels
     *
     * @since 3.0.0
     */
    public static function defineLabels($ctn, $hierarchical = false)
    {
        $labels = array(
            'name' => $ctn['name'],
            'singular_name' => isset($ctn['singular_name']) && !empty($ctn['singular_name']) 
                ? $ctn['singular_name'] 
                : $ctn['name'],
            'menu_name' => isset($ctn['menu_name']) && !empty($ctn['menu_name']) 
                ? $ctn['menu_name'] 
                : $ctn['name'],

            'search_items' => isset($ctn['search_items']) && !empty($ctn['search_items']) 
                ? $ctn['search_items'] 
                : TeaThemeOptions::__('Search items'),

            'all_items' => isset($ctn['all_items']) && !empty($ctn['all_items']) 
                ? $ctn['all_items'] 
                : $ctn['name'],
            'edit_item' => isset($ctn['edit_item']) && !empty($ctn['edit_item']) 
                ? $ctn['edit_item'] 
                : TeaThemeOptions::__('Edit item'),
            'update_item' => isset($ctn['update_item']) && !empty($ctn['update_item']) 
                ? $ctn['update_item'] 
                : TeaThemeOptions::__('Update item'),
            'add_new_item' => isset($ctn['add_new_item']) && !empty($ctn['add_new_item']) 
                ? $ctn['add_new_item'] 
                : TeaThemeOptions::__('Add new item'),
            'new_item_name' => isset($ctn['new_item_name']) && !empty($ctn['new_item_name']) 
                ? $ctn['new_item_name'] 
                : TeaThemeOptions::__('New item'),
        );

        if ($hierarchical) {
            $labels['parent_item'] = isset($ctn['parent_item']) && !empty($ctn['parent_item']) 
                ? $ctn['parent_item'] 
                : TeaThemeOptions::__('Parent item');
            $labels['parent_item_colon'] = isset($ctn['parent_item_colon']) && !empty($ctn['parent_item_colon']) 
                ? $ctn['parent_item_colon'] 
                : TeaThemeOptions::__('Parent item colon');
        }
        else {
            $labels['parent_item'] = null;
            $labels['parent_item_colon'] = null;

            $labels['popular_items'] = isset($ctn['popular_items']) && !empty($ctn['popular_items']) 
                ? $ctn['popular_items'] 
                : TeaThemeOptions::__('Popular items');
            $labels['separate_items_with_commas'] = isset($ctn['separate_items_with_commas']) && !empty($ctn['separate_items_with_commas']) 
                ? $ctn['separate_items_with_commas'] 
                : TeaThemeOptions::__('Separate items with commas');
            $labels['choose_from_most_used'] = isset($ctn['choose_from_most_used']) && !empty($ctn['choose_from_most_used']) 
                ? $ctn['choose_from_most_used'] 
                : TeaThemeOptions::__('Chooose from the most item used');

            $labels['add_or_remove_items'] = isset($ctn['add_or_remove_items']) && !empty($ctn['add_or_remove_items']) 
                ? $ctn['add_or_remove_items'] 
                : TeaThemeOptions::__('Add or remove items');
            $labels['not_found'] = isset($ctn['not_found']) && !empty($ctn['not_found']) 
                ? $ctn['not_found'] 
                : TeaThemeOptions::__('No item found');
        }

        return $labels;
    }

    /**
     * Hook building custom fields.
     *
     * @param string|object $term Contain term used by function
     * @uses add_meta_box()
     *
     * @since 3.0.0
     */
    public function hookFieldsDisplay($term)
    {
        if (empty($this->contents)) {
            return;
        }

        //Get all authorized fields
        $unauthorized = Field::getUnauthorizedFields();
        $ids = array();

        //Get current
        $isobject = is_object($term);
        $current = $isobject ? $term->taxonomy : $term;

        //Check contents
        if (!isset($this->contents[$current]) || empty($this->contents[$current])) {
            return;
        }

        //Get contents
        foreach ($this->contents[$current] as $ctn) {
            //Check fields
            if (empty($ctn)) {
                continue;
            }

            //Get type and id
            $type = isset($ctn['type']) ? $ctn['type'] : '';
            $id = isset($ctn['id']) ? $ctn['id'] : '';

            //Check if we are authorized to use this field in CPTs
            if (empty($type) || in_array($type, $unauthorized)) {
                continue;
            }

            //Get field instance
            $field = Field::getField($type, $id, array(), $ids);

            //Prefix
            $prefix = $isobject 
                ? str_replace(array('%TERM%', '%SLUG%'), array($term->term_id, $current), Engine::getPrefix())
                : $current . '-';

            //Get template
            $tpl = $field->prepareField($ctn, array(
                'prefix' => $prefix
            ));

            //Display it
            TeaThemeOptions::getRender($tpl['template'], $tpl['vars']);
        }
    }

    /**
     * Hook building custom fields for Post types.
     *
     * @param number $term_id Contain term ID
     * @return number|void
     * @uses update_post_meta()
     *
     * @since 3.0.0
     */
    public function hookFieldsSave($term_id)
    {
        //Admin panel
        if (!TTO_IS_ADMIN) {
            return;
        }

        //Check term
        if (!isset($term_id) || empty($term_id)) {
            return;
        }

        //Get all requests
        $request = isset($_REQUEST) ? $_REQUEST : array();

        //Check request
        if (empty($request)) {
            return;
        }

        //Check if we have some terms
        if (empty($this->terms) || empty($this->contents)) {
            return;
        }

        //Check integrity
        if (!isset($request['taxonomy'])) {
            return;
        }

        //Get current saved taxonomy
        $current = $request['taxonomy'];

        //Check if tax exists or if its contents are empty
        if (!isset($this->terms[$current]) || empty($this->contents[$current])) {
            return;
        }

        //Make it works!
        foreach ($this->contents[$current] as $ctn) {
            $value = isset($request[$ctn['id']]) ? $request[$ctn['id']] : '';
            $prefix = str_replace(array('%TERM%', '%SLUG%'), array($term_id, $current), Engine::getPrefix());

            TeaThemeOptions::updateOption($prefix . $ctn['id'], $value);
        }

        return;
    }

    /**
     * Hook registering post types.
     *
     * @uses add_node()
     *
     * @since 3.0.0
     */
    public function hookRegisterTerms()
    {
        //Check post types
        if (empty($this->terms)) {
            return;
        }

        //Register post type
        foreach ($this->terms as $term) {
            $t = $this->registerTerm($term);

            if (empty($t)) {
                unset($this->terms[$term['slug']]);
                continue;
            }

            $this->terms[$term['slug']] = $t;
        }

        //Update DB
        TeaThemeOptions::setConfigs(Engine::getIndex(), $this->terms);

        //Flush all rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Register term
     *
     * @param array $term Contains all term details
     * @return array $term
     * @uses register_taxonomy()
     *
     * @since 3.1.0
     */
    public function registerTerm($term = array())
    {
        //Check post types
        if (empty($term)) {
            return array();
        }

        //Check slug
        if (!isset($term['slug']) || empty($term['slug'])) {
            return array();
        }

        //Check post_type
        if (!isset($term['post_type']) || empty($term['post_type'])) {
            return array();
        }

        $slug = $term['slug'];
        $posttype = $term['post_type'];

        //Register slug contents
        if (!empty($term['contents'])) {
            $this->contents[$slug] = $term['contents'];
        }

        //Special case: define a category/post_tag
        if (!in_array($slug, self::$existingTerms) && !taxonomy_exists($slug)) {
            //Define hierarchical state
            $hierarchical = isset($term['hierarchical']) && $term['hierarchical'] ? true : false;

            //Build labels
            $labels = $this->defineLabels($term['labels'], $hierarchical);

            //Build args
            $args = $this->defineArgs($slug, $hierarchical);
            $args['labels'] = $labels;

            //Action to register
            register_taxonomy($slug, $posttype, $args);

            //Update term
            $term = array_merge($term, $args);
        }

        //Get all admin details
        if (TTO_IS_ADMIN) {
            //Display custom fields
            add_action($slug . '_edit_form_fields', array(&$this, 'hookFieldsDisplay'), 10, 1);
            //add_action($slug . '_add_form_fields', array(&$this, 'hookFieldsDisplay'), 10, 1);

            //Save custom fields
            add_action('edited_' . $slug, array(&$this, 'hookFieldsSave'), 10, 2);
            add_action('created_' . $slug, array(&$this, 'hookFieldsSave'), 10, 2);
        }

        return $term;
    }

    /**
     * Build terms.
     *
     * @param array $terms Contains all terms.
     * @uses add_action()
     *
     * @since 3.0.0
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;

        //Add WP Hooks
        add_action('init', array(&$this, 'hookRegisterTerms'));
    }
}
