<?php

namespace crewstyle\TeaThemeOptions\Core\PostType\Hook;

use crewstyle\TeaThemeOptions\TeaThemeOptions;
use crewstyle\TeaThemeOptions\Core\Field\Field;
use crewstyle\TeaThemeOptions\Core\PostType\Engine\Engine;

/**
 * TTO POSTTYPE HOOK
 */

if (!defined('TTO_CONTEXT')) {
    die('You are not authorized to directly access to this page');
}


//----------------------------------------------------------------------------//

/**
 * TTO PostType Hook
 *
 * Class used to work with PostType Hook.
 *
 * @package Tea Theme Options
 * @subpackage Core\PostType\Hook\Hook
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 3.3.0
 *
 */
class Hook
{
    /**
     * @var array
     */
    protected $posttypes = array();

    /**
     * Constructor.
     *
     * @since 3.0.0
     */
    public function __construct(){}

    /**
     * Build args.
     *
     * @param array $ctn Contains all post type contents
     * @return array $args Contains all args
     *
     * @since 3.3.0
     */
    public static function defineArgs($ctn)
    {
        return array(
            'can_export' => !isset($ctn['can_export']) || !$ctn['can_export'] ? false : true,
            'capability_type' => isset($ctn['capability_type']) ? $ctn['capability_type'] : 'post',
            'description' => isset($ctn['description']) ? $ctn['description'] : '',
            'exclude_from_search' => isset($ctn['exclude_from_search']) && $ctn['exclude_from_search'] ? true : false,
            'has_archive' => isset($ctn['has_archive']) && $ctn['has_archive'] ? true : false,
            'hierarchical' => isset($ctn['hierarchical']) && $ctn['hierarchical'] ? true : false,
            'menu_icon' => isset($ctn['menu_icon']) ? $ctn['menu_icon'] : '',
            'menu_position' => isset($ctn['menu_position']) ? $ctn['menu_position'] : 100,
            'public' => isset($ctn['public']) && $ctn['public'] ? true : false,
            'publicly_queryable' => isset($ctn['publicly_queryable']) && $ctn['publicly_queryable'] ? true : false,

            'permalink_epmask' => EP_PERMALINK,
            'query_var' => true,
            'rewrite' => false,
            'show_in_menu' => true,
            'show_ui' => true,

            'supports' => isset($ctn['supports']) && is_array($ctn['supports']) ? $ctn['supports'] : array(),
            'taxonomies' => isset($ctn['taxonomies']) && is_array($ctn['taxonomies']) ? $ctn['taxonomies'] : array(),

            'show_in_rest' => true,
            'rest_base' => $ctn['slug'],
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        );
    }

    /**
     * Build lables.
     *
     * @param array $ctn Contains all post type contents
     * @return array $labels Contains all labels
     *
     * @since 3.0.0
     */
    public static function defineLabels($ctn)
    {
        return array(
            'name' => $ctn['name'],
            'singular_name' => isset($ctn['singular_name']) && !empty($ctn['singular_name']) 
                ? $ctn['singular_name'] 
                : $ctn['name'],
            'menu_name' => isset($ctn['menu_name']) && !empty($ctn['menu_name']) 
                ? $ctn['menu_name'] 
                : $ctn['name'],

            'add_new' => isset($ctn['add_new']) && !empty($ctn['add_new']) 
                ? $ctn['add_new'] 
                : TeaThemeOptions::__('Add new'),
            'add_new_item' => isset($ctn['add_new_item']) && !empty($ctn['add_new_item']) 
                ? $ctn['add_new_item'] 
                : TeaThemeOptions::__('Add new item'),
            'all_items' => isset($ctn['all_items']) && !empty($ctn['all_items']) 
                ? $ctn['all_items'] 
                : $ctn['name'],
            'edit' => isset($ctn['edit']) && !empty($ctn['edit']) 
                ? $ctn['edit'] 
                : TeaThemeOptions::__('Edit'),
            'edit_item' => isset($ctn['edit_item']) && !empty($ctn['edit_item']) 
                ? $ctn['edit_item'] 
                : TeaThemeOptions::__('Edit item'),
            'new_item' => isset($ctn['new_item']) && !empty($ctn['new_item']) 
                ? $ctn['new_item'] 
                : TeaThemeOptions::__('New item'),

            'not_found' => isset($ctn['not_found']) && !empty($ctn['not_found']) 
                ? $ctn['not_found'] 
                : TeaThemeOptions::__('No item found'),
            'not_found_in_trash' => isset($ctn['not_found_in_trash']) && !empty($ctn['not_found_in_trash']) 
                ? $ctn['not_found_in_trash'] 
                : TeaThemeOptions::__('No item found in Trash'),
            'parent_item_colon' => isset($ctn['parent_item_colon']) && !empty($ctn['parent_item_colon']) 
                ? $ctn['parent_item_colon'] 
                : TeaThemeOptions::__('Parent item'),
            'search_items' => isset($ctn['search_items']) && !empty($ctn['search_items']) 
                ? $ctn['search_items'] 
                : TeaThemeOptions::__('Search items'),

            'view' => isset($ctn['view']) && !empty($ctn['view']) 
                ? $ctn['view'] 
                : TeaThemeOptions::__('View'),
            'view_item' => isset($ctn['view_item']) && !empty($ctn['view_item']) 
                ? $ctn['view_item'] 
                : TeaThemeOptions::__('View item'),
        );
    }

