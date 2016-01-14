<?php

namespace crewstyle\OlympusZeus\Core\Term;

use crewstyle\OlympusZeus\OlympusZeus;
use crewstyle\OlympusZeus\Core\Field\Field;
use crewstyle\OlympusZeus\Core\Term\TermEngine;
use crewstyle\OlympusZeus\Core\Walker\WalkerSingle;

/**
 * Works with Term.
 *
 * @package Olympus Zeus
 * @subpackage Core\Term\TermHook
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 4.0.0
 *
 */

class TermHook
{
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
     * @since 3.3.0
     */
    public static function defineArgs($slug, $hierarchical = false)
    {
        return array(
            'hierarchical' => $hierarchical,
            'query_var' => true,
            'rewrite' => array('slug' => $slug),
            'show_admin_column' => true,
            'show_ui' => true,

            'show_in_rest' => true,
            'rest_base' => $slug,
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        );
    }

    /**
     * Build lables.
     *
     * @param array $ctn Contains all term contents
     * @param boolean $hierarchical Define if term is hierarchical or not (like categories)
     * @return array $labels Contains all labels
     *
     * @since 4.0.0
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
                : OlympusZeus::translate('Search items'),

            'all_items' => isset($ctn['all_items']) && !empty($ctn['all_items']) 
                ? $ctn['all_items'] 
                : $ctn['name'],
            'edit_item' => isset($ctn['edit_item']) && !empty($ctn['edit_item']) 
                ? $ctn['edit_item'] 
                : OlympusZeus::translate('Edit item'),
            'update_item' => isset($ctn['update_item']) && !empty($ctn['update_item']) 
                ? $ctn['update_item'] 
                : OlympusZeus::translate('Update item'),
            'add_new_item' => isset($ctn['add_new_item']) && !empty($ctn['add_new_item']) 
                ? $ctn['add_new_item'] 
                : OlympusZeus::translate('Add new item'),
            'new_item_name' => isset($ctn['new_item_name']) && !empty($ctn['new_item_name']) 
                ? $ctn['new_item_name'] 
                : OlympusZeus::translate('New item'),
        );

        if ($hierarchical) {
            $labels['parent_item'] = isset($ctn['parent_item']) && !empty($ctn['parent_item']) 
                ? $ctn['parent_item'] 
                : OlympusZeus::translate('Parent item');
            $labels['parent_item_colon'] = isset($ctn['parent_item_colon']) && !empty($ctn['parent_item_colon']) 
                ? $ctn['parent_item_colon'] 
                : OlympusZeus::translate('Parent item colon');
        }
        else {
            $labels['parent_item'] = null;
            $labels['parent_item_colon'] = null;

            $labels['popular_items'] = isset($ctn['popular_items']) && !empty($ctn['popular_items']) 
                ? $ctn['popular_items'] 
                : OlympusZeus::translate('Popular items');
            $labels['separate_items_with_commas'] = isset($ctn['separate_items_with_commas']) && !empty($ctn['separate_items_with_commas']) 
                ? $ctn['separate_items_with_commas'] 
                : OlympusZeus::translate('Separate items with commas');
            $labels['choose_from_most_used'] = isset($ctn['choose_from_most_used']) && !empty($ctn['choose_from_most_used']) 
                ? $ctn['choose_from_most_used'] 
                : OlympusZeus::translate('Chooose from the most item used');

            $labels['add_or_remove_items'] = isset($ctn['add_or_remove_items']) && !empty($ctn['add_or_remove_items']) 
                ? $ctn['add_or_remove_items'] 
                : OlympusZeus::translate('Add or remove items');
            $labels['not_found'] = isset($ctn['not_found']) && !empty($ctn['not_found']) 
                ? $ctn['not_found'] 
                : OlympusZeus::translate('No item found');
        }

        return $labels;
    }

    /**
     * Hook to change columns on term list page.
     *
     * @param array $columns Contains list of columns
     * @return array $columns Contains list of columns
     *
     * @since 4.0.0
     */
    public function hookColumns($columns)
    {
        //Get current post type
        $current = isset($_GET['taxonomy']) ? $_GET['taxonomy'] : '';

        //check post type
        if (empty($current)) {
            return $columns;
        }

        /**
         * Filter the column headers for a list table on a specific screen.
         *
         * The dynamic portion of the hook name, `$current`, refers to the
         * post type of the current edit screen ID.
         *
         * @var string $current
         * @param array $columns
         * @return array $columns
         *
         * @since 4.0.0
         */
        return apply_filters('olz_manage_edit-'.$current.'_term_columns', $columns);
    }

    /**
     * Hook to add custom column.
     *
     * @param string $content Contains contents to display
     * @param string $column Contains column name
     * @param int $term_id Contains current term ID
     *
     * @since 4.0.0
     */
    public function hookCustomColumn($content, $column, $term_id)
    {
        //Get current post type
        $current = isset($_GET['taxonomy']) ? $_GET['taxonomy'] : '';

        //check post type
        if (empty($current)) {
            return;
        }

        /**
         * Fires for each custom column of a specific post type in the Posts list table.
         *
         * @param string $content
         * @param string $column
         * @param int $term_id
         *
         * @since 4.0.0
         */
        return apply_filters('olz_manage_'.$current.'_term_custom_column', $content, $column, $term_id);
    }