    /**
     * Hook to change columns on post type list page.
     *
     * @param array $columns Contains list of columns
     * @return array $columns Contains list of columns
     *
     * @since 3.3.0
     */
    public function hookColumns($columns)
    {
        //Get current post type
        $current = isset($_GET['post_type']) ? $_GET['post_type'] : '';

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
         * @since 3.3.0
         */
        return apply_filters('tto_manage_edit-'.$current.'_posttype_columns', $columns);
    }

    /**
     * Hook to add featured image to column.
     *
     * @param string $column Contains current column ID
     * @param integer $post_id Contains current post ID
     *
     * @since 3.3.0
     */
    public function hookCustomColumn($column, $post_id)
    {
        //Get current post type
        $current = isset($_GET['post_type']) ? $_GET['post_type'] : '';

        //check post type
        if (empty($current)) {
            return;
        }

        /**
         * Fires for each custom column of a specific post type in the Posts list table.
         *
         * @param string $column
         * @param int $post_id
         *
         * @since 3.3.0
         */
        do_action('tto_manage_'.$current.'_posttype_custom_column', $column, $post_id);
    }

    /**
     * Hook building custom fields for CPTS.
     *
     * @uses add_meta_box()
     *
     * @since 3.3.0
     */
    public function hookFieldsDisplay()
    {
        //Get all authorized fields
        $unauthorized = Field::getUnauthorizedFields();
        $slug = isset($_GET['post_type']) ? $_GET['post_type'] : '';
        $contents = array();
        $ids = array();

        //Define current post type's contents
        if (empty($slug)) {
            $post = isset($_GET['post']) ? $_GET['post'] : 0;
            $slug = !empty($post) ? get_post_type($post) : '';

            if (empty($slug)) {
                return;
            }
        }

        /**
         * Build post type contents.
         *
         * @var string $slug
         * @param array $contents
         * @return array $contents
         *
         * @since 3.3.0
         */
        $contents = apply_filters('tto_posttype_'.$slug.'_contents', $contents);

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
            if (empty($type) || in_array($type, $unauthorized)) {
                continue;
            }

            //Title
            $title = isset($ctn['title']) ? $ctn['title'] : TeaThemeOptions::__('Metabox');

            //Get field instance
            $field = Field::getField($type, $id, array(), $ids);

            //Check error
            if (is_array($field) && $field['error']) {
                continue;
            }

            //Update ids
            if (!empty($id)) {
                $ids[] = $id;
            }

            //Add meta box
            add_meta_box(
                $slug.'-meta-box-'.$id,
                $title,
                array(&$field, 'hookFieldBuild'),
                $slug,
                'normal',
                'low',
                array(
                    'type' => $type,
                    'field' => $field,
                    'contents' => $ctn
                )
            );
        }
    }

    /**
     * Hook building custom options in Permalink settings page.
     *
     * @uses register_setting()
     * @uses add_settings_field()
     *
     * @since 3.2.4
     */
    public function hookFieldsPermalink()
    {
        if (empty($this->posttypes)) {
            return false;
        }

        //Add section
        add_settings_section(
            'tea-to-permalinks',                        //ID
            TeaThemeOptions::__('Custom Permalinks'),   //Title
            array(&$this,'hookPermalinkTitle'),         //Callback
            'permalink'                                 //Page
        );

        //Flush all rewrite rules
        if (isset($_POST['tea-to-flushpermalink'])) {
            flush_rewrite_rules();
        }

        //Iterate on each cpt
        foreach ($this->posttypes as $pt) {
            //Special case: do not change post/page component
            if (in_array($pt['slug'], array('post', 'page'))) {
                continue;
            }

            //Option
            $opt = str_replace('%SLUG%', $pt['slug'], Engine::getPermalink());

            //Check POST
            if (isset($_POST[$opt])) {
                $value = $_POST[$opt];
                TeaThemeOptions::setOption($opt, $value);
            }
            else {
                $value = TeaThemeOptions::getOption($opt, '/%'.$pt['slug'].'%-%post_id%');
            }

            //Define metabox title
            $title = $pt['labels']['name'].' <code>%'.$pt['slug'].'%</code>';

            //Add fields
            add_settings_field(
                $opt,                               //Identifier
                $title,                             //Title
                array(&$this,'hookPermalinkSet'),   //Callback function
                'permalink',                        //Page
                'tea-to-permalinks',                //Section
                array(
                    'name' => $opt,
                    'value' => $value,
                )
            );
        }

        return true;
    }

    /**
     * Hook building custom fields for Post types.
     *
     * @uses update_post_meta()
     *
     * @since 3.3.0
     */
    public function hookFieldsSave()
    {
        global $post;

        if (!isset($post)) {
            return false;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post->ID;
        }

        //Get contents
        $slug = $post->post_type;
        $contents = array();

        /**
         * Build post type contents.
         *
         * @var string $slug
         * @param array $contents
         * @return array $contents
         *
         * @since 3.3.0
         */
        $contents = apply_filters('tto_posttype_'.$slug.'_contents', $contents);

        //Check contents
        if (empty($contents)) {
            return false;
        }

        //Update all metas
        foreach ($contents as $ctn) {
            $value = isset($_REQUEST[$ctn['id']]) ? $_REQUEST[$ctn['id']] : '';
            update_post_meta($post->ID, $post->post_type.'-'.$ctn['id'], $value);
        }

        return true;
    }

    /**
     * Hook building custom permalinks for post types.
     * From: http://shibashake.com/wordpress-theme/custom-post-type-permalinks-part-2
     *
     * @param string $permalink Contains permalink structure
     * @param integer $post_id Contains post ID
     * @param boolean $leavename Defeine wether to use postname or not
     * @return string $permalink Permalink final structure
     *
     * @since 3.0.0
     */
    public function hookPermalinkMake($permalink, $post_id, $leavename)
    {
        if (!$post_id) {
            return '';
        }

        //Get post's datas
        $post = get_post($post_id);

        //Define permalink structure
        $rewritecode = array(
            '%year%',
            '%monthnum%',
            '%day%',
            '%hour%',
            '%minute%',
            '%second%',
            $leavename ? '' : '%postname%',
            '%post_id%',
            '%category%',
            '%author%',
            $leavename ? '' : '%pagename%',
        );

        if ('' === $permalink || in_array($post->post_status, array('draft', 'pending', 'auto-draft'))) {
            return $permalink;
        }

        //Need time
        $unixtime = strtotime($post->post_date);
        $date = explode(' ', date('Y m d H i s', $unixtime));

        //Need category
        $category = '';

        //Get categories
        if (strpos($permalink, '%category%') !== false) {
            $cats = get_the_category($post->ID);

            if ($cats) {
                usort($cats, '_usort_terms_by_ID');
                $category = $cats[0]->slug;

                if ($parent = $cats[0]->parent) {
                    $category = get_category_parents($parent, false, '/', true) . $category;
                }
            }

            //Show default category in permalinks, without having to assign it explicitly
            if (empty($category)) {
                $default_category = get_category(TeaThemeOptions::getOption('default_category'));
                $category = is_wp_error($default_category) ? '' : $default_category->slug;
            }
        }

        //Need author
        $author = '';

        //Get authors
        if (strpos($permalink, '%author%') !== false) {
            $authordata = get_userdata($post->post_author);
            $author = $authordata->__get('user_nicename');
        }

        //Define permalink values
        $rewritereplace = array(
            $date[0],
            $date[1],
            $date[2],
            $date[3],
            $date[4],
            $date[5],
            $post->post_name,
            $post->ID,
            $category,
            $author,
            $post->post_name,
        );

        //Change structure
        $permalink = str_replace($rewritecode, $rewritereplace, $permalink);

        //Return permalink
        return $permalink;
    }

    /**
     * Hook to display input value on Permalink settings page.
     *
     * @param array $vars Contains all usefull data
     *
     * @since 3.0.0
     */
    public function hookPermalinkSet($vars)
    {
        if (empty($vars)) {
            return;
        }

        $vars['t_description'] = TeaThemeOptions::__('The following structure uses the same rules 
            than post\'s permalink structure that you can built with <code>%year%</code>, <code>%monthnum%</code>, 
            <code>%day%</code>, <code>%hour%</code>, <code>%minute%</code>, <code>%second%</code>, 
            <code>%post_id%</code>, <code>%category%</code>, <code>%author%</code> and <code>%pagename%</code>. 
            If you need to display the <code>%postname%</code>, simply use the custom post type\'s slug instead.');
        $vars['t_home'] = TTO_HOME;

        //Render template
        TeaThemeOptions::getRender('layouts/permalinks.html.twig', $vars);
    }

    /**
     * Hook to display hidden input on Permalink settings title page.
     *
     * @since 3.0.0
     */
    public function hookPermalinkTitle()
    {
        //Display settings
        $this->hookPermalinkSet(array(
            'name' => 'tea-to-flushpermalink',
            'value' => '1',
        ));
    }

    /**
     * Register post type
     *
     * @param array $posttype Contains all posttype details
     * @return array $posttype
     * @uses register_post_type()
     *
     * @since 3.3.0
     */
    public function registerPostType($posttype = array())
    {
        //Check post types
        if (empty($posttype)) {
            return array();
        }

        //Check slug
        if (!isset($posttype['slug']) || empty($posttype['slug'])) {
            return array();
        }

        //Store slug
        $slug = $posttype['slug'];

        //Special case: define a post/page as title
        //to edit default post/page component
        if (in_array($slug, array('post', 'page'))) {
            return array();
        }

        //Check if post type already exists
        if (post_type_exists($slug)) {
            return array();
        }

        //Build labels
        $labels = $this->defineLabels($posttype['labels']);

        //Build args
        $args = $this->defineArgs($posttype);
        $args['labels'] = $labels;

        //Action to register
        register_post_type($slug, $args);

        //Update post type
        $posttype = array_merge($posttype, $args);

        //Option
        $opt = str_replace('%SLUG%', $slug, Engine::getPermalink());

        //Get value
        $structure = TeaThemeOptions::getOption($opt, '/%'.$slug.'%-%post_id%');

        //Change structure
        add_rewrite_tag('%'.$slug.'%', '([^/]+)', $slug.'=');
        add_permastruct($slug, $structure, false);

        return array($posttype, $slug);
    }

    /**
     * Build post types.
     *
     * @param array $pts Contains all post types.
     * @uses add_action()
     *
     * @since 3.3.0
     */
    public function setPostTypes($pts)
    {
        //Define post types
        $this->posttypes = $pts;

        //Add WP Hooks
        add_action('init', function (){
            //Check post types
            if (empty($this->posttypes)) {
                return;
            }

            //Get all registered post types
            $posttypes = array();

            //Register post type
            foreach ($this->posttypes as $pt) {
                $p = $this->registerPostType($pt);

                if (empty($p) || empty($p[0])) {
                    unset($this->posttypes[$pt['slug']]);
                    continue;
                }

                //Manage columns
                if (TTO_IS_ADMIN) {
                    add_filter('manage_edit-'.$p[1].'_columns', array(&$this, 'hookColumns'), 10);
                    add_action('manage_'.$p[1].'_posts_custom_column', array(&$this, 'hookCustomColumn'), 11, 2);
                }

                $this->posttypes[$pt['slug']] = $p[0];
            }

            //Update DB
            TeaThemeOptions::setConfigs(Engine::getIndex(), $this->posttypes);

            //Permalink structures
            add_action('post_type_link', array(&$this, 'hookPermalinkMake'), 10, 3);

            if (TTO_IS_ADMIN) {
                //Display post type's custom fields
                add_action('admin_init', array(&$this, 'hookFieldsDisplay'));

                //Display settings in permalinks page
                add_action('admin_init', array(&$this, 'hookFieldsPermalink'));

                //Save post type's custom fields
                add_action('save_post', array(&$this, 'hookFieldsSave'));
            }
        }, 10, 1);
    }
}