    /**
     * Hook building custom fields.
     *
     * @param string|object $term Contain term used by function
     * @uses add_meta_box()
     *
     * @since 4.0.0
     */
    public function hookFieldsDisplay($term)
    {
        //Definitions
        $contents = array();
        $ids = array();

        //Get current
        $isobject = is_object($term);
        $slug = $isobject ? $term->taxonomy : $term;
        $termid = $isobject ? $term->term_id : 0;

        /**
         * Build term contents.
         *
         * @var string $slug
         * @param array $contents
         * @return array $contents
         *
         * @since 4.0.0
         */
        $contents = apply_filters('olz_term_'.$slug.'_contents', $contents);

        //Check contents
        if (empty($contents)) {
            return;
        }

        //Get contents
        foreach ($contents as $ctn) {
            //Check fields
            if (empty($ctn)) {
                continue;
            }

            //Get type and id
            $type = isset($ctn['type']) ? $ctn['type'] : '';
            $id = isset($ctn['id']) ? $ctn['id'] : '';

            //Check if we are authorized to use this field in CPTs
            if (empty($type)) {
                continue;
            }

            //Get field instance
            $field = Field::getField($type, $id, array(), $ids);

            //Get template
            $tpl = $field->prepareField($ctn, array(
                'prefix' => $slug,
                'term_id' => $termid,
                'structure' => TermEngine::getPrefix()
            ));

            //Display it
            OlympusZeus::getRender($tpl['template'], $tpl['vars']);
        }
    }

    /**
     * Hook building custom fields for Post types.
     *
     * @param number $term_id Contain term ID
     * @return number|void
     * @uses update_post_meta()
     *
     * @since 4.0.0
     */
    public function hookFieldsSave($term_id)
    {
        //Admin panel
        if (!OLZ_ISADMIN) {
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
        if (empty($this->terms)) {
            return;
        }

        //Check integrity
        if (!isset($request['taxonomy'])) {
            return;
        }

        //Get current saved taxonomy
        $slug = $request['taxonomy'];
        $contents = array();

        //Check if tax exists or if its contents are empty
        if (!isset($this->terms[$slug])) {
            return;
        }

        /**
         * Build term contents.
         *
         * @var string $slug
         * @param array $contents
         * @return array $contents
         *
         * @since 4.0.0
         */
        $contents = apply_filters('olz_term_'.$slug.'_contents', $contents);

        //Make it works!
        foreach ($contents as $ctn) {
            $value = isset($request[$ctn['id']]) ? $request[$ctn['id']] : '';
            $prefix = str_replace(array('%TERM%', '%SLUG%'), array($term_id, $slug), TermEngine::getPrefix());

            //WP 4.4
            if (function_exists('update_term_meta')) {
                update_term_meta($term_id, $slug.'-'.$ctn['id'], $value);
            }
            else {
                OlympusZeus::updateOption($prefix.'-'.$ctn['id'], $value);
            }
        }

        return;
    }

    /**
     * Register term
     *
     * @param array $term Contains all term details
     * @return array $term
     * @uses register_taxonomy()
     *
     * @since 3.3.0
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

        return array($term, $slug);
    }

    /**
     * Build terms.
     *
     * @param array $terms Contains all terms.
     * @uses add_action()
     *
     * @since 4.0.0
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;

        //Add WP Hooks
        add_action('init', function (){
            //Check post types
            if (empty($this->terms)) {
                return;
            }

            //Register post type
            foreach ($this->terms as $term) {
                $t = $this->registerTerm($term);

                if (empty($t) || empty($t[0])) {
                    unset($this->terms[$term['slug']]);
                    continue;
                }

                //Get all admin details
                if (OLZ_ISADMIN) {
                    //Display custom fields
                    add_action($t[1].'_edit_form_fields', array(&$this, 'hookFieldsDisplay'), 10, 1);
                    //add_action($t[1].'_add_form_fields', array(&$this, 'hookFieldsDisplay'), 10, 1);

                    //Save custom fields
                    add_action('created_'.$t[1], array(&$this, 'hookFieldsSave'), 10, 2);
                    add_action('edited_'.$t[1], array(&$this, 'hookFieldsSave'), 10, 2);

                    //Display custom columns
                    add_filter('manage_edit-'.$t[1].'_columns', array(&$this, 'hookColumns'), 10);
                    add_filter('manage_'.$t[1].'_custom_column', array(&$this, 'hookCustomColumn'), 11, 3);

                    //Special case: single choice on post edit page
                    if (isset($term['choice']) && 'single' === $term['choice']) {
                        //Define slug
                        $termslug = $t[1];

                        //Apply filter
                        add_filter('wp_terms_checklist_args', function ($args, $post_id) use ($termslug) {
                            //Check taxonomy
                            if (isset($args['taxonomy']) && $termslug === $args['taxonomy']) {
                                $args['walker'] = new WalkerSingle();
                                $args['popular_cats'] = array();
                                $args['checked_ontop'] = false;
                            }

                            return $args;
                        }, 10, 2);
                    }
                }

                $this->terms[$term['slug']] = $t[0];
            }

            //Update DB
            OlympusZeus::setConfigs(TermEngine::getIndex(), $this->terms);

            //Flush all rewrite rules
            flush_rewrite_rules();
        }, 10, 1);
    }
}
